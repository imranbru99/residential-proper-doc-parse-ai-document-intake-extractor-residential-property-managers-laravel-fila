<?php

namespace App\Services;

use App\Models\Approval;
use App\Models\DocumentIntake;
use App\Models\MaintenanceRequest;
use App\Models\Property;
use App\Models\VendorWorkOrder;
use App\Support\ModuleManager;
use Illuminate\Support\Str;

class DocumentExtractionService
{
    /**
     * MVP flow: intake trigger → AI extract → validate/route → human approval → audit.
     */
    public function process(DocumentIntake $intake): DocumentIntake
    {
        $intake->update(['status' => 'extracting']);

        $fields = $this->extractFields($intake);
        $errors = $this->validateFields($fields);
        $confidence = $this->scoreConfidence($fields, $errors);

        $status = empty($errors) && $confidence >= 80
            ? 'pending_approval'
            : 'exception';

        $queue = $status === 'exception' ? 'ops_review' : 'owner_approval';

        $intake->update([
            'extracted_fields' => $fields,
            'validation_errors' => $errors,
            'confidence_score' => $confidence,
            'status' => $status,
            'exception_reason' => $status === 'exception'
                ? 'Low confidence or missing required residential property intake fields.'
                : null,
            'routed_queue' => ModuleManager::enabled('exception_routing') ? $queue : null,
            'extracted_at' => now(),
        ]);

        if (ModuleManager::enabled('human_approval')) {
            Approval::query()->updateOrCreate(
                ['document_intake_id' => $intake->id],
                [
                    'requested_by' => $intake->uploaded_by,
                    'status' => 'pending',
                    'notes' => 'AI extraction complete — awaiting human approval (fair-housing aware).',
                ]
            );
        }

        AuditLogger::log('document.extracted', $intake, [
            'confidence' => $confidence,
            'status' => $status,
            'errors' => $errors,
        ]);

        return $intake->fresh();
    }

    public function approve(DocumentIntake $intake, int $userId, ?string $notes = null): DocumentIntake
    {
        $fields = $intake->extracted_fields ?? [];

        $property = null;
        if (ModuleManager::enabled('properties') && ! empty($fields['property_code'])) {
            $property = Property::query()->where('code', $fields['property_code'])->first();
        }

        $maintenanceRequest = null;
        if (ModuleManager::enabled('maintenance_requests')) {
            $maintenanceRequest = MaintenanceRequest::query()->create([
                'reference' => 'MR-'.strtoupper(Str::random(8)),
                'property_id' => $property?->id ?? $intake->property_id,
                'resident_name' => $fields['resident_name'] ?? 'Unknown Resident',
                'resident_email' => $fields['resident_email'] ?? null,
                'resident_phone' => $fields['resident_phone'] ?? null,
                'unit_number' => $fields['unit_number'] ?? null,
                'category' => $fields['category'] ?? 'general',
                'priority' => $fields['priority'] ?? 'normal',
                'status' => 'open',
                'description' => $fields['scope_of_work'] ?? $intake->original_filename,
                'messaging_consent' => (bool) ($fields['messaging_consent'] ?? false),
                'fair_housing_flag' => (bool) ($fields['fair_housing_flag'] ?? false),
            ]);
        }

        $intake->update([
            'status' => 'approved',
            'approved_by' => $userId,
            'approved_at' => now(),
            'property_id' => $property?->id ?? $intake->property_id,
            'maintenance_request_id' => $maintenanceRequest?->id,
            'exception_reason' => null,
        ]);

        if ($intake->approval) {
            $intake->approval->update([
                'status' => 'approved',
                'reviewed_by' => $userId,
                'reviewed_at' => now(),
                'notes' => $notes ?? 'Approved for downstream maintenance / vendor work order.',
            ]);
        }

        if (ModuleManager::enabled('vendor_work_orders') && $maintenanceRequest) {
            VendorWorkOrder::query()->create([
                'work_order_number' => 'WO-'.strtoupper(Str::random(8)),
                'maintenance_request_id' => $maintenanceRequest->id,
                'document_intake_id' => $intake->id,
                'property_id' => $property?->id ?? $intake->property_id,
                'title' => ($fields['category'] ?? 'Maintenance').' — '.($fields['resident_name'] ?? 'Resident'),
                'scope_of_work' => $fields['scope_of_work'] ?? null,
                'status' => 'ready_to_dispatch',
                'unit_number' => $fields['unit_number'] ?? null,
                'site_address' => $fields['property_address'] ?? $property?->address,
                'compliance_notes' => [
                    'fair_housing_flag' => $fields['fair_housing_flag'] ?? false,
                    'privacy_restricted' => $fields['privacy_restricted'] ?? false,
                    'messaging_consent' => $fields['messaging_consent'] ?? false,
                    'vendor_trade' => $fields['vendor_trade'] ?? null,
                ],
            ]);
        }

        AuditLogger::log('document.approved', $intake, [
            'maintenance_request_id' => $maintenanceRequest?->id,
            'notes' => $notes,
        ]);

        return $intake->fresh(['maintenanceRequest', 'vendorWorkOrders', 'approval', 'property']);
    }

    protected function extractFields(DocumentIntake $intake): array
    {
        if (! ModuleManager::enabled('ai_field_extraction')) {
            return [
                'resident_name' => 'Manual Entry Required',
                'note' => 'AI extraction module is disabled.',
            ];
        }

        // Demo heuristic extractor (works offline). Swap for AIHub when API keys are present.
        $seed = strtolower(($intake->original_filename ?? '').' '.($intake->document_type ?? ''));
        $demoProfiles = [
            [
                'resident_name' => 'Jordan Miles',
                'resident_email' => 'jordan.miles@example.com',
                'resident_phone' => '+1-512-555-0188',
                'unit_number' => '2B',
                'property_code' => 'OAK-RIDGE',
                'property_address' => '1842 Oak Ridge Ln, Austin, TX 78745',
                'category' => 'plumbing',
                'priority' => 'high',
                'vendor_trade' => 'plumbing',
                'scope_of_work' => 'Unit 2B kitchen sink leak; inspect supply line and dispose of water damage photos attached.',
                'messaging_consent' => true,
                'privacy_restricted' => false,
                'fair_housing_flag' => false,
            ],
            [
                'resident_name' => 'Priya Shah',
                'resident_email' => 'priya.shah@example.com',
                'resident_phone' => '+1-214-555-0162',
                'unit_number' => '401',
                'property_code' => 'CEDAR-COURT',
                'property_address' => '900 Cedar Court, Dallas, TX 75201',
                'category' => 'hvac',
                'priority' => 'normal',
                'vendor_trade' => 'hvac',
                'scope_of_work' => 'Resident HVAC outage form with thermostat photo; request vendor work order for same-day diagnosis.',
                'messaging_consent' => true,
                'privacy_restricted' => false,
                'fair_housing_flag' => false,
            ],
            [
                'resident_name' => 'Alex Rivera',
                'resident_email' => 'alex.rivera@example.com',
                'resident_phone' => '+1-713-555-0114',
                'unit_number' => '12A',
                'property_code' => 'BAYOU-FLATS',
                'property_address' => '55 Bayou Flats Rd, Houston, TX 77024',
                'category' => 'lease_renewal',
                'priority' => 'normal',
                'vendor_trade' => null,
                'scope_of_work' => 'Lease renewal packet and income verification attachments for unit 12A.',
                'messaging_consent' => false,
                'privacy_restricted' => true,
                'fair_housing_flag' => true,
            ],
        ];

        $index = abs(crc32($seed)) % count($demoProfiles);
        $fields = $demoProfiles[$index];
        $fields['_extractor'] = 'demo_heuristic';
        $fields['_document_type'] = $intake->document_type;

        if (class_exists(\ImranDevBD\LaravelAiHub\Facades\AIHub::class) && config('ai-hub.default_provider')) {
            $fields['_ai_hub_available'] = true;
        }

        return $fields;
    }

    protected function validateFields(array $fields): array
    {
        $errors = [];
        foreach (['resident_name', 'property_address', 'category', 'scope_of_work'] as $required) {
            if (empty($fields[$required])) {
                $errors[] = "Missing required field: {$required}";
            }
        }

        if (ModuleManager::enabled('fair_housing_guard') && ! empty($fields['fair_housing_flag'])) {
            $errors[] = 'Fair-housing risk language detected — route to human review before resident-facing action.';
        }

        if (ModuleManager::enabled('tenant_privacy') && ! empty($fields['privacy_restricted']) && empty($fields['messaging_consent'])) {
            $errors[] = 'Privacy-restricted resident record lacks messaging consent.';
        }

        if (ModuleManager::enabled('messaging_consent') && ! array_key_exists('messaging_consent', $fields)) {
            $errors[] = 'Messaging consent flag not extracted.';
        }

        return $errors;
    }

    protected function scoreConfidence(array $fields, array $errors): float
    {
        $base = 96.0 - (count($errors) * 12);
        if (($fields['fair_housing_flag'] ?? false) === true) {
            $base -= 4;
        }

        return max(40, min(99.5, $base));
    }
}

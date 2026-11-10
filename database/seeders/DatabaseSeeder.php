<?php

namespace Database\Seeders;

use App\Models\AuditLog;
use App\Models\DocumentIntake;
use App\Models\FeatureModule;
use App\Models\Property;
use App\Models\Resident;
use App\Models\User;
use App\Models\Vendor;
use App\Models\VendorWorkOrder;
use App\Services\DocumentExtractionService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        foreach (config('modules') as $key => $module) {
            FeatureModule::query()->updateOrCreate(
                ['key' => $key],
                [
                    'name' => $module['name'],
                    'description' => $module['description'],
                    'enabled' => (bool) $module['enabled'],
                    'is_core' => (bool) ($module['core'] ?? false),
                ]
            );
        }

        $users = [
            ['name' => 'Admin User', 'email' => 'admin@residentialproperdocparse.test', 'role' => 'admin'],
            ['name' => 'Ops Owner', 'email' => 'owner@residentialproperdocparse.test', 'role' => 'owner'],
            ['name' => 'Office Staff', 'email' => 'staff@residentialproperdocparse.test', 'role' => 'staff'],
            ['name' => 'Maintenance Tech', 'email' => 'tech@residentialproperdocparse.test', 'role' => 'technician', 'license_number' => 'TX-MNT-28441'],
            ['name' => 'Demo Resident', 'email' => 'customer@residentialproperdocparse.test', 'role' => 'customer', 'messaging_consent' => true],
        ];

        $created = [];
        foreach ($users as $data) {
            $created[$data['role']] = User::query()->updateOrCreate(
                ['email' => $data['email']],
                [
                    'name' => $data['name'],
                    'password' => Hash::make('password'),
                    'role' => $data['role'],
                    'phone' => '+1-555-0100',
                    'license_number' => $data['license_number'] ?? null,
                    'messaging_consent' => $data['messaging_consent'] ?? false,
                    'is_active' => true,
                    'email_verified_at' => now(),
                ]
            );
        }

        $oak = Property::query()->updateOrCreate(
            ['code' => 'OAK-RIDGE'],
            [
                'name' => 'Oak Ridge Apartments',
                'address' => '1842 Oak Ridge Ln',
                'city' => 'Austin',
                'state' => 'TX',
                'postal_code' => '78745',
                'unit_count' => 120,
                'property_type' => 'garden',
                'status' => 'active',
            ]
        );

        $cedar = Property::query()->updateOrCreate(
            ['code' => 'CEDAR-COURT'],
            [
                'name' => 'Cedar Court Residences',
                'address' => '900 Cedar Court',
                'city' => 'Dallas',
                'state' => 'TX',
                'postal_code' => '75201',
                'unit_count' => 86,
                'property_type' => 'highrise',
                'status' => 'active',
            ]
        );

        Property::query()->updateOrCreate(
            ['code' => 'BAYOU-FLATS'],
            [
                'name' => 'Bayou Flats',
                'address' => '55 Bayou Flats Rd',
                'city' => 'Houston',
                'state' => 'TX',
                'postal_code' => '77024',
                'unit_count' => 64,
                'property_type' => 'multifamily',
                'status' => 'active',
            ]
        );

        Resident::query()->updateOrCreate(
            ['email' => 'jordan.miles@example.com'],
            [
                'property_id' => $oak->id,
                'name' => 'Jordan Miles',
                'phone' => '+1-512-555-0188',
                'unit_number' => '2B',
                'lease_status' => 'active',
                'messaging_consent' => true,
                'privacy_restricted' => false,
            ]
        );

        Resident::query()->updateOrCreate(
            ['email' => $created['customer']->email],
            [
                'property_id' => $cedar->id,
                'user_id' => $created['customer']->id,
                'name' => $created['customer']->name,
                'phone' => $created['customer']->phone,
                'unit_number' => '401',
                'lease_status' => 'active',
                'messaging_consent' => true,
                'privacy_restricted' => false,
            ]
        );

        $techUser = $created['technician'];
        $vendor = Vendor::query()->updateOrCreate(
            ['email' => $techUser->email],
            [
                'user_id' => $techUser->id,
                'name' => $techUser->name,
                'phone' => $techUser->phone,
                'company_name' => 'In-House Maintenance',
                'license_number' => $techUser->license_number,
                'trade_specialty' => 'general_maintenance',
                'status' => 'available',
                'rating' => 4.9,
            ]
        );

        Vendor::query()->updateOrCreate(
            ['email' => 'flowfix.plumbing@example.com'],
            [
                'name' => 'Sam Ortiz',
                'company_name' => 'FlowFix Plumbing Co',
                'phone' => '+1-555-0144',
                'license_number' => 'TX-PLB-33018',
                'trade_specialty' => 'plumbing',
                'status' => 'on_job',
                'rating' => 4.8,
            ]
        );

        $extractor = app(DocumentExtractionService::class);

        $samples = [
            [
                'reference' => 'DOC-1001',
                'source' => 'upload',
                'original_filename' => 'oak-ridge-2b-leak-form.pdf',
                'document_type' => 'resident_form',
                'mime_type' => 'application/pdf',
                'property_id' => $oak->id,
            ],
            [
                'reference' => 'DOC-1002',
                'source' => 'email',
                'original_filename' => 'cedar-court-401-hvac-outage.pdf',
                'document_type' => 'maintenance_photo',
                'mime_type' => 'application/pdf',
                'property_id' => $cedar->id,
            ],
            [
                'reference' => 'DOC-1003',
                'source' => 'integration',
                'original_filename' => 'bayou-flats-12a-lease-renewal.jpg',
                'document_type' => 'lease_packet',
                'mime_type' => 'image/jpeg',
            ],
        ];

        foreach ($samples as $sample) {
            $intake = DocumentIntake::query()->updateOrCreate(
                ['reference' => $sample['reference']],
                array_merge($sample, [
                    'status' => 'received',
                    'uploaded_by' => $created['staff']->id,
                    'storage_path' => 'intakes/'.$sample['original_filename'],
                    'raw_text' => ['preview' => 'Demo OCR text for '.$sample['original_filename']],
                ])
            );

            $extractor->process($intake);
        }

        // Approve first document end-to-end for demo
        $approved = DocumentIntake::query()->where('reference', 'DOC-1001')->first();
        if ($approved && $approved->status !== 'approved') {
            $extractor->approve($approved, $created['owner']->id, 'Demo approval — create maintenance request and vendor work order.');
            $workOrder = VendorWorkOrder::query()->where('document_intake_id', $approved->id)->first();
            if ($workOrder) {
                $workOrder->update([
                    'vendor_id' => $vendor->id,
                    'status' => 'scheduled',
                    'scheduled_at' => now()->addDay(),
                ]);
            }
        }

        AuditLog::query()->firstOrCreate(
            ['action' => 'system.seeded'],
            [
                'entity_type' => 'system',
                'entity_id' => 0,
                'user_id' => $created['admin']->id,
                'payload' => ['message' => 'ResidentialProperDocParse demo dataset loaded'],
                'ip_address' => '127.0.0.1',
            ]
        );
    }
}

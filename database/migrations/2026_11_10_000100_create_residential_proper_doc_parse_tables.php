<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default('staff')->after('email');
            $table->string('phone')->nullable()->after('role');
            $table->string('license_number')->nullable()->after('phone');
            $table->boolean('messaging_consent')->default(false)->after('license_number');
            $table->boolean('is_active')->default(true)->after('messaging_consent');
        });

        Schema::create('feature_modules', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->string('name');
            $table->text('description')->nullable();
            $table->boolean('enabled')->default(false);
            $table->boolean('is_core')->default(false);
            $table->timestamps();
        });

        Schema::create('properties', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('state', 2)->nullable();
            $table->string('postal_code', 20)->nullable();
            $table->unsignedInteger('unit_count')->default(0);
            $table->string('property_type')->default('multifamily');
            $table->string('status')->default('active');
            $table->timestamps();
        });

        Schema::create('residents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('unit_number')->nullable();
            $table->string('lease_status')->default('active');
            $table->boolean('messaging_consent')->default(false);
            $table->boolean('privacy_restricted')->default(false);
            $table->timestamps();
        });

        Schema::create('vendors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('company_name')->nullable();
            $table->string('trade_specialty')->default('general_maintenance');
            $table->string('license_number')->nullable();
            $table->string('status')->default('available');
            $table->decimal('rating', 3, 2)->default(5);
            $table->timestamps();
        });

        Schema::create('maintenance_requests', function (Blueprint $table) {
            $table->id();
            $table->string('reference')->unique();
            $table->foreignId('property_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('resident_id')->nullable()->constrained()->nullOnDelete();
            $table->string('resident_name');
            $table->string('resident_email')->nullable();
            $table->string('resident_phone')->nullable();
            $table->string('unit_number')->nullable();
            $table->string('category')->default('general');
            $table->string('priority')->default('normal');
            $table->string('status')->default('open');
            $table->text('description')->nullable();
            $table->boolean('messaging_consent')->default(false);
            $table->boolean('fair_housing_flag')->default(false);
            $table->foreignId('assigned_vendor_id')->nullable()->constrained('vendors')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('document_intakes', function (Blueprint $table) {
            $table->id();
            $table->string('reference')->unique();
            $table->string('source')->default('upload');
            $table->string('original_filename')->nullable();
            $table->string('mime_type')->nullable();
            $table->string('storage_path')->nullable();
            $table->string('document_type')->default('resident_form');
            $table->string('status')->default('received');
            $table->decimal('confidence_score', 5, 2)->nullable();
            $table->json('raw_text')->nullable();
            $table->json('extracted_fields')->nullable();
            $table->json('validation_errors')->nullable();
            $table->text('exception_reason')->nullable();
            $table->string('routed_queue')->nullable();
            $table->foreignId('property_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('maintenance_request_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('uploaded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('extracted_at')->nullable();
            $table->timestamps();
        });

        Schema::create('vendor_work_orders', function (Blueprint $table) {
            $table->id();
            $table->string('work_order_number')->unique();
            $table->foreignId('maintenance_request_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('document_intake_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('vendor_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('property_id')->nullable()->constrained()->nullOnDelete();
            $table->string('title');
            $table->text('scope_of_work')->nullable();
            $table->string('status')->default('draft');
            $table->timestamp('scheduled_at')->nullable();
            $table->string('unit_number')->nullable();
            $table->string('site_address')->nullable();
            $table->json('compliance_notes')->nullable();
            $table->timestamps();
        });

        Schema::create('approvals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('document_intake_id')->constrained()->cascadeOnDelete();
            $table->foreignId('requested_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('status')->default('pending');
            $table->text('notes')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();
        });

        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->string('action');
            $table->string('entity_type')->nullable();
            $table->unsignedBigInteger('entity_id')->nullable();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->json('payload')->nullable();
            $table->string('ip_address')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
        Schema::dropIfExists('approvals');
        Schema::dropIfExists('vendor_work_orders');
        Schema::dropIfExists('document_intakes');
        Schema::dropIfExists('maintenance_requests');
        Schema::dropIfExists('vendors');
        Schema::dropIfExists('residents');
        Schema::dropIfExists('properties');
        Schema::dropIfExists('feature_modules');

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'phone', 'license_number', 'messaging_consent', 'is_active']);
        });
    }
};

<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Feature modules — toggle ON/OFF per property management business needs
    |--------------------------------------------------------------------------
    | Core document intake workflow modules default ON.
    | Niche / optional integrations default OFF until enabled in admin.
    */
    'document_intake' => [
        'enabled' => env('MODULE_DOCUMENT_INTAKE', true),
        'name' => 'Document Intake Extractor',
        'description' => 'Ingest lease packets, resident forms, PDFs and attachments from email, upload and PMS integrations.',
        'core' => true,
    ],
    'ai_field_extraction' => [
        'enabled' => env('MODULE_AI_FIELD_EXTRACTION', true),
        'name' => 'AI Field Extraction',
        'description' => 'Extract property, resident, unit, maintenance and vendor fields with AI validation and confidence scoring.',
        'core' => true,
    ],
    'exception_routing' => [
        'enabled' => env('MODULE_EXCEPTION_ROUTING', true),
        'name' => 'Exception Routing',
        'description' => 'Route low-confidence or missing fields to staff queues for fair-housing-aware review.',
        'core' => true,
    ],
    'human_approval' => [
        'enabled' => env('MODULE_HUMAN_APPROVAL', true),
        'name' => 'Human Approval Gate',
        'description' => 'Require owner/staff approval before pushing extracted data into maintenance or vendor workflows.',
        'core' => true,
    ],
    'audit_log' => [
        'enabled' => env('MODULE_AUDIT_LOG', true),
        'name' => 'Audit Log',
        'description' => 'Immutable trail of intake, extraction, approval and routing events for tenant-privacy accountability.',
        'core' => true,
    ],
    'properties' => [
        'enabled' => env('MODULE_PROPERTIES', true),
        'name' => 'Properties',
        'description' => 'Manage residential communities, buildings and unit inventories linked to intake documents.',
        'core' => true,
    ],
    'residents' => [
        'enabled' => env('MODULE_RESIDENTS', true),
        'name' => 'Residents',
        'description' => 'Track residents and lease contacts with privacy-aware messaging consent flags.',
        'core' => true,
    ],
    'maintenance_requests' => [
        'enabled' => env('MODULE_MAINTENANCE_REQUESTS', true),
        'name' => 'Maintenance Requests',
        'description' => 'Link parsed documents to resident maintenance requests and work scopes.',
        'core' => true,
    ],
    'vendors' => [
        'enabled' => env('MODULE_VENDORS', true),
        'name' => 'Vendors',
        'description' => 'Manage preferred vendors and trade specialties for work-order assignment.',
        'core' => true,
    ],
    'vendor_work_orders' => [
        'enabled' => env('MODULE_VENDOR_WORK_ORDERS', true),
        'name' => 'Vendor Work Orders',
        'description' => 'Create and track vendor work orders from validated document intake.',
        'core' => true,
    ],
    'fair_housing_guard' => [
        'enabled' => env('MODULE_FAIR_HOUSING', true),
        'name' => 'Fair Housing Guard',
        'description' => 'Flag protected-class language risks and require human review before resident-facing actions.',
        'core' => false,
    ],
    'tenant_privacy' => [
        'enabled' => env('MODULE_TENANT_PRIVACY', true),
        'name' => 'Tenant Privacy Controls',
        'description' => 'Mask/minimize PII in exports and enforce consent checks on resident communications.',
        'core' => false,
    ],
    'messaging_consent' => [
        'enabled' => env('MODULE_MESSAGING_CONSENT', true),
        'name' => 'Messaging Consent',
        'description' => 'Track SMS/email consent for resident communications (TCPA-aware).',
        'core' => false,
    ],
    'pdf_export' => [
        'enabled' => env('MODULE_PDF_EXPORT', true),
        'name' => 'Unicode PDF Export',
        'description' => 'Export intake summaries and work-order packets as multilingual PDFs.',
        'core' => false,
    ],
    'resident_portal' => [
        'enabled' => env('MODULE_RESIDENT_PORTAL', false),
        'name' => 'Resident Portal',
        'description' => 'Let residents upload documents and track intake status securely.',
        'core' => false,
    ],
    'integrations_appfolio' => [
        'enabled' => env('MODULE_APPFOLIO', false),
        'name' => 'AppFolio Integration',
        'description' => 'Push approved intake records into AppFolio properties, residents and work orders.',
        'core' => false,
    ],
    'integrations_buildium' => [
        'enabled' => env('MODULE_BUILDIUM', false),
        'name' => 'Buildium Integration',
        'description' => 'Sync approved tickets, residents and vendors with Buildium.',
        'core' => false,
    ],
    'integrations_email' => [
        'enabled' => env('MODULE_EMAIL_INTAKE', true),
        'name' => 'Email Intake',
        'description' => 'Capture lease and maintenance attachments from property operations inboxes.',
        'core' => false,
    ],
    'integrations_accounting' => [
        'enabled' => env('MODULE_ACCOUNTING', false),
        'name' => 'Accounting Integration',
        'description' => 'Map extracted billing fields to accounting vendors and expense codes.',
        'core' => false,
    ],
    'attendance_hub' => [
        'enabled' => env('MODULE_ATTENDANCE_HUB', false),
        'name' => 'Attendance Hub',
        'description' => 'Optional clock-in/out for maintenance crews via attendance devices.',
        'core' => false,
    ],
    'ai_catalog_scrape' => [
        'enabled' => env('MODULE_AI_CATALOG', false),
        'name' => 'AI Parts & Spec Catalog',
        'description' => 'Scrape/enrich appliance and materials catalogs from online datasheets.',
        'core' => false,
    ],
    'performance_optimizer' => [
        'enabled' => env('MODULE_PERFORMANCE', false),
        'name' => 'Performance Optimizer',
        'description' => 'Detect and visualize Laravel performance bottlenecks (Drutogoti-style).',
        'core' => false,
    ],
];

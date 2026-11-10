<?php $__env->startSection('title', 'Workflow — ResidentialProperDocParse Document Intake Extractor'); ?>

<?php $__env->startSection('content'); ?>
<section class="wrap" style="padding-top:3rem">
    <div class="section-head">
        <div class="eyebrow">MVP automation loop</div>
        <h2>1 intake → extract → approve → audit</h2>
        <p class="lead">Designed for residential property manager owners and operations leads who drown in lease packets, resident forms and maintenance photos.</p>
    </div>
    <div class="grid grid-2">
        <article class="module">
            <h3>Document operations</h3>
            <p>Normalize incoming forms, PDFs and attachments. Classify document type (lease packet, resident form, maintenance photo). Store raw OCR/text for reprocessing.</p>
        </article>
        <article class="module">
            <h3>AI field extraction</h3>
            <p>Pull resident name, unit, property address, category, priority, vendor trade and scope of work with confidence scoring.</p>
        </article>
        <article class="module">
            <h3>Exception routing</h3>
            <p>Low-confidence or missing required fields route to ops_review. Fair-housing flags and privacy gaps force human review before resident-facing action.</p>
        </article>
        <article class="module">
            <h3>Ops handoff</h3>
            <p>On approval, create a maintenance request and vendor work order, optionally assign a vendor, and write an immutable audit log.</p>
        </article>
    </div>
    <div class="cta-row" style="margin-top:2rem">
        <a class="btn btn-primary" href="/admin/document-intakes">Manage Document Intakes</a>
        <a class="btn btn-ghost" href="<?php echo e(route('demo')); ?>">View Demo Credentials</a>
    </div>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.public', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/html/resources/views/public/workflow.blade.php ENDPATH**/ ?>
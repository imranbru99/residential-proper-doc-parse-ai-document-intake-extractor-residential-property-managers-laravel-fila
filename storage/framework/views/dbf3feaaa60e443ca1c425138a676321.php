<?php $__env->startSection('title', 'Demo Users — ResidentialProperDocParse'); ?>

<?php $__env->startSection('content'); ?>
<section class="wrap" style="padding-top:3rem">
    <div class="section-head">
        <div class="eyebrow">Seeded demo access</div>
        <h2>Multi-role users ready</h2>
        <p class="lead">Password for every demo account: <strong style="color:#fff">password</strong></p>
    </div>
    <div class="module">
        <table class="demo">
            <thead>
                <tr>
                    <th>Role</th>
                    <th>Email</th>
                    <th>Access</th>
                </tr>
            </thead>
            <tbody>
                <tr><td>Admin</td><td>admin@residentialproperdocparse.test</td><td>Full platform + modules</td></tr>
                <tr><td>Owner</td><td>owner@residentialproperdocparse.test</td><td>Approvals + operations</td></tr>
                <tr><td>Staff</td><td>staff@residentialproperdocparse.test</td><td>Intake + routing queues</td></tr>
                <tr><td>Technician</td><td>tech@residentialproperdocparse.test</td><td>Vendor work orders + field view</td></tr>
                <tr><td>Resident / Customer</td><td>customer@residentialproperdocparse.test</td><td>Public/demo (no admin)</td></tr>
            </tbody>
        </table>
    </div>
    <div class="cta-row" style="margin-top:1.5rem">
        <a class="btn btn-primary" href="/admin/login">Login to Filament Admin</a>
    </div>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.public', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/html/resources/views/public/demo.blade.php ENDPATH**/ ?>
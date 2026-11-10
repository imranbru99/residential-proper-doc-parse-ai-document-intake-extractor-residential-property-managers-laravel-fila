@extends('layouts.public')

@section('title', 'ResidentialProperDocParse — AI Document Intake Extractor for Residential Property Managers')
@section('meta_description', 'Extract fields from residential lease packets, resident forms and maintenance PDFs. Validate, route exceptions, approve, and create vendor work orders.')

@section('content')
<section class="wrap hero">
    <div>
        <div class="eyebrow">Residential Property Managers · Document Operations</div>
        <h1>ResidentialProperDocParse</h1>
        <p class="lead">AI document intake extractor that pulls property, resident, unit and maintenance fields from forms and PDFs, validates them, routes exceptions, and creates maintenance requests plus vendor work orders after human approval.</p>
        <div class="cta-row">
            <a class="btn btn-primary" href="/admin">Open Admin Panel</a>
            <a class="btn btn-ghost" href="{{ route('features') }}">Explore Modules</a>
        </div>
    </div>
    <div class="hero-visual" aria-hidden="true">
        <div class="pulse-line"></div>
        <div class="doc-chip">
            <strong>oak-ridge-2b-leak-form.pdf</strong>
            <small>Source: email · Type: resident form · Queue: owner_approval</small>
        </div>
        <div class="meter">
            <div>AI confidence</div>
            <strong style="color:var(--sand)">92.0%</strong>
            <div class="bar"><span></span></div>
        </div>
    </div>
</section>

<section class="wrap">
    <div class="section-head">
        <h2>Built for property ops</h2>
        <p class="lead">Intake trigger → AI draft/extract → human approval → audit log. Operate on properties, residents, maintenance requests and vendor work orders.</p>
    </div>
    <div class="steps">
        <div class="step"><span>01</span><h3>Intake</h3><p class="lead" style="font-size:0.95rem">Capture uploads, email attachments and AppFolio/Buildium payloads.</p></div>
        <div class="step"><span>02</span><h3>Extract</h3><p class="lead" style="font-size:0.95rem">AI pulls property, resident, unit, category and scope fields.</p></div>
        <div class="step"><span>03</span><h3>Route</h3><p class="lead" style="font-size:0.95rem">Exceptions go to ops review; fair-housing flags get extra scrutiny.</p></div>
        <div class="step"><span>04</span><h3>Dispatch</h3><p class="lead" style="font-size:0.95rem">Approved intake becomes maintenance requests and vendor work orders.</p></div>
    </div>
</section>

<section class="wrap">
    <div class="notice">
        Fair-housing & tenant privacy: this platform is designed with MED/HIGH housing compliance awareness — human approval gates, PII minimization hooks, messaging consent and audit trails are first-class modules.
    </div>
</section>

<section class="wrap">
    <div class="section-head">
        <h2>Modules on this instance</h2>
        <p class="lead">Enable only what your residential property management business needs. Core workflow modules stay ON by default.</p>
    </div>
    <div class="grid grid-3">
        @foreach($modules as $key => $module)
            <article class="module">
                <span class="badge {{ ($module['enabled'] ?? false) ? 'on' : 'off' }}">{{ ($module['enabled'] ?? false) ? 'ON' : 'OFF' }}</span>
                <h3>{{ $module['name'] }}</h3>
                <p>{{ $module['description'] }}</p>
            </article>
        @endforeach
    </div>
</section>
@endsection

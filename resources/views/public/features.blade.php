@extends('layouts.public')

@section('title', 'Features & Modules — ResidentialProperDocParse')

@section('content')
<section class="wrap" style="padding-top:3rem">
    <div class="section-head">
        <div class="eyebrow">Complete feature catalog</div>
        <h2>Everything ResidentialProperDocParse can run</h2>
        <p class="lead">Toggle modules from the Filament admin. Core document operations remain available for the MVP workflow.</p>
    </div>
    <div class="grid grid-2">
        @foreach($modules as $key => $module)
            <article class="module">
                <span class="badge {{ ($module['enabled'] ?? false) ? 'on' : 'off' }}">{{ ($module['enabled'] ?? false) ? 'ENABLED' : 'DISABLED' }}</span>
                <h3>{{ $module['name'] }}</h3>
                <p>{{ $module['description'] }}</p>
                <p style="margin-top:0.75rem;color:var(--sand);font-size:0.85rem">Key: {{ $key }} @if(!empty($module['core'])) · CORE @endif</p>
            </article>
        @endforeach
    </div>
</section>
@endsection

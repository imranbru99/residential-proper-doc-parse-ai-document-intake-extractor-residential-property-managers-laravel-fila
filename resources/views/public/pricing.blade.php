@extends('layouts.public')

@section('title', 'Pricing — ResidentialProperDocParse')

@section('content')
<section class="wrap" style="padding-top:3rem">
    <div class="section-head">
        <div class="eyebrow">Pilot pricing hypothesis</div>
        <h2>$299–$1,499 / month</h2>
        <p class="lead">Right-sized for residential property managers automating document intake across AppFolio, Buildium, email and accounting.</p>
    </div>
    <div class="grid grid-3">
        <div class="price">
            <h3>Pilot</h3>
            <div class="amount">$299</div>
            <p class="lead" style="font-size:0.95rem">Single portfolio · core intake + AI extract + approval + audit</p>
        </div>
        <div class="price" style="border-color:var(--teal)">
            <h3>Growth</h3>
            <div class="amount">$799</div>
            <p class="lead" style="font-size:0.95rem">Multi-property ops · fair-housing guard · messaging consent</p>
        </div>
        <div class="price">
            <h3>Ops+</h3>
            <div class="amount">$1,499</div>
            <p class="lead" style="font-size:0.95rem">AppFolio/Buildium pack · resident portal · accounting sync</p>
        </div>
    </div>
</section>
@endsection

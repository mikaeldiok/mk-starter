@extends('frontend.layouts.app')

@section('title') {{ __("Donatur") }} @endsection

@section('content')

<?php
    $name = explode(" ", \Auth::user()->donator_name);
    $fist_name = $name[0];
?>

<div class="block-31" style="position: relative;">
  <div class="background-1 header-bg"></div>
</div>

<section class="section section-lg line-bottom-light bg-light">
    <div class="container mt-n7 mt-lg-n12 z-2">
        <div class="row">
             
            <div class="col-lg-12 mb-5">
                <div class="card bg-white border-light shadow-soft flex-md-row no-gutters p-4">
                    <div class="card-body d-flex flex-column justify-content-between col-auto py-4 p-lg-5">
                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="donasi-tab" data-toggle="tab" href="#donasi" role="tab" aria-controls="donasi" aria-selected="true">Riwayat</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="detail-tab" data-toggle="tab" href="#detail" role="tab" aria-controls="detail" aria-selected="false">Donasi</a>
                            </li>
                        </ul>
                        <div class="tab-content mt-4" id="myTabContent">
                            <div class="tab-pane fade show active" id="donasi" role="tabpanel" aria-labelledby="donasi-tab">
                                first user tab
                            </div>
                            <div class="tab-pane fade" id="detail" role="tabpanel" aria-labelledby="detail-tab">
                                next tab
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</section>

@endsection

@push ('after-styles')
@endpush

@push ('after-scripts')
@endpush
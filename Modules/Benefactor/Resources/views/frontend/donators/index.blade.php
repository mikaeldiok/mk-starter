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
                                @if($$module_name_singular->donations->count() > 0)
                                    <?php
                                        //Total Donations
                                        $total = 0;
                                        foreach($$module_name_singular->donations as $donation){
                                            $total += $donation->amount;
                                        }
                                    ?>        
                                    <tr>
                                        <td>
                                            <strong>Total Donasi</strong>  
                                        </td>
                                        <td> 
                                            <h4>Rp. {{number_format($total, 2, ',', '.')}}</h4>                         
                                        </td>
                                    </tr>

                                    {{ $dataTable->table() }}
                                @else
                                    <h1>Yuk Mulai Berdonasi!</h1>
                                @endif
                            </div>
                            <div class="tab-pane fade" id="detail" role="tabpanel" aria-labelledby="detail-tab">
                                @include("benefactor::frontend.commitments.index")
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    @include('benefactor::frontend.commitments.edit-modal')

</section>

@endsection

@push ('after-styles')
<!-- DataTables Fund and Extensions -->
<link rel="stylesheet" href="{{ asset('vendor/datatable/datatables.min.css') }}">

@endpush

@push ('after-scripts')

<script type="text/javascript" src="{{ asset('vendor/datatable/datatables.min.js') }}"></script>
        
<script src="/vendor/datatables/buttons.server-side.js"></script>

<!-- DataTables Fund and Extensions -->
{!! $dataTable->scripts()  !!}
@endpush
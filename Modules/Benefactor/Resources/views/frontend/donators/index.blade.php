@extends('benefactor::frontend.layouts.app')

@section('title') {{ __("Posts") }} @endsection

@section('content')

<?php
    $name = explode(" ", \Auth::user()->donator_name);
    $fist_name = $name[0];
?>
<section class="section-header bg-primary text-white pb-7 pb-lg-11">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-md-8 text-center">
                <h1 class="display-2 mb-4">
                    Halo, {{$fist_name}}!
                    
                </h1>
                <p class="lead">
                    Kamu bisa melihat riwayat donasimu ataupun melakukan pengaturan komitmen sumbanganmu disini
                </p>

            </div>
        </div>
    </div>
</section>

<section class="section section-lg line-bottom-light bg-light">
    <div class="container mt-n7 mt-lg-n12 z-2">
        <div class="row">
             
            <div class="col-lg-12 mb-5">
                <div class="card bg-white border-light shadow-soft flex-md-row no-gutters p-4">
                    <div class="card-body d-flex flex-column justify-content-between col-auto py-4 p-lg-5">
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

                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th scope="col">Tanggal</th>
                                    <th scope="col">Nominal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($$module_name_singular->donations as $donation)
                                    <tr>
                                        <td>
                                        {{$donation->donation_date}}    
                                        </td>
                                        <td> 
                                            Rp. {{number_format($donation->amount, 2, ',', '.')}}                              
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table> 
                    </div>
                </div>
            </div>

        </div>

        <div class="d-flex justify-content-center w-100 mt-3">
        </div>
    </div>
</section>

@endsection

@extends('frontend.layouts.app')

@section('title') {{app_name()}} @endsection

@section('content')

<div class="block-31" style="position: relative;">
  <div class="owl-carousel loop-block-31 ">
    <div class="block-30 block-30-sm item" style="background-image: url('images/bg_kbtk_1.jpg');background-color: rgba(0, 0, 0, 0.5);background-blend-mode: multiply;" data-stellar-background-ratio="0.5">
      <div class="container">
        <div class="row align-items-center justify-content-center text-center">
          <div class="col-md-7">
            <h2 class="heading mb-4">Halo kawan Peduli!</h2>
            <p class="lead">Terima kasih sudah mampir ke Gerakan Peduli Pendidikan Yayasan Pendidikan Warga Surakarta</p>
          </div>
        </div>
      </div>
    </div>
    
  </div>
</div>

<div class="site-section section-counter">
  <div class="container">
    <div class="row">
      <div class="col-md-6 px-4 welcome-text">
        <h2 class="display-4 mb-3">Apa itu Gerakan Peduli Pendidikan?</h2>
        <p class="lead">Gerakan peduli pendidikan adalah,, Far far away, behind the word mountains, far from the countries Vokalia and Consonantia, there live the blind texts. Separated they live in Bookmarksgrove right at the coast of the Semantics, a large language ocean.</p>
        <p class="mb-4">Kami berharaap,,, A small river named Duden flows by their place and supplies it with the necessary regelialia. </p>
        <!-- <p class="mb-0"><a href="#" class="btn btn-primary px-3 py-2">Learn More</a></p> -->
      </div>
      <div class="col-md-6 pb-4">
        <div class="block-48 shadow">
            <span class="block-48-text-1 ">Untuk lebih dari</span>
            <div class="block-48-counter ftco-number" data-number="744">0</div>
            <span class="block-48-text-1 mb-4 d-block">Anak di 6 unit pendidikan</span>
            <!-- <p class="mb-0"><a href="#" class="btn btn-white px-3 py-2">View Our Program</a></p> -->
          </div>
      </div>
    </div>
  </div>
</div>

<div class="site-section fund-raisers bg-primary">
  <div class="container bg-white rounded">
    <div class="row mb-3 justify-content-center pt-5">
      <div class="col-md-8 text-center">
        <h2>Donasi Terakhir</h2>
        <p class="lead">Beberapa donasi terakhir yang telah kami konfirmasi.</p>
      </div>
    </div>
    <div class="card-body  d-flex flex-column justify-content-between col-auto py-4 p-lg-5">
  
        {{ $dataTable->table() }}

      </div>
  </div>
</div> <!-- .section -->

<div class="site-section border-top">
  <div class="container">
    <div class="row">

      <div class="col-md-4">
        <div class="media block-6">
          <div class="icon text-warning"><span class="ion-ios-sync"></span></div>
          <div class="media-body">
            <h3 class="heading">Our Mission</h3>
            <p>A small river named Duden flows by their place and supplies it with the necessary regelialia.</p>
            <p><a href="#" class="link-underline">Learn More</a></p>
          </div>
        </div>     
      </div>

      <div class="col-md-4">
        <div class="media block-6">
          <div class="icon text-warning"><span class="ion-ios-cash"></span></div>
          <div class="media-body">
            <h3 class="heading">Make Donations</h3>
            <p>A small river named Duden flows by their place and supplies it with the necessary regelialia.</p>
            <p><a href="#" class="link-underline">Learn More</a></p>
          </div>
        </div>  
      </div>

      <div class="col-md-4">
        <div class="media block-6">
          <div class="icon text-warning"><span class="ion-ios-contacts"></span></div>
          <div class="media-body">
            <h3 class="heading">We Need Volunteers</h3>
            <p>A small river named Duden flows by their place and supplies it with the necessary regelialia.</p>
            <p><a href="#" class="link-underline">Learn More</a></p>
          </div>
        </div> 
      </div>

    </div>
  </div>
</div> <!-- .site-section -->

<div class="featured-section overlay-color-2" style="background-image: url('images/bg_sd_1.jpeg');">
  
  <div class="container">
    <div class="row">

      <div class="col-md-6 mb-5 mb-md-0">
        <img src="images/bg_sd_1.jpeg" alt="Image placeholder" class="img-fluid">
        
      </div>

      <div class="col-md-6 pl-md-5">

        <div class="form-volunteer">
          
          <h2>Bergabung bersama kami hari ini</h2>
          <p class="lead">Kirimkan donasi anda pada rekening dengan detail sebagai berikut:</p>
          <p class="lead">
            BCA 1239213 A/N Gependik
          </p>  

        </div>
      </div>
      
    </div>
  </div>

</div> <!-- .featured-donate -->

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
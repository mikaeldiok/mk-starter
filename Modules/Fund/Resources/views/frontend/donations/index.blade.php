@extends('fund::frontend.layouts.app')

@section('title') {{ __("Posts") }} @endsection

@section('content')

<section class="section-header bg-primary text-white pb-7 pb-lg-11">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-md-8 text-center">
                <h1 class="display-2 mb-4">
                    The Super Articles
                    {{\Auth::guard('donation')->user()}}
                </h1>
                <p class="lead">
                    We publish articles on a number of topics. We encourage you to read our posts and let us know your feedback. It would be really help us to move forward.
                </p>

                @include('frontend.includes.messages')
            </div>
        </div>
    </div>
    <div class="pattern bottom"></div>
</section>

<section class="section section-lg line-bottom-light">
    <div class="container mt-n7 mt-lg-n12 z-2">
        <div class="row">
           
        </div>

        <div class="d-flex justify-content-center w-100 mt-3">
            
        </div>
    </div>
</section>

@endsection

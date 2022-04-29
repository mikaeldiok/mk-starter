@extends('auth.layout')

@section('title') @lang('Register') @endsection

@section('content')

<div class="main-content bg-sunset">

    <div class="header py-7 py-lg-8">
        <div class="container">
            <div class="header-body text-center mb-6">
                <div class="row justify-content-center">
                    <div class="col-xl-5 col-lg-6 col-md-8 px-5 py-5">
                        <h1 class="text-white">
                            @lang('Register')
                        </h1>
                        <p class="text-lead text-white">
                            @lang("Silakan mengisi data-data anda")
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container mt--9 pb-5">

        <div class="row justify-content-center">
            <div class="col-lg-6 col-md-8">
                <div class="card bg-light border border-soft">
                    <div class="card-body px-lg-5 py-lg-5">
                        <div class="text-center text-muted mb-4">
                            <small> @lang("Semua wajib diisi") </small>
                        </div>

                        @include('flash::message')

                        @if ($errors->any())
                        <!-- Validation Errors -->
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <p>
                                <i class="fas fa-exclamation-triangle"></i> @lang('Please fix the following errors & try again!')
                            </p>
                            <ul>
                                @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>

                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        @endif

                        <form role="form" id="register" method="POST" action="{{ route('register') }}">
                            @csrf
                            <div class="form-group">
                                <div class="input-group input-group-merge input-group-alternative mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                                    </div>
                                    <input type="text" class="form-control" id="first_name" name="first_name" value="{{ old('first_name') }}" placeholder="{{ __('Nama Depan') }}" aria-label="first_name" aria-describedby="first_name" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="input-group input-group-merge input-group-alternative mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="far fa-user"></i></span>
                                    </div>
                                    <input type="text" class="form-control" id="last_name" name="last_name" value="{{ old('last_name') }}" placeholder="{{ __('Nama Belakang') }}" aria-label="last_name" aria-describedby="last_name" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="input-group input-group-merge input-group-alternative mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fa-solid fa-phone"></i></span>
                                    </div>
                                    <input type="text" class="form-control" id="mobile" name="mobile" value="{{ old('mobile') }}" placeholder="{{ __('Nomor Telepon') }}" aria-label="mobile" aria-describedby="mobile" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="input-group input-group-merge input-group-alternative mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Jenis Donatur</span>
                                    </div>
                                    <?php
                                        $field_name = 'donator_type';
                                        $field_data_id = 'donator_type';
                                        $field_lable = __("benefactor::donators.$field_name");
                                        $field_placeholder = __("Select an option");
                                        $required = "required";
                                        $select_options = $donator_types;
                                    ?>
                                    {{ html()->select($field_data_id, $select_options)->placeholder($field_placeholder)->class('form-control select2')->attributes(["$required"]) }}                           
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="input-group input-group-merge input-group-alternative mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fa-solid fa-house"></i></span>
                                    </div>
                                    <textarea form="register" class="form-control" id="address" name="address" value="{{ old('address') }}" placeholder="{{ __('Alamat') }}" aria-label="address" aria-describedby="address" required></textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="input-group input-group-merge input-group-alternative mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Bank Donatur</i></span>
                                    </div>
                                    <?php
                                        $field_name = 'donator_bank_name';
                                        $field_data_id = 'donator_bank_name';
                                        $field_lable = __("benefactor::donators.$field_name");
                                        $field_placeholder = __("Select an option");
                                        $required = "required";
                                        $select_options = $banks;
                                    ?>
                                    {{ html()->select($field_data_id, $select_options)->placeholder($field_placeholder)->class('form-control select2')->attributes(["$required"]) }}    
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="input-group input-group-merge input-group-alternative mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">No. Rekening</span>
                                    </div>
                                    <input type="text" class="form-control" id="donator_bank_account" name="donator_bank_account" value="{{ old('donator_bank_account') }}" placeholder="{{ __('No. Rekening') }}" aria-label="donator_bank_account" aria-describedby="donator_bank_account" required>
                                </div>
                            </div>
                            <hr>
                            <div class="form-group">
                                <div class="input-group input-group-merge input-group-alternative mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                    </div>
                                    <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" placeholder="{{ __('E-Mail Address') }}" aria-label="email" aria-describedby="email" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="input-group input-group-merge input-group-alternative">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-key"></i></span>
                                    </div>
                                    <input type="password" class="form-control" id="password" name="password" placeholder="@lang('Password')" aria-label="@lang('Password')" aria-describedby="password" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="input-group input-group-merge input-group-alternative">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-key"></i></span>
                                    </div>
                                    <input type="password" class="form-control is-invalid has-danger" id="password_confirmation" name="password_confirmation" placeholder="@lang('Password Confirmation')" aria-label="@lang('password_confirmation')" aria-describedby="password_confirmation" required>
                                </div>
                            </div>

                            <!-- <div class="row my-4">
                                <div class="col-12">
                                    <div class="custom-control custom-control-alternative custom-checkbox">
                                        <input class="custom-control-input" id="customCheckRegister" type="checkbox">
                                        <label class="custom-control-label" for="customCheckRegister">
                                            <span class="text-muted">I agree with the <a href="#!">Privacy Policy</a></span>
                                        </label>
                                    </div>
                                </div>
                            </div> -->
                            <div class="text-center">
                                <button type="submit" class="btn btn-primary">
                                    @lang("Create account")
                                </button>
                            </div>
                            <div class="text-center text-muted m-4">
                                <small>Sudah Punya Akun? </small><a href={{route('login')}}><u>MASUK</u></a>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-6 text-left">
                        <a href="{{ route('password.request') }}" class="text-gray">
                            <small>{{ __('Forgot Your Password?') }}</small>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

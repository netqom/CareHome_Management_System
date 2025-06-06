@extends('layouts.guest')

@section('content')
    <div class="middle-box text-center loginscreen animated fadeInDown">
        <div>
            <div class="mb-4"><a href="{{ route('home') }}">
                {{-- <img src="{{ asset('login-page-logo.png') }}" width="100px;" alt="logo-img"> --}}
                <img src="{{ asset('logo.svg') }}" width="100px;" alt="logo-img" style="width:70px;height:70px">
            </a></div>
            <h3>OTP Verification</h3>
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif


            @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            <form method="POST" class="m-t" role="form" action="{{ route('otp.verified') }}" id="otp_form">
                @csrf
                <div class="form-group ">
                    <input class="form-control" type="text" id="otp" name="otp" maxlength="4" pattern="\d{4}"
                        title="Please enter a 4-digit OTP" placeholder="Enter OTP" required>
                        <div class="pt-1 text-right">
                            <a class="" href="{{ route('otp.resend') }}">
                    {{ __('Resend OTP') }}</a>
                         </div>
                </div>
                
                <button type="submit" class="btn btn-primary block full-width m-b" id="">Verify OTP</button>
            </form>
            <p class="m-t"> <!--small>Inspinia we app framework base on Bootstrap 3 &copy; 2014</small--> </p>
        </div>
    </div>
@endsection

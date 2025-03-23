@extends('layouts.app')

@section('content')
    <section class="py-[50px] flex flex-col items-center justify-center px-4">
        <div class="flex items-center">
            <svg class="w-16 h-16 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19a5 5 0 1 0 0-10 5 5 0 0 0 0 10z"></path>
            </svg>
            <div class="text-dark text-[24px] font-bold mt-2">Klasifikasi Penerima Bantuan</div>
        </div>
        <div class="text-[32px] font-semibold text-dark mt-[70px]">Sign In</div>
        <p class="mt-4 text-base leading-7 text-center mb-[50px] text-grey">
            Kelola data penduduk untuk<br />
            klasifikasi penerima bantuan
        </p>
        <form class="w-full card" method="POST" action="{{ route('login') }}">
            @csrf
            <div class="form-group">
                <label for="" class="text-grey">Email Address</label>
                <input type="email" name="email" class="input-field" />
            </div>
            <div class="form-group">
                <label for="" class="text-grey">Password</label>
                <input type="password" name="password" class="input-field" />
            </div>
            <button type="submit" class="w-full btn btn-primary mt-[14px] cursor-pointer">
                Sign In
            </button>
        </form>
    </section>
@endsection

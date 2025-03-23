@extends('layouts.app')

@section('content')
    <section class="py-[50px] flex flex-col items-center justify-center px-4">
        <img src="/assets/svgs/logo-type.svg" alt="" />
        <div class="text-[32px] font-semibold text-dark mt-[70px]">Sign In</div>
        <p class="mt-4 text-base leading-7 text-center mb-[50px] text-grey">
            Manage your data for<br />
            poor people classification
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

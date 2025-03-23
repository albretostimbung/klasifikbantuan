@extends('layouts.dashboard')

@section('content')
    <section class="py-[200px] flex flex-col items-center justify-center px-4">
        <div class="text-[32px] font-semibold text-dark mb-4">Klasifikasi Penduduk Miskin<br />dengan Decision Tree C.45
        </div>
        <form class="w-full card" @submit.prevent="createCompany">
            <div class="form-group">
                <label for="" class="text-grey">Name</label>
                <input type="text" class="input-field" name="name" />
            </div>
            <button type="submit" class="w-full btn btn-primary mt-[14px] cursor-pointer">
                Submit
            </button>
        </form>
    </section>
@endsection

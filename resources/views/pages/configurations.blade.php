@extends('layouts.base')

@section('content')
    <div class="app-content pt-3 p-md-3 p-lg-4">
        <div class="container-xl">

            <h1 class="app-page-title">Configurations</h1>
            <div class="row gy-4">
                @foreach ($settings as $setting)
                    @include('partials.config_item', ['setting' => $setting])
                @endforeach
            </div>
            <!--//row-->

        </div>
        <!--//container-fluid-->
    </div>
@endsection

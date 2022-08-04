@extends('layouts.base')

@section('content')
    <div class="app-content pt-3 p-md-3 p-lg-4">
        <div class="container-xl">
            <div class="row">
                <div class="col-md-12">
                    @include('partials.transfert-table', ['transferts' => $transferts])
                </div>
            </div>
        </div>
    </div>
@endsection

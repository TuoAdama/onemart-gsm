@extends('layouts.base')

@section('content')
    <div class="app-content pt-3 p-md-3 p-lg-4">
        <div class="container-xl">
            <div class="d-flex align-items-center justify-content-between mb-5">
                <h1 class="app-page-title">Transferts</h1>
                <form action="{{ route('setting.update') }}" method="POST">
                    @csrf
                    <div class="form-group">

                        <input type="hidden" class="form-control" name="key" value="{{ $setting->key ?? '' }}">
                    </div>
                    <div class="d-flex align-items-center">
                        <div class="">
                            <label for="form-label">Nombre d'echec : </label>
                            <input type="text" class="form-control" name="value" value="{{ $setting->value ?? '' }}">
                        </div>
                        <div class="ms-4 mt-3">
                            <button class="btn btn-primary" type="submit">Modifier</button>
                        </div>
                    </div>
                </form>
            </div>
            @if (Session::has('cancel'))
                <div class="row mb-3">
                    <div class="alert alert-warning">{{ Session::get('cancel') }}</div>
                </div>
            @endif
            @if (Session::has('relaunch'))
                <div class="row mb-3">
                    <div class="alert alert-success">{{ Session::get('relaunch') }}</div>
                </div>
            @endif
            <div class="row mt-5">
                <div class="col-md-4 mb-4">
                    <a class="btn btn-success" data-toggle="modal" data-target="#encours">Annuler tous les transferts en
                        attente</a>
                </div>
                <div class="col-md-4 mb-4">
                    <a class="btn btn-warning" data-toggle="modal" data-target="#relancer">Relancer tous les transferts
                        échoués</a>
                </div>
                <div class="col-md-4 mb-4">
                    <a class="btn btn-danger" data-toggle="modal" data-target="#annuler">Annuler tous les transferts
                        échoués</a>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    @include('partials.transfert-table', ['transferts' => $transferts])
                </div>
            </div>

        </div>
    </div>

    <div class="modal fade" id="annuler" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-danger">
                    <h5 class="modal-title" id="exampleModalLabel">Voulez-vous annuler tous les transferts echoués ?</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body"></div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Annuler</button>
                    <a href="{{ route('cancel') }}" class="btn btn-primary">Confirmer</a>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="relancer" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-danger">
                    <h5 class="modal-title" id="exampleModalLabel">Voulez-vous relancer tous les transferts echoués ?</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body"></div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Annuler</button>
                    <a href="{{ route('relaunch') }}" class="btn btn-primary">Confirmer</a>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="encours" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-danger">
                    <h5 class="modal-title" id="exampleModalLabel">Annuler tous les transferts en cours ?</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body"></div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Annuler</button>
                    <a href="{{ route('annuler.encours') }}" class="btn btn-primary">Confirmer</a>
                </div>
            </div>
        </div>
    </div>
@endsection

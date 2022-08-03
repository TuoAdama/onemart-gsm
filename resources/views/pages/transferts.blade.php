@extends('layouts.base')

@section('content')
    <div class="app-content pt-3 p-md-3 p-lg-4">
        <div class="container-xl">
            <h1 class="app-page-title">Transferts</h1>
            @if (Session::has('cancel'))
            <div class="row mb-3">
              <div class="alert alert-warning">{{Session::get('cancel')}}</div>
            </div>
            @endif
            @if (Session::has('relaunch'))
            <div class="row mb-3">
              <div class="alert alert-success">{{Session::get('relaunch')}}</div>
            </div>
            @endif
            <div class="row">
                <div class="col-md-12">
                    <table class="table table-striped" id="table">
                        <thead>
                          <tr>
                            <th scope="col">Date</th>
                            <th scope="col">Operation ID</th>
                            <th scope="col">Numero</th>
                            <th scope="col">Montant</th>
                            <th scope="col">Etats</th>
                          </tr>
                        </thead>
                        <tbody>
                          @foreach ($transferts as $transfert)
                              <tr>
                                <td>{{$transfert->updated_at->locale('fr')->isoFormat('lll')}}</td>
                                <td>{{$transfert->id}}</td>
                                <td>{{$transfert->numero}}</td>
                                <td>{{number_format($transfert->montant, 0, '.', ' ')}} FCFA</td>
                                <td class="text-bold {{$colors[$transfert->etat_id-1]}}">
                                  @php
                                      $etat = $transfert->etat;
                                  @endphp
                                  <span>{{$etat->libelle}}</span>
                                </td>
                              </tr>
                          @endforeach
                        </tbody>
                      </table>
                </div>
            </div>
            
        </div>
    </div>

    <div class="modal fade" id="annuler" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
            <a href="{{route('cancel')}}" class="btn btn-primary">Confirmer</a>
          </div>
        </div>
      </div>
    </div>

    <div class="modal fade" id="relancer" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
            <a href="{{route('relaunch')}}" class="btn btn-primary">Confirmer</a>
          </div>
        </div>
      </div>
    </div>

    <div class="modal fade" id="encours" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
            <a href="{{route('annuler.encours')}}" class="btn btn-primary">Confirmer</a>
          </div>
        </div>
      </div>
    </div>
@endsection
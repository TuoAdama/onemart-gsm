@extends('layouts.base')

@section('content')
    <div class="app-content pt-3 p-md-3 p-lg-4">
        <div class="container-xl">
            <h1 class="app-page-title">Transferts</h1>
            <div class="row">
                <div class="col-md-12">
                    <table class="table table-striped">
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
                                <td>{{$transfert->created_at}}</td>
                                <td>{{$transfert->id}}</td>
                                <td>{{$transfert->numero}}</td>
                                <td>{{number_format($transfert->montant, 0, '.', ' ')}} FCFA</td>
                                <td class="text-bold {{$colors[$transfert->etat_id-1]}}">{{$transfert->etat->libelle}}</td>
                              </tr>
                          @endforeach
                        </tbody>
                      </table>
                      {{$transferts->links()}}
                </div>
            </div>
        </div>
    </div>
@endsection
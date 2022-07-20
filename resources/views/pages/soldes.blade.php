@extends('layouts.base')

@section('content')
    <div class="app-content pt-3 p-md-3 p-lg-4">
        <div class="container-xl">
            <h1 class="app-page-title">Historique des soldes</h1>
            <div class="row">
                <div class="col-md-12">
                    <table class="table table-striped" id="table">
                        <thead>
                          <tr>
                            <th scope="col">Date</th>
                            <th scope="col">ID</th>
                            <th scope="col">Bonus</th>
                            <th scope="col">Solde</th>
                            <th scope="col">Total</th>
                          </tr>
                        </thead>
                        <tbody>
                          @foreach ($soldes as $solde)
                              <tr>
                                <td>{{$solde->updated_at->locale('fr')->isoFormat('lll')}}</td>
                                <td>{{$solde->id}}</td>
                                <td>{{number_format($solde->bonus, 0, '.', ' ')}} FCFA</td>
                                <td>{{number_format($solde->solde, 0, '.', ' ')}} FCFA</td>
                                <td>{{number_format($solde->bonus + $solde->solde, 0, '.', ' ')}} FCFA</td>
                              </tr>
                          @endforeach
                        </tbody>
                      </table>
                </div>
            </div>
        </div>
    </div>
@endsection
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
            @php
                $created_at = $transfert->getRawOriginal('created_at');
                $created_at = Carbon\Carbon::parse($created_at);
            @endphp
            <tr>
                <td>{{ $created_at->locale('fr')->isoFormat('lll') }}</td>
                <td>{{ $transfert->id }}</td>
                <td>{{ $transfert->numero }}</td>
                <td>{{ number_format($transfert->montant, 0, '.', ' ') }} FCFA</td>
                <td class="text-bold {{ $colors[$transfert->etat_id - 1] }}">
                    @php
                        $etat = $transfert->etat;
                    @endphp
                    <span>{{ $etat->libelle }}</span>
                    @isset($resetTransfert)
                        @if ($etat->libelle == App\Models\Etat::ECHOUE)
                            <a href="{{ route('transfert.update', ['transfert_id' => $transfert->id, 'etat' => App\Models\Etat::EN_ATTENTE]) }}"
                                class="ms-3 btn btn-primary">R</a>
                            <a href="{{ route('transfert.update', ['transfert_id' => $transfert->id, 'etat' => App\Models\Etat::ANNULE]) }}"
                                class="ms-3 btn btn-danger">
                                <i class="fa-solid fa-arrow-rotate-right">A</i>
                            </a>
                        @endif
                    @endisset
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

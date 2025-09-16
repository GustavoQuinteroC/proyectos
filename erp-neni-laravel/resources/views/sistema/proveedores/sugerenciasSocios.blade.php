@if(count($socios) > 0)
    <ul class="list-group">
        @foreach($socios as $socio)
            <li class="list-group-item suggestion" data-name="{{ $socio->name }}" data-telefono="{{ $socio->telefono }}" data-sexo="{{ $socio->sexo }}">
                {{ $socio->name }}
            </li>
        @endforeach
    </ul>
@endif
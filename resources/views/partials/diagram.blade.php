{{--
    El diagrama del hero: Laravel en el centro y los sistemas que integras alrededor.
    Para cambiar los nodos, edita $nodes (x, y = esquina superior izquierda; edge = ruta hasta el núcleo).
--}}
@php
    $nodes = [
        ['name' => 'WooCommerce', 'kind' => 'tienda online', 'x' => 40,  'y' => 40,  'edge' => 'M190 66 H290 V228'],
        ['name' => 'WhatsApp API', 'kind' => 'notificaciones', 'x' => 450, 'y' => 40,  'edge' => 'M450 66 H350 V228'],
        ['name' => 'React', 'kind' => 'interfaz', 'x' => 0,   'y' => 234, 'edge' => 'M150 260 H235'],
        ['name' => 'Azure', 'kind' => 'nube', 'x' => 490, 'y' => 234, 'edge' => 'M490 260 H405'],
        ['name' => 'MySQL', 'kind' => 'datos', 'x' => 40,  'y' => 428, 'edge' => 'M190 454 H290 V292'],
        ['name' => 'Dolibarr', 'kind' => 'ERP', 'x' => 450, 'y' => 428, 'edge' => 'M450 454 H350 V292'],
    ];
@endphp
<svg class="diagram" viewBox="-6 0 652 520" role="img" aria-labelledby="diagram-title diagram-desc" data-diagram>
    <title id="diagram-title">Diagrama de integraciones</title>
    <desc id="diagram-desc">Laravel en el centro, conectado con {{ collect($nodes)->pluck('name')->join(', ', ' y ') }}.</desc>

    @foreach ($nodes as $i => $node)
        <path id="edge-{{ $i }}" class="edge" d="{{ $node['edge'] }}" data-edge="{{ $i }}"/>
    @endforeach

    {{-- Paquetes de datos viajando: unos van hacia el núcleo y otros salen de él --}}
    @foreach ($nodes as $i => $node)
        <circle class="packet" r="4" aria-hidden="true">
            <animateMotion dur="{{ 2.6 + ($i % 3) * 0.7 }}s" begin="-{{ $i * 0.45 }}s" repeatCount="indefinite"
                @if ($i % 2) keyPoints="1;0" keyTimes="0;1" calcMode="linear" @endif>
                <mpath href="#edge-{{ $i }}"/>
            </animateMotion>
        </circle>
    @endforeach

    @foreach ($nodes as $i => $node)
        <g class="node" tabindex="0" data-node="{{ $i }}" transform="translate({{ $node['x'] }} {{ $node['y'] }})">
            <rect width="150" height="52"/>
            <text x="14" y="22">{{ $node['name'] }}</text>
            <text class="kind" x="14" y="39">{{ $node['kind'] }}</text>
        </g>
    @endforeach

    <circle class="core-ring" cx="320" cy="260" r="112" aria-hidden="true"/>
    <g class="node core">
        <rect x="235" y="228" width="170" height="64"/>
        <text x="253" y="255">Laravel</text>
        <text class="kind" x="253" y="275">núcleo del negocio</text>
    </g>
    @foreach ([[290, 228], [350, 228], [235, 260], [405, 260], [290, 292], [350, 292]] as [$px, $py])
        <circle class="port" cx="{{ $px }}" cy="{{ $py }}" r="4.5" aria-hidden="true"/>
    @endforeach
</svg>

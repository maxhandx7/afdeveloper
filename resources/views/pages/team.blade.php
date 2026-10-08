@extends('layouts.web')
@section('title', 'Equipo — '.$business->name)
@section('description', 'Las personas detrás de AF Developer.')

@section('content')
<section class="page-head">
    <div class="wrap">
        <h1 class="display">Equipo</h1>
        <p class="lead">Las personas con las que construyo los proyectos más grandes.</p>
    </div>
</section>
<section class="section">
    <div class="wrap">
        @if ($teams->isEmpty())
            <p class="empty">Pronto presentaré al equipo.</p>
        @else
            <ul class="team">
                @foreach ($teams as $member)
                    <li>
                        @if ($member->image)<img src="{{ asset($member->image) }}" alt="{{ $member->name }}" loading="lazy">@endif
                        <h3>{{ $member->name }}</h3>
                        <p>{{ $member->rol }}</p>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
</section>
@endsection

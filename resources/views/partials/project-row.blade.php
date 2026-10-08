<a class="project" href="{{ route('projects.show', $project) }}">
    <div class="project-thumb">
        @if ($project->image)
            <img src="{{ asset($project->image) }}" alt="" loading="lazy">
        @endif
    </div>
    <div>
        <h3>{{ $project->title }}</h3>
        @if ($project->description)
            <p>{{ \Illuminate\Support\Str::limit($project->description, 160) }}</p>
        @endif
        @if (! empty($project->tech_stack))
            <ul class="pipeline" aria-label="Tecnologías">
                @foreach (array_slice($project->tech_stack, 0, 5) as $tech)
                    <li>{{ $tech }}</li>
                @endforeach
            </ul>
        @endif
    </div>
    <span class="project-go" aria-hidden="true">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
    </span>
</a>

<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Project;
use App\Models\Team;
use App\Models\Testimonial;
use Illuminate\Contracts\View\View;

class SiteController extends Controller
{
    public function home(): View
    {
        $featured = Project::published()->where('is_featured', true)->limit(3)->get();

        return view('pages.home', [
            'proyectos' => $featured->isNotEmpty() ? $featured : Project::published()->limit(3)->get(),
            'projectsCount' => Project::published()->count(),
            'clients' => Testimonial::published()->limit(6)->get(),
            'posts' => Post::published()->limit(3)->get(),
        ]);
    }

    public function projects(): View
    {
        return view('pages.projects', ['proyects' => Project::published()->get()]);
    }

    public function project(Project $project): View
    {
        abort_unless(Project::published()->whereKey($project->getKey())->exists(), 404);

        return view('pages.project', [
            'project' => $project,
            'others' => Project::published()->whereKeyNot($project->getKey())->limit(3)->get(),
        ]);
    }

    public function testimonials(): View
    {
        return view('pages.testimonials', ['clients' => Testimonial::published()->get()]);
    }

    public function team(): View
    {
        return view('pages.team', ['teams' => Team::orderBy('sort_order')->get()]);
    }
}

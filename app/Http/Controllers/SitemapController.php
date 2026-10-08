<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Project;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function __invoke(): Response
    {
        $urls = collect([
            ['loc' => route('home'), 'priority' => '1.0'],
            ['loc' => route('projects'), 'priority' => '0.8'],
            ['loc' => route('blog.index'), 'priority' => '0.8'],
            ['loc' => route('testimonials'), 'priority' => '0.5'],
            ['loc' => route('team'), 'priority' => '0.4'],
        ])->merge(Project::published()->get()->map(fn (Project $project) => [
            'loc' => route('projects.show', $project),
            'lastmod' => $project->updated_at?->toAtomString(),
            'priority' => '0.7',
        ]))->merge(Post::published()->get()->map(fn (Post $post) => [
            'loc' => route('blog.show', $post),
            'lastmod' => $post->updated_at?->toAtomString(),
            'priority' => '0.7',
        ]));

        return response()
            ->view('sitemap', ['urls' => $urls])
            ->header('Content-Type', 'application/xml');
    }
}

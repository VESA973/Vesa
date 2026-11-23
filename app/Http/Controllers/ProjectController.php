<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ProjectController extends Controller
{
    /**
     * List published projects, 4 per page, optionally filtered by website type.
     */
    public function index(Request $request): View
    {
        $category = $request->validate([
            'categorie' => ['nullable', Rule::in(array_keys(Project::CATEGORIES))],
        ])['categorie'] ?? null;

        // Key figures and client logos always cover the whole portfolio, not just the current page.
        $allProjects = Project::query()->published()->get();

        $projects = Project::query()
            ->published()
            ->when($category, fn ($query) => $query->where('category', $category))
            ->paginate(4)
            ->withQueryString();

        return view('pages.realisations.index', compact('allProjects', 'projects', 'category'));
    }

    /**
     * Show one published project as a short case study.
     */
    public function show(Project $project): View
    {
        abort_unless($project->is_published, 404);

        $nextProject = Project::query()->published()->whereKeyNot($project->id)->first();

        return view('pages.realisations.show', compact('project', 'nextProject'));
    }
}

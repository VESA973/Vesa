<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProjectTest extends TestCase
{
    use RefreshDatabase;

    public function test_portfolio_shows_only_published_projects(): void
    {
        $published = Project::factory()->create(['title' => 'Borne de commande']);
        $draft = Project::factory()->draft()->create(['title' => 'Projet brouillon']);

        $this->get('/realisations')
            ->assertOk()
            ->assertSee($published->title)
            ->assertDontSee($draft->title);
    }

    public function test_portfolio_shows_empty_state_without_projects(): void
    {
        $this->get('/realisations')->assertOk()->assertSee('Nos réalisations arrivent très bientôt.');
    }

    public function test_portfolio_filters_by_category(): void
    {
        Project::factory()->create(['title' => 'Site association', 'category' => 'vitrine']);
        Project::factory()->create(['title' => 'Boutique en ligne', 'category' => 'ecommerce']);

        $this->get('/realisations?categorie=ecommerce')
            ->assertOk()
            ->assertSee('Boutique en ligne')
            ->assertDontSee('Site association');
    }

    public function test_portfolio_shows_four_projects_per_page(): void
    {
        foreach (range(1, 5) as $position) {
            Project::factory()->create(['title' => "Projet numéro {$position}", 'sort_order' => $position]);
        }

        $this->get('/realisations')
            ->assertOk()
            ->assertSee('Projet numéro 4')
            ->assertDontSee('Projet numéro 5')
            ->assertSee('Suivant');

        $this->get('/realisations?page=2')
            ->assertOk()
            ->assertSee('Projet numéro 5')
            ->assertDontSee('Projet numéro 1')
            ->assertSee('data-count="5"', false);
    }

    public function test_portfolio_shows_stats_and_client_logos(): void
    {
        foreach (['Client A', 'Client B', 'Client C'] as $client) {
            Project::factory()->create(['client' => $client, 'client_logo' => 'clients/'.str($client)->slug().'.png']);
        }

        $this->get('/realisations')
            ->assertOk()
            ->assertSee('data-count="3"', false)
            ->assertSee('clients/client-a.png', false);
    }

    public function test_unknown_category_is_rejected(): void
    {
        $this->get('/realisations?categorie=inconnue')->assertRedirect();
    }

    public function test_published_project_page_shows_case_study(): void
    {
        $project = Project::factory()->create(['challenge' => 'Un besoin précis', 'solution' => 'Une solution claire']);

        $this->get("/realisations/{$project->slug}")
            ->assertOk()
            ->assertSee($project->title)
            ->assertSee('Un besoin précis')
            ->assertSee('Une solution claire');
    }

    public function test_draft_project_page_is_not_found(): void
    {
        $project = Project::factory()->draft()->create();

        $this->get("/realisations/{$project->slug}")->assertNotFound();
    }

    public function test_admin_lists_projects(): void
    {
        $project = Project::factory()->create();

        $this->actingAs(User::factory()->create())
            ->get('/admin/projects')
            ->assertOk()
            ->assertSee($project->title);

        $this->actingAs(User::factory()->create())
            ->get('/admin/projects/create')
            ->assertOk();
    }
}

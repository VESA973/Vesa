<?php

namespace Tests\Feature;

use App\Models\RepairDevice;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RepairTest extends TestCase
{
    use RefreshDatabase;

    public function test_repair_overview_links_to_each_section(): void
    {
        RepairDevice::factory()->create(['family' => 'instruments', 'name' => 'Yamaha']);

        $this->get('/reparation')
            ->assertOk()
            ->assertSee('/reparation/instruments', false)
            ->assertSee('/reparation/consoles', false)
            ->assertSee('/reparation/autres', false)
            ->assertSee('Yamaha');
    }

    public function test_section_page_lists_its_published_devices_with_their_repairs(): void
    {
        $piano = RepairDevice::factory()->create(['family' => 'instruments', 'name' => 'Yamaha']);
        $piano->services()->create(['name' => 'Touches qui ne répondent plus', 'price_from' => 49]);
        RepairDevice::factory()->create(['family' => 'consoles', 'name' => 'PlayStation 5']);
        RepairDevice::factory()->draft()->create(['family' => 'instruments', 'name' => 'Piano caché']);

        $this->get('/reparation/instruments')
            ->assertOk()
            ->assertSeeInOrder(['Instruments de musique', 'Yamaha', 'Touches qui ne répondent plus', 'dès 49 €'])
            ->assertDontSee('PlayStation 5')
            ->assertDontSee('Piano caché');
    }

    public function test_empty_section_shows_a_message(): void
    {
        $this->get('/reparation/consoles')->assertOk()->assertSee('Cette rubrique sera bientôt complétée.');
    }

    public function test_unknown_section_is_not_found(): void
    {
        $this->get('/reparation/inconnue')->assertNotFound();
    }

    public function test_header_has_the_repair_submenu(): void
    {
        $this->get('/solutions')
            ->assertOk()
            ->assertSee('href="'.route('home').'" class="relative', false)
            ->assertSee('Accueil')
            ->assertSee(route('reparation.family', 'instruments'), false)
            ->assertSee(route('reparation.family', 'consoles'), false);
    }

    public function test_admin_can_manage_repairs(): void
    {
        $device = RepairDevice::factory()->create(['name' => 'Roland']);
        $admin = User::factory()->create();

        $this->actingAs($admin)->get('/admin/repair-devices')->assertOk()->assertSee('Roland');
        $this->actingAs($admin)->get('/admin/repair-devices/create')->assertOk();
        $this->actingAs($admin)->get("/admin/repair-devices/{$device->id}/edit")->assertOk();
    }
}

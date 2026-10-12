<?php

namespace Tests\Feature;

use App\Models\Evenement;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class EvenementTest extends TestCase
{
    use RefreshDatabase;

    private function evenement(array $overrides = []): Evenement
    {
        return Evenement::create(array_merge([
            'title_fr' => 'Panel Digital',
            'title_en' => 'Digital Panel',
            'slug' => 'panel-digital',
            'type' => 'panel',
            'starts_at' => now()->addWeek(),
            'published' => true,
        ], $overrides));
    }

    private function payload(array $overrides = []): array
    {
        return array_merge([
            'full_name' => 'Awa Ouedraogo',
            'phone' => '70 11 22 33',
            'profession' => 'Développeuse',
            'city' => 'Bobo-Dioulasso',
            'epa_status' => 'non',
            'how_heard' => 'Facebook',
        ], $overrides);
    }

    public function test_public_pages_show_published_events_only(): void
    {
        $this->evenement();
        $this->evenement(['title_fr' => 'Brouillon secret', 'slug' => 'brouillon', 'published' => false]);

        $this->get('/evenements')->assertOk()->assertSee('Panel Digital')->assertDontSee('Brouillon secret');
        $this->get('/evenements/panel-digital')->assertOk()->assertSee("Je m'inscris", false);
        $this->get('/evenements/brouillon')->assertNotFound();
        $this->get('/evenements/panel-digital/calendrier.ics')->assertOk()->assertSee('BEGIN:VEVENT');
        $this->get('/')->assertOk()->assertSee('Prochains événements');
    }

    public function test_registration_with_day_choice_and_capacity(): void
    {
        Mail::fake();
        $evenement = $this->evenement();
        $jour1 = $evenement->sessions()->create(['title_fr' => 'Jour 1', 'title_en' => 'Day 1', 'capacity' => 1]);
        $jour2 = $evenement->sessions()->create(['title_fr' => 'Jour 2', 'title_en' => 'Day 2']);

        $url = "/evenements/{$evenement->slug}/inscription";

        $this->post($url, $this->payload())->assertSessionHasErrors('sessions');

        $this->post($url, $this->payload(['email' => 'awa@example.com', 'sessions' => [$jour1->id, $jour2->id]]))
            ->assertRedirect();
        $this->assertDatabaseCount('evenement_inscriptions', 1);
        $this->assertCount(2, $evenement->inscriptions()->first()->sessions);

        // même numéro formaté autrement → doublon
        $this->post($url, $this->payload(['phone' => '70112233', 'sessions' => [$jour2->id]]))
            ->assertSessionHasErrors('phone');

        // jour 1 complet
        $this->post($url, $this->payload(['phone' => '75 00 00 00', 'sessions' => [$jour1->id]]))
            ->assertSessionHasErrors('sessions');

        $this->post($url, $this->payload(['phone' => '75 00 00 00', 'sessions' => [$jour2->id]]))->assertRedirect();
        $this->assertDatabaseCount('evenement_inscriptions', 2);
    }

    public function test_registration_closed_or_past_is_refused(): void
    {
        $closed = $this->evenement(['registration_open' => false]);
        $this->post("/evenements/{$closed->slug}/inscription", $this->payload())->assertSessionHas('error');

        $past = $this->evenement(['slug' => 'passe', 'starts_at' => now()->subMonth()]);
        $this->post("/evenements/{$past->slug}/inscription", $this->payload())->assertSessionHas('error');
        $this->assertDatabaseCount('evenement_inscriptions', 0);
    }

    public function test_admin_can_create_event_with_sessions_and_export(): void
    {
        $this->get('/admin/evenements')->assertRedirect('/login');

        $admin = User::factory()->create();

        $this->actingAs($admin)->post('/admin/evenements', [
            'title_fr' => '3e Panel',
            'title_en' => '3rd Panel',
            'type' => 'panel',
            'starts_at' => now()->addDays(10)->format('Y-m-d\TH:i'),
            'published' => 1,
            'registration_open' => 1,
            'sessions' => [
                ['title_fr' => 'Jour 1', 'capacity' => 50],
                ['title_fr' => 'Jour 2'],
            ],
        ])->assertRedirect('/admin/evenements');

        $evenement = Evenement::firstWhere('slug', '3e-panel');
        $this->assertNotNull($evenement);
        $this->assertCount(2, $evenement->sessions);

        $this->actingAs($admin)->get('/admin/evenements')->assertOk()->assertSee('3e Panel');
        $this->actingAs($admin)->get("/admin/evenements/{$evenement->id}/edit")->assertOk();
        $this->actingAs($admin)->get("/admin/evenements/{$evenement->id}/inscriptions")->assertOk();
        $this->actingAs($admin)->get("/admin/evenements/{$evenement->id}/inscriptions/export")->assertOk();
    }
}

<?php

namespace Database\Seeders;

use App\Models\Project;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

/**
 * Real client websites shown in the portfolio. Safe to re-run: projects are matched by slug.
 */
class PortfolioSeeder extends Seeder
{
    public function run(): void
    {
        // Covers and client logos ship with the code (storage/ is not in Git): copy them in if missing.
        foreach (File::allFiles(database_path('seeders/images')) as $file) {
            $path = $file->getRelativePathname();
            if (! Storage::disk('public')->exists($path)) {
                Storage::disk('public')->put($path, $file->getContents());
            }
        }

        $projects = [
            [
                'slug' => 'hostellerie-des-iles',
                'title' => 'Hostellerie des Îles',
                'category' => 'application',
                'client' => 'Hostellerie des Îles',
                'excerpt' => 'Le site et le système de réservation d’un hôtel-restaurant sur l’Île Royale, aux îles du Salut en Guyane.',
                'challenge' => 'Présenter un lieu hors du temps, accessible uniquement par bateau, et permettre de réserver en ligne chambres, repas, groupes et traversées.',
                'solution' => 'Un site immersif (galerie, hébergement, restaurant, groupes, histoire de l’île) relié à un back-office complet : chambres et tarifs saisonniers, réservations individuelles et de groupe, transport, menus, codes promo, facturation et paiements.',
                'result' => 'L’hôtel gère réservations, séjours et factures depuis un seul outil, et ses visiteurs préparent leur voyage directement en ligne.',
                'technologies' => ['Symfony', 'PHP 8.4'],
                'url' => 'https://hostelleriedesiles.fr',
                'cover_image' => 'projects/hostellerie-des-iles.webp',
                'is_featured' => true,
                'sort_order' => 1,
            ],
            [
                'slug' => 'class-affaire',
                'title' => 'Class’Affaire',
                'category' => 'application',
                'client' => 'Class’Affaire Exclusive',
                'client_logo' => 'clients/class-affaire.png',
                'excerpt' => 'Une plateforme de location de véhicules avec chauffeur pour les évènements : « Vos évènements, nos chauffeurs. »',
                'challenge' => 'Proposer un catalogue de véhicules haut de gamme et traiter les demandes de réservation, du devis jusqu’à la facture.',
                'solution' => 'Un catalogue de véhicules, un formulaire de demande en ligne et un espace de gestion : clients, prestations, réservations, devis, factures et suivi des paiements, avec e-mails automatiques.',
                'result' => 'Chaque demande devient un devis puis une facture sans ressaisie, et le client suit son évènement de bout en bout.',
                'technologies' => ['Laravel', 'React', 'Tailwind CSS'],
                'cover_image' => 'projects/class-affaire.webp',
                'is_featured' => true,
                'sort_order' => 2,
            ],
            [
                'slug' => 'rvld-radio',
                'title' => 'RVLD Radio',
                'category' => 'vitrine',
                'client' => 'RVLD Radio',
                'client_logo' => 'clients/rvld.png',
                'excerpt' => 'Le site de la radio gospel RVLD : direct, webradios, podcasts, programmes et actualités.',
                'challenge' => 'Réunir l’écoute en direct, les émissions, les podcasts et la vie de la radio dans un site simple à mettre à jour par l’équipe.',
                'solution' => 'Un lecteur en direct, une grille des programmes, des podcasts classés par catégorie, des actualités, des témoignages, une page partenaires et un module de dons, le tout administrable.',
                'result' => 'L’équipe publie elle-même émissions, podcasts et actualités, et les auditeurs retrouvent tout au même endroit.',
                'technologies' => ['Laravel', 'Tailwind CSS'],
                'url' => 'https://rvld.fr',
                'cover_image' => 'projects/rvld-radio.webp',
                'is_featured' => true,
                'sort_order' => 3,
            ],
            [
                'slug' => 'addc-cayenne',
                'title' => 'Église AdDC Cayenne',
                'category' => 'vitrine',
                'client' => 'Église AdDC Cayenne',
                'client_logo' => 'clients/addc.png',
                'excerpt' => 'Le site de l’église AdDC à Cayenne : prédications, évènements, ministères et vie de la communauté.',
                'challenge' => 'Donner à l’église un site vivant pour partager ses enseignements et informer sa communauté.',
                'solution' => 'Des prédications classées en séries, un agenda des évènements, la présentation des ministères et des responsables, la louange, les actions sociales, les dons et une newsletter, avec des pages modifiables depuis l’administration.',
                'result' => 'La communauté retrouve en ligne enseignements et évènements, et l’église garde la main sur tout son contenu.',
                'technologies' => ['Symfony', 'PHP 8.4'],
                'cover_image' => 'projects/addc-cayenne.webp',
                'sort_order' => 4,
            ],
        ];

        foreach ($projects as $project) {
            Project::query()->updateOrCreate(
                ['slug' => $project['slug']],
                $project + ['is_published' => true, 'is_featured' => false, 'client_logo' => null, 'url' => null],
            );
        }
    }
}

<?php

namespace Database\Seeders;

use App\Models\RepairDevice;
use Illuminate\Database\Seeder;

/**
 * Starting content for the repair page (instruments, consoles, other devices). Everything is editable
 * in the admin afterwards. Safe to re-run: devices are matched by family and name, their repairs replaced.
 * No prices are set: they are added in the admin when known.
 */
class RepairCatalogSeeder extends Seeder
{
    public function run(): void
    {
        $catalog = [
            'instruments' => [
                'Yamaha' => ['Pianos numériques, Clavinova, claviers arrangeurs et synthétiseurs.', [
                    'Touches qui ne répondent plus ou sonnent mal',
                    'Remplacement des contacts et capteurs du clavier',
                    'Panne d’alimentation ou de carte électronique',
                    'Haut-parleurs, sortie casque et connectique audio',
                    'Écran, boutons et potentiomètres',
                ]],
                'Korg' => ['Synthétiseurs, workstations et pianos numériques.', [
                    'Clavier : contacts, touches et vélocité',
                    'Encodeurs, potentiomètres et boutons',
                    'Alimentation et carte électronique',
                    'Sorties audio et MIDI',
                ]],
                'Roland' => ['Pianos numériques, synthétiseurs et instruments de scène.', [
                    'Capteurs de touches et mécanique du clavier',
                    'Carte principale et alimentation',
                    'Pédales et connecteurs de pédale',
                    'Connectique audio, USB et MIDI',
                ]],
                'Autres marques' => ['Casio, Kawai, Nord, Kurzweil… diagnostic toutes marques de claviers, pédales et effets.', [
                    'Diagnostic complet et devis',
                    'Clavier, alimentation et connectique',
                    'Pédales d’effet et accessoires',
                ]],
            ],
            'consoles' => [
                'PlayStation 5' => ['Console de salon et manette DualSense.', [
                    'Remplacement du port HDMI',
                    'Nettoyage, pâte thermique et ventilateur',
                    'Lecteur de disque',
                    'Manette DualSense : joysticks qui dérivent, gâchettes',
                ]],
                'PlayStation 4' => ['PS4, PS4 Slim et PS4 Pro.', [
                    'Remplacement du port HDMI',
                    'Surchauffe et bruit du ventilateur',
                    'Disque dur et passage en SSD',
                    'Alimentation',
                ]],
                'Nintendo Switch' => ['Switch, Switch Lite et Switch OLED.', [
                    'Remplacement de l’écran',
                    'Joy-Con qui dérivent',
                    'Port de charge USB-C',
                    'Lecteur de cartouches',
                ]],
                'Xbox' => ['Xbox Series X|S et Xbox One.', [
                    'Remplacement du port HDMI',
                    'Alimentation',
                    'Surchauffe et ventilateur',
                ]],
            ],
            'autres' => [
                'Ordinateurs' => ['Portables et fixes : panne, lenteur, remplacement de pièces.', []],
                'Cartes électroniques' => ['Diagnostic et réparation au niveau composant.', []],
                'Matériel professionnel' => ['Équipements spécifiques de votre activité.', []],
            ],
        ];

        foreach ($catalog as $family => $devices) {
            $order = 0;
            foreach ($devices as $name => [$description, $repairs]) {
                $device = RepairDevice::query()->updateOrCreate(
                    ['family' => $family, 'name' => $name],
                    ['description' => $description, 'is_published' => true, 'sort_order' => $order++],
                );
                $device->services()->delete();
                foreach ($repairs as $position => $repair) {
                    $device->services()->create(['name' => $repair, 'sort_order' => $position]);
                }
            }
        }
    }
}

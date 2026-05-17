<?php

namespace Database\Seeders;

use App\Models\Service;
use App\Models\ServiceCategory;
use Illuminate\Database\Seeder;

class ServiceCatalogSeeder extends Seeder
{
    public function run(): void
    {
        $facials = ServiceCategory::query()->updateOrCreate(
            ['slug' => 'facials'],
            [
                'name_fr' => 'Hydrafacial',
                'name_en' => 'Hydrafacial',
                'description_fr' => 'Protocoles Hydrafacial experts adaptés aux besoins de votre peau.',
                'description_en' => 'Expert Hydrafacial protocols tailored to your skin needs.',
                'is_active' => true,
                'sort_order' => 1,
            ]
        );

        $services = [
            [
                'slug' => 'hydrafacial-essentiel',
                'category_id' => $facials->id,
                'name_fr' => 'Hydrafacial Essentiel',
                'name_en' => 'Hydrafacial Essential',
                'short_description_fr' => 'Nettoyage profond, extraction douce, masque ciblé et LED.',
                'short_description_en' => 'Deep cleansing, gentle extraction, targeted mask, and LED.',
                'description_fr' => 'Nettoyage cutané profond associé à une exfoliation contrôlée, suivi d’une extraction douce des comédons ouverts et fermés. Le soin se complète par l’application d’un masque adapté et une séance de photobiomodulation LED afin d’optimiser l’éclat et l’équilibre cutané.',
                'description_en' => 'Deep skin cleansing with controlled exfoliation, followed by gentle extraction of open and closed comedones. The treatment ends with a targeted mask and LED photobiomodulation session to optimize glow and skin balance.',
                'price_tnd' => 250,
                'price_eur' => 75,
                'duration_minutes' => 60,
                'buffer_minutes' => 10,
                'is_featured' => true,
                'sort_order' => 1,
            ],
            [
                'slug' => 'hydrafacial-detoxifiant',
                'category_id' => $facials->id,
                'name_fr' => 'Hydrafacial Détoxifiant',
                'name_en' => 'Hydrafacial Detoxifying',
                'short_description_fr' => 'Soin purifiant intensif avec dermaplaning, masque exfoliant et LED.',
                'short_description_en' => 'Intensive purifying treatment with dermaplaning, exfoliating mask, and LED.',
                'description_fr' => 'Soin purifiant intensif visant à désengorger les peaux asphyxiées. Il associe un nettoyage complet, un dermaplaning pour éliminer les cellules mortes et le duvet, une tonification cutanée ainsi qu’un masque exfoliant, suivi d’une luminothérapie LED pour rééquilibrer la peau. Indiqué pour les peaux ternes, obstruées ou sujettes aux imperfections.',
                'description_en' => 'Intensive purifying treatment designed to decongest suffocated skin. It combines complete cleansing, dermaplaning to remove dead cells and peach fuzz, skin toning, and an exfoliating mask, followed by LED light therapy to rebalance the skin. Recommended for dull, congested, or blemish-prone skin.',
                'price_tnd' => 300,
                'price_eur' => 90,
                'duration_minutes' => 60,
                'buffer_minutes' => 10,
                'is_featured' => true,
                'sort_order' => 2,
            ],
        ];

        foreach ($services as $serviceData) {
            Service::query()->updateOrCreate(
                ['slug' => $serviceData['slug']],
                array_merge($serviceData, ['is_active' => true])
            );
        }

        Service::query()->whereNotIn('slug', array_column($services, 'slug'))->update(['is_active' => false]);
    }
}

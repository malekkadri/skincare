<?php

namespace Database\Seeders;

use App\Models\Service;
use App\Models\ServiceCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ServiceCatalogSeeder extends Seeder
{
    public function run(): void
    {
        $facials = ServiceCategory::query()->updateOrCreate(
            ['slug' => 'facials'],
            [
                'name_fr' => 'Soins du visage',
                'name_en' => 'Facials',
                'description_fr' => 'Des protocoles visage premium pour hydrater et illuminer la peau.',
                'description_en' => 'Premium facial protocols designed to hydrate and brighten the skin.',
                'is_active' => true,
                'sort_order' => 1,
            ]
        );

        $advanced = ServiceCategory::query()->updateOrCreate(
            ['slug' => 'advanced-treatments'],
            [
                'name_fr' => 'Traitements avancés',
                'name_en' => 'Advanced Treatments',
                'description_fr' => 'Traitements ciblés pour texture, taches et éclat global.',
                'description_en' => 'Targeted treatments for texture, discoloration, and overall glow.',
                'is_active' => true,
                'sort_order' => 2,
            ]
        );

        $consultations = ServiceCategory::query()->updateOrCreate(
            ['slug' => 'consultations'],
            [
                'name_fr' => 'Consultations',
                'name_en' => 'Consultations',
                'description_fr' => 'Évaluation personnalisée et plan de soin adapté.',
                'description_en' => 'Personalized evaluation with a tailored skincare plan.',
                'is_active' => true,
                'sort_order' => 3,
            ]
        );

        $services = [
            [
                'category_id' => $facials->id,
                'name_fr' => 'Soin Hydratant Intense',
                'name_en' => 'Hydrating Facial',
                'short_description_fr' => 'Hydratation profonde pour peaux sèches ou déshydratées.',
                'short_description_en' => 'Deep hydration treatment for dry or dehydrated skin.',
                'description_fr' => 'Nettoyage doux, exfoliation légère, masque hydratant et massage relaxant.',
                'description_en' => 'Gentle cleansing, light exfoliation, hydrating mask, and relaxing massage.',
                'price_tnd' => 180,
                'price_eur' => 55,
                'duration_minutes' => 60,
                'buffer_minutes' => 10,
                'is_featured' => true,
                'sort_order' => 1,
            ],
            [
                'category_id' => $facials->id,
                'name_fr' => 'Soin Purifiant Profond',
                'name_en' => 'Deep Cleansing Facial',
                'short_description_fr' => 'Purifie les pores et réduit les imperfections.',
                'short_description_en' => 'Purifies pores and helps reduce blemishes.',
                'description_fr' => 'Extraction experte, apaisement et finition équilibrante.',
                'description_en' => 'Expert extraction, soothing care, and balancing finish.',
                'price_tnd' => 200,
                'price_eur' => 62,
                'duration_minutes' => 75,
                'buffer_minutes' => 10,
                'is_featured' => true,
                'sort_order' => 2,
            ],
            [
                'category_id' => $consultations->id,
                'name_fr' => 'Consultation Cutanée',
                'name_en' => 'Skin Consultation',
                'short_description_fr' => 'Analyse complète de la peau et routine recommandée.',
                'short_description_en' => 'Comprehensive skin analysis with routine recommendations.',
                'description_fr' => 'Diagnostic personnalisé avec conseils produits et plan de suivi.',
                'description_en' => 'Personalized diagnosis with product guidance and follow-up plan.',
                'price_tnd' => 90,
                'price_eur' => 28,
                'duration_minutes' => 40,
                'buffer_minutes' => 5,
                'is_featured' => false,
                'sort_order' => 3,
            ],
            [
                'category_id' => $advanced->id,
                'name_fr' => 'Peeling Éclat Doux',
                'name_en' => 'Glow Peel Treatment',
                'short_description_fr' => 'Peeling doux pour raviver l\'éclat et lisser le grain de peau.',
                'short_description_en' => 'Gentle peel to boost glow and smooth texture.',
                'description_fr' => 'Protocole ciblé anti-terne avec phase apaisante post-traitement.',
                'description_en' => 'Targeted anti-dullness protocol with soothing post-treatment phase.',
                'price_tnd' => 260,
                'price_eur' => 80,
                'duration_minutes' => 50,
                'buffer_minutes' => 10,
                'is_featured' => false,
                'sort_order' => 4,
            ],
        ];

        foreach ($services as $serviceData) {
            Service::query()->updateOrCreate(
                ['slug' => Str::slug($serviceData['name_en'])],
                array_merge($serviceData, [
                    'slug' => Str::slug($serviceData['name_en']),
                    'is_active' => true,
                ])
            );
        }
    }
}

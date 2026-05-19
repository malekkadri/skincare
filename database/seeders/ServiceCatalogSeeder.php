<?php

namespace Database\Seeders;

use App\Models\Service;
use App\Models\ServiceCategory;
use Illuminate\Database\Seeder;

class ServiceCatalogSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'slug' => 'noor',
                'name_fr' => 'Noor',
                'name_en' => 'Noor',
                'description_fr' => 'Soins de la peau, Hydrafacial et protocoles esthétiques personnalisés.',
                'description_en' => 'Skincare, Hydrafacial, and personalized aesthetic protocols.',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'slug' => 'dr-aziz',
                'name_fr' => 'Dr Aziz',
                'name_en' => 'Dr Aziz',
                'description_fr' => 'Médecine esthétique, conseils personnalisés et accompagnement professionnel par Dr Aziz Sahly.',
                'description_en' => 'Aesthetic medicine, personalized guidance, and professional care by Dr Aziz Sahly.',
                'sort_order' => 2,
                'is_active' => true,
            ],
        ];

        $categoryIdsBySlug = [];
        foreach ($categories as $categoryData) {
            $category = ServiceCategory::query()->updateOrCreate(
                ['slug' => $categoryData['slug']],
                $categoryData
            );

            $categoryIdsBySlug[$categoryData['slug']] = $category->id;
        }

        $services = [
            [
                'slug' => 'hydrafacial-essentiel',
                'category_slug' => 'noor',
                'name_fr' => 'Hydrafacial Essentiel',
                'name_en' => 'Hydrafacial Essential',
                'short_description_fr' => 'Nettoyage profond, exfoliation contrôlée, extraction douce, masque adapté et LED.',
                'short_description_en' => 'Deep cleansing, controlled exfoliation, gentle extraction, adapted mask, and LED.',
                'description_fr' => 'Nettoyage cutané profond associé à une exfoliation contrôlée, suivi d’une extraction douce des comédons ouverts et fermés. Le soin se complète par l’application d’un masque adapté et une séance de photobiomodulation LED afin d’optimiser l’éclat et l’équilibre cutané.',
                'description_en' => 'Deep skin cleansing with controlled exfoliation, followed by gentle extraction of open and closed comedones. The treatment ends with an adapted mask and LED photobiomodulation session to support radiance and skin balance.',
                'price_tnd' => 250,
                'price_eur' => 75,
                'duration_minutes' => 60,
                'buffer_minutes' => 10,
                'is_featured' => true,
                'is_active' => true,
                'sort_order' => 1,
                'image_path' => null,
            ],
            [
                'slug' => 'hydrafacial-detoxifiant',
                'category_slug' => 'noor',
                'name_fr' => 'Hydrafacial Détoxifiant',
                'name_en' => 'Hydrafacial Detoxifying',
                'short_description_fr' => 'Soin purifiant intensif avec dermaplaning, tonification, masque exfoliant et LED.',
                'short_description_en' => 'Intensive purifying treatment with dermaplaning, toning, exfoliating mask, and LED.',
                'description_fr' => 'Soin purifiant intensif visant à désengorger les peaux asphyxiées. Il associe un nettoyage complet, un dermaplaning pour éliminer les cellules mortes et le duvet, une tonification cutanée ainsi qu’un masque exfoliant, suivi d’une luminothérapie LED pour rééquilibrer la peau. Indiqué pour les peaux ternes, obstruées ou sujettes aux imperfections.',
                'description_en' => 'Intensive purifying treatment designed to decongest suffocated skin. It combines complete cleansing, dermaplaning to remove dead cells and peach fuzz, skin toning, and an exfoliating mask, followed by LED light therapy to rebalance the skin. Recommended for dull, congested, or blemish-prone skin.',
                'price_tnd' => 300,
                'price_eur' => 90,
                'duration_minutes' => 60,
                'buffer_minutes' => 10,
                'is_featured' => true,
                'is_active' => true,
                'sort_order' => 2,
                'image_path' => null,
            ],
            [
                'slug' => 'consultation-peau-noor',
                'category_slug' => 'noor',
                'name_fr' => 'Consultation peau Noor',
                'name_en' => 'Noor skin consultation',
                'short_description_fr' => 'Échange personnalisé pour comprendre votre peau et orienter le soin adapté.',
                'short_description_en' => 'Personalized exchange to understand your skin and guide you toward the right treatment.',
                'description_fr' => 'Un rendez-vous d’échange pour analyser vos besoins esthétiques, discuter de votre routine et vous orienter vers un protocole de soin adapté.',
                'description_en' => 'A consultation to understand your aesthetic needs, discuss your routine, and guide you toward a suitable skincare protocol.',
                'price_tnd' => 0,
                'price_eur' => 0,
                'duration_minutes' => 30,
                'buffer_minutes' => 10,
                'is_featured' => false,
                'is_active' => true,
                'sort_order' => 3,
                'image_path' => null,
            ],
            [
                'slug' => 'protocole-eclat-personnalise',
                'category_slug' => 'noor',
                'name_fr' => 'Protocole éclat personnalisé',
                'name_en' => 'Personalized glow protocol',
                'short_description_fr' => 'Protocole adapté pour accompagner l’éclat, la texture et l’équilibre de la peau.',
                'short_description_en' => 'Tailored protocol to support radiance, texture, and skin balance.',
                'description_fr' => 'Un protocole esthétique personnalisé selon les besoins de votre peau, avec des conseils adaptés et une approche progressive.',
                'description_en' => 'A personalized aesthetic protocol based on your skin needs, with tailored advice and a progressive approach.',
                'price_tnd' => 0,
                'price_eur' => 0,
                'duration_minutes' => 45,
                'buffer_minutes' => 10,
                'is_featured' => false,
                'is_active' => true,
                'sort_order' => 4,
                'image_path' => null,
            ],
            [
                'slug' => 'consultation-dr-aziz',
                'category_slug' => 'dr-aziz',
                'name_fr' => 'Consultation Dr Aziz',
                'name_en' => 'Dr Aziz consultation',
                'short_description_fr' => 'Consultation médico-esthétique et conseils personnalisés avec Dr Aziz Sahly.',
                'short_description_en' => 'Medical-aesthetic consultation and personalized guidance with Dr Aziz Sahly.',
                'description_fr' => 'Une consultation dédiée pour échanger sur vos objectifs, vos besoins et les options d’accompagnement médico-esthétique adaptées.',
                'description_en' => 'A dedicated consultation to discuss your goals, needs, and suitable medical-aesthetic care options.',
                'price_tnd' => 0,
                'price_eur' => 0,
                'duration_minutes' => 30,
                'buffer_minutes' => 10,
                'is_featured' => true,
                'is_active' => true,
                'sort_order' => 1,
                'image_path' => null,
            ],
            [
                'slug' => 'accompagnement-medico-esthetique',
                'category_slug' => 'dr-aziz',
                'name_fr' => 'Accompagnement médico-esthétique',
                'name_en' => 'Medical-aesthetic guidance',
                'short_description_fr' => 'Accompagnement personnalisé pour définir une prise en charge adaptée.',
                'short_description_en' => 'Personalized guidance to define suitable care.',
                'description_fr' => 'Un accompagnement professionnel pour vous aider à choisir une prise en charge cohérente avec vos besoins, votre peau et vos attentes.',
                'description_en' => 'Professional support to help you choose care aligned with your needs, skin, and expectations.',
                'price_tnd' => 0,
                'price_eur' => 0,
                'duration_minutes' => 30,
                'buffer_minutes' => 10,
                'is_featured' => false,
                'is_active' => true,
                'sort_order' => 2,
                'image_path' => null,
            ],
            [
                'slug' => 'suivi-post-soin',
                'category_slug' => 'dr-aziz',
                'name_fr' => 'Suivi post-soin',
                'name_en' => 'Post-treatment follow-up',
                'short_description_fr' => 'Suivi et conseils après un soin ou un protocole.',
                'short_description_en' => 'Follow-up and advice after a treatment or protocol.',
                'description_fr' => 'Un rendez-vous de suivi pour répondre à vos questions et adapter les conseils après un soin ou un protocole.',
                'description_en' => 'A follow-up appointment to answer your questions and adapt advice after a treatment or protocol.',
                'price_tnd' => 0,
                'price_eur' => 0,
                'duration_minutes' => 20,
                'buffer_minutes' => 10,
                'is_featured' => false,
                'is_active' => true,
                'sort_order' => 3,
                'image_path' => null,
            ],
        ];

        $seededServiceSlugs = [];

        foreach ($services as $serviceData) {
            $seededServiceSlugs[] = $serviceData['slug'];

            $payload = $serviceData;
            $payload['category_id'] = $categoryIdsBySlug[$serviceData['category_slug']];
            unset($payload['category_slug']);

            Service::query()->updateOrCreate(
                ['slug' => $serviceData['slug']],
                $payload
            );
        }

        $legacyFacialsCategory = ServiceCategory::query()->where('slug', 'facials')->first();
        if ($legacyFacialsCategory !== null) {
            $hasSeededActiveServices = Service::query()
                ->where('category_id', $legacyFacialsCategory->id)
                ->where('is_active', true)
                ->whereIn('slug', $seededServiceSlugs)
                ->exists();

            if (! $hasSeededActiveServices) {
                $legacyFacialsCategory->update(['is_active' => false]);
            }
        }
    }
}

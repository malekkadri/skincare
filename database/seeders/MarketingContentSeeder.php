<?php

namespace Database\Seeders;

use App\Models\AboutPage;
use App\Models\Faq;
use App\Models\GalleryItem;
use App\Models\HomepageSection;
use App\Models\Policy;
use App\Models\Testimonial;
use Illuminate\Database\Seeder;

class MarketingContentSeeder extends Seeder
{
    public function run(): void
    {
        foreach ([
            ['key' => 'hero', 'title_fr' => 'Votre peau, votre éclat', 'title_en' => 'Your skin, your glow', 'content_fr' => 'Soins experts à Tunis.', 'content_en' => 'Expert skincare in Tunis.', 'button_text_fr' => 'Réserver', 'button_text_en' => 'Book now', 'secondary_button_text_fr' => 'WhatsApp', 'secondary_button_text_en' => 'WhatsApp', 'secondary_button_url' => '/contact', 'is_active' => true, 'sort_order' => 1],
            ['key' => 'intro', 'title_fr' => 'Une beauté naturelle et raffinée', 'title_en' => 'Natural, refined beauty', 'content_fr' => 'Chez Skin by Noor, chaque soin est personnalisé.', 'content_en' => 'At Skin by Noor, every treatment is tailored.', 'is_active' => true, 'sort_order' => 2],
            ['key' => 'benefits', 'title_fr' => 'Pourquoi nous choisir', 'title_en' => 'Why choose us', 'content_fr' => 'Protocoles professionnels, hygiène irréprochable, résultats visibles.', 'content_en' => 'Professional protocols, impeccable hygiene, visible results.', 'is_active' => true, 'sort_order' => 3],
        ] as $section) {
            HomepageSection::query()->updateOrCreate(['key' => $section['key']], $section);
        }

        AboutPage::query()->updateOrCreate(['id' => 1], [
            'title_fr' => 'À propos de Skin by Noor', 'title_en' => 'About Skin by Noor',
            'intro_fr' => 'Un institut premium dédié à la santé de la peau.', 'intro_en' => 'A premium studio dedicated to healthy skin.',
            'story_fr' => 'Fondé à Tunis, Skin by Noor allie expertise dermo-esthétique et douceur.',
            'story_en' => 'Founded in Tunis, Skin by Noor blends dermo-aesthetic expertise with gentle care.',
            'philosophy_fr' => 'Des soins ciblés, élégants et durables.', 'philosophy_en' => 'Targeted, elegant, long-lasting care.',
            'qualifications_fr' => 'Esthétique avancée, hygiène médicale, protocoles certifiés.', 'qualifications_en' => 'Advanced aesthetics, medical hygiene, certified protocols.', 'is_published' => true,
        ]);

        foreach (range(1, 4) as $i) {
            GalleryItem::query()->updateOrCreate(['title_en' => "Glow Session {$i}"], [
                'title_fr' => "Soin Éclat {$i}", 'caption_fr' => 'Résultat lumineux et peau apaisée.', 'caption_en' => 'Radiant, soothed skin result.',
                'image_path' => 'gallery/placeholder-'.$i.'.jpg', 'category' => $i % 2 ? 'facial' : 'hydration', 'is_featured' => $i <= 3, 'is_active' => true, 'sort_order' => $i,
            ]);
        }

        foreach ([
            ['question_fr' => 'Quel soin choisir ?', 'question_en' => 'Which treatment should I choose?', 'answer_fr' => 'Nous vous conseillons après diagnostic.', 'answer_en' => 'We advise you after a skin diagnosis.', 'category' => 'General', 'sort_order' => 1],
            ['question_fr' => 'Puis-je réserver via WhatsApp ?', 'question_en' => 'Can I book via WhatsApp?', 'answer_fr' => 'Oui, c\'est notre canal principal.', 'answer_en' => 'Yes, it is our primary channel.', 'category' => 'Booking', 'sort_order' => 2],
        ] as $faq) {
            Faq::query()->updateOrCreate(['question_en' => $faq['question_en']], $faq + ['is_active' => true]);
        }

        foreach ([
            ['title_fr' => 'Politique d\'annulation', 'title_en' => 'Cancellation Policy', 'slug' => 'cancellation-policy', 'content_fr' => 'Merci de prévenir 24h à l\'avance.', 'content_en' => 'Please notify us 24h in advance.', 'sort_order' => 1],
            ['title_fr' => 'Politique de confidentialité', 'title_en' => 'Privacy Policy', 'slug' => 'privacy-policy', 'content_fr' => 'Vos données restent confidentielles.', 'content_en' => 'Your data remains confidential.', 'sort_order' => 2],
        ] as $policy) {
            Policy::query()->updateOrCreate(['slug' => $policy['slug']], $policy + ['is_active' => true]);
        }

        if (\App\Models\Service::query()->exists()) {
            Testimonial::query()->updateOrCreate(['client_name' => 'Sarra B.'], [
                'title_fr' => 'Peau transformée', 'title_en' => 'Skin transformed', 'content_fr' => 'Résultats visibles dès la première séance.', 'content_en' => 'Visible results from the first session.', 'rating' => 5,
                'service_id' => \App\Models\Service::query()->value('id'), 'is_featured' => true, 'is_active' => true, 'sort_order' => 1,
            ]);
        }
    }
}

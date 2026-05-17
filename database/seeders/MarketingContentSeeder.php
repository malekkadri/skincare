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
            ['key' => 'intro', 'title_fr' => 'Une prise en charge esthétique personnalisée', 'title_en' => 'Personalized aesthetic care', 'content_fr' => 'Chez Asthetika, chaque soin est adapté à votre peau, vos besoins et vos objectifs, avec une approche professionnelle et attentive.', 'content_en' => 'At Asthetika, every treatment is adapted to your skin, your needs, and your goals with a professional and attentive approach.', 'is_active' => true, 'sort_order' => 2],
            ['key' => 'benefits', 'title_fr' => 'Pourquoi choisir Asthetika', 'title_en' => 'Why choose Asthetika', 'content_fr' => 'Protocoles médico-esthétiques personnalisés, hygiène rigoureuse, conseils adaptés et suivi attentif.', 'content_en' => 'Personalized medical-aesthetic protocols, rigorous hygiene, tailored advice, and attentive follow-up.', 'is_active' => true, 'sort_order' => 3],
        ] as $section) {
            HomepageSection::query()->updateOrCreate(['key' => $section['key']], $section);
        }

        AboutPage::query()->updateOrCreate(['id' => 1], [
            'title_fr' => 'À propos d’Asthetika', 'title_en' => 'About Asthetika',
            'intro_fr' => 'Asthetika est un espace dédié à la médecine esthétique et aux soins de la peau, dirigé par Dr Aziz Sahly.', 'intro_en' => 'Asthetika is a space dedicated to aesthetic medicine and skincare, led by Dr Aziz Sahly.',
            'story_fr' => 'Situé à Ennasr, Ariana, Asthetika accompagne chaque patient avec des protocoles personnalisés, une écoute attentive et une approche centrée sur la santé de la peau.',
            'story_en' => 'Located in Ennasr, Ariana, Asthetika supports each patient with personalized protocols, attentive care, and a skin-health-focused approach.',
            'philosophy_fr' => 'Sublimer la peau avec naturel, précision et sécurité.', 'philosophy_en' => 'Enhancing the skin with natural-looking, precise, and safe care.',
            'qualifications_fr' => 'Médecine esthétique, protocoles cutanés personnalisés, hygiène médicale et accompagnement professionnel.', 'qualifications_en' => 'Aesthetic medicine, personalized skin protocols, medical hygiene, and professional care.', 'is_published' => true,
        ]);

        foreach (range(1, 4) as $i) {
            GalleryItem::query()->updateOrCreate(['title_en' => "Asthetika Care Session {$i}"], [
                'title_fr' => "Soin Asthetika {$i}", 'caption_fr' => 'Résultat lumineux et peau apaisée.', 'caption_en' => 'Radiant, soothed skin result.',
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

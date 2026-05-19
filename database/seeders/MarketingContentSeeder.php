<?php

namespace Database\Seeders;

use App\Models\AboutPage;
use App\Models\Faq;
use App\Models\GalleryItem;
use App\Models\HomeBannerSlide;
use App\Models\HomepageSection;
use App\Models\PageHero;
use App\Models\Policy;
use App\Models\Testimonial;
use Illuminate\Database\Seeder;

class MarketingContentSeeder extends Seeder
{
    public function run(): void
    {
        foreach ([
            ['key' => 'hero', 'title_fr' => 'Asthetika · Dr Aziz Sahly', 'title_en' => 'Asthetika · Dr Aziz Sahly', 'content_fr' => 'Médecine esthétique et soins de la peau à La Soukra, Ariana.', 'content_en' => 'Aesthetic medicine and skincare in La Soukra, Ariana.', 'button_text_fr' => 'Prendre rendez-vous', 'button_text_en' => 'Book an appointment', 'button_url' => '/booking', 'secondary_button_text_fr' => 'Nous contacter', 'secondary_button_text_en' => 'Contact us', 'secondary_button_url' => '/contact', 'is_active' => true, 'sort_order' => 1],
            ['key' => 'intro', 'title_fr' => 'Une prise en charge esthétique personnalisée', 'title_en' => 'Personalized aesthetic care', 'content_fr' => 'Chez Asthetika, chaque soin est adapté à votre peau, vos besoins et vos objectifs, avec une approche professionnelle et attentive.', 'content_en' => 'At Asthetika, every treatment is adapted to your skin, your needs, and your goals with a professional and attentive approach.', 'is_active' => true, 'sort_order' => 2],
            ['key' => 'benefits', 'title_fr' => 'Pourquoi choisir Asthetika', 'title_en' => 'Why choose Asthetika', 'content_fr' => 'Protocoles personnalisés, hygiène rigoureuse, conseils adaptés et suivi attentif.', 'content_en' => 'Personalized protocols, rigorous hygiene, tailored advice, and attentive follow-up.', 'is_active' => true, 'sort_order' => 3],
            ['key' => 'categories', 'title_fr' => 'Noor & Dr Aziz', 'title_en' => 'Noor & Dr Aziz', 'content_fr' => 'Deux univers complémentaires : les soins de la peau Noor et l’accompagnement médico-esthétique par Dr Aziz Sahly.', 'content_en' => 'Two complementary care areas: Noor skincare treatments and medical-aesthetic guidance by Dr Aziz Sahly.', 'button_text_fr' => 'Découvrir les services', 'button_text_en' => 'Discover services', 'button_url' => '/services', 'is_active' => true, 'sort_order' => 4],
            ['key' => 'noor', 'title_fr' => 'Noor', 'title_en' => 'Noor', 'content_fr' => 'Soins de la peau, Hydrafacial et protocoles esthétiques personnalisés pour accompagner l’éclat et l’équilibre cutané.', 'content_en' => 'Skincare, Hydrafacial, and personalized aesthetic protocols to support radiance and skin balance.', 'button_text_fr' => 'Voir Noor', 'button_text_en' => 'View Noor', 'button_url' => '/services#noor', 'is_active' => true, 'sort_order' => 5],
            ['key' => 'dr_aziz', 'title_fr' => 'Dr Aziz', 'title_en' => 'Dr Aziz', 'content_fr' => 'Consultation, conseils personnalisés et accompagnement professionnel avec Dr Aziz Sahly.', 'content_en' => 'Consultation, personalized guidance, and professional care with Dr Aziz Sahly.', 'button_text_fr' => 'Voir Dr Aziz', 'button_text_en' => 'View Dr Aziz', 'button_url' => '/services#dr-aziz', 'is_active' => true, 'sort_order' => 6],
            ['key' => 'services', 'title_fr' => 'Soins et protocoles Asthetika', 'title_en' => 'Asthetika treatments and protocols', 'content_fr' => 'Découvrez les soins Noor et les consultations Dr Aziz selon vos besoins.', 'content_en' => 'Discover Noor treatments and Dr Aziz consultations according to your needs.', 'button_text_fr' => 'Voir les services', 'button_text_en' => 'View services', 'button_url' => '/services', 'is_active' => true, 'sort_order' => 7],
            ['key' => 'consultation', 'title_fr' => 'Besoin d’être orientée ?', 'title_en' => 'Need guidance?', 'content_fr' => 'Une consultation permet de mieux comprendre vos besoins et de choisir un protocole adapté.', 'content_en' => 'A consultation helps understand your needs and choose a suitable protocol.', 'button_text_fr' => 'Demander conseil', 'button_text_en' => 'Ask for guidance', 'button_url' => '/contact', 'is_active' => true, 'sort_order' => 8],
            ['key' => 'gallery', 'title_fr' => 'L’univers Asthetika', 'title_en' => 'The Asthetika experience', 'content_fr' => 'Un aperçu de l’ambiance, des soins et de l’approche Asthetika.', 'content_en' => 'A glimpse into Asthetika’s atmosphere, treatments, and approach.', 'button_text_fr' => 'Voir la galerie', 'button_text_en' => 'View gallery', 'button_url' => '/gallery', 'is_active' => true, 'sort_order' => 9],
            ['key' => 'testimonials', 'title_fr' => 'Retours d’expérience', 'title_en' => 'Client feedback', 'content_fr' => 'Des retours simples et authentiques sur l’accompagnement Asthetika.', 'content_en' => 'Simple and authentic feedback about the Asthetika experience.', 'button_text_fr' => 'Lire les avis', 'button_text_en' => 'Read reviews', 'button_url' => '/testimonials', 'is_active' => true, 'sort_order' => 10],
            ['key' => 'faq', 'title_fr' => 'Questions fréquentes', 'title_en' => 'Frequently asked questions', 'content_fr' => 'Retrouvez les réponses utiles avant votre rendez-vous.', 'content_en' => 'Find useful answers before your appointment.', 'button_text_fr' => 'Consulter la FAQ', 'button_text_en' => 'View FAQ', 'button_url' => '/faq', 'is_active' => true, 'sort_order' => 11],
            ['key' => 'booking_cta', 'title_fr' => 'Prête à prendre soin de votre peau ?', 'title_en' => 'Ready to care for your skin?', 'content_fr' => 'Réservez votre rendez-vous chez Asthetika et bénéficiez d’un accompagnement adapté.', 'content_en' => 'Book your appointment at Asthetika and receive care tailored to your needs.', 'button_text_fr' => 'Prendre rendez-vous', 'button_text_en' => 'Book an appointment', 'button_url' => '/booking', 'secondary_button_text_fr' => 'WhatsApp', 'secondary_button_text_en' => 'WhatsApp', 'secondary_button_url' => '/contact', 'is_active' => true, 'sort_order' => 12],
            ['key' => 'contact_cta', 'title_fr' => 'Contactez Asthetika', 'title_en' => 'Contact Asthetika', 'content_fr' => 'Pour toute question, contactez-nous par téléphone, WhatsApp ou Instagram.', 'content_en' => 'For any question, contact us by phone, WhatsApp, or Instagram.', 'button_text_fr' => 'Page contact', 'button_text_en' => 'Contact page', 'button_url' => '/contact', 'is_active' => true, 'sort_order' => 13],
        ] as $section) {
            HomepageSection::query()->updateOrCreate(['key' => $section['key']], $section);
        }

        foreach ([
            ['page_key' => 'home', 'title_fr' => 'Asthetika · Dr Aziz Sahly', 'title_en' => 'Asthetika · Dr Aziz Sahly', 'subtitle_fr' => 'Médecine esthétique et soins de la peau', 'subtitle_en' => 'Aesthetic medicine and skincare', 'description_fr' => 'Des protocoles personnalisés à La Soukra, Ariana, pour accompagner la santé et l’éclat de votre peau.', 'description_en' => 'Personalized protocols in La Soukra, Ariana, to support your skin health and radiance.', 'cta_label_fr' => 'Prendre rendez-vous', 'cta_label_en' => 'Book an appointment', 'cta_url' => '/booking', 'alt_text_fr' => 'Ambiance Asthetika', 'alt_text_en' => 'Asthetika atmosphere', 'sort_order' => 1],
            ['page_key' => 'about', 'title_fr' => 'À propos d’Asthetika', 'title_en' => 'About Asthetika', 'subtitle_fr' => 'Une approche attentive et personnalisée', 'subtitle_en' => 'Attentive and personalized care', 'description_fr' => 'Asthetika accompagne chaque patient avec écoute, précision et conseils adaptés.', 'description_en' => 'Asthetika supports each patient with attentive care, precision, and tailored guidance.', 'cta_label_fr' => 'Découvrir les services', 'cta_label_en' => 'Discover services', 'cta_url' => '/services', 'alt_text_fr' => 'À propos d’Asthetika', 'alt_text_en' => 'About Asthetika', 'sort_order' => 2],
            ['page_key' => 'services', 'title_fr' => 'Soins et protocoles Asthetika', 'title_en' => 'Asthetika treatments and protocols', 'subtitle_fr' => 'Noor & Dr Aziz', 'subtitle_en' => 'Noor & Dr Aziz', 'description_fr' => 'Découvrez les soins de la peau Noor et l’accompagnement Dr Aziz.', 'description_en' => 'Discover Noor skincare treatments and Dr Aziz guidance.', 'cta_label_fr' => 'Prendre rendez-vous', 'cta_label_en' => 'Book an appointment', 'cta_url' => '/booking', 'alt_text_fr' => 'Soins Asthetika', 'alt_text_en' => 'Asthetika treatments', 'sort_order' => 3],
            ['page_key' => 'gallery', 'title_fr' => 'Galerie', 'title_en' => 'Gallery', 'subtitle_fr' => 'Ambiance et soins Asthetika', 'subtitle_en' => 'Asthetika atmosphere and treatments', 'description_fr' => 'Aperçu de l’univers Asthetika, sans promesses exagérées ni avant/après trompeurs.', 'description_en' => 'A glimpse into the Asthetika experience, without exaggerated claims or misleading before/after promises.', 'cta_label_fr' => 'Prendre rendez-vous', 'cta_label_en' => 'Book an appointment', 'cta_url' => '/booking', 'alt_text_fr' => 'Galerie Asthetika', 'alt_text_en' => 'Asthetika gallery', 'sort_order' => 4],
            ['page_key' => 'testimonials', 'title_fr' => 'Témoignages', 'title_en' => 'Testimonials', 'subtitle_fr' => 'Retours d’expérience', 'subtitle_en' => 'Client feedback', 'description_fr' => 'Des retours simples et authentiques sur l’accompagnement Asthetika.', 'description_en' => 'Simple and authentic feedback about the Asthetika experience.', 'cta_label_fr' => 'Voir les services', 'cta_label_en' => 'View services', 'cta_url' => '/services', 'alt_text_fr' => 'Témoignages Asthetika', 'alt_text_en' => 'Asthetika testimonials', 'sort_order' => 5],
            ['page_key' => 'faq', 'title_fr' => 'Questions fréquentes', 'title_en' => 'Frequently asked questions', 'subtitle_fr' => 'Informations utiles', 'subtitle_en' => 'Useful information', 'description_fr' => 'Retrouvez les réponses aux questions les plus fréquentes avant votre rendez-vous.', 'description_en' => 'Find answers to common questions before your appointment.', 'cta_label_fr' => 'Nous contacter', 'cta_label_en' => 'Contact us', 'cta_url' => '/contact', 'alt_text_fr' => 'FAQ Asthetika', 'alt_text_en' => 'Asthetika FAQ', 'sort_order' => 6],
            ['page_key' => 'contact', 'title_fr' => 'Contactez Asthetika', 'title_en' => 'Contact Asthetika', 'subtitle_fr' => 'Rendez-vous et informations', 'subtitle_en' => 'Appointments and information', 'description_fr' => 'Contactez-nous par téléphone, WhatsApp ou Instagram pour organiser votre rendez-vous.', 'description_en' => 'Contact us by phone, WhatsApp, or Instagram to organize your appointment.', 'cta_label_fr' => 'Prendre rendez-vous', 'cta_label_en' => 'Book an appointment', 'cta_url' => '/booking', 'alt_text_fr' => 'Contact Asthetika', 'alt_text_en' => 'Asthetika contact', 'sort_order' => 7],
            ['page_key' => 'consultation', 'title_fr' => 'Consultation', 'title_en' => 'Consultation', 'subtitle_fr' => 'Conseils personnalisés', 'subtitle_en' => 'Personalized guidance', 'description_fr' => 'Un échange pour mieux comprendre vos besoins et vous orienter vers un protocole adapté.', 'description_en' => 'A conversation to better understand your needs and guide you toward a suitable protocol.', 'cta_label_fr' => 'Commencer', 'cta_label_en' => 'Start', 'cta_url' => '/consultation', 'alt_text_fr' => 'Consultation Asthetika', 'alt_text_en' => 'Asthetika consultation', 'sort_order' => 8],
            ['page_key' => 'booking', 'title_fr' => 'Réservation', 'title_en' => 'Booking', 'subtitle_fr' => 'Choisissez votre soin et votre créneau', 'subtitle_en' => 'Choose your treatment and time', 'description_fr' => 'Réservez votre rendez-vous en quelques étapes simples.', 'description_en' => 'Book your appointment in a few simple steps.', 'cta_label_fr' => 'Voir les services', 'cta_label_en' => 'View services', 'cta_url' => '/services', 'alt_text_fr' => 'Réservation Asthetika', 'alt_text_en' => 'Asthetika booking', 'sort_order' => 9],
            ['page_key' => 'recommender', 'title_fr' => 'IA Recommendation', 'title_en' => 'AI Recommendation', 'subtitle_fr' => 'Orientation intelligente', 'subtitle_en' => 'Smart guidance', 'description_fr' => 'Un assistant d’orientation pour vous aider à identifier les soins à discuter avec Asthetika.', 'description_en' => 'A guidance assistant to help identify treatments to discuss with Asthetika.', 'cta_label_fr' => 'Commencer', 'cta_label_en' => 'Start', 'cta_url' => '/recommender', 'alt_text_fr' => 'IA Recommendation Asthetika', 'alt_text_en' => 'Asthetika AI Recommendation', 'sort_order' => 10],
            ['page_key' => 'policies', 'title_fr' => 'Politiques', 'title_en' => 'Policies', 'subtitle_fr' => 'Informations et conditions', 'subtitle_en' => 'Information and terms', 'description_fr' => 'Retrouvez les informations utiles liées à la réservation, la confidentialité et les conditions générales.', 'description_en' => 'Find useful information related to booking, privacy, and terms.', 'cta_label_fr' => 'Nous contacter', 'cta_label_en' => 'Contact us', 'cta_url' => '/contact', 'alt_text_fr' => 'Politiques Asthetika', 'alt_text_en' => 'Asthetika policies', 'sort_order' => 11],
        ] as $hero) {
            PageHero::query()->updateOrCreate(['page_key' => $hero['page_key']], $hero + ['image_path' => null, 'mobile_image_path' => null, 'overlay_opacity' => 0.35, 'is_active' => true]);
        }

        foreach ([
            ['title_fr' => 'Asthetika · Dr Aziz Sahly', 'title_en' => 'Asthetika · Dr Aziz Sahly', 'subtitle_fr' => 'Médecine esthétique et soins de la peau', 'subtitle_en' => 'Aesthetic medicine and skincare', 'description_fr' => 'Des soins personnalisés à La Soukra, Ariana, dans une approche professionnelle et attentive.', 'description_en' => 'Personalized care in La Soukra, Ariana, with a professional and attentive approach.', 'cta_label_fr' => 'Prendre rendez-vous', 'cta_label_en' => 'Book an appointment', 'cta_url' => '/booking', 'sort_order' => 1, 'alt_text_fr' => 'Asthetika à La Soukra', 'alt_text_en' => 'Asthetika in La Soukra'],
            ['title_fr' => 'Noor', 'title_en' => 'Noor', 'subtitle_fr' => 'Soins de la peau & Hydrafacial', 'subtitle_en' => 'Skincare & Hydrafacial', 'description_fr' => 'Hydrafacial, protocoles personnalisés et conseils adaptés pour accompagner l’éclat de votre peau.', 'description_en' => 'Hydrafacial, personalized protocols, and tailored advice to support your skin radiance.', 'cta_label_fr' => 'Découvrir Noor', 'cta_label_en' => 'Discover Noor', 'cta_url' => '/services#noor', 'sort_order' => 2, 'alt_text_fr' => 'Soins Noor', 'alt_text_en' => 'Noor treatments'],
            ['title_fr' => 'Dr Aziz', 'title_en' => 'Dr Aziz', 'subtitle_fr' => 'Consultation et accompagnement', 'subtitle_en' => 'Consultation and guidance', 'description_fr' => 'Un accompagnement médico-esthétique professionnel pour définir une prise en charge adaptée.', 'description_en' => 'Professional medical-aesthetic guidance to define suitable care.', 'cta_label_fr' => 'Découvrir Dr Aziz', 'cta_label_en' => 'Discover Dr Aziz', 'cta_url' => '/services#dr-aziz', 'sort_order' => 3, 'alt_text_fr' => 'Dr Aziz Sahly', 'alt_text_en' => 'Dr Aziz Sahly'],
            ['title_fr' => 'Réservez votre rendez-vous', 'title_en' => 'Book your appointment', 'subtitle_fr' => 'En ligne ou par WhatsApp', 'subtitle_en' => 'Online or by WhatsApp', 'description_fr' => 'Choisissez votre soin, votre créneau et confirmez votre rendez-vous facilement.', 'description_en' => 'Choose your treatment, your time slot, and confirm your appointment easily.', 'cta_label_fr' => 'Réserver', 'cta_label_en' => 'Book now', 'cta_url' => '/booking', 'sort_order' => 4, 'alt_text_fr' => 'Réservation Asthetika', 'alt_text_en' => 'Asthetika booking'],
        ] as $slide) {
            HomeBannerSlide::query()->updateOrCreate(['title_en' => $slide['title_en']], $slide + ['image_path' => null, 'mobile_image_path' => null, 'is_active' => true]);
        }

        AboutPage::query()->updateOrCreate(['id' => 1], [
            'title_fr' => 'À propos d’Asthetika', 'title_en' => 'About Asthetika',
            'intro_fr' => 'Asthetika est un espace dédié à la médecine esthétique et aux soins de la peau, dirigé par Dr Aziz Sahly.', 'intro_en' => 'Asthetika is a space dedicated to aesthetic medicine and skincare, led by Dr Aziz Sahly.',
            'story_fr' => 'Situé à La Soukra, Ariana, Asthetika accompagne chaque patient avec des protocoles personnalisés, une écoute attentive et une approche centrée sur la santé de la peau.',
            'story_en' => 'Located in La Soukra, Ariana, Asthetika supports each patient with personalized protocols, attentive care, and a skin-health-focused approach.',
            'philosophy_fr' => 'Sublimer la peau avec naturel, précision et sécurité.', 'philosophy_en' => 'Enhancing the skin with natural-looking, precise, and safe care.',
            'qualifications_fr' => 'Médecine esthétique, protocoles cutanés personnalisés, hygiène médicale et accompagnement professionnel.', 'qualifications_en' => 'Aesthetic medicine, personalized skin protocols, medical hygiene, and professional care.', 'is_published' => true,
        ]);

        foreach (range(1, 4) as $i) {
            GalleryItem::query()->updateOrCreate(['title_en' => "Asthetika Care Session {$i}"], [
                'title_fr' => "Soin Asthetika {$i}", 'caption_fr' => 'Résultat lumineux et peau apaisée.', 'caption_en' => 'Radiant, soothed skin result.',
                'image_path' => null, 'category' => $i % 2 ? 'facial' : 'hydration', 'is_featured' => $i <= 3, 'is_active' => true, 'sort_order' => $i,
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

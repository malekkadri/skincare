<?php

namespace Database\Seeders;

use App\Models\Appointment;
use App\Models\BlockedDate;
use App\Models\BlockedTimeRange;
use App\Models\Consultation;
use App\Models\ConsultationAiResult;
use App\Models\Customer;
use App\Models\Service;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class AsthetikaOperationalDemoSeeder extends Seeder
{
    public function run(): void
    {
        $customers = $this->seedCustomers();
        $this->seedAppointments($customers);
        $consultations = $this->seedConsultations($customers);
        $this->seedConsultationAiResults($consultations);
        $this->seedBlockedAvailability();
    }

    private function seedCustomers(): array
    {
        $rows = [
            ['first_name' => 'Cliente', 'last_name' => 'Asthetika 1', 'phone' => '+216 20 000 101', 'email' => 'cliente.asthetika1@example.test', 'preferred_language' => 'fr', 'preferred_currency' => 'TND', 'notes' => 'Cliente de démonstration pour les tests locaux.', 'allergies' => 'Aucune allergie déclarée.', 'skin_notes' => 'Peau terne, souhaite améliorer l’éclat.', 'medical_notes' => 'Données fictives de test.'],
            ['first_name' => 'Cliente', 'last_name' => 'Noor 2', 'phone' => '+216 20 000 102', 'email' => 'cliente.noor2@example.test', 'preferred_language' => 'fr', 'preferred_currency' => 'TND', 'notes' => 'Profil de démonstration local.', 'allergies' => 'Aucune allergie déclarée.', 'skin_notes' => 'Pores visibles, routine à adapter.', 'medical_notes' => 'Données fictives de test.'],
            ['first_name' => 'Cliente', 'last_name' => 'Hydrafacial 3', 'phone' => '+216 20 000 103', 'email' => 'cliente.hydrafacial3@example.test', 'preferred_language' => 'fr', 'preferred_currency' => 'TND', 'notes' => 'Profil de démonstration local.', 'allergies' => 'Aucune allergie déclarée.', 'skin_notes' => 'Peau obstruée, intérêt pour Hydrafacial.', 'medical_notes' => 'Données fictives de test.'],
            ['first_name' => 'Patiente', 'last_name' => 'Dr Aziz 4', 'phone' => '+216 20 000 104', 'email' => 'patiente.draziz4@example.test', 'preferred_language' => 'fr', 'preferred_currency' => 'TND', 'notes' => 'Profil de démonstration local.', 'allergies' => 'Aucune allergie déclarée.', 'skin_notes' => 'Souhaite une orientation médico-esthétique.', 'medical_notes' => 'Données fictives de test.'],
            ['first_name' => 'Cliente', 'last_name' => 'Consultation 5', 'phone' => '+216 20 000 105', 'email' => 'cliente.consultation5@example.test', 'preferred_language' => 'fr', 'preferred_currency' => 'TND', 'notes' => 'Profil de démonstration local.', 'allergies' => 'Aucune allergie déclarée.', 'skin_notes' => 'Peau sensible, besoin de conseils.', 'medical_notes' => 'Données fictives de test.'],
            ['first_name' => 'Cliente', 'last_name' => 'Suivi 6', 'phone' => '+216 20 000 106', 'email' => 'cliente.suivi6@example.test', 'preferred_language' => 'fr', 'preferred_currency' => 'TND', 'notes' => 'Profil de démonstration local.', 'allergies' => 'Aucune allergie déclarée.', 'skin_notes' => 'Suivi après soin.', 'medical_notes' => 'Données fictives de test.'],
            ['first_name' => 'Cliente', 'last_name' => 'Routine 7', 'phone' => '+216 20 000 107', 'email' => 'cliente.routine7@example.test', 'preferred_language' => 'fr', 'preferred_currency' => 'TND', 'notes' => 'Profil de démonstration local.', 'allergies' => 'Aucune allergie déclarée.', 'skin_notes' => 'Routine quotidienne à simplifier.', 'medical_notes' => 'Données fictives de test.'],
            ['first_name' => 'Cliente', 'last_name' => 'Asthetika 8', 'phone' => '+216 20 000 108', 'email' => 'cliente.asthetika8@example.test', 'preferred_language' => 'en', 'preferred_currency' => 'TND', 'notes' => 'Demo customer for local testing.', 'allergies' => 'No declared allergies.', 'skin_notes' => 'Wants skincare guidance in English.', 'medical_notes' => 'Fictional testing data.'],
        ];

        $customers = [];
        foreach ($rows as $row) {
            $customers[] = Customer::query()->updateOrCreate(['email' => $row['email']], $row);
        }

        return $customers;
    }

    private function seedAppointments(array $customers): void
    {
        $candidateSlugs = ['hydrafacial-essentiel', 'hydrafacial-detoxifiant', 'consultation-peau-noor', 'consultation-dr-aziz', 'accompagnement-medico-esthetique', 'suivi-post-soin'];
        $services = Service::query()->whereIn('slug', $candidateSlugs)->where('is_active', true)->get()->keyBy('slug');
        $fallbackService = Service::query()->where('is_active', true)->orderBy('id')->first();

        if (! $fallbackService) {
            return;
        }

        $statuses = ['completed', 'confirmed', 'pending', 'cancelled', 'no_show'];
        $dayOffsets = [-10, -8, -6, -4, -2, 0, 0, 0, 2, 4, 5, 7, 9, 11, 13];
        $slots = ['09:00:00', '10:30:00', '12:00:00', '14:00:00', '16:00:00'];

        for ($i = 0; $i < 15; $i++) {
            $customer = $customers[$i % count($customers)];
            $service = $services->values()->get($i % max($services->count(), 1)) ?? $fallbackService;
            $appointmentDate = Carbon::today()->addDays($dayOffsets[$i])->toDateString();
            $startTime = $slots[$i % count($slots)];
            $duration = max((int) ($service->duration_minutes ?? 60), 30);
            $endTime = Carbon::createFromFormat('H:i:s', $startTime)->addMinutes($duration)->format('H:i:s');
            $status = $statuses[$i % count($statuses)];
            $priceTnd = (float) ($service->price_tnd ?? 0);

            Appointment::query()->updateOrCreate(
                [
                    'customer_id' => $customer->id,
                    'appointment_date' => $appointmentDate,
                    'start_time' => $startTime,
                ],
                [
                    'service_id' => $service->id,
                    'end_time' => $endTime,
                    'status' => $status,
                    'booked_currency' => 'TND',
                    'preferred_language' => $customer->preferred_language ?? 'fr',
                    'booked_price' => $priceTnd,
                    'service_name_snapshot_fr' => $service->name_fr,
                    'service_name_snapshot_en' => $service->name_en,
                    'service_price_tnd_snapshot' => $service->price_tnd,
                    'service_price_eur_snapshot' => $service->price_eur,
                    'notes' => $priceTnd <= 0 ? 'Tarif sur consultation' : 'Rendez-vous de démonstration opérationnelle.',
                    'admin_notes' => 'Entrée de démo locale pour dashboard/calendrier/rapports.',
                    'cancelled_at' => $status === 'cancelled' ? Carbon::parse($appointmentDate.' '.$startTime)->subDay() : null,
                ]
            );
        }
    }

    private function seedConsultations(array $customers): array
    {
        $statuses = ['new', 'reviewed', 'contacted', 'converted', 'archived', 'reviewed', 'contacted', 'new'];
        $concerns = ['Éclat', 'Pores visibles', 'Peau terne', 'Imperfections', 'Sensibilité', 'Routine à adapter', 'Suivi post-soin', 'Orientation soin'];
        $results = [];

        foreach ($customers as $i => $customer) {
            if ($i > 7) {
                break;
            }

            $results[] = Consultation::query()->updateOrCreate(
                ['email' => $customer->email],
                [
                    'first_name' => $customer->first_name,
                    'last_name' => $customer->last_name,
                    'phone' => $customer->phone,
                    'preferred_language' => $customer->preferred_language,
                    'age_range' => ['18-24', '25-34', '35-44', '45+'][$i % 4],
                    'skin_type' => ['Sèche', 'Mixte', 'Grasse', 'Normale'][$i % 4],
                    'skin_sensitivity_level' => ['Faible', 'Modérée', 'Élevée'][$i % 3],
                    'main_concerns' => $concerns[$i],
                    'allergies' => $customer->allergies,
                    'current_products' => 'Nettoyant doux, hydratant, protection solaire.',
                    'current_treatments_or_medications' => 'Aucun traitement en cours (donnée fictive).',
                    'pregnancy_or_breastfeeding_status' => 'Non concernée (donnée fictive).',
                    'preferred_goals' => 'Routine claire et amélioration progressive de la peau.',
                    'additional_notes' => 'Demande de démonstration locale sans données réelles.',
                    'consent' => true,
                    'status' => $statuses[$i],
                    'admin_notes' => 'Consultation de test pour espace admin.',
                    'customer_id' => $customer->id,
                ]
            );
        }

        return $results;
    }

    private function seedConsultationAiResults(array $consultations): void
    {
        foreach (array_slice($consultations, 0, 2) as $consultation) {
            ConsultationAiResult::query()->updateOrCreate(
                ['consultation_id' => $consultation->id, 'status' => 'success'],
                [
                    'provider' => 'demo-seeder',
                    'model' => 'demo-safe',
                    'summary_text' => 'Résumé fictif: besoin de routine douce et suivi régulier.',
                    'user_summary' => 'Votre profil suggère une routine douce et progressive.',
                    'admin_summary' => 'Profil sensible, recommander suivi et adaptation progressive.',
                    'recommended_services_json' => ['consultation-peau-noor', 'suivi-post-soin'],
                    'risk_flags_json' => ['sensibilité'],
                    'normalized_result_json' => ['confidence' => 0.82],
                    'needs_human_review' => false,
                    'refer_to_dermatologist' => false,
                    'generated_at' => now(),
                    'processed_at' => now(),
                ]
            );
        }
    }

    private function seedBlockedAvailability(): void
    {
        $nextMonthFirstMonday = Carbon::today()->addMonthNoOverflow()->startOfMonth();
        while ($nextMonthFirstMonday->dayOfWeek !== Carbon::MONDAY) {
            $nextMonthFirstMonday->addDay();
        }

        BlockedDate::query()->updateOrCreate(
            ['blocked_date' => $nextMonthFirstMonday->toDateString()],
            ['reason' => 'Formation interne Asthetika']
        );

        $nextWednesday = Carbon::today()->next(Carbon::WEDNESDAY);
        BlockedTimeRange::query()->updateOrCreate(
            ['blocked_date' => $nextWednesday->toDateString(), 'start_time' => '13:00:00', 'end_time' => '15:00:00'],
            ['reason' => 'Créneau réservé']
        );
    }
}

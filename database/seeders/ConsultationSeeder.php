<?php

namespace Database\Seeders;

use App\Models\Consultation;
use App\Models\ConsultationAiResult;
use App\Models\Customer;
use Illuminate\Database\Seeder;

class ConsultationSeeder extends Seeder
{
    public function run(): void
    {
        $customer = Customer::query()->firstOrCreate(
            ['phone' => '+216 11 222 333'],
            [
                'first_name' => 'Amira',
                'last_name' => 'Ben Salah',
                'email' => 'amira@example.com',
                'preferred_language' => 'fr',
                'preferred_currency' => 'TND',
            ]
        );

        $consultation = Consultation::query()->updateOrCreate(
            ['phone' => '+216 11 222 333', 'first_name' => 'Amira'],
            [
                'last_name' => 'Ben Salah',
                'email' => 'amira@example.com',
                'preferred_language' => 'fr',
                'age_range' => '25-34',
                'skin_type' => 'Mixte',
                'skin_sensitivity_level' => 'Modérée',
                'main_concerns' => 'Éclat, taches légères et texture irrégulière.',
                'allergies' => 'Aucune connue',
                'current_products' => 'Nettoyant doux et SPF 50',
                'preferred_goals' => 'Routine simple et teint lumineux',
                'consent' => true,
                'status' => 'new',
                'customer_id' => $customer->id,
            ]
        );

        ConsultationAiResult::query()->updateOrCreate(
            ['consultation_id' => $consultation->id],
            [
                'provider' => 'grok',
                'model' => 'grok-2-latest',
                'summary_text' => 'Cliente avec peau mixte et sensibilité modérée. Les soins hydratants et éclat peuvent être adaptés, revue manuelle recommandée.',
                'recommended_services_json' => ['hydra-glow-facial', 'brightening-peel-light'],
                'risk_flags_json' => ['sensibilité modérée'],
                'status' => 'success',
                'generated_at' => now(),
            ]
        );
    }
}

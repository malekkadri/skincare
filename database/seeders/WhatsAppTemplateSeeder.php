<?php

namespace Database\Seeders;

use App\Models\WhatsAppTemplate;
use Illuminate\Database\Seeder;

class WhatsAppTemplateSeeder extends Seeder
{
    public function run(): void
    {
        $defaults = [
            ['key' => 'booking_confirmation', 'language' => 'fr', 'message_body' => 'Bonjour {client_name}, votre rendez-vous pour {service_name} est confirmé le {appointment_date} à {appointment_time}. {business_name} vous remercie.'],
            ['key' => 'booking_confirmation', 'language' => 'en', 'message_body' => 'Hello {client_name}, your appointment for {service_name} is confirmed on {appointment_date} at {appointment_time}. Thank you, {business_name}.'],
            ['key' => 'booking_cancellation', 'language' => 'fr', 'message_body' => 'Bonjour {client_name}, votre rendez-vous du {appointment_date} à {appointment_time} a été annulé. Contact: {whatsapp_number}.'],
            ['key' => 'booking_cancellation', 'language' => 'en', 'message_body' => 'Hello {client_name}, your appointment on {appointment_date} at {appointment_time} has been cancelled. Contact: {whatsapp_number}.'],
            ['key' => 'booking_rescheduled', 'language' => 'fr', 'message_body' => 'Bonjour {client_name}, votre rendez-vous a été reprogrammé au {appointment_date} à {appointment_time}.'],
            ['key' => 'booking_rescheduled', 'language' => 'en', 'message_body' => 'Hello {client_name}, your appointment has been rescheduled to {appointment_date} at {appointment_time}.'],
            ['key' => 'appointment_reminder_24h', 'language' => 'fr', 'message_body' => 'Rappel: votre soin {service_name} est prévu demain à {appointment_time}.'],
            ['key' => 'appointment_reminder_24h', 'language' => 'en', 'message_body' => 'Reminder: your {service_name} appointment is tomorrow at {appointment_time}.'],
            ['key' => 'appointment_reminder_2h', 'language' => 'fr', 'message_body' => 'Rappel: votre rendez-vous commence dans 2 heures ({appointment_time}).'],
            ['key' => 'appointment_reminder_2h', 'language' => 'en', 'message_body' => 'Reminder: your appointment starts in 2 hours ({appointment_time}).'],
        ];

        foreach ($defaults as $template) {
            WhatsAppTemplate::query()->updateOrCreate(
                ['key' => $template['key'], 'language' => $template['language']],
                ['message_body' => $template['message_body'], 'is_active' => true]
            );
        }
    }
}

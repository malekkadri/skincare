<?php

namespace App\Services;

class WhatsAppTemplateRenderer
{
    public function render(string $messageBody, array $variables): string
    {
        $replace = [];

        foreach ($variables as $key => $value) {
            $replace['{'.$key.'}'] = (string) $value;
        }

        return strtr($messageBody, $replace);
    }
}

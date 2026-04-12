<?php

namespace App\Services\AI;

use App\Models\Setting;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use Throwable;

class GroqService
{
    public const DEFAULT_BASE_URL = 'https://api.groq.com/openai/v1/chat/completions';
    public const DEFAULT_MODEL = 'llama-3.3-70b-versatile';

    public function __construct(protected ?Setting $settings = null)
    {
        $this->settings ??= Setting::current();
    }

    public function generateJson(string $prompt, string $systemInstruction): array
    {
        $settings = $this->settings;

        $response = $this->client()
            ->post($settings->ai_base_url ?: self::DEFAULT_BASE_URL, [
                'model' => $settings->ai_model ?: self::DEFAULT_MODEL,
                'temperature' => (float) ($settings->ai_temperature ?? 0.3),
                'response_format' => ['type' => 'json_object'],
                'messages' => [
                    ['role' => 'system', 'content' => $systemInstruction],
                    ['role' => 'user', 'content' => $prompt],
                ],
            ]);

        $response->throw();

        $payload = $response->json();
        $content = Arr::get($payload, 'choices.0.message.content');

        if (! is_string($content) || trim($content) === '') {
            throw new \RuntimeException('Empty AI content response.');
        }

        $decoded = json_decode($this->stripCodeFence($content), true);

        if (! is_array($decoded)) {
            throw new \RuntimeException('AI response JSON decoding failed.');
        }

        return [
            'data' => $decoded,
            'raw_response' => $payload,
            'provider' => 'groq',
            'model' => $settings->ai_model ?: self::DEFAULT_MODEL,
        ];
    }

    protected function client(): PendingRequest
    {
        return Http::asJson()
            ->acceptJson()
            ->withToken((string) $this->settings->ai_api_key)
            ->timeout((int) ($this->settings->ai_timeout_seconds ?: 25));
    }

    protected function stripCodeFence(string $content): string
    {
        $trimmed = trim($content);

        if (preg_match('/^```(?:json)?\s*(.*?)\s*```$/is', $trimmed, $matches) === 1) {
            return $matches[1];
        }

        return $trimmed;
    }
}

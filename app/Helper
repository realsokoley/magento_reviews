<?php
namespace App\Helper;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ChatGPTRequest
{
    public function ask(string $prompt): string
    {
        $response = $this->askToChatGPT($prompt);

        return $response;
    }

    private function askToChatGPT($prompt)
    {
        try {
            $response = Http::timeout(20)
                ->withHeaders([
                    'Authorization' => 'Bearer ' . env('CHATGPT_API_KEY'),
                    'Content-Type' => 'application/json',
                ])->post('https://api.openai.com/v1/engines/text-davinci-003/completions', [
                    "prompt" => $prompt,
                    "max_tokens" => 1000,
                    "temperature" => 0.5
                ]);
        } catch (\Exception $exception) {
            try {
                print 'issue fetched';
                $response = Http::timeout(20)
                    ->withHeaders([
                        'Authorization' => 'Bearer ' . env('CHATGPT_API_KEY'),
                        'Content-Type' => 'application/json',
                    ])->post('https://api.openai.com/v1/engines/text-davinci-003/completions', [
                        "prompt" => $prompt,
                        "max_tokens" => 1000,
                        "temperature" => 0.5
                    ]);
                } catch (\Exception $exception) {
                    print 'issue fetched2';
                    $response = Http::timeout(20)
                        ->withHeaders([
                            'Authorization' => 'Bearer ' . env('CHATGPT_API_KEY'),
                            'Content-Type' => 'application/json',
                        ])->post('https://api.openai.com/v1/engines/text-davinci-003/completions', [
                            "prompt" => $prompt,
                            "max_tokens" => 1000,
                            "temperature" => 0.5
                        ]);
                    }
        }

        return $response->json()['choices'][0]['text'];
    }
}

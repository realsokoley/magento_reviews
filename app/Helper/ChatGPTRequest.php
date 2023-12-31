<?php
namespace App\Helper;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ChatGPTRequest
{
    public function ask(string $prompt, string $model = ''): string
    {
        $response = $this->askToChatGPT($prompt, $model);

        return $response;
    }

    private function askToChatGPT($prompt, $model)
    {
        try {
            $response = Http::timeout(40)
                ->withHeaders([
                    'Authorization' => 'Bearer ' . env('CHATGPT_API_KEY'),
                    'Content-Type' => 'application/json',
                    //'model' => $model,
                ])->post('https://api.openai.com/v1/engines/text-davinci-003/completions', [
                    "prompt" => $prompt,
                    "max_tokens" => 2000,
                    "temperature" => 0.5
                ]);
        } catch (\Exception $exception) {
            try {
                print 'issue fetched';
                $response = Http::timeout(60)
                    ->withHeaders([
                        'Authorization' => 'Bearer ' . env('CHATGPT_API_KEY'),
                        'Content-Type' => 'application/json',
                        //'model' => $model,
                    ])->post('https://api.openai.com/v1/engines/text-davinci-003/completions', [
                        "prompt" => $prompt,
                        "max_tokens" => 2000,
                        "temperature" => 0.5
                    ]);
            } catch (\Exception $exception) {
                print 'issue fetched2';
                $response = Http::timeout(40)
                    ->withHeaders([
                        'Authorization' => 'Bearer ' . env('CHATGPT_API_KEY'),
                        'Content-Type' => 'application/json',
                        //'model' => $model,
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

<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Http;

class TelegramService
{
    protected $client;
    protected $botToken;
    protected $chatId;

    public function __construct()
    {
        $this->client = new Client();
        $this->botToken = '7222495229:AAEJqA6pUj9xZIQDQ7AgsN_9O3rMNkk4dfg';
        $this->chatId = '-4232852781';
    }

    public function sendMessage($message, $buttonUrl)
    {
        $response = Http::post("https://api.telegram.org/bot{$this->botToken}/sendMessage", [
            'chat_id' => '-4232852781',
            'text' => $message,
            'reply_markup' => json_encode([
                'inline_keyboard' => [[
                    ['text' => 'Accept Request', 'url' => $buttonUrl]
                ]]
            ])
        ]);

        return $response->json();
    }
}
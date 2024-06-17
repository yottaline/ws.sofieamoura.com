<?php

namespace App\Services;

use GuzzleHttp\Client;

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

    public function sendMessage($message)
    {
        $url = "https://api.telegram.org/bot{$this->botToken}/sendMessage";

        $this->client->post($url, [
            'json' => [
                'chat_id' => $this->chatId,
                'text' => $message,
            ]
        ]);
    }
}
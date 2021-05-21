<?php

namespace App\Http\Middleware;

use App\User;
use BotMan\BotMan\BotMan;
use BotMan\BotMan\Interfaces\Middleware\Heard;
use BotMan\BotMan\Messages\Incoming\IncomingMessage;

class AttachUser implements Heard
{
    public function heard(IncomingMessage $message, $next, BotMan $bot)
    {
        $user = User::where('telegram_id', $bot->getUser()->getId())->first();

        if (!$user) {
            $bot->reply('Сначала залогинься. Используй комманду login <токен buxfer>');
            exit(0);
        }

        auth()->setUser($user);

        return $next($message);
    }
}
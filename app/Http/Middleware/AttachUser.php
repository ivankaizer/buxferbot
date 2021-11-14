<?php

namespace App\Http\Middleware;

use App\Services\Helper;
use App\User;
use BotMan\BotMan\BotMan;
use BotMan\BotMan\Interfaces\Middleware\Heard;
use BotMan\BotMan\Messages\Incoming\IncomingMessage;

class AttachUser implements Heard
{
    public function heard(IncomingMessage $message, $next, BotMan $bot)
    {
        if (Helper::startsWith($message->getText(), 'login ') || Helper::startsWith($message->getText(), '__create_user')) {
            return $next($message);
        }

        $user = User::where('telegram_id', $bot->getUser()->getId())->first();

        if (!$user) {
            $bot->reply('Login first. Try `login <buxfer token>`');
            exit(0);
        }

        auth()->setUser($user);

        return $next($message);
    }
}
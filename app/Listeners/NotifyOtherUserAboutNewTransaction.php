<?php

namespace App\Listeners;

use App\Events\TransactionWasAdded;
use App\User;
use BotMan\BotMan\BotManFactory;
use BotMan\Drivers\Telegram\TelegramDriver;

class NotifyOtherUserAboutNewTransaction
{
    public function handle(TransactionWasAdded $event)
    {
        $currentUser = User::find($event->getUserId());

        $otherUsers = User::where('buxfer_token', $currentUser->buxfer_token)->where('id', '!=', $currentUser->id)->get();

        $botman = BotManFactory::create(config('botman'));
        $botman->loadDriver(TelegramDriver::class);

        $otherUsers->each(function (User $user) use ($botman, $event) {
            $botman->sendRequest('sendMessage', [
               'chat_id' => $user->telegram_id,
                'text' => sprintf(
                    'Другой пользователь добавил: %s - %s - %s - %s',
                    $event->getType(),
                    $event->getAmount(),
                    $event->getCategory(),
                    $event->getDescription()
                )
            ]);
        });
    }
}
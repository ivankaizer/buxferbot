<?php

namespace App\Http\Controllers;

use App\Context\Context;
use App\Context\EmptyContext;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Outgoing\Question;

class Help extends Action
{
    public $context = EmptyContext::class;

    public $commands = [
        'add', 'limit', 'refund', 'shortcuts', 'shortcut', 'transactions',
    ];

    /**
     * @param EmptyContext $context
     */
    public function handle(Context $context): void
    {
        $buttons = array_map(function ($command) {
            return Button::create($command)->value(sprintf('%s ?', $command));
        }, $this->commands);

        $this->bot->reply(Question::create('Выбери комманду')->addButtons($buttons));
    }
}
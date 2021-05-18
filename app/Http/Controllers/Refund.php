<?php

namespace App\Http\Controllers;

use App\Services\ContextParser;
use BotMan\BotMan\BotMan;

class Refund extends Action
{
    public function contextType(): string
    {
        return ContextParser::AMOUNT_DESCRIPTION;
    }

    public function signature(): string
    {
        return 'refund <сумма> <описание>';
    }

    public function __invoke(BotMan $bot, string $context): void
    {
        $this->context = $context;

        $categories = $this->apiService->getCategories();

        if (!$this->contextIsValid()) {
            $bot->reply($this->unclearContextReply());
            return;
        }

        $bot->reply($this->askForCategory('__add_transaction_refund', $categories));
    }
}
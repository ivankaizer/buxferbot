<?php

namespace App\Http\Controllers;

use App\Services\ContextParser;
use App\Services\Helper;
use BotMan\BotMan\BotMan;

class Limit extends Action
{
    public function contextType(): string
    {
        return ContextParser::DESCRIPTION;
    }

    public function signature(): string
    {
        return 'limit <категория>';
    }

    public function __invoke(BotMan $bot, string $context): void
    {
        $this->context = $context;

        $budgets = $this->apiService->getBudgets();

        if (!$this->contextIsValid()) {
            $bot->reply($this->unclearContextReply());
            return;
        }

        [$category] = $this->resolveContext();

        $limit = $budgets->first(function ($limit) use ($category) {
            return Helper::startsWith($limit->name, strtolower($category));
        });

        if (!$limit) {
            $bot->reply($bot->reply($this->unclearContextReply()));
            return;
        }

        $bot->reply(sprintf('%s: %s', $limit->name, $limit->balance));
    }
}
<?php

namespace App\Http\Controllers;

use App\Services\ContextParser;
use BotMan\BotMan\BotMan;

class AddRefund extends Action
{
    public function __invoke(BotMan $bot, string $context)
    {
        $this->context = $context;

        $categories = $this->apiService->getCategories();

        if (!$this->contextIsValid()) {
            $bot->reply($this->unclearContextReply());
            return;
        }

        [$amount, $categoryId, $description] = $this->resolveContext();

        $categoryName = $categories[$categoryId];

        $this->saveTransaction($amount, $categoryName, $description);

        $bot->reply(sprintf('Сохранено возврат %s в %s с описанием: %s', $amount, $categoryName, $description));

    }

    public function contextType(): string
    {
        return ContextParser::AMOUNT_CATEGORY_DESCRIPTION;
    }

    public function isVisibleInMenu(): bool
    {
        return false;
    }

    private function saveTransaction(string $amount, string $category, string $description)
    {
        $finalAmount = str_replace(['-', '+'], '', $amount);

        $this->apiService->addTransaction([
            'type' => 'refund',
            'amount' => $finalAmount,
            'status' => 'cleared',
            'description' => sprintf('%s - Bot', $description),
            'accountId' => 1199540, // @todo
            'date' => date('Y-m-d'),
            'tags' => $category,
        ]);
    }
}
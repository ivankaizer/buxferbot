<?php

namespace App\Http\Controllers;

use App\Context\AmountDescriptionContext;
use App\Context\Context;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Outgoing\Question;

class Add extends Action
{
    public $context = AmountDescriptionContext::class;

    public function signature(): array
    {
        return [
            'add <сумма> <описание>',
            'a <сумма> <описание>',
        ];
    }

    /**
     * @param AmountDescriptionContext $context
     */
    public function handle(Context $context): void
    {
        $categories = $this->apiService->getCategories();

        $buttons = $categories
            ->map(function ($category, $id) use ($context) {
                return Button::create($category)
                    ->value(sprintf('%s %s | %s | %s', '__add_transaction', $context->getAmount(), $id, $context->getDescription()));
            })->toArray();

        $this->bot->reply(Question::create('Выбери категорию')->addButtons($buttons));
    }
}
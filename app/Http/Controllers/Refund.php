<?php

namespace App\Http\Controllers;

use App\Context\AmountDescriptionContext;
use App\Context\Context;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Outgoing\Question;

class Refund extends Action
{
    public $context = AmountDescriptionContext::class;

    public function signature(): array
    {
        return [
            'refund <сумма> <описание>',
            'r <сумма> <описание>',
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
                    ->value(sprintf('%s %s %s %s', '__add_transaction_refund', $context->getAmount(), $id, $context->getDescription()));
            })->toArray();

        $this->bot->reply(Question::create('Выбери категорию')->addButtons($buttons));
    }
}
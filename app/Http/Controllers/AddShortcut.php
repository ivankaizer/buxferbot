<?php

namespace App\Http\Controllers;

use App\Context\AmountDescriptionContext;
use App\Context\Context;

class AddShortcut extends Action
{
    public $context = AmountDescriptionContext::class;

    /**
     * @param AmountDescriptionContext $context
     */
    public function handle(Context $context): void
    {
        auth()->user()->shortcuts()->create([
            'short_name' => $context->getAmount(),
            'category_name' => $context->getDescription()
        ]);

        $this->bot->reply(sprintf('Сохранено сокращение %s: %s', $context->getAmount(), $context->getDescription()));
    }
}
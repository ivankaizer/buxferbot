<?php

namespace App\Http\Controllers;

use App\Context\AmountCategoryDescriptionContext;
use App\Context\Context;

class AddTransaction extends Action
{
    public $context = AmountCategoryDescriptionContext::class;

    /**
     * @param AmountCategoryDescriptionContext $context
     */
    public function handle(Context $context): void
    {
        $categories = $this->apiService->getCategories();

        $categoryName = $categories[$context->getCategory()];

        $type = $this->transactionCreator->guessType($context->getAmount());
        $transaction = $this->transactionCreator->create($type, $context->getAmount(), $categoryName, $context->getDescription());
        $this->apiService->addTransaction($transaction);

        $this->bot->reply(sprintf('Saved %s in %s with description: %s', $context->getAmount(), $categoryName, $context->getDescription()));
    }
}
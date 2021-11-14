<?php

namespace App\Http\Controllers;

use App\Context\AmountCategoryDescriptionContext;
use App\Context\Context;

class AddRefund extends Action
{
    public $context = AmountCategoryDescriptionContext::class;

    /**
     * @param AmountCategoryDescriptionContext $context
     */
    public function handle(Context $context): void
    {
        $categories = $this->apiService->getCategories();

        $categoryName = $categories[$context->getCategory()];

        $transaction = $this->transactionCreator->create('refund', $context->getAmount(), $categoryName, $context->getDescription());
        $this->apiService->addTransaction($transaction);

        $this->bot->reply(sprintf('Saved refund %s in %s with description: %s', $context->getAmount(), $categoryName, $context->getDescription()));
    }
}
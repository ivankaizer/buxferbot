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

        $type = substr($context->getAmount(), 0, 1) === '+' ? 'income' : 'expense';
        $transaction = $this->accountCreator->create($type, $context->getAmount(), $categoryName, $context->getDescription());
        $this->apiService->addTransaction($transaction);

        $this->bot->reply(sprintf('Сохранено %s в %s с описанием: %s', $context->getAmount(), $categoryName, $context->getDescription()));
    }
}
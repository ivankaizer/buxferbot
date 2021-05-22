<?php

namespace App\Http\Controllers;

use App\Context\Context;
use App\Context\KeywordCategoryContext;

class AddShortcut extends Action
{
    public $context = KeywordCategoryContext::class;

    /**
     * @param KeywordCategoryContext $context
     */
    public function handle(Context $context): void
    {
        auth()->user()->shortcuts()->create([
            'short_name' => $context->getKeyword(),
            'category_name' => $context->getCategory()
        ]);

        $this->bot->reply(sprintf('Сохранено сокращение %s: %s', $context->getKeyword(), $context->getCategory()));
    }
}
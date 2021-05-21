<?php

namespace App\Services;

use App\Context\AmountCategoryDescriptionContext;
use App\Context\AmountDescriptionContext;
use App\Context\DescriptionContext;
use App\Context\EmptyContext;
use App\Exceptions\UnclearContext;

class ContextParser
{
    /**
     * @throws UnclearContext
     */
    public function amountDescription(string $context): AmountDescriptionContext
    {
        preg_match('/(.+?) (.+)/', $context, $matches);

        if (empty($matches)) {
            throw new UnclearContext;
        }

        [, $amount, $description] = $matches;

        return new AmountDescriptionContext($amount, $description);
    }

    public function amountCategoryDescription(string $context): AmountCategoryDescriptionContext
    {
        preg_match('/(.+?) \| (.+?) \| (.+)/', $context, $matches);

        if (empty($matches)) {
            throw new UnclearContext;
        }

        [, $amount, $category, $description] = $matches;

        return new AmountCategoryDescriptionContext($amount, $category, $description);
    }

    public function description(string $context): DescriptionContext
    {
        if (!$context) {
            throw new UnclearContext();
        }

        return new DescriptionContext($context);
    }

    public function emptyContext(): EmptyContext
    {
        return new EmptyContext();
    }
}
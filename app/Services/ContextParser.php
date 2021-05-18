<?php

namespace App\Services;

use App\Exceptions\UnclearContext;

class ContextParser
{
    const AMOUNT_DESCRIPTION = 'amountDescription';

    const DESCRIPTION = 'description';

    const AMOUNT_CATEGORY_DESCRIPTION = 'amountCategoryDescription';

    /**
     * @throws UnclearContext
     */
    public function amountDescription(string $context): array
    {
        preg_match('/(.+?) (.+)/', $context, $matches);

        if (empty($matches)) {
            throw new UnclearContext;
        }

        [, $amount, $description] = $matches;

        return [$amount, $description];
    }

    public function amountCategoryDescription(string $context): array
    {
        preg_match('/(.+?) \| (.+?) \| (.+)/', $context, $matches);

        if (empty($matches)) {
            throw new UnclearContext;
        }

        [, $amount, $category, $description] = $matches;

        return [$amount, $category, $description];
    }

    public function description(string $context): array
    {
        if (!$context) {
            throw new UnclearContext();
        }

        return [$context];
    }
}
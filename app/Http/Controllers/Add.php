<?php

namespace App\Http\Controllers;

use App\Context\Context;

class Add extends AbstractSaveAction
{
    public function signature(): array
    {
        return [
            'add <amount> <description>',
            'a <amount> <description>',
            '- <amount> <description>',
        ];
    }

    protected function getTypeFromAmount(Context $context): string
    {
        return $this->transactionCreator->guessType($context->getAmount());
    }

    protected function getAddRoute(): string
    {
        return '__add_transaction';
    }

    protected function getSavedText(): string
    {
        return 'Saved';
    }
}
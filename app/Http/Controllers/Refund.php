<?php

namespace App\Http\Controllers;

use App\Context\Context;

class Refund extends AbstractSaveAction
{
    public function signature(): array
    {
        return [
            'refund <amount> <description>',
            'r <amount> <description>',
            '+ <amount> <description>',
        ];
    }

    protected function getTypeFromAmount(Context $context): string
    {
        return 'refund';
    }

    protected function getAddRoute(): string
    {
        return '__add_transaction_refund';
    }

    protected function getSavedText(): string
    {
        return 'Refund saved';
    }
}
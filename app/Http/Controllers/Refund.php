<?php

namespace App\Http\Controllers;

use App\Context\Context;

class Refund extends AbstractSaveAction
{
    public function signature(): array
    {
        return [
            'refund <сумма> <описание>',
            'r <сумма> <описание>',
            '+ <сумма> <описание>',
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
        return 'Сохранено возврат';
    }
}
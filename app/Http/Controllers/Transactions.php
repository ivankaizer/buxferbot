<?php

namespace App\Http\Controllers;

use App\Context\Context;
use App\Context\DescriptionContext;
use App\Services\Helper;
use Carbon\Carbon;

class Transactions extends Action
{
    public $context = DescriptionContext::class;

    /**
     * @param DescriptionContext $context
     */
    public function handle(Context $context): void
    {
        $transactions = $this->apiService->getTransactions();

        $message = $transactions
            ->filter(function ($transaction) use ($context) {
                return Helper::contains($transaction['tags'], $context->getDescription());
            })
            ->transform(function ($transaction) {
                return [
                    'date' => Carbon::parse($transaction['date'])->format('d M'),
                    'amount' => sprintf('%s%s', $transaction['type'] === 'expense' ? '-' : '+', $transaction['amount']),
                    'description' => $transaction['description'],
                ];
            })
            ->map(function ($t) {
                return sprintf('%s: %s - %s', $t['date'], $t['amount'], $t['description']);
            })
            ->implode("\n");

        $this->bot->reply($message);
    }
}
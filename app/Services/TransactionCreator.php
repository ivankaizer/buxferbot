<?php

namespace App\Services;

class TransactionCreator
{
    public function create(string $type, string $amount, string $category, string $description): array
    {
        $finalAmount = str_replace(['-', '+'], '', $amount);

        $source = env('TRANSACTION_SOURCE', '') ? '- ' .env('TRANSACTION_SOURCE', '') : '';

        return [
            'type' => $type,
            'amount' => $finalAmount,
            'status' => 'cleared',
            'description' => sprintf('%s %s', $description, $source),
            'accountId' => auth()->user()->account_id,
            'date' => date('Y-m-d'),
            'tags' => $category,
        ];
    }

    public function guessType(string $amount): string
    {
        return substr($amount, 0, 1) === '+' ? 'income' : 'expense';
    }
}
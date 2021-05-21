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
            'accountId' => 1199540, // @todo
            'date' => date('Y-m-d'),
            'tags' => $category,
        ];
    }
}
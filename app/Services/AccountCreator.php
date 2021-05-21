<?php

namespace App\Services;

class AccountCreator
{
    public function create(string $type, string $amount, string $category, string $description): array
    {
        $finalAmount = str_replace(['-', '+'], '', $amount);

        return [
            'type' => $type,
            'amount' => $finalAmount,
            'status' => 'cleared',
            'description' => sprintf('%s', $description),
            'accountId' => 1199540, // @todo
            'date' => date('Y-m-d'),
            'tags' => $category,
        ];
    }
}
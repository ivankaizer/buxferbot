<?php

namespace App\Services;

use App\Exceptions\ApiError;
use GuzzleHttp\Client;
use Illuminate\Support\Collection;
use function GuzzleHttp\Psr7\build_query;

class ApiService
{
    private $httpClient;

    private $buxferToken;

    private $ignoredCategories = [
        'Interest', 'Fees', 'Investment', 'Subscriptions', 'Clothing', 'Food / Restaurants', 'Clothing / Clothing - Ania',
        'Clothing / Clothing - Vania', 'Transportation / Taxi', 'Rent & Utilities / Rent', 'Food / Groceries',
        'Rent & Utilities / Bills', 'Bills / Phone'
    ];

    public function __construct(Client $client)
    {
        $this->httpClient = $client;
    }

    public function getCategories(): Collection
    {
        $categories = data_get($this->get('tags'), 'tags', []);

        return collect($categories)
            ->unique('name')
            ->pluck('name', 'id')
            ->filter(function ($category) {
                return !in_array($category, $this->ignoredCategories);
            });
    }

    public function getBudgets(): Collection
    {
        $budgets = data_get($this->get('budgets'), 'budgets', []);

        return collect($budgets);
    }

    public function getAccounts(): Collection
    {
        $accounts = data_get($this->get('accounts'), 'accounts', []);

        return collect($accounts)->unique('name')
            ->pluck('name', 'id');
    }

    public function getTransactions()
    {
        $params = [
            'accountId' => auth()->user()->account_id,
            'month' => strtolower(date('My')),
            'status' => 'cleared',
        ];

        $transactions = collect([]);

        $response = $this->get('transactions', $params);
        $transactions = $transactions->merge($response['transactions']);
        $count = $response['numTransactions'];

        $pages = ceil($count / 25);

        foreach (range(2, $pages) as $page) {
            $response = $this->get('transactions', array_merge($params, ['page' => $page]));
            $transactions = $transactions->merge($response['transactions']);
        }

        return $transactions;
    }

    public function setToken(string $token): void
    {
        $this->buxferToken = $token;
    }

    private function generateUrl(string $endpoint, array $params): string
    {
        return sprintf(
            'https://www.buxfer.com/api/%s?token=%s&%s',
            $endpoint,
            $this->buxferToken ?: auth()->user()->buxfer_token,
            build_query($params)
        );
    }

    public function addTransaction(array $transaction)
    {
        $this->post('add_transaction', $transaction);
    }

    private function post(string $endpoint, array $body): void
    {
        $this->httpClient->post(
            $this->generateUrl($endpoint, []),
            ['form_params' => $body]
        );
    }

    private function get(string $endpoint, array $params = []): array
    {
        try {
            $response = $this->httpClient->get(
                $this->generateUrl($endpoint, $params)
            );
        } catch (\Exception $e) {
            throw new ApiError();
        }

        $json = json_decode($response->getBody()->getContents(), true);

        return data_get($json, 'response', []);
    }
}
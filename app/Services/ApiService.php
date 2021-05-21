<?php

namespace App\Services;

use App\Exceptions\ApiError;
use GuzzleHttp\Client;
use Illuminate\Support\Collection;

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

    public function setToken(string $token): void
    {
        $this->buxferToken = $token;
    }

    private function generateUrl(string $endpoint): string
    {
        return sprintf(
            'https://www.buxfer.com/api/%s?token=%s',
            $endpoint,
            $this->buxferToken ?: auth()->user()->buxfer_token
        );
    }

    public function addTransaction(array $transaction)
    {
        $this->post('add_transaction', $transaction);
    }

    private function post(string $endpoint, array $body): void
    {
        $this->httpClient->post(
            $this->generateUrl($endpoint),
            ['form_params' => $body]
        );
    }

    private function get(string $endpoint): array
    {
        try {
            $response = $this->httpClient->get(
                $this->generateUrl($endpoint)
            );
        } catch (\Exception $e) {
            throw new ApiError();
        }

        $json = json_decode($response->getBody()->getContents(), true);

        return data_get($json, 'response', []);
    }
}
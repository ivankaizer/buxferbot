<?php

use App\Http\Controllers\BotManController;
use BotMan\BotMan\BotMan;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Outgoing\Question;
use GuzzleHttp\Client;

$botman = resolve('botman');

function callApi(string $method, string $endpoint, array $params = [])
{
    $buxferToken = env('BUXFER_TOKEN');

    $guzzle = new Client;

    $response = $guzzle->request(
        $method,
        'https://www.buxfer.com/api/' . $endpoint . '?token=' . $buxferToken,
        $method === 'POST' ? ['form_params' => $params] : $params
    );

    return json_decode($response->getBody()->getContents(), true)['response'];
}

function saveTransaction(string $amount, string $category, string $description, string $type = 'expense')
{
    if (substr($amount, 0, 1) === '+' && $type !== 'refund') {
        $type = 'income';
    }

    $finalAmount = str_replace('-', '', $amount);

    callApi('POST','add_transaction', [
        'type' => $type,
        'amount' => $finalAmount,
        'status' => 'cleared',
        'description' => $description . ' - Bot',
        'accountId' => 1199540,
        'date' => date('Y-m-d'),
        'tags' => $category,
    ]);
}

function filterCategories(array $categories)
{
    $ignored = ['Interest', 'Fees', 'Investment', 'Subscriptions', 'Clothing', 'Food / Restaurants', 'Clothing / Clothing - Ania', 'Clothing / Clothing - Vania', 'Transportation / Taxi', 'Rent & Utilities / Rent', 'Food / Groceries', 'Rent & Utilities / Bills', 'Bills / Phone'];

    $filtered = [];

    foreach ($categories as $id => $category) {
        if (in_array($category, $ignored)) {
            continue;
        }
        $filtered[$id] = $category;
    }

    $filtered = array_sort($filtered, function ($a, $b) {
        return in_array($a, ['Food', 'Аня - Личное']) ? 1 : -1;
    });

    return $filtered;
}

$botman->hears('/start', function ($bot) {
    $bot->reply('Привет. Используй /add сумма описание');
});

$botman->hears('add {amount} {description}', function (BotMan $bot, $amount, $description = '') {
    $categories = collect(callApi('GET', 'tags')['tags'])->unique('name')->pluck('name', 'id')->toArray();
    $categories = filterCategories($categories);

    $buttons = [];

    foreach ($categories as $id => $name) {
        $buttons[] = Button::create($name)->value(sprintf('/save %s %s %s', $amount, $id, $description));
    }

    $question = Question::create('Выбери категорию')
        ->addButtons($buttons);

    $bot->reply($question);
});

$botman->hears('refund {amount} {description}', function (BotMan $bot, $amount, $description = '') {
    $categories = collect(callApi('GET', 'tags')['tags'])->unique('name')->pluck('name', 'id')->toArray();
    $categories = filterCategories($categories);

    $buttons = [];

    foreach ($categories as $id => $name) {
        $buttons[] = Button::create($name)->value(sprintf('/save-refund %s %s %s', $amount, $id, $description));
    }

    $question = Question::create('Выбери категорию')
        ->addButtons($buttons);

    $bot->reply($question);
});

$botman->hears('/save {amount} {categoryId} {description}', function (BotMan $bot, $amount, $categoryId, $description) {
    $categories = collect(callApi('GET', 'tags')['tags'])->unique('name')->pluck('name', 'id')->toArray();
    $categories = filterCategories($categories);

    $categoryName = $categories[$categoryId];
    $description = $description ?: $categoryName;

    saveTransaction($amount, $categoryName, $description);

    $bot->reply(sprintf('Сохранено %s в %s с описанием: %s', $amount, $categoryName, $description));
});

$botman->hears('/save-refund {amount} {categoryId} {description}', function (BotMan $bot, $amount, $categoryId, $description) {
    $categories = collect(callApi('GET', 'tags')['tags'])->unique('name')->pluck('name', 'id')->toArray();
    $categories = filterCategories($categories);

    $categoryName = $categories[$categoryId];
    $description = $description ?: $categoryName;

    saveTransaction($amount, $categoryName, $description, 'refund');

    $bot->reply(sprintf('Сохранено возврат %s в %s с описанием: %s', $amount, $categoryName, $description));
});

$botman->hears('limit {category}', function (BotMan $bot, $category) {
    $limit = collect(callApi('GET', 'budgets')['budgets'])->first(function ($limit) use ($category) {
        return $limit['name'] === $category || substr($limit['name'], 0, strlen($category)) === $category;
    });

    if (!$limit) {
        $bot->reply('Не найдено');
        return;
    }

    $bot->reply($limit['balance']);
});

$botman->hears('Start conversation', BotManController::class . '@startConversation');

$botman->fallback(function ($bot) {
    $bot->reply('Используй add сумма описание');
});


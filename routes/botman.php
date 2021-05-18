<?php

use App\Http\Controllers\Add;
use App\Http\Controllers\AddRefund;
use App\Http\Controllers\AddTransaction;
use App\Http\Controllers\Limit;
use App\Http\Controllers\Refund;

$botman = resolve('botman');

$botman->hears('/start', function ($bot) {
    $bot->reply('Привет. Я бот для Buxfer');
});

$botman->hears('add {context}', [Add::class]);
$botman->hears('refund {context}', [Refund::class]);
$botman->hears('limit {context}', [Limit::class]);

$botman->hears('__add_transaction {context}', [AddTransaction::class]);
$botman->hears('__add_transaction_refund {context}', [AddRefund::class]);

$botman->fallback(function ($bot) {
    $bot->reply('Ниче не понял. Скажи нормально');
});


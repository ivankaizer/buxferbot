<?php

use App\Http\Controllers\Add;
use App\Http\Controllers\AddRefund;
use App\Http\Controllers\AddTransaction;
use App\Http\Controllers\Help;
use App\Http\Controllers\Limit;
use App\Http\Controllers\Refund;

$botman = resolve('botman');

$botman->hears('/start', function ($bot) {
    $bot->reply('Привет. Я бот для Buxfer');
});

$botman->hears('add {context}', Add::class);
//$botman->hears('add! {context}');
$botman->hears('a {context}', Add::class);

$botman->hears('refund {context}', Refund::class);
$botman->hears('r {context}', Refund::class);

$botman->hears('limit {context}', Limit::class);
$botman->hears('l {context}', Limit::class);

$botman->hears('h', Help::class);
$botman->hears('help', Help::class);

//$botman->hears('shortcut {context}');
//$botman->hears('shortcuts');
//$botman->hears('s');
//$botman->hears('sl');

//$botman->hears('login {context}');
//$botman->hears('logout');

$botman->hears('__add_transaction {context}', AddTransaction::class);
$botman->hears('__add_transaction_refund {context}', AddRefund::class);

$botman->fallback(function ($bot) {
    $bot->reply('Не понимаю. Попробуй help');
});


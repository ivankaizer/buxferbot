<?php

use App\Http\Controllers\Add;
use App\Http\Controllers\AddRefund;
use App\Http\Controllers\AddShortcut;
use App\Http\Controllers\AddTransaction;
use App\Http\Controllers\CreateUser;
use App\Http\Controllers\Help;
use App\Http\Controllers\Limit;
use App\Http\Controllers\Login;
use App\Http\Controllers\Refund;
use App\Http\Controllers\Shortcuts;
use App\Http\Controllers\Shortcut;
use App\Http\Controllers\Transactions;

$botman = resolve('botman');

$botman->hears('/start', function ($bot) {
    $bot->reply('Привет. Я бот для Buxfer');
});

$botman->hears('add {context}', Add::class);
$botman->hears('a {context}', Add::class);

$botman->hears('refund {context}', Refund::class);
$botman->hears('r {context}', Refund::class);

$botman->hears('limit {context}', Limit::class);
$botman->hears('l {context}', Limit::class);

$botman->hears('h', Help::class);
$botman->hears('help', Help::class);

$botman->hears('shortcut {context}', Shortcut::class);
$botman->hears('s {context}', Shortcut::class);

$botman->hears('shortcuts {context}', Shortcuts::class);
$botman->hears('shortcuts', Shortcuts::class);
$botman->hears('sl {context}', Shortcuts::class);
$botman->hears('sl', Shortcuts::class);

$botman->hears('login {context}', Login::class);

$botman->hears('transactions {context}', Transactions::class);
$botman->hears('t {context}', Transactions::class);

$botman->hears('__add_transaction {context}', AddTransaction::class);
$botman->hears('__add_transaction_refund {context}', AddRefund::class);
$botman->hears('__create_user {context}', CreateUser::class);
$botman->hears('__add_shortcut {context}', AddShortcut::class);

$botman->fallback(function ($bot) {
    $bot->reply('Не понимаю. Попробуй help');
});


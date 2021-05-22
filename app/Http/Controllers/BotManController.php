<?php

namespace App\Http\Controllers;

use App\Http\Middleware\AttachUser;
use BotMan\BotMan\BotMan;

class BotManController extends Controller
{
    /**
     * Place your BotMan logic here.
     */
    public function handle()
    {
        try {
            /** @var BotMan $botman */
            $botman = app('botman');

            $botman->middleware->heard(new AttachUser());

            $botman->listen();
        } catch (\Exception $e) {
            $botman->reply('Ошибка.');
            app('sentry')->captureException($e);
        }
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function tinker()
    {
        return view('tinker');
    }
}

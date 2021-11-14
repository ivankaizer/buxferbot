<?php

namespace App\Http\Controllers;

use App\Context\Context;
use App\Context\EmptyContext;
use App\Shortcut;

class Shortcuts extends Action
{
    public $context = EmptyContext::class;

    public function signature(): array
    {
        return [
            'shortcuts',
            'sl',
        ];
    }

    /**
     * @param EmptyContext $context
     */
    public function handle(Context $context): void
    {
        $shortcuts = auth()->user()->shortcuts;

        $message = $shortcuts->transform(function (Shortcut $shortcut) {
            return $shortcut->short_name . ': ' . $shortcut->category_name;
        })->implode("\n");

        if ($shortcuts->isEmpty()) {
            $this->bot->reply('Empty');
            return;
        }

        $this->bot->reply($message);
    }
}
<?php

namespace App\Http\Controllers;

use App\Context\Context;
use App\Context\DescriptionContext;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Outgoing\Question;

class Shortcut extends Action
{
    public $context = DescriptionContext::class;

    public function signature(): array
    {
        return [
            'shortcut <сокращение>',
            's <сокращение>',
        ];
    }

    /**
     * @param DescriptionContext $context
     */
    public function handle(Context $context): void
    {
        $formattedKeyword = strtolower($context->getDescription());
        $existingShortcut = auth()->user()->shortcuts()->where('short_name', $formattedKeyword)->first();

        if ($existingShortcut) {
            $this->bot->reply('Сокращение уже существует. ' . $existingShortcut->short_name . ': ' . $existingShortcut->category_name);
            return;
        }

        $categories = $this->apiService->getCategories();

        $buttons = $categories
            ->map(function ($category) use ($formattedKeyword) {
                return Button::create($category)
                    ->value(sprintf('%s %s %s', '__add_shortcut', $formattedKeyword, $category));
            })->toArray();

        $this->bot->reply(Question::create('Выбери категорию')->addButtons($buttons));
    }
}
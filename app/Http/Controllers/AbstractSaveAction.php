<?php

namespace App\Http\Controllers;

use App\Context\AmountDescriptionContext;
use App\Context\Context;
use App\Services\Helper;
use App\Shortcut;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Outgoing\Question;

abstract class AbstractSaveAction extends Action
{
    public $context = AmountDescriptionContext::class;

    /**
     * @param AmountDescriptionContext $context
     */
    public function handle(Context $context): void
    {
        $categories = $this->apiService->getCategories();

        if ($matchedShortcut = $this->getShortcutForContext($context)) {
            $type = $this->getTypeFromAmount($context);
            $transaction = $this->transactionCreator->create($type, $context->getAmount(), $matchedShortcut->category_name, $context->getDescription());
            $this->apiService->addTransaction($transaction);

            $this->bot->reply(sprintf('%s %s в %s с описанием: %s', $this->getSavedText(), $context->getAmount(), $matchedShortcut->category_name, $context->getDescription()));
            return;
        }

        $buttons = $categories
            ->map(function ($category, $id) use ($context) {
                return Button::create($category)
                    ->value(sprintf('%s %s | %s | %s', $this->getAddRoute(), $context->getAmount(), $id, $context->getDescription()));
            })->toArray();

        $this->bot->reply(Question::create('Выбери категорию')->addButtons($buttons));
    }

    protected function getShortcutForContext(Context $context)
    {
        $shortcuts = auth()->user()->shortcuts;

        return $shortcuts
            ->filter(function (Shortcut $shortcut) use ($context) {
                return Helper::startsWith(strtolower($context->getDescription()), strtolower($shortcut->short_name));
            })
            ->first();
    }

    abstract protected function getTypeFromAmount(Context $context): string;

    abstract protected function getAddRoute(): string;

    abstract protected function getSavedText(): string;
}
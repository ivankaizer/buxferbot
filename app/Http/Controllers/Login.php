<?php

namespace App\Http\Controllers;

use App\Context\Context;
use App\Context\DescriptionContext;
use App\Exceptions\ApiError;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Outgoing\Question;

class Login extends Action
{
    public $context = DescriptionContext::class;

    public function signature(): array
    {
        return [
            'login <токен buxfer>',
        ];
    }

    /**
     * @param DescriptionContext $context
     */
    public function handle(Context $context): void
    {
        $this->apiService->setToken($context->getDescription());

        try {
            $accounts = $this->apiService->getAccounts();
        } catch (ApiError $apiError) {
            $this->bot->reply('Неправильный токен');
            return;
        }

        $buttons = $accounts
            ->map(function ($account, $id) use ($context) {
                return Button::create($account)
                    ->value(sprintf('%s %s %s', '__create_user', $context->getDescription(), $id));
            })->toArray();

        $this->bot->reply(Question::create('Выбери свой аккаунт')->addButtons($buttons));
    }
}
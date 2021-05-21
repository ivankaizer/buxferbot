<?php

namespace App\Http\Controllers;

use App\Context\Context;
use App\Context\TokenAccountContext;
use App\Exceptions\ApiError;
use App\User;

class CreateUser extends Action
{
    public $context = TokenAccountContext::class;

    /**
     * @param TokenAccountContext $context
     */
    public function handle(Context $context): void
    {
        $this->apiService->setToken($context->getToken());

        try {
            $accounts = $this->apiService->getAccounts();
        } catch (ApiError $apiError) {
            $this->bot->reply('Неправильный токен');
            return;
        }

        $accountName = $accounts[$context->getAccount()];

        $user = new User([
            'telegram_id' => $this->bot->getUser()->getId(),
            'buxfer_token' => $context->getToken(),
            'account_id' => $context->getAccount()
        ]);
        $user->save();

        $this->bot->reply(sprintf('Добро пожаловать. Выбранный аккаунт: %s', $accountName));
    }
}
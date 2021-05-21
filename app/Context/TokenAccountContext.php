<?php

namespace App\Context;

class TokenAccountContext extends AmountDescriptionContext
{
    public function getToken(): string
    {
        return $this->getAmount();
    }

    public function getAccount(): string
    {
        return $this->getDescription();
    }
}
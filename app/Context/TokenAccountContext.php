<?php

namespace App\Context;

class TokenAccountContext extends KeywordCategoryContext
{
    public function getToken(): string
    {
        return $this->getKeyword();
    }

    public function getAccount(): string
    {
        return $this->getCategory();
    }
}
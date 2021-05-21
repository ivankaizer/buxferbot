<?php

namespace App\Context;

class AmountDescriptionContext implements Context
{
    /**
     * @var string
     */
    private $amount;

    /**
     * @var string
     */
    private $description;

    public function __construct(string $amount, string $description)
    {
        $this->amount = $amount;
        $this->description = $description;
    }

    public function getAmount(): string
    {
        return $this->amount;
    }

    public function getDescription(): string
    {
        return $this->description;
    }
}
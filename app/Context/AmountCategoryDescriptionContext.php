<?php

namespace App\Context;

class AmountCategoryDescriptionContext implements Context
{
    /**
     * @var string
     */
    private $amount;

    /**
     * @var string
     */
    private $description;

    /**
     * @var string
     */
    private $category;

    public function __construct(string $amount, string $category, string $description)
    {
        $this->amount = $amount;
        $this->description = $description;
        $this->category = $category;
    }

    public function getAmount(): string
    {
        return $this->amount;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getCategory(): string
    {
        return $this->category;
    }
}
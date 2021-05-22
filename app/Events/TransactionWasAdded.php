<?php

namespace App\Events;

class TransactionWasAdded
{
    /**
     * @var int
     */
    private $userId;

    /**
     * @var string
     */
    private $type;

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

    public function __construct(int $userId, array $transaction)
    {
        $this->userId = $userId;
        $this->type = $transaction['type'];
        $this->amount = $transaction['amount'];
        $this->description = $transaction['description'];
        $this->category = $transaction['tags'];
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getType(): string
    {
        return $this->type;
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
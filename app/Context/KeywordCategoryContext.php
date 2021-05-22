<?php

namespace App\Context;

class KeywordCategoryContext implements Context
{
    /**
     * @var string
     */
    private $keyword;

    /**
     * @var string
     */
    private $category;

    public function __construct(string $keyword, string $category)
    {
        $this->keyword = $keyword;
        $this->category = $category;
    }

    /**
     * @return string
     */
    public function getCategory(): string
    {
        return $this->category;
    }

    /**
     * @return string
     */
    public function getKeyword(): string
    {
        return $this->keyword;
    }
}
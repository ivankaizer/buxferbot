<?php

namespace App\Http\Controllers;

use App\Context\AmountCategoryDescriptionContext;
use App\Context\AmountDescriptionContext;
use App\Context\Context;
use App\Context\DescriptionContext;
use App\Exceptions\UnclearContext;
use App\Services\AccountCreator;
use App\Services\ApiService;
use App\Services\ContextParser;
use BotMan\BotMan\BotMan;

abstract class Action
{
    public $context;

    /**
     * @var ApiService
     */
    protected $apiService;

    /**
     * @var ContextParser
     */
    protected $contextParser;

    protected $rawContext;

    /**
     * @var BotMan
     */
    protected $bot;

    /**
     * @var AccountCreator
     */
    protected $accountCreator;

    public function __construct(ApiService $apiService, ContextParser $contextParser, AccountCreator $accountCreator)
    {
        $this->apiService = $apiService;
        $this->contextParser = $contextParser;
        $this->accountCreator = $accountCreator;
    }

    public function __invoke(BotMan $bot, string $context): void
    {
        $this->rawContext = $context;
        $this->bot = $bot;

        if (!$this->contextIsValid()) {
            $bot->reply($this->unclearContextReply());
            return;
        }

        $context = $this->resolveContext();

        $this->handle($context);
    }

    abstract public function handle(Context $context): void;

    public function signature(): array
    {
        return [];
    }

    public function resolveContext(): Context
    {
        return $this->callContext();
    }

    public function contextIsValid(): bool
    {
        try {
            $this->callContext();
            return true;
        } catch (UnclearContext $exception) {
            return false;
        }
    }

    public function unclearContextReply(): string
    {
        return 'Не понимаю. Попробуй ' . implode(', ', $this->signature());
    }

    /**
     * @return Context
     * @throws UnclearContext
     */
    private function callContext(): Context
    {
        switch ($this->context) {
            case AmountDescriptionContext::class:
                return $this->contextParser->amountDescription($this->rawContext);
            case AmountCategoryDescriptionContext::class:
                return $this->contextParser->amountCategoryDescription($this->rawContext);
            case DescriptionContext::class:
                return $this->contextParser->description($this->rawContext);
            default:
                throw new \InvalidArgumentException(sprintf('Context %s is not known.', $this->context));
        }
    }
}
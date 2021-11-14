<?php

namespace App\Http\Controllers;

use App\Context\AmountCategoryDescriptionContext;
use App\Context\AmountDescriptionContext;
use App\Context\Context;
use App\Context\DescriptionContext;
use App\Context\EmptyContext;
use App\Context\KeywordCategoryContext;
use App\Context\TokenAccountContext;
use App\Exceptions\UnclearContext;
use App\Services\TransactionCreator;
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
     * @var TransactionCreator
     */
    protected $transactionCreator;

    public function __construct(ApiService $apiService, ContextParser $contextParser, TransactionCreator $transactionCreator)
    {
        $this->apiService = $apiService;
        $this->contextParser = $contextParser;
        $this->transactionCreator = $transactionCreator;
    }

    public function __invoke(BotMan $bot, string $context = ""): void
    {
        $this->rawContext = $context;
        $this->bot = $bot;

        if ($this->rawContext === '?') {
            $this->bot->reply($this->helpMessage());
            return;
        }

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
        return "Command not found. Try: \n\n" . $this->helpMessage();
    }

    public function helpMessage(): string
    {
        return implode("\n", $this->signature());
    }

    /**
     * @return Context
     * @throws UnclearContext
     */
    private function callContext(): Context
    {
        switch ($this->context) {
            case TokenAccountContext::class:
                return $this->contextParser->tokenAccount($this->rawContext);
            case AmountDescriptionContext::class:
                return $this->contextParser->amountDescription($this->rawContext);
            case AmountCategoryDescriptionContext::class:
                return $this->contextParser->amountCategoryDescription($this->rawContext);
            case DescriptionContext::class:
                return $this->contextParser->description($this->rawContext);
            case EmptyContext::class:
                return $this->contextParser->emptyContext();
            case KeywordCategoryContext::class:
                return $this->contextParser->keywordCategory($this->rawContext);
            default:
                throw new \InvalidArgumentException(sprintf('Context %s is not known.', $this->context));
        }
    }
}
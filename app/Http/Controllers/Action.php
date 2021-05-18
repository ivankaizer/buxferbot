<?php

namespace App\Http\Controllers;

use App\Exceptions\UnclearContext;
use App\Services\ApiService;
use App\Services\ContextParser;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Outgoing\Question;
use Illuminate\Support\Collection;

abstract class Action
{
    /**
     * @var ApiService
     */
    protected $apiService;

    /**
     * @var ContextParser
     */
    protected $contextParser;

    protected $context;

    public function __construct(ApiService $apiService, ContextParser $contextParser)
    {
        $this->apiService = $apiService;
        $this->contextParser = $contextParser;
    }

    abstract public function contextType(): string;

    public function signature(): string
    {
        return "";
    }

    public function isVisibleInMenu(): bool
    {
        return true;
    }

    public function resolveContext(): array
    {
        try {
            return call_user_func_array([$this->contextParser, $this->contextType()], [$this->context]);
        } catch (UnclearContext $exception) {
            return [];
        }
    }

    public function contextIsValid(): bool
    {
        try {
            call_user_func_array([$this->contextParser, $this->contextType()], [$this->context]);
            return true;
        } catch (UnclearContext $exception) {
            return false;
        }
    }

    public function unclearContextReply(): string
    {
        return 'Не понимаю. Попробуй ' . $this->signature();
    }

    protected function askForCategory(string $url, Collection $categories): Question
    {
        [$amount, $description] = $this->resolveContext();

        $buttons = $categories
            ->map(function ($category, $id) use ($url, $amount, $description) {
                return Button::create($category)
                    ->value(sprintf('%s %s %s %s', $url, $amount, $id, $description));
            })->toArray();

        return Question::create('Выбери категорию')->addButtons($buttons);
    }
}
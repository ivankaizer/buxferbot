<?php

namespace Tests\Unit;

use App\Services\ContextParser;
use Tests\TestCase;

class ContextParserTest extends TestCase
{
    /** @test */
    public function it_correctly_parses_amount_description_method()
    {
        $parser = new ContextParser();

        $this->assertEquals(['10', 'food'], $parser->amountDescription('10 food'));
        $this->assertEquals(['10.30', 'sport food'], $parser->amountDescription('10.30 sport food'));
        $this->assertEquals(['10.3', 'sport food subscription'], $parser->amountDescription('10.3 sport food subscription'));
        $this->assertEquals(['10,25', 'sport food subscription service'], $parser->amountDescription('10,25 sport food subscription service'));
        $this->assertEquals(['10', '<empty>'], $parser->amountDescription('10'));
    }

    /** @test */
    public function it_correctly_parses_description_method()
    {
        $parser = new ContextParser();

        $this->assertEquals(['10'], $parser->description('10'));
        $this->assertEquals(['10 20'], $parser->description('10 20'));
        $this->assertEquals(['10 20 30'], $parser->description('10 20 30'));
        $this->assertEquals(['10 20 30 40'], $parser->description('10 20 30 40'));
    }
}
<?php

namespace Tests\Unit;

use App\Exceptions\UnclearContext;
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
    }

    /** @test */
    public function it_throws_exception_if_failed_to_parse_amount_description()
    {
        $parser = new ContextParser();

        $this->expectException(UnclearContext::class);

        $this->assertEquals(['10'], $parser->amountDescription('10'));
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

    /** @test */
    public function it_correctly_parses_amount_category_description_method()
    {
        $parser = new ContextParser();

        $this->assertEquals(['10', 'Food', 'Sport'], $parser->amountCategoryDescription('10 | Food | Sport'));
        $this->assertEquals(['10', 'Food', 'Sport food'], $parser->amountCategoryDescription('10 | Food | Sport food'));
        $this->assertEquals(['10', 'Games & Apps', 'Uncharted 4'], $parser->amountCategoryDescription('10 | Games & Apps | Uncharted 4'));
    }

    /** @test */
    public function it_throws_exception_if_failed_to_parse_amount_category_description()
    {
        $parser = new ContextParser();

        $this->expectException(UnclearContext::class);

        $this->assertEquals(['10'], $parser->amountCategoryDescription('10'));
    }
}
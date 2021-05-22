<?php

namespace Tests\Unit;

use App\Context\AmountCategoryDescriptionContext;
use App\Context\AmountDescriptionContext;
use App\Context\DescriptionContext;
use App\Context\KeywordCategoryContext;
use App\Exceptions\UnclearContext;
use App\Services\ContextParser;
use Tests\TestCase;

class ContextParserTest extends TestCase
{
    /** @test */
    public function it_correctly_parses_amount_description_method()
    {
        $parser = new ContextParser();

        $this->assertEquals(new AmountDescriptionContext('5', 'food'), $parser->amountDescription('5 food'));
        $this->assertEquals(new AmountDescriptionContext('10', 'food'), $parser->amountDescription('10 food'));
        $this->assertEquals(new AmountDescriptionContext('10.30', 'sport food'), $parser->amountDescription('10.30 sport food'));
        $this->assertEquals(new AmountDescriptionContext('10.3', 'sport food subscription'), $parser->amountDescription('10.3 sport food subscription'));
        $this->assertEquals(new AmountDescriptionContext('10,25', 'sport food subscription service'), $parser->amountDescription('10,25 sport food subscription service'));
    }

    /** @test */
    public function it_correctly_parses_keyword_category_method()
    {
        $parser = new ContextParser();

        $this->assertEquals(new KeywordCategoryContext('5', 'food'), $parser->keywordCategory('5 food'));
        $this->assertEquals(new KeywordCategoryContext('10', 'food'), $parser->keywordCategory('10 food'));
        $this->assertEquals(new KeywordCategoryContext('10.30', 'sport food'), $parser->keywordCategory('10.30 sport food'));
        $this->assertEquals(new KeywordCategoryContext('10.3', 'sport food subscription'), $parser->keywordCategory('10.3 sport food subscription'));
        $this->assertEquals(new KeywordCategoryContext('10,25', 'sport food subscription service'), $parser->keywordCategory('10,25 sport food subscription service'));
    }

    /** @test */
    public function amount_description_parser_should_not_accept_text_as_amount_value()
    {
        $parser = new ContextParser();

        $this->expectException(UnclearContext::class);

        $parser->amountDescription('food 10');
    }

    /** @test */
    public function amount_category_description_parser_should_not_accept_text_as_amount_value()
    {
        $parser = new ContextParser();

        $this->expectException(UnclearContext::class);

        $parser->amountCategoryDescription('food 10 test');
    }

    /** @test */
    public function it_throws_exception_if_failed_to_parse_amount_description()
    {
        $parser = new ContextParser();

        $this->expectException(UnclearContext::class);

        $parser->amountDescription('10');
    }

    /** @test */
    public function it_correctly_parses_description_method()
    {
        $parser = new ContextParser();

        $this->assertEquals(new DescriptionContext('10'), $parser->description('10'));
        $this->assertEquals(new DescriptionContext('10 20'), $parser->description('10 20'));
        $this->assertEquals(new DescriptionContext('10 20 30'), $parser->description('10 20 30'));
        $this->assertEquals(new DescriptionContext('10 20 30 40'), $parser->description('10 20 30 40'));
    }

    /** @test */
    public function it_correctly_parses_amount_category_description_method()
    {
        $parser = new ContextParser();

        $this->assertEquals(new AmountCategoryDescriptionContext('10', 'Food', 'Sport'), $parser->amountCategoryDescription('10 | Food | Sport'));
        $this->assertEquals(new AmountCategoryDescriptionContext('10', 'Food', 'Sport food'), $parser->amountCategoryDescription('10 | Food | Sport food'));
        $this->assertEquals(new AmountCategoryDescriptionContext('10', 'Games & Apps', 'Uncharted 4'), $parser->amountCategoryDescription('10 | Games & Apps | Uncharted 4'));
    }

    /** @test */
    public function it_throws_exception_if_failed_to_parse_amount_category_description()
    {
        $parser = new ContextParser();

        $this->expectException(UnclearContext::class);

        $parser->amountCategoryDescription('10');
    }
}
<?php

namespace Kristuff\Parselog\Tests;

class GenericParserTest extends \PHPUnit\Framework\TestCase
{
    public function testParser()
    {
        $parser = new \Kristuff\Parselog\LogParser();
        $parser->setFormat('(?P<foo>(fooValue1|fooValue2)) (?P<bar>[0-9]+)');
        $line = 'fooValue2 123456';
        $entry = $parser->parse($line);
        
        $this->assertEquals('fooValue2', $entry->foo);
        $this->assertEquals('123456', $entry->bar);
    }

    public function testAddPattern()
    {
        $parser = new \Kristuff\Parselog\LogParser();
        $parser->addPattern('%1', '(?P<foo>(fooValue1|fooValue2))'); 
        $parser->addPattern('%2', '(?P<bar>[0-9]+)'); 
        $parser->setFormat('%1 %2');
        $line = 'fooValue2 123456';
        $entry = $parser->parse($line);
        
        $this->assertEquals('fooValue2', $entry->foo);
        $this->assertEquals('123456', $entry->bar);
    }

    public function testAddNamedPattern()
    {
        $parser = new \Kristuff\Parselog\LogParser();
        $parser->addNamedPattern('%1', 'foo', '(fooValue1|fooValue2)'); 
        $parser->addNamedPattern('%2', 'bar', '[0-9]+'); 
        $parser->setFormat('%1 %2');
        $line = 'fooValue2 123456';
        $entry = $parser->parse($line);
        
        $this->assertEquals('fooValue2', $entry->foo);
        $this->assertEquals('123456', $entry->bar);
    }

    public function testAddNamedPatternOptionalInFirst()
    {
        $this->expectException(\Kristuff\Parselog\InvalidArgumentException::class);

        $parser = new \Kristuff\Parselog\LogParser();
        $parser->addNamedPattern('%1', 'foo', '(fooValue1|fooValue2)', false); 
    }

    public function testAddNamedPatternOptional()
    {
        $parser = new \Kristuff\Parselog\LogParser();
        $parser->addNamedPattern('%1', 'foo', '(fooValue1|fooValue2)'); 
        $parser->addNamedPattern('%2', 'bar', '[0-9]+', false); 
        $parser->addNamedPattern('%3', 'foobar', '(AAA|BBB)', false); 
        $parser->setFormat('%1 %2 %3');
        
        $line = 'fooValue2';
        $entry = $parser->parse($line);
        
        $this->assertEquals('fooValue2', $entry->foo);
        $this->assertEquals('', $entry->bar);
        $this->assertEquals('', $entry->foobar);
        $this->assertNull($entry->stamp);

    }

 

    public function testInvavidLine()
    {
        $this->expectException(\Kristuff\Parselog\FormatException::class);
        $this->parser = new \Kristuff\Parselog\LogParser();
        $this->parser->addPattern('col1', '(foo|bar)');
        $this->parser->setFormat('col1');
        $this->parser->parse('fooBAR');
    }

}

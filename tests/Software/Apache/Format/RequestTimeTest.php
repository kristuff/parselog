<?php

namespace Kristuff\Parselog\Tests\Software\Apache\Format;

use Kristuff\Parselog\Software\ApacheAccessLogParser;

/**
 * @format %T
 * @description The request time
 */
class RequestTimeTest extends \PHPUnit\Framework\TestCase
{
    protected $parser = null;

    protected function setUp(): void
    {
        $this->parser = new \Kristuff\Parselog\Software\ApacheAccessLogParser();
        $this->parser->setFormat('%T');
    }

    protected function tearDown(): void
    {
        $this->parser = null;
    }

    /**
     * @dataProvider successProvider
     */
    public function testSuccess($line)
    {
        $entry = $this->parser->parse($line);
        $this->assertEquals($line, $entry->requestTime);
    }

    /**
     * @dataProvider invalidProvider
     */
    public function testInvalid($line)
    {
        $this->expectException(\Kristuff\Parselog\FormatException::class);
        $this->parser->parse($line);
    }

    public function successProvider()
    {
        return [
            ['0.000'],
            ['1.234'],
            ['999.999'],
            // apache provides %T without the milisecond part
            ['3'],
            ['0'],
        ];
    }

    public function invalidProvider()
    {
        return [
            ['abc '],
            [''],
            [' '],
            ['-'],
        ];
    }
}

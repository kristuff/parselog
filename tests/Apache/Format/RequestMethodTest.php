<?php

namespace Kristuff\Parselog\Tests\Apache\Format;

use Kristuff\Parselog\ApacheAccessLogParser;

/**
 * @format %m
 * @description The request method
 */
class RequestMethodTest extends \PHPUnit\Framework\TestCase
{
    protected $parser = null;

    protected function setUp(): void
    {
        $this->parser = new ApacheAccessLogParser();
        $this->parser->setFormat('%m');
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
        $this->assertEquals($line, $entry->requestMethod);
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
            ['OPTIONS'],
            ['GET'],
            ['HEAD'],
            ['POST'],
            ['PUT'],
            ['DELETE'],
            ['TRACE'],
            ['CONNECT'],
        ];
    }

    public function invalidProvider()
    {
        return [
            ['GET '],
            ['OPTION'],
            [''],
            ['GET/POST'],
            ['1'],
        ];
    }
}

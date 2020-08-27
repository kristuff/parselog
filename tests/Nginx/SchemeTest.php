<?php

namespace Kristuff\Parselog\Tests\Apache\Format;

use Kristuff\Parselog\ApacheAccessLogParser;
use Kristuff\Parselog\ApacheAccessLogFormat;

/**
 * @format %S
 * @description Scheme
 */
class SchemeTest extends \PHPUnit\Framework\TestCase
{
    protected $parser = null;

    protected function setUp(): void
    {
        $this->parser = new ApacheAccessLogParser();
        $this->parser->setFormat('%S');
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
        $this->assertEquals($line, $entry->scheme);
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
            ['http'],
            ['https'],
        ];
    }

    public function invalidProvider()
    {
        return [
            ['http '],
            ['ftp'],
            [''],
            ['h2'],
            ['1'],
        ];
    }
}

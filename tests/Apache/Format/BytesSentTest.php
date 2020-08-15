<?php

namespace Kristuff\Parselog\Tests\Apache\Format;

use Kristuff\Parselog\ApacheAccessLogParser;
use Kristuff\Parselog\Tests\Provider\PositiveInteger as PositiveIntegerProvider;
use Kristuff\Parselog\ApacheAccessLogFormat;

/**
 * @format %O
 * @description Bytes sent, including headers, cannot be zero. You need to enable mod_logio to use this.
 */
class BytesSentTest extends PositiveIntegerProvider
{
    protected $parser = null;

    protected function setUp(): void
    {
        $this->parser = new ApacheAccessLogParser();
        $this->parser->setFormat('%O');
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
        $this->assertEquals($line, $entry->sentBytes);
    }

    /**
     * @dataProvider invalidProvider
     */
    public function testInvalid($line)
    {
        $this->expectException(\Kristuff\Parselog\FormatException::class);
        $this->parser->parse($line);
    }
}

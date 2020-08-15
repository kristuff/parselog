<?php

namespace Kristuff\Parselog\Tests\Apache\Format;

use Kristuff\Parselog\ApacheAccessLogParser;
use Kristuff\Parselog\Tests\Provider\PositiveInteger as PositiveIntegerProvider;

/**
 * @format %I
 * @description Bytes received, including request and headers, cannot be zero. You need to enable mod_logio to use this.
 */
class BytesReceivedTest extends PositiveIntegerProvider
{
    protected $parser = null;

    protected function setUp(): void
    {
        $this->parser = new \Kristuff\Parselog\ApacheAccessLogParser();
        $this->parser->setFormat('%I');
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
        $this->assertEquals($line, $entry->receivedBytes);
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

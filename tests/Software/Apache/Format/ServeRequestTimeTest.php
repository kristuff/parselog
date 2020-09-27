<?php

namespace Kristuff\Parselog\Tests\Software\Apache\Format;

/**
 * @format %D
 */
class ServeRequestTimeTest extends \PHPUnit\Framework\TestCase
{
    protected $parser = null;

    protected function setUp(): void
    {
        $this->parser = new \Kristuff\Parselog\Software\ApacheAccessLogParser();
        $this->parser->setFormat('%D');
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
        $this->assertEquals($line, $entry->timeServeRequest);
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
            ['2966894'],
            ['4547567567'],
            ['56867'],
        ];
    }

    public function invalidProvider()
    {
        return [
            [''],
            ['abc'],
            [' '],
            ['-'],
        ];
    }
}

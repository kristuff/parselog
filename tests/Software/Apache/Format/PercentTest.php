<?php

namespace Kristuff\Parselog\Tests\Software\Apache\Format;

/**
 * @format %%
 * @description The percent sign
 */
class PercentTest extends \PHPUnit\Framework\TestCase
{
    protected $parser = null;

    protected function setUp(): void
    {
        $this->parser = new \Kristuff\Parselog\Software\ApacheAccessLogParser();
        $this->parser->setFormat('%%');
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
        $this->assertEquals($line, $entry->percent);
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
            ['%'],
        ];
    }

    public function invalidProvider()
    {
        return [
            ['0'],
            ['1'],
            ['dummy 1234'],
            ['lala'],
            ['-'],
        ];
    }
}

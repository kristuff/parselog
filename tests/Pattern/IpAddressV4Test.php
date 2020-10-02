<?php

namespace Kristuff\Parselog\Tests\Software\Apache\Pattern;

use Kristuff\Parselog\LogParser;
use Kristuff\Parselog\Tests\Provider\IpAddressV4;

/**
 */
class IpAddressV4Test extends IpAddressV4
{
    protected $parser = null;

    protected function setUp(): void
    {
        $this->parser = new \Kristuff\Parselog\LogParser();
        $this->parser->addNamedPattern('ip', 'IP', LogParser::PATTERN_IP_V4);
        $this->parser->setFormat('ip');
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
        $this->assertEquals($line, $entry->IP);
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

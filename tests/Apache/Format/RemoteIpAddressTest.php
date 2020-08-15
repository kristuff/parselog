<?php

namespace Kristuff\Parselog\Tests\Apache\Format;

use Kristuff\Parselog\ApacheAccessLogParser;
use Kristuff\Parselog\Tests\Provider\IpAddress as IpAddressProvider;
use Kristuff\Parselog\ApacheAccessLogFormat;

/**
 * @format %a
 * @description Remote IP-address
 */
class RemoteIpAddressTest extends IpAddressProvider
{
    protected $parser = null;

    protected function setUp(): void
    {
        $this->parser = new ApacheAccessLogParser();
        $this->parser->setFormat('%a');
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
        $this->assertEquals($line, $entry->remoteIp);
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

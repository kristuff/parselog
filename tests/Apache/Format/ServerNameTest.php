<?php

namespace Kristuff\Parselog\Tests\Apache\Format;

use Kristuff\Parselog\ApacheAccessLogParser;
use Kristuff\Parselog\Tests\Provider\HostName as HostNameProvider;
use Kristuff\Parselog\ApacheAccessLogFormat;

/**
 * @format %V
 * @description The server name according to the UseCanonicalName setting.
 */
class ServerNameTest extends HostNameProvider
{
    protected $parser = null;

    protected function setUp(): void
    {
        $this->parser = new \Kristuff\Parselog\ApacheAccessLogParser();
        $this->parser->setFormat('%v');
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
        $this->assertEquals($line, $entry->serverName);
    }

    /**
     * @dataProvider invalidProvider
     */
    public function testInvalid($line)
    {
        $this->markTestIncomplete('This test has not been implemented yet.');
        $this->expectException(\Kristuff\Parselog\FormatException::class);
        $this->parser->parse($line);
    }
}

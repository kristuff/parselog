<?php

namespace Kristuff\Parselog\Tests\Apache\Format;

use Kristuff\Parselog\ApacheAccessLogParser;
use Kristuff\Parselog\Tests\Provider\HostName as HostNameProvider;


/**
 * @format %v
 * @description The canonical ServerName of the server serving the request.
 */
class CanonicalServerNameTest extends HostNameProvider
{
    protected $parser = null;

    protected function setUp(): void
    {
        $this->parser = new ApacheAccessLogParser();
        $this->parser->setFormat('%V');
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
        $this->assertEquals($line, $entry->canonicalServerName);
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

<?php

namespace Kristuff\Parselog\Tests;

use Kristuff\Parselog\ApacheAccessLogParser;
use Kristuff\Parselog\ApacheAccessLogFormat;

class CreateEntryTest extends \PHPUnit\Framework\TestCase
{
    public function testCreateEntryMocked()
    {
        $fakeFactory = new \Kristuff\Parselog\Tests\Entry\FakeFactory();
        $parser = new \Kristuff\Parselog\ApacheAccessLogParser('%h', $fakeFactory);
        $entry = $parser->parse('66.249.74.132');

        $this->assertInstanceOf(\Kristuff\Parselog\Tests\Entry\Fake::class, $entry);
        $this->assertEquals($entry->host, '66.249.74.132');
    }
}

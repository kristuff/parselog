<?php

namespace Kristuff\Parselog\Tests\Issue;

use Kristuff\Parselog\ApacheAccessLogParser;
use Kristuff\Parselog\ApacheAccessLogFormat;

class Issue29Test extends \PHPUnit\Framework\TestCase
{
    public function testAuthUserWithDots()
    {
        $parser = new ApacheAccessLogParser();
        $parser->setFormat('%h %l %u %t "%r" %>s %O "%{Referer}i" "%{User-Agent}i"');
        $entry = $parser->parse('127.0.0.1 - user.namespace [25/Jun/2017:10:26:04 +0000] "GET / HTTP/1.1" 200 799 "-" "curl/7.47.0"');

        $this->assertEquals('127.0.0.1', $entry->host);
        $this->assertEquals('-', $entry->logname);
        $this->assertEquals('user.namespace', $entry->user);
        $this->assertEquals('25/Jun/2017:10:26:04 +0000', $entry->time);
        $this->assertEquals('GET / HTTP/1.1', $entry->request);
        $this->assertEquals('200', $entry->status);
        $this->assertEquals('799', $entry->sentBytes);
        $this->assertEquals('-', $entry->headerReferer);
        $this->assertEquals('curl/7.47.0', $entry->headerUserAgent);
    }

    public function testAuthUserAndCustomFormatUsingDots()
    {
        $parser = new ApacheAccessLogParser();
        $parser->setFormat('%u.%t');
        $entry = $parser->parse('user.namespace.[25/Jun/2017:10:26:04 +0000]');

        $this->assertEquals('user.namespace', $entry->user);
        $this->assertEquals('25/Jun/2017:10:26:04 +0000', $entry->time);
    }
}

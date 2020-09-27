<?php

namespace Kristuff\Parselog\Tests\Apache;

class ErrorTest extends \PHPUnit\Framework\TestCase
{
    public function testCommonFormat()
    {
        $parser = new \Kristuff\Parselog\Software\ApacheErrorLogParser();

        $entry = $parser->parse('[Fri Aug 14 12:13:21.650367 2020] [core:info] [pid 31608] [client 79.142.76.206:59415] AH00128: File does not exist: /var/www/index.php');
        $this->assertEquals('79.142.76.206', $entry->remoteIp);
        $this->assertEquals('core:info', $entry->level);
        $this->assertEquals('Fri Aug 14 12:13:21.650367 2020', $entry->time);
        $this->assertEquals('31608', $entry->pid);
        $this->assertEquals('AH00128: File does not exist: /var/www/index.php', $entry->message);

        $entry = $parser->parse('[Fri Aug 14 20:08:22.985375 2020] [php7:error] [pid 29669] [client 114.119.163.185:61710] script \'/var/www/domain.com/badfile.php\' not found or unable to stat');
        $this->assertEquals('114.119.163.185', $entry->remoteIp);
        $this->assertEquals('php7:error', $entry->level);
        $this->assertEquals('Fri Aug 14 20:08:22.985375 2020', $entry->time);
        $this->assertEquals('29669', $entry->pid);
        $this->assertEquals('script \'/var/www/domain.com/badfile.php\' not found or unable to stat', $entry->message);

        $entry = $parser->parse("[Mon Dec 23 07:49:01.981912 2013] [:error] [pid 3790] [client 204.232.202.107:46301] script '/var/www/timthumb.php' not found or unable to");
        $this->assertEquals(':error', $entry->level);
        $this->assertEquals('', $entry->errorCode);

        $entry = $parser->parse("[Fri Sep 25 20:23:41.378709 2020] [mpm_prefork:notice] [pid 10578] AH00169: caught SIGTERM, shutting down");
        $this->assertEquals('mpm_prefork:notice', $entry->level);
        $this->assertEquals('', $entry->remoteIp);
        $this->assertEquals('AH00169', $entry->errorCode);
        $this->assertEquals('caught SIGTERM, shutting down', $entry->message);

        $entry = $parser->parse("[Wed Oct 11 14:32:52 2000] [error] [client 127.0.0.1] client denied by server configuration: /export/home/live/ap/htdocs/test");
        $this->assertEquals('127.0.0.1', $entry->remoteIp);
        $this->assertEquals('error', $entry->level);
        $this->assertEquals('', $entry->pid);
    }

}
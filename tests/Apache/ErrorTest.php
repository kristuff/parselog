<?php

namespace Kristuff\Parselog\Tests\Apache;

use Kristuff\Parselog\ApacheAccessLogParser;

class ErrorTest extends \PHPUnit\Framework\TestCase
{
    public function testFormat()
    {
        $parser = new \Kristuff\Parselog\ApacheErrorLogParser('%t %l %P %a %M');

        $entry = $parser->parse('[Fri Aug 14 12:13:21.650367 2020] [core:info] [pid 31608] [client 79.142.76.206:59415] AH00128: File does not exist: /var/www/index.php');
        $this->assertEquals('79.142.76.206', $entry->remoteIp);
        $this->assertEquals('core:info', $entry->severity);
        $this->assertEquals('Fri Aug 14 12:13:21.650367 2020', $entry->time);
        $this->assertEquals('31608', $entry->pid);
        $this->assertEquals('AH00128: File does not exist: /var/www/index.php', $entry->message);

        $entry = $parser->parse('[Fri Aug 14 20:08:22.985375 2020] [php7:error] [pid 29669] [client 114.119.163.185:61710] script \'/var/www/domain.com/badfile.php\' not found or unable to stat');
        $this->assertEquals('114.119.163.185', $entry->remoteIp);
        $this->assertEquals('php7:error', $entry->severity);
        $this->assertEquals('Fri Aug 14 20:08:22.985375 2020', $entry->time);
        $this->assertEquals('29669', $entry->pid);
        $this->assertEquals('script \'/var/www/domain.com/badfile.php\' not found or unable to stat', $entry->message);
    }
}
<?php

namespace Kristuff\Parselog\Tests\Software;

class SyslogTest extends \PHPUnit\Framework\TestCase
{
    public function testFormat()
    {
        $parser = new \Kristuff\Parselog\Software\SyslogParser('%t %h %s %m');
        $entry = $parser->parse('Aug 15 10:39:01 domain CRON[25038]: (root) CMD (  [ -x /usr/lib/php/sessionclean ] && if [ ! -d /run/systemd/system ]; then /usr/lib/php/sessionclean; fi)');

        $this->assertEquals('Aug 15 10:39:01', $entry->time);
        $this->assertEquals('domain', $entry->hostname);
        $this->assertEquals('CRON[25038]', $entry->service);
        $this->assertEquals('(root) CMD (  [ -x /usr/lib/php/sessionclean ] && if [ ! -d /run/systemd/system ]; then /usr/lib/php/sessionclean; fi)', $entry->message);

        // strtotime takes the current year when ommitted 
        $expectedStamp = mktime(10,39,01,8,15,date('Y'));
        $this->assertEquals($expectedStamp, $entry->stamp);

    }

}

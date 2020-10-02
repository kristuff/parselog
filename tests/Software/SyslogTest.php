<?php

namespace Kristuff\Parselog\Tests\Software;

class SyslogTest extends \PHPUnit\Framework\TestCase
{
    public function testFormat()
    {
        $parser = new \Kristuff\Parselog\Software\SyslogParser();

        $entry = $parser->parse('Aug 15 10:39:01 domain CRON[25038]: (root) CMD (  [ -x /usr/lib/php/sessionclean ] && if [ ! -d /run/systemd/system ]; then /usr/lib/php/sessionclean; fi)');
        $this->assertEquals('Aug 15 10:39:01', $entry->time);
        $this->assertEquals('domain', $entry->hostname);
        $this->assertEquals('CRON', $entry->service);
        $this->assertEquals('25038', $entry->pid);
        $this->assertEquals('(root) CMD (  [ -x /usr/lib/php/sessionclean ] && if [ ! -d /run/systemd/system ]; then /usr/lib/php/sessionclean; fi)', $entry->message);

        // strtotime takes the current year when ommitted 
        $expectedStamp = mktime(10,39,01,8,15,date('Y'));
        $this->assertEquals($expectedStamp, $entry->stamp);

        $entry = $parser->parse('Oct  2 14:51:43 domain systemd-logind[342]: New session 827 of user XXX');
        $this->assertEquals('Oct  2 14:51:43', $entry->time);
        $this->assertEquals('domain', $entry->hostname);
        $this->assertEquals('342', $entry->pid);
        $this->assertEquals('systemd-logind', $entry->service);
        $this->assertEquals('New session 827 of user XXX', $entry->message);

        $parser = new \Kristuff\Parselog\Software\SyslogParser();

        $entry = $parser->parse('Oct  2 14:51:49 domain su: (to root) xxx on pts/1');
        $this->assertEquals('Oct  2 14:51:49', $entry->time);
        $this->assertEquals('domain', $entry->hostname);
        $this->assertEquals('su', $entry->service);
        $this->assertEquals('(to root) xxx on pts/1', $entry->message);

        $expectedStamp =  mktime(14,51,49,10,2,date('Y'));
        $this->assertEquals($expectedStamp, $entry->stamp);
    }

}

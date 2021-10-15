<?php

namespace Kristuff\Parselog\Tests\Software;

class MariadbTest extends \PHPUnit\Framework\TestCase
{
    public function testFormat()
    {
        $parser = new \Kristuff\Parselog\Software\SyslogParser();

        $entry = $parser->parse("2021-10-15  5:04:02 3163 [Warning] Aborted connection 3163 to db: 'xxx' user: 'xxx' host: 'localhost' (Got timeout reading communication packets)");
        $this->assertEquals('2021-10-15  5:04:02', $entry->time);
        $this->assertEquals(1634274242, $entry->stamp);
        $this->assertEquals('3163', $entry->tid);
        $this->assertEquals('Warning', $entry->level);
        $this->assertEquals("Aborted connection 3163 to db: 'xxx' user: 'xxx' host: 'localhost' (Got timeout reading communication packets)", $entry->message);
    }

}

<?php

namespace Kristuff\Parselog\Tests\Software;

class Fail2BanTest extends \PHPUnit\Framework\TestCase
{
    public function testFormatCustom()
    {
        $parser = new \Kristuff\Parselog\Software\Fail2BanLogParser('%t %s %p %l %m');
        $entry = $parser->parse('2020-08-14 15:23:34,093 fail2ban.actions        [6924]: NOTICE  [_port-scan] Ban 1.2.3.4');
        $this->assertEquals('2020-08-14 15:23:34', $entry->time);
        $this->assertEquals('fail2ban.actions', $entry->service);
        $this->assertEquals('6924', $entry->pid);

        $this->assertEquals('NOTICE', $entry->level);
        $this->assertEquals('[_port-scan] Ban 1.2.3.4', $entry->message);
    }

    public function testFormatDefault()
    {
        $parser = new \Kristuff\Parselog\Software\Fail2BanLogParser();
        $entry = $parser->parse('2020-08-15 15:23:34,093 fail2ban.actions        [6924]: NOTICE  [_apache_hack] Ban 1.2.3.4');

        $this->assertEquals('2020-08-15 15:23:34', $entry->time);
        $this->assertEquals('fail2ban.actions', $entry->service);
        $this->assertEquals('6924', $entry->pid);
        $this->assertEquals('NOTICE', $entry->level);
        $this->assertEquals('_apache_hack', $entry->jail);
        $this->assertEquals('Ban 1.2.3.4', $entry->message);

 
    }
    public function testFormatLevel39()
    {
        $parser = new \Kristuff\Parselog\Software\Fail2BanLogParser();
        $line = "2020-08-14 10:44:57,101 fail2ban.utils          [536]: Level 39 7f4d265d09f0 -- returned 1";

echo $parser->getPCRE();

        $entry = $parser->parse($line);
        
        $this->assertEquals('2020-08-14 10:44:57', $entry->time);
        $this->assertEquals('fail2ban.utils', $entry->service); 
        $this->assertEquals('536', $entry->pid);
        $this->assertEquals('Level 39', $entry->level);
        $this->assertEquals('', $entry->jail);
        $this->assertEquals('7f4d265d09f0 -- returned 1', $entry->message);
        
        $this->assertEquals('1597401897', $entry->stamp);

    }

 

   
}

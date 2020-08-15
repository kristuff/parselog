<?php

namespace Kristuff\Parselog\Tests;

use Kristuff\Parselog\ApacheAccessLogParser;
use Kristuff\Parselog\LogFormats;

class Fail2BanTest extends \PHPUnit\Framework\TestCase
{
    public function testFormat1()
    {
        $parser = new \Kristuff\Parselog\Fail2BanLogParser('%t %s %p %l %m');
        $entry = $parser->parse('2020-08-14 15:23:34,093 fail2ban.actions        [6924]: NOTICE  [_port-scan] Ban 195.144.21.56');
        $this->assertEquals('2020-08-14 15:23:34', $entry->time);
        $this->assertEquals('fail2ban.actions', $entry->service);
        $this->assertEquals('6924', $entry->pid);
        $this->assertEquals('NOTICE', $entry->level);
        $this->assertEquals('[_port-scan] Ban 195.144.21.56', $entry->message);
    }

    public function testFormat2()
    {
        $parser = new \Kristuff\Parselog\Fail2BanLogParser();
        $entry = $parser->parse('2020-08-14 10:44:57,101 fail2ban.utils          [6924]: Level 39 7f3d4c0a78c8 -- exec: [\'f2bV_matches=$0 \n/usr/sbin/abuseipdb -R "156.96.56.103" -c "11" -m "$f2bV_matches" >> /tmp/abuseipdb-ftb-last-command.txt\', \'Aug 14 10:44:54 kristuff postfix/smtpd[15598]: NOQUEUE: reject: RCPT from unknown[156.96.56.103] 454 4.7.1 <spameri@tiscali.it>: Relay access denied; from=<spameri@tiscali.it> to=<spameri@tiscali.it> proto=ESMTP helo=<WIN-6HF4HIGXJRE>\']');
        
        $this->assertEquals('2020-08-14 10:44:57', $entry->time);
        $this->assertEquals('fail2ban.utils', $entry->service); 
        $this->assertEquals('6924', $entry->pid);
        $this->assertEquals('Level 39', $entry->level);
        $this->assertEquals('7f3d4c0a78c8 -- exec: [\'f2bV_matches=$0 \n/usr/sbin/abuseipdb -R "156.96.56.103" -c "11" -m "$f2bV_matches" >> /tmp/abuseipdb-ftb-last-command.txt\', \'Aug 14 10:44:54 kristuff postfix/smtpd[15598]: NOQUEUE: reject: RCPT from unknown[156.96.56.103] 454 4.7.1 <spameri@tiscali.it>: Relay access denied; from=<spameri@tiscali.it> to=<spameri@tiscali.it> proto=ESMTP helo=<WIN-6HF4HIGXJRE>\']', $entry->message);
        $this->assertEquals('1597401897', $entry->stamp);

    }

   
}

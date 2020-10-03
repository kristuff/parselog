<?php

namespace Kristuff\Parselog\Tests;

use Kristuff\Parselog\LogParserFactory;

class SoftwareTest extends \PHPUnit\Framework\TestCase
{
    public function testApacheFactory()
    {

        $parser = LogParserFactory::getParser(LogParserFactory::TYPE_APACHE_ACCESS);
        $this->assertTrue($parser instanceof \Kristuff\Parselog\Software\ApacheAccessLogParser);
        $this->assertEquals('Apache', $parser->getSoftware());
        $this->assertEquals('Apache Access', $parser->getPrettyName());
        $this->assertEquals('%h %l %u %t "%r" %>s %b', $parser->getFormat());

        $parser = LogParserFactory::getParser(LogParserFactory::TYPE_APACHE_ERROR);
        $this->assertTrue($parser instanceof \Kristuff\Parselog\Software\ApacheErrorLogParser);
        $this->assertEquals('Apache', $parser->getSoftware());
        $this->assertEquals('Apache Error', $parser->getPrettyName());

    }

    public function testSyslogFactory()
    {
        $parser = LogParserFactory::getParser(LogParserFactory::TYPE_SYSLOG);
        $this->assertTrue($parser instanceof \Kristuff\Parselog\Software\SyslogParser);
        $this->assertEquals('Syslog', $parser->getSoftware());
        $this->assertEquals('Syslog', $parser->getPrettyName());

    }

    public function testF2bFactory()
    {
        $parser = LogParserFactory::getParser(LogParserFactory::TYPE_FAIL2BAN);
        $this->assertTrue($parser instanceof \Kristuff\Parselog\Software\Fail2BanLogParser);
        $this->assertEquals('Fail2ban', $parser->getSoftware());
        $this->assertEquals('Fail2ban', $parser->getPrettyName());
        $this->assertEquals('/var/log/', $parser->getPaths()[0]);
        $this->assertEquals('fail2ban.log', $parser->getFiles()[0]);
        $this->assertEquals('default', array_keys($parser->getKnownFormats())[0]);
        $this->assertEquals('%t %s %p %l %j %m', $parser->getKnownFormats()['default']);
        $this->assertEquals('%t %s %p %l %j %m', $parser->getFormat());
        

    }

    public function testWrongtype()
    {
        $parser = LogParserFactory::getParser('wrong type');
        $this->assertNull($parser);

    }


}

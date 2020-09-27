<?php

namespace Kristuff\Parselog\Tests\Software\Apache\Format;

use Kristuff\Parselog\LogParserFactory;

class BaseTest extends \PHPUnit\Framework\TestCase
{
    public function testApacheFactory()
    {

        $parser = LogParserFactory::getParser(LogParserFactory::TYPE_APACHE_ACCESS);
        $this->assertTrue($parser instanceof \Kristuff\Parselog\Software\ApacheAccessLogParser);
        $this->assertEquals('Apache', $parser->getSotware());
        $this->assertEquals('Apache Access', $parser->getPrettyName());
        $this->assertEquals('%h %l %u %t "%r" %>s %b', $parser->getFormat());

        $parser = LogParserFactory::getParser(LogParserFactory::TYPE_APACHE_ERROR);
        $this->assertTrue($parser instanceof \Kristuff\Parselog\Software\ApacheErrorLogParser);
        $this->assertEquals('Apache', $parser->getSotware());
        $this->assertEquals('Apache Error', $parser->getPrettyName());

    }

    public function testSyslogFactory()
    {
        $parser = LogParserFactory::getParser(LogParserFactory::TYPE_SYSLOG);
        $this->assertTrue($parser instanceof \Kristuff\Parselog\Software\SyslogParser);
        $this->assertEquals('Syslog', $parser->getSotware());
        $this->assertEquals('Syslog', $parser->getPrettyName());

    }

    public function testF2bFactory()
    {
        $parser = LogParserFactory::getParser(LogParserFactory::TYPE_FAIL2BAN);
        $this->assertTrue($parser instanceof \Kristuff\Parselog\Software\Fail2BanLogParser);
        $this->assertEquals('Fail2ban', $parser->getSotware());
        $this->assertEquals('Fail2ban', $parser->getPrettyName());
        $this->assertEquals('/var/log/', $parser->getPaths()[0]);
        $this->assertEquals('fail2ban.log', $parser->getFiles()[0]);
        $this->assertEquals('default', array_keys($parser->getKnownFormats())[0]);
        $this->assertEquals('%t %s %p %l %m', $parser->getKnownFormats()['default']);

    }

    public function testWrongtype()
    {
        $parser = LogParserFactory::getParser('wrong type');
        $this->assertNull($parser);

    }


}

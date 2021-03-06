<?php

namespace Kristuff\Parselog\Tests\Apache;
use Kristuff\Parselog\Software\ApacheErrorLogParser;
/**
 * // [Sun Sep 27 08:27:44.404252 2020] [reqtimeout:info] [pid 23081] [client 223.176.112.108:44736] AH01382: Request header read timeout
 */
class ErrorTest extends \PHPUnit\Framework\TestCase
{

    public function testApache22Format()
    {
        $parser = new ApacheErrorLogParser('%t %l %a %M');
        $entry = $parser->parse("[Wed Oct 11 14:32:52 2000] [error] [client 127.0.0.1] client denied by server configuration: /export/home/live/ap/htdocs/test");
        $this->assertEquals('Wed Oct 11 14:32:52 2000', $entry->time);
        $this->assertEquals('127.0.0.1', $entry->remoteIp);
        $this->assertEquals('error', $entry->level);
        $this->assertEquals('client denied by server configuration: /export/home/live/ap/htdocs/test', $entry->message);
    }

    public function testApache22FormatWithBrackets()
    {
        $parser = new ApacheErrorLogParser(ApacheErrorLogParser::FORMAT_DEFAULT_APACHE_2_2);
        $entry = $parser->parse("[Wed Oct 11 14:32:52 2000] [error] [client 127.0.0.1] client denied by server configuration: /export/home/live/ap/htdocs/test");
        $this->assertEquals('Wed Oct 11 14:32:52 2000', $entry->time);
        $this->assertEquals('', $entry->fileName);
        $this->assertEquals('', $entry->errorCode);
        $this->assertEquals('127.0.0.1', $entry->remoteIp);
        $this->assertEquals('error', $entry->level);
        $this->assertEquals('client denied by server configuration: /export/home/live/ap/htdocs/test', $entry->message);
        $this->assertEquals('971274772', $entry->stamp);

    }

    public function testApache24Format()
    {
        $parser = new ApacheErrorLogParser(ApacheErrorLogParser::FORMAT_DEFAULT_APACHE_2_4);

        $entry = $parser->parse('[Fri Aug 14 12:13:21.650367 2020] [core:info] [pid 31608] [client 79.142.76.206:59415] AH00128: File does not exist: /var/www/index.php');
        $this->assertEquals('79.142.76.206', $entry->remoteIp);
        $this->assertEquals('', $entry->fileName);
        $this->assertEquals('AH00128', $entry->errorCode);
        $this->assertEquals('core:info', $entry->level);
        $this->assertEquals('Fri Aug 14 12:13:21.650367 2020', $entry->time);
        $this->assertEquals('31608', $entry->pid);
        $this->assertEquals('File does not exist: /var/www/index.php', $entry->message);
        $this->assertEquals('1597407201', $entry->stamp);

        $entry = $parser->parse('[Fri Aug 14 20:08:22.985375 2020] [php7:error] [pid 29669] [client 114.119.163.185:61710] script \'/var/www/domain.com/badfile.php\' not found or unable to stat');
        $this->assertEquals('114.119.163.185', $entry->remoteIp);
        $this->assertEquals('', $entry->fileName);
        $this->assertEquals('', $entry->errorCode);
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
    }

    public function testApache24NPMFormat()
    {
        $parser = new ApacheErrorLogParser(ApacheErrorLogParser::FORMAT_MPM_APACHE_2_4);
        $entry = $parser->parse('[Fri Aug 14 12:13:21.650367 2020] [core:info] [pid 31608] [client 79.142.76.206:59415] AH00128: File does not exist: /var/www/index.php');
        
        $this->assertEquals('79.142.76.206', $entry->remoteIp);
        $this->assertEquals('', $entry->fileName);
        $this->assertEquals('AH00128', $entry->errorCode);
        $this->assertEquals('core', $entry->module);
        $this->assertEquals('info', $entry->level);
        $this->assertEquals('Fri Aug 14 12:13:21.650367 2020', $entry->time);
        $this->assertEquals('31608', $entry->pid);
        $this->assertEquals('File does not exist: /var/www/index.php', $entry->message);

        $entry = $parser->parse("[Mon Dec 23 07:49:01.981912 2013] [:error] [pid 3790] [client 204.232.202.107:46301] script '/var/www/timthumb.php' not found or unable to");
        $this->assertEquals('', $entry->module);
        $this->assertEquals('error', $entry->level);
        $this->assertEquals('', $entry->errorCode);
    }

    public function testApache24NPM_TIDFormat()
    {
        $parser = new ApacheErrorLogParser(ApacheErrorLogParser::FORMAT_MPM_TID_APACHE_2_4);
        $entry = $parser->parse('[Fri Aug 14 12:13:21.650367 2020] [core:info] [pid 31608:tid 2992634688] [client 79.142.76.206:59415] AH00128: File does not exist: /var/www/index.php');
        
        $this->assertEquals('79.142.76.206', $entry->remoteIp);
        $this->assertEquals('', $entry->fileName);
        $this->assertEquals('AH00128', $entry->errorCode);
        $this->assertEquals('core', $entry->module);
        $this->assertEquals('info', $entry->level);
        $this->assertEquals('Fri Aug 14 12:13:21.650367 2020', $entry->time);
        $this->assertEquals('31608', $entry->pid);
        $this->assertEquals('2992634688', $entry->tid);
        $this->assertEquals('File does not exist: /var/www/index.php', $entry->message);

    }

}
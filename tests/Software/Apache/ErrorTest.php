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
        $parser = new ApacheErrorLogParser(ApacheErrorLogParser::FORMAT_APACHE_2_2_DEFAULT);
        $entry = $parser->parse("[Wed Oct 11 14:32:52 2000] [error] [client 127.0.0.1] client denied by server configuration: /export/home/live/ap/htdocs/test");
        $this->assertEquals('Wed Oct 11 14:32:52 2000', $entry->time);
        //$this->assertEquals('', $entry->fileName);
        $this->assertEquals('', $entry->errorCode);
        $this->assertEquals('127.0.0.1', $entry->remoteIp);
        $this->assertEquals('error', $entry->level);
        $this->assertEquals('client denied by server configuration: /export/home/live/ap/htdocs/test', $entry->message);
        $this->assertEquals('971274772', $entry->stamp);

    }

    public function testApache24Format()
    {
        $parser = new ApacheErrorLogParser(ApacheErrorLogParser::FORMAT_APACHE_2_4_DEFAULT);

        $entry = $parser->parse('[Fri Aug 14 12:13:21.650367 2020] [core:info] [pid 31608] [client 79.142.76.206:59415] AH00128: File does not exist: /var/www/index.php');
        $this->assertEquals('79.142.76.206', $entry->remoteIp);
        //$this->assertEquals('', $entry->fileName);
        $this->assertEquals('', $entry->errorCode);
        $this->assertEquals('core:info', $entry->level);
        $this->assertEquals('Fri Aug 14 12:13:21.650367 2020', $entry->time);
        $this->assertEquals('31608', $entry->pid);
        $this->assertEquals('AH00128: File does not exist: /var/www/index.php', $entry->message);
        $this->assertEquals('1597407201', $entry->stamp);

        $entry = $parser->parse('[Fri Aug 14 20:08:22.985375 2020] [php7:error] [pid 29669] [client 114.119.163.185:61710] script \'/var/www/domain.com/badfile.php\' not found or unable to stat');
        $this->assertEquals('114.119.163.185', $entry->remoteIp);
        //$this->assertEquals('', $entry->fileName);
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
        $this->assertEquals('', $entry->errorCode);
        $this->assertEquals('AH00169: caught SIGTERM, shutting down', $entry->message);
    }

    public function testApache24NPMFormat()
    {
        $parser = new ApacheErrorLogParser(ApacheErrorLogParser::FORMAT_APACHE_2_4_MPM);
        $entry = $parser->parse('[Fri Aug 14 12:13:21.650367 2020] [core:info] [pid 31608] [client 79.142.76.206:59415] AH00128: File does not exist: /var/www/index.php');
        
        $this->assertEquals('79.142.76.206', $entry->remoteIp);
        //$this->assertEquals('', $entry->fileName);
        $this->assertEquals('', $entry->errorCode);
        $this->assertEquals('core', $entry->module);
        $this->assertEquals('info', $entry->level);
        $this->assertEquals('Fri Aug 14 12:13:21.650367 2020', $entry->time);
        $this->assertEquals('31608', $entry->pid);
        $this->assertEquals('AH00128: File does not exist: /var/www/index.php', $entry->message);

        $entry = $parser->parse("[Mon Dec 23 07:49:01.981912 2013] [:error] [pid 3790] [client 204.232.202.107:46301] script '/var/www/timthumb.php' not found or unable to");
        $this->assertEquals('', $entry->module);
        $this->assertEquals('error', $entry->level);
        $this->assertEquals('', $entry->errorCode);
    }

    public function testApachErrorCodeFormat()
    {
        // 2.4  ???  [Tue Oct 13 23:03:12.080268 2020] [proxy:error] [pid 29705] (20014)Internal error (specific information not available): [client 1.2.3.4:56450] AH01084: pass request body failed to [::1]:8080 (localhost)
        // 2.4  ???  [Thu Jul 22 08:19:23.627412 2021] [proxy_http:error] [pid 1723] (-102)Unknown error -102: [client 1.2.3.4:32840] AH01095: prefetch request body failed to 127.0.0.1:3000 (127.0.0.1) from 1.2.3.4 ()

        $parser = new ApacheErrorLogParser(ApacheErrorLogParser::FORMAT_APACHE_2_4_MPM_EXTENDED);
        $entry = $parser->parse('[Thu Jul 22 08:19:23.627412 2021] [proxy_http:error] [pid 1723] (-102)Unknown error -102: [client 1.2.3.4:32840] AH01095: prefetch request body failed to 127.0.0.1:3000 (127.0.0.1) from 1.2.3.4 ()');
        
        $this->assertEquals('Thu Jul 22 08:19:23.627412 2021', $entry->time);
        $this->assertEquals('proxy_http', $entry->module);
        $this->assertEquals('error', $entry->level);
        $this->assertEquals('1723', $entry->pid);
        $this->assertEquals('', $entry->fileName);
        $this->assertEquals('(-102)Unknown error -102', $entry->errorCode);
        $this->assertEquals('1.2.3.4', $entry->remoteIp);
        $this->assertEquals('AH01095: prefetch request body failed to 127.0.0.1:3000 (127.0.0.1) from 1.2.3.4 ()', $entry->message);
    }


    public function testWithReferer()
    {

        //$format = '[%{u}t] [%-m:%l] [pid %P] %E: [client %a] %M , referer %{Referer}i';
        //$format = str_replace("\\", '', $format);
        $parser = new ApacheErrorLogParser(ApacheErrorLogParser::FORMAT_APACHE_2_4_MPM_REFERER);


        //debug
        echo $parser->getPCRE();

        $entry = $parser->parse('[Fri Aug 14 12:13:21.650367 2020] [core:info] [pid 31608] [client 79.142.76.206:59415] AH00128: File does not exist: /var/www/index.php, referer: https://domain.com/');

        $this->assertEquals('Fri Aug 14 12:13:21.650367 2020', $entry->time);
        $this->assertEquals('', $entry->errorCode);
        $this->assertEquals('core', $entry->module);
        $this->assertEquals('info', $entry->level);
        $this->assertEquals('31608', $entry->pid);
        $this->assertEquals('79.142.76.206', $entry->remoteIp);
        $this->assertEquals('AH00128: File does not exist: /var/www/index.php', $entry->message);
        $this->assertEquals('https://domain.com/', $entry->referer);

        $entry = $parser->parse('[Fri Aug 14 12:13:21.650367 2020] [core:info] [pid 31608] [client 79.142.76.206:59415] AH00128: File does not exist: /var/www/index.php');

        $this->assertEquals('Fri Aug 14 12:13:21.650367 2020', $entry->time);
        $this->assertEquals('', $entry->errorCode);
        $this->assertEquals('core', $entry->module);
        $this->assertEquals('info', $entry->level);
        $this->assertEquals('31608', $entry->pid);
        $this->assertEquals('79.142.76.206', $entry->remoteIp);
        $this->assertEquals('AH00128: File does not exist: /var/www/index.php', $entry->message);

        //
        $this->assertFalse(property_exists($entry, 'referer'));


    }

    public function testApache24NPM_EXTENTED_Format()
    {
        $parser = new ApacheErrorLogParser(ApacheErrorLogParser::FORMAT_APACHE_2_4_MPM_EXTENDED);
        $entry = $parser->parse('[Fri Aug 14 12:13:21.650367 2020] [core:info] [pid 31608] core.c(4752): [client 79.142.76.206:59415] AH00128: File does not exist: /var/www/index.php');
        
        $this->assertEquals('Fri Aug 14 12:13:21.650367 2020', $entry->time);
        $this->assertEquals('', $entry->errorCode);
        $this->assertEquals('core', $entry->module);
        $this->assertEquals('info', $entry->level);
        $this->assertEquals('31608', $entry->pid);
        $this->assertEquals('core.c(4752)', $entry->fileName);
        $this->assertEquals('79.142.76.206', $entry->remoteIp);
        $this->assertEquals('AH00128: File does not exist: /var/www/index.php', $entry->message);
    }

    public function testApache24NPM_TIDFormat()
    {
        $parser = new ApacheErrorLogParser(ApacheErrorLogParser::FORMAT_APACHE_2_4_MPM_TID);
        $entry = $parser->parse('[Fri Aug 14 12:13:21.650367 2020] [core:info] [pid 31608:tid 2992634688] [client 79.142.76.206:59415] AH00128: File does not exist: /var/www/index.php');
        
        $this->assertEquals('79.142.76.206', $entry->remoteIp);
        $this->assertEquals('', $entry->fileName);
        $this->assertEquals('', $entry->errorCode);
        $this->assertEquals('core', $entry->module);
        $this->assertEquals('info', $entry->level);
        $this->assertEquals('Fri Aug 14 12:13:21.650367 2020', $entry->time);
        $this->assertEquals('31608', $entry->pid);
        $this->assertEquals('2992634688', $entry->tid);
        $this->assertEquals('AH00128: File does not exist: /var/www/index.php', $entry->message);

    }

}
<?php declare(strict_types=1);

/** 
 *  ___                      _
 * | _ \ __ _  _ _  ___ ___ | | ___  __ _
 * |  _// _` || '_|(_-</ -_)| |/ _ \/ _` |
 * |_|  \__,_||_|  /__/\___||_|\___/\__, |
 *                                  |___/
 * 
 * (c) Kristuff <contact@kristuff.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @version    0.5.0
 * @copyright  2017-2020 Kristuff
 */

namespace Kristuff\Parselog\Software;

use Kristuff\Parselog\Core\LogEntryFactoryInterface;

/**
 * ApacheErrorLogParser 
 * 
 * The directive ErrorLogFormat is documented in version 2.4, but not in 2.2
 * 
 * Changes between 2.2/2.4:
 * - time field includes milliseconds in apache 2.4
 * - client field includes port number in apache 2.4
 * - note sure about pid/tid in apache 2.2
 * 
 * Example (default in 2.4): 
 * ErrorLogFormat "[%t] [%l] [pid %P] %F: %E: [client %a] %M"
 * 
 * Example (similar to the 2.2.x format):
 * ErrorLogFormat "[%t] [%l] %7F: %E: [client\ %a] %M% ,\ referer\ %{Referer}i"
 * 
 * Example (default format for threaded MPMs):
 * ErrorLogFormat "[%{u}t] [%-m:%l] [pid %P:tid %T] %7F: %E: [client\ %a] %M% ,\ referer\ %{Referer}i"
 * 
 * Note that depending on error, some field may be missing from output.
 * 
 * 2.2:                 [Wed Oct 11 14:32:52 2000] [error] [client 127.0.0.1] client denied by server configuration: /export/home/live/ap/htdocs/test
 * 2.4:                 [Thu Jun 27 11:55:44.569531 2013] [core:info] [pid 4101:tid 2992634688] [client 1.2.3.4:46652]
 * 2.4:                 [Thu Oct 01 14:01:53.127021 2020] [reqtimeout:info] [pid 21290] [client 1.2.3.4:63044] AH01382: Request header read timeout
 * 2.4:                 [Sun Sep 27 00:00:48.784450 2020] [mpm_prefork:notice] [pid 747] AH00163: Apache/2.4.38 (Debian) OpenSSL/1.1.1d configured -- resuming normal operations
 * 2.4 (with client):   [Thu Jun 27 11:55:44.569531 2013] [core:info] [pid 4101:tid 2992634688] [client 1.2.3.4:46652] AH00128: File does not exist: <path>
 * 2.4 (no client):     [Fri Sep 25 20:23:41.378709 2020] [mpm_prefork:notice] [pid 10578] AH00169: caught SIGTERM, shutting down
 * 2.4 (perfork):       [Mon Dec 23 07:49:01.981912 2013] [:error] [pid 3790] [client 1.2.3.4:46301] script '/var/www/timthumb.php' not found or unable to
 * 2.4 (with referer):  [Sat Oct 03 13:56:38.054651 2020] [authz_core:error] [pid 6257] [client 1.2.3.4:63032] AH01630: client denied by server configuration: /var/www/xxx.dommain.fr, referer: https://xxx.dommain.fr/
 * 2.4 (with 'F:'):     [Fri Jul 23 21:29:32.087762 2021] [php7:error] [pid 29504] sapi_apache2.c(356): [client 1.2.3.4:64950] script '/var/www/foo.php' not found or unable to stat
 * 2.4  ???             [Tue Oct 13 23:03:12.080268 2020] [proxy:error] [pid 29705] (20014)Internal error (specific information not available): [client 1.2.3.4:56450] AH01084: pass request body failed to [::1]:8080 (localhost)
 * 2.4  ???             [Thu Jul 22 08:19:23.627412 2021] [proxy_http:error] [pid 1723] (-102)Unknown error -102: [client 1.2.3.4:32840] AH01095: prefetch request body failed to 127.0.0.1:3000 (127.0.0.1) from 1.2.3.4 ()
 * @see https://httpd.apache.org/docs/2.2/mod/core.html#errorlog
 * @see https://httpd.apache.org/docs/2.4/mod/core.html#errorlogformat
 * @see https://github.com/fail2ban/fail2ban/issues/268
 */
class ApacheErrorLogParser extends SoftwareLogParser
{
    /**
     */
    const FORMAT_APACHE_2_2_DEFAULT = '[%t] [%l] %E: [client %a] %M';

    /**
     */
    const FORMAT_APACHE_2_2_REFERER = '[%t] [%l] %E: [client %a] %M ,\ referer\ %{Referer}i';

    /**
     */
    const FORMAT_APACHE_2_2_EXTENDED = '[%t] [%l] %F: %E: [client %a] %M';

    /**
     */
    const FORMAT_APACHE_2_2_REFERER_EXTENDED = '[%t] [%l] %F: %E: [client %a] %M ,\ referer\ %{Referer}i';

    /**
     * 
     */
    const FORMAT_APACHE_2_4_DEFAULT = '[%{u}t] [%l] [pid %P] %E: [client %a] %M';

    /**
     * 2_4_DEFAULT + %F:
     */
    const FORMAT_APACHE_2_4_EXTENDED = '[%{u}t] [%l] [pid %P] %F: %E: [client %a] %M';
    
    /**
     * based on that example (default format for threaded MPMs)
     * ErrorLogFormat "[%{u}t] [%-m:%l] [pid %P:tid %T] %7F: %E: [client\ %a] %M% ,\ referer\ %{Referer}i"
     * @see https://httpd.apache.org/docs/2.4/fr/mod/core.html#errorlog
     */
    const FORMAT_APACHE_2_4_MPM = '[%{u}t] [%-m:%l] [pid %P] %E: [client %a] %M';

    /**
     * based on that example (default format for threaded MPMs)
     * ErrorLogFormat "[%{u}t] [%-m:%l] [pid %P:tid %T] %7F: %E: [client\ %a] %M% ,\ referer\ %{Referer}i"
     * @see https://httpd.apache.org/docs/2.4/fr/mod/core.html#errorlog
     * 
     * 2_4_NPM + %F:
     */
    const FORMAT_APACHE_2_4_MPM_EXTENDED = '[%{u}t] [%-m:%l] [pid %P] %F: %E: [client %a] %M';

    /**
     * based on that example (default format for threaded MPMs)
     * ErrorLogFormat "[%{u}t] [%-m:%l] [pid %P:tid %T] %7F: %E: [client\ %a] %M% ,\ referer\ %{Referer}i"
     * @see https://httpd.apache.org/docs/2.4/fr/mod/core.html#errorlog
     */
    const FORMAT_APACHE_2_4_MPM_REFERER = '[%{u}t] [%-m:%l] [pid %P] %E: [client %a] %M ,\ referer\ %{Referer}i';

    /**
     * based on that example (default format for threaded MPMs)
     * ErrorLogFormat "[%{u}t] [%-m:%l] [pid %P:tid %T] %7F: %E: [client\ %a] %M% ,\ referer\ %{Referer}i"
     * @see https://httpd.apache.org/docs/2.4/fr/mod/core.html#errorlog
     */
    const FORMAT_APACHE_2_4_MPM_REFERER_EXTENDED = '[%{u}t] [%-m:%l] [pid %P] %F: %E: [client %a] %M ,\ referer\ %{Referer}i';

    /**
     * 2_4_NPM + tid %T + %F: %E:
     */
    const FORMAT_APACHE_2_4_MPM_TID = '[%{u}t] [%-m:%l] [pid %P:tid %T] %F: %E: [client %a] %M';
 
    /**
     * based on that example (default format for threaded MPMs)
     * ErrorLogFormat "[%{u}t] [%-m:%l] [pid %P:tid %T] %7F: %E: [client\ %a] %M% ,\ referer\ %{Referer}i"
     * @see https://httpd.apache.org/docs/2.4/fr/mod/core.html#errorlog
     */
    const FORMAT_APACHE_2_4_MPM_TID_REFERER = '[%{u}t] [%-m:%l] [pid %P:tid %T] %F: %E: [client %a] %M ,\ referer\ %{Referer}i';
 
    /**
     * Constructor
     * 
     * @access public
     * @param string                    $format    
     * @param LogEntryFactoryInterface  $factory        
     * 
     * @return void
     */
    public function __construct(string $format = null, LogEntryFactoryInterface $factory = null)
    {
        $this->software           = 'Apache';
        $this->prettyName         = 'Apache Error';
        $this->defaultFormat      = self::FORMAT_APACHE_2_4_DEFAULT;
        
        // set 2.2 format by default. Will be changed to 2.4 format, ie:
        // $this->timeFormat        = 'D M d H:i:s.u Y';
        // , if the format contain %{u} insted of %t  
        $this->timeFormat        = 'D M d H:i:s Y';

        $this->addFormat('default',                         self::FORMAT_APACHE_2_4_DEFAULT);  
        $this->addFormat('apache2.2 default',               self::FORMAT_APACHE_2_2_DEFAULT);  
        $this->addFormat('apache2.2 extented',              self::FORMAT_APACHE_2_2_EXTENDED);  
        $this->addFormat('apache2.2 referer',               self::FORMAT_APACHE_2_2_REFERER);  
        $this->addFormat('apache2.2 referer extented',      self::FORMAT_APACHE_2_2_REFERER_EXTENDED);  
        $this->addFormat('apache2.4 default',               self::FORMAT_APACHE_2_4_DEFAULT);  
        $this->addFormat('apache2.4 extented',              self::FORMAT_APACHE_2_4_EXTENDED);  
        $this->addFormat('apache2.4 npm',                   self::FORMAT_APACHE_2_4_MPM);  
        $this->addFormat('apache2.4 npm extented',          self::FORMAT_APACHE_2_4_MPM_EXTENDED);  
        $this->addFormat('apache2.4 npm referer',           self::FORMAT_APACHE_2_4_MPM_REFERER);  
        $this->addFormat('apache2.4 npm referer extented',  self::FORMAT_APACHE_2_4_MPM_REFERER_EXTENDED);  
        $this->addFormat('apache2.4 npm tid',               self::FORMAT_APACHE_2_4_MPM_TID);  
        $this->addFormat('apache2.4 npm tid referer',       self::FORMAT_APACHE_2_4_MPM_TID_REFERER);  

        $this->addPath("/var/log/");
        $this->addPath("/var/log/apache/");
        $this->addPath("/var/log/apache2/");
        $this->addPath("/var/log/httpd/");
        $this->addPath("/usr/local/var/log/apache/");
        $this->addPath("/usr/local/var/log/apache2/");
        $this->addPath("/usr/local/var/log/httpd/");
        $this->addPath("/opt/local/apache/logs/");
        $this->addPath("/opt/local/apache2/logs/");
        $this->addPath("/opt/local/httpd/logs/");
        $this->addPath("C:/wamp/logs/");

        $this->addFile('error.log');
        $this->addFile('error_log');
        $this->addFile("apache_error.log"); 

        // ***************
        // define patterns
        // ***************

        $this->addNamedPattern('%%' , 'percent', '\%');
                
        // %t 	        The current time
        // %{u}t 	The current time including micro-seconds
        $datePattern = '\[(?P<time>(?:Mon|Tue|Wed|Thu|Fri|Sat|Sun) (?:Jan|Feb|Mar|Apr|May|Jun|Jul|Aug|Sep|Oct|Nov|Dec) \d{2} \d{2}:\d{2}:\d{2}(?:\.\d{6}|) \d{4})\]';
        $this->addPattern('\[%{u}t\]', $datePattern);
        $this->addPattern('\[%t\]', $datePattern);
        $this->addPattern('%t', $datePattern);
        
        // %a 	Client IP address and port of the request (port is not registered by parser). 
        //      That column may be missing depending of error
        $clientIpPattern = '( \[client (?P<remoteIp>' . self::PATTERN_IP_ALL . ')(:[\d]+|)?\])?';
        $this->addPattern(' \[client %a\]' , $clientIpPattern);
        $this->addPattern(' \[%a\]' ,  $clientIpPattern);
        $this->addPattern(' %a' , $clientIpPattern);

        // %A 	Local IP-address and port
        //      That column may be missing depending of error
        $this->addNamedPattern('%A',  'localIP', self::PATTERN_IP_ALL, false);
        
        // %m 	Name of the module logging the message
        // %l 	Loglevel of the message
        $this->addPattern('\[%m:%l\]',  '\[(?<module>.+?)?:(?P<level>[\w]+)\]');
        $this->addPattern('\[%-m:%l\]', '\[(?<module>.+?)?:(?P<level>[\w]+)\]');
        $this->addPattern('\[%l\]',     '\[(?P<level>[\w:]+)\]');
        $this->addPattern('%l',         '\[(?P<level>[\w:]+)\]');

        // %P 	Process ID of current process (since apache 2.4?)
        // %T 	Thread ID of current thread
        $this->addPattern('\[pid %P:tid %T\]', '\[pid (?P<pid>\d+):tid (?P<tid>\d+)\]'); 
        $this->addPattern('%P %T',             '\[pid (?P<pid>\d+):tid (?P<tid>\d+)\]');
        $this->addPattern('\[pid %P\]', '\[pid (?P<pid>\d+)\]');
        $this->addPattern('\[%P\]',     '\[pid (?P<pid>\d+)\]');   
        $this->addPattern('%P',         '\[pid (?P<pid>\d+)\]');
        
        // %E 	APR/OS error status code and string
        //      That column may be missing depending of error
        $this->addPattern(' %E:', '( (?P<errorCode>.+?):)?');              

        // %F 	Source file name and line number of the log call
        $this->addPattern(' %F:', '( (?P<fileName>[^\*\s\|><\?]*[\/][^\*\s\|><\?]*):)?');              

        // %M 	The actual log message
        $this->addNamedPattern('%M',  'message', '.+?');

        // 
        //$this->addNamedPattern(', referer %{Referer}i', 'referer', '', false);

        // now let default constructor
        parent::__construct($format, $factory);
    } 

    /**
     * Sets the log format  
     * 
     * @access public
     * @param string    $format
     * 
     * @return void
     */
    public function setFormat(string $format): void
    {
        parent::setFormat($format);

        // set the correct time format
        if (strpos($this->logFormat, '%{u}t') !== false){
            $this->timeFormat = 'D M d H:i:s.u Y';
        }
    }
}
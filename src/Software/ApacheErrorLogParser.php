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
 * @version    0.2.0
 * @copyright  2017-2020 Kristuff
 */

namespace Kristuff\Parselog\Software;

use Kristuff\Parselog\Core\LogEntryFactoryInterface;

/**
 * 
 * ApacheErrorLogParser 
 * Default format from software doc [%t] [%l] [pid %P] %F: %E: [client %a] %M
 * 
 * Depending on the version and error it could be
 * 2.2:             [Wed Oct 11 14:32:52 2000] [error] [client 127.0.0.1] client denied by server configuration: /export/home/live/ap/htdocs/test
 * 2.4:             [Thu Jun 27 11:55:44.569531 2013] [core:info] [pid 4101:tid 2992634688] [client 1.2.3.4:46652]
 * 2.4 (no client): [Fri Sep 25 20:23:41.378709 2020] [mpm_prefork:notice] [pid 10578] AH00169: caught SIGTERM, shutting down
 * 2.4 (perfork):   [Mon Dec 23 07:49:01.981912 2013] [:error] [pid 3790] [client 204.232.202.107:46301] script '/var/www/timthumb.php' not found or unable to
 * Reference: https://github.com/fail2ban/fail2ban/issues/268
 * 
 * @see https://httpd.apache.org/docs/2.4/fr/mod/core.html#errorlog
 * 
 */
class ApacheErrorLogParser extends SoftwareLogParser
{
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
        $this->defaultFormat      = '%t %l %P %E: %a %M';
        $this->addFormat('default', '%t %l %P %E: %a %M');  

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

        $this->addColumn('%%' , 'percent',      '',             '(?P<percent>\%)');
        $this->addColumn('%t' , 'time',         'Date',         '\[(?P<time>(?:Mon|Tue|Wed|Thu|Fri|Sat|Sun) (?:Jan|Feb|Mar|Apr|May|Jun|Jul|Aug|Sep|Oct|Nov|Dec) \d{2} \d{2}:\d{2}:\d{2}(\.\d{6}|) \d{4})\]');
        
        // %a 	Client IP address and port of the request 
        $this->addColumn('%a' , 'remoteIP',     'IP',           '\[client (?P<remoteIp>{{PATTERN_IP_ALL}})(:[\d]+|)\]', false);
        
        $this->addColumn('%A',  'localIP',      'Local IP',      '(?P<localIp>{{PATTERN_IP_ALL}})', false);
        
        // %l 	Loglevel of the message
        $this->addColumn('%l', 'level',         'Level',        '\[(?P<level>[\w:]+)\]');

        // %P 	Process ID of current process (since apache 2.4?)
        $this->addColumn('%P',  'pid',           'PID',          '\[pid (?P<pid>\d+)\]', false);
        
        // %E 	APR/OS error status code and string
        $this->addColumn('%E:' , 'errorCode',   'Error',        '(?P<errorCode>[\w\d\s:]+):', false);              

        // %M 	The actual log message
        $this->addColumn('%M', 'message', 'Message', '(?P<message>.+?)');
    
        parent::__construct($format, $factory);
    } 
}
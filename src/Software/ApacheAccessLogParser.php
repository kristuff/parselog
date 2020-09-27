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

class ApacheAccessLogParser extends SoftwareLogParser
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
        $this->software         = 'Apache';
        $this->prettyName       = 'Apache Access';
        $this->defaultFormat    = "%h %l %u %t \"%r\" %>s %b";

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

        $this->addFile('access.log');
        $this->addFile('access_log');
        $this->addFile('apache.log');
        $this->addFile('apache_access.log');

        $this->addFormat('apache_common',          '%h %l %u %t \"%r\" %>s %O"');  
        $this->addFormat('apache_combined_vhost',  '%v:%p %h %l %u %t \"%r\" %>s %O \"%{Referer}i\" \"%{User-Agent}i\"'); 
        $this->addFormat('apache_combined',        '%h %l %u %t \"%r\" %>s %O \"%{Referer}i\" \"%{User-Agent}i\"');

        $this->addColumn('%%' , 'percent',      '',             '(?P<percent>\%)');
        $this->addColumn('%t' , 'time',         'Date',         '\[(?P<time>\d{2}/(?:Jan|Feb|Mar|Apr|May|Jun|Jul|Aug|Sep|Oct|Nov|Dec)/\d{4}:\d{2}:\d{2}:\d{2} (?:-|\+)\d{4})\]');
        $this->addColumn('%v' , 'serverName',   'ServerName',  '(?P<serverName>([a-zA-Z0-9]+)([a-z0-9.-]*))');
        $this->addColumn('%V' , 'canonicalServerName',  'Canonical ServerName',     '(?P<canonicalServerName>([a-zA-Z0-9]+)([a-z0-9.-]*))');
        $this->addColumn('%p' , 'port',         'Port',         '(?P<port>\d+)');

        // Client IP address of the request
        $this->addColumn('%a',  'remoteIp',     'IP',           '(?P<remoteIp>{{PATTERN_IP_ALL}})');
        
        // Remote hostname
        $this->addColumn('%h' , 'host',         'Remote Host',  '(?P<host>[a-zA-Z0-9\-\._:]+)'); 

        // Local IP-address
        $this->addColumn('%A',  'localIp',      'Local IP',     '(?P<localIp>{{PATTERN_IP_ALL}})');

        // Remote user if the request was authenticated. May be bogus if return status (%s) is 401 (unauthorized).
        $this->addColumn('%u',  'user',         'User',         '(?P<user>(?:-|[\w\-\.]+))');

        $this->addColumn('%l', 'logname',           'Log Name',     '(?P<logname>(?:-|[\w-]+))');
        $this->addColumn('%m', 'requestMethod',     'Method',       '(?P<requestMethod>OPTIONS|GET|HEAD|POST|PUT|DELETE|TRACE|CONNECT|PATCH|PROPFIND)');
        $this->addColumn('%U', 'URL',               'URL',          '(?P<URL>.+?)');
        $this->addColumn('%H', 'requestProtocol',   'Protocol',     '(?P<requestProtocol>HTTP/(1\.0|1\.1|2\.0))');
        $this->addColumn('%r', 'request',           'Request',      '(?P<request>(?:(?:[A-Z]+) .+? HTTP/(1\.0|1\.1|2\.0))|-|)');
        $this->addColumn('%>s','statuts',           'Status',       '(?P<status>\d{3}|-)');
        $this->addColumn('%O', 'sentBytes',         'Size',         '(?P<sentBytes>[0-9]+)');
    
        // Size of response in bytes, excluding HTTP headers. In CLF format, i.e. a '-' rather than a 0 when no bytes are sent.
        $this->addColumn('%b', 'responseBytes', 'Response Size',  '(?P<responseBytes>(\d+|-))');

        //The time taken to serve the request, in seconds.
        $this->addColumn('%T', 'requestTime',       'Request Time',         '(?P<requestTime>(\d+\.?\d*))');
        $this->addColumn('%D', 'timeServeRequest',  'Time Server Request',  '(?P<timeServeRequest>[0-9]+)');
        $this->addColumn('%I', 'receivedBytes',     'Received Bytes',       '(?P<receivedBytes>[0-9]+)');

        // common named columns (no pattern, use generic pattern bellow)
        $this->addColumn('%{Referer}i',     'headerReferer',        'Referer', '');
        $this->addColumn('%{User-Agent}i',  'headerUserAgent',      'User Agent', '');

        // dymanic named columns
        $this->addPattern('\%\{(?P<name>[a-zA-Z]+)(?P<name2>[-]?)(?P<name3>[a-zA-Z]+)\}i', '(?P<header\\1\\3>.*?)');


        parent::__construct($format, $factory);
    }
  
}
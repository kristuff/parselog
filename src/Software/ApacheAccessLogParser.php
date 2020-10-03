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
 * 
 * 
 * @see https://httpd.apache.org/docs/2.4/en/logs.html
 * @see https://httpd.apache.org/docs/2.4/en/mod/mod_log_config.html#formats
 */
class ApacheAccessLogParser extends SoftwareLogParser
{

    const FORMAT_COMMON         = '%h %l %u %t "%r" %>s %b';
    const FORMAT_COMMON_VHOST   = '%v %h %l %u %t "%r" %>s %b';
    const FORMAT_COMBINED       = '%h %l %u %t "%r" %>s %O "%{Referer}i" "%{User-Agent}i"';
    const FORMAT_COMBINED_VHOST = '%v:%p %h %l %u %t "%r" %>s %b "%{Referer}i" "%{User-Agent}i"';
    const FORMAT_REFERER        = '%{Referer}i';
    const FORMAT_AGENT          = '%{User-Agent}i'; // TODO

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
        $this->defaultFormat    = self::FORMAT_COMMON;
        $this->timeFormat       = 'd/M/Y:H:i:s P';
       
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
        $this->addFormat('apache_common_vhost',    '%v %l %u %t \"%r\" %>s %b');


        $this->addNamedPattern('%%' , 'percent', '\%');

        // time 
        $this->addPattern('%t' , '\[(?P<time>\d{2}/(?:Jan|Feb|Mar|Apr|May|Jun|Jul|Aug|Sep|Oct|Nov|Dec)/\d{4}:\d{2}:\d{2}:\d{2} (?:-|\+)\d{4})\]');
        
        $this->addNamedPattern('%v' , 'serverName',          '([a-zA-Z0-9]+)([a-z0-9.-]*)');
        $this->addNamedPattern('%V' , 'canonicalServerName', '([a-zA-Z0-9]+)([a-z0-9.-]*)');
        
        // port
        $this->addNamedPattern('%p' , 'port', '\d+');

        // Client IP address of the request
        $this->addNamedPattern('%a',  'remoteIp', self::PATTERN_IP_ALL);
        
        // Remote hostname
        $this->addNamedPattern('%h' , 'host', '[a-zA-Z0-9\-\._:]+'); 

        // Local IP-address
        $this->addNamedPattern('%A',  'localIp', self::PATTERN_IP_ALL);

        // Remote user if the request was authenticated. May be bogus if return status (%s) is 401 (unauthorized).
        $this->addNamedPattern('%u',  'user', '(?:-|[\w\-\.]+)');

        $this->addNamedPattern('%l', 'logname',         '(?:-|[\w-]+)');
        $this->addNamedPattern('%m', 'requestMethod',   'OPTIONS|GET|HEAD|POST|PUT|DELETE|TRACE|CONNECT|PATCH|PROPFIND');
        $this->addNamedPattern('%U', 'URL',             '.+?');
        $this->addNamedPattern('%H', 'requestProtocol', 'HTTP/(1\.0|1\.1|2\.0)');
        $this->addNamedPattern('%r', 'request',         '(?:(?:[A-Z]+) .+? HTTP/(1\.0|1\.1|2\.0))|-|');
        
        // Status of the final request
        $this->addNamedPattern('%>s', 'status', '\d{3}|-');
        
        // Bytes sent, including headers. May be zero in rare cases such as when a request is aborted before a 
        // response is sent. You need to enable mod_logio to use this.
        $this->addNamedPattern('%O', 'sentBytes',  '[0-9]+');
    
        // Size of response in bytes, excluding HTTP headers. In CLF format, i.e. a '-' rather than a 0 when no bytes are sent.
        $this->addNamedPattern('%b', 'responseBytes',  '(\d+|-)');

        //The time taken to serve the request, in seconds.
        $this->addNamedPattern('%T', 'requestTime', '(\d+\.?\d*)');
        
        // The time taken to serve the request, in microseconds
        $this->addNamedPattern('%D', 'timeServeRequest', '[0-9]+');
        
        $this->addNamedPattern('%I', 'receivedBytes', '[0-9]+');
       
        // dymanic named header columns
        $this->addPattern('\%\{(?P<name>[a-zA-Z]+)(?P<name2>[-]?)(?P<name3>[a-zA-Z]+)\}i', '(?P<header\\1\\3>.*?)');


        parent::__construct($format, $factory);
    }
  
}
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
 * @version    0.1.1
 * @copyright  2017-2020 Kristuff
 */

namespace Kristuff\Parselog;

class ApacheAccessLogParser extends LogParser
{
    /** 
     * @var string
     */
    public $defaultFormat = '%h %l %u %t \"%r\" %>s %b';

    /** 
     * @var array 
     */
    public $patterns = [
        '%%' => '(?P<percent>\%)',
        '%a' => '(?P<remoteIp>{{PATTERN_IP_ALL}})',
        '%A' => '(?P<localIp>{{PATTERN_IP_ALL}})',
        '%h' => '(?P<host>[a-zA-Z0-9\-\._:]+)',
        '%l' => '(?P<logname>(?:-|[\w-]+))',
        '%m' => '(?P<requestMethod>OPTIONS|GET|HEAD|POST|PUT|DELETE|TRACE|CONNECT|PATCH|PROPFIND)',
        '%H' => '(?P<requestProtocol>HTTP/(1\.0|1\.1|2\.0))',
        '%p' => '(?P<port>\d+)',
        '%r' => '(?P<request>(?:(?:[A-Z]+) .+? HTTP/(1\.0|1\.1|2\.0))|-|)',
        '%t' => '\[(?P<time>\d{2}/(?:Jan|Feb|Mar|Apr|May|Jun|Jul|Aug|Sep|Oct|Nov|Dec)/\d{4}:\d{2}:\d{2}:\d{2} (?:-|\+)\d{4})\]',
        '%u' => '(?P<user>(?:-|[\w\-\.]+))',
        '%U' => '(?P<URL>.+?)',
        '%v' => '(?P<serverName>([a-zA-Z0-9]+)([a-z0-9.-]*))',
        '%V' => '(?P<canonicalServerName>([a-zA-Z0-9]+)([a-z0-9.-]*))',
        '%>s' => '(?P<status>\d{3}|-)',
        '%b' => '(?P<responseBytes>(\d+|-))',
        '%T' => '(?P<requestTime>(\d+\.?\d*))',
        '%O' => '(?P<sentBytes>[0-9]+)',
        '%I' => '(?P<receivedBytes>[0-9]+)',
        '\%\{(?P<name>[a-zA-Z]+)(?P<name2>[-]?)(?P<name3>[a-zA-Z]+)\}i' => '(?P<header\\1\\3>.*?)',
        '%D' => '(?P<timeServeRequest>[0-9]+)',
    ];
}
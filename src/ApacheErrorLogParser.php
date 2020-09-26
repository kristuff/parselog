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

class ApacheErrorLogParser extends LogParser
{
    /** 
     * Default format from software doc [%t] [%l] [pid %P] %F: %E: [client %a] %M
     * 
     * Depending on the version and error it could be
     * 2.2:             [Wed Oct 11 14:32:52 2000] [error] [client 127.0.0.1] client denied by server configuration: /export/home/live/ap/htdocs/test
     * 2.4:             [Thu Jun 27 11:55:44.569531 2013] [core:info] [pid 4101:tid 2992634688] [client 1.2.3.4:46652]
     * 2.4 (no client): [Fri Sep 25 20:23:41.378709 2020] [mpm_prefork:notice] [pid 10578] AH00169: caught SIGTERM, shutting down
     * 2.4 (perfork):   [Mon Dec 23 07:49:01.981912 2013] [:error] [pid 3790] [client 204.232.202.107:46301] script '/var/www/timthumb.php' not found or unable to
     * Reference: https://github.com/fail2ban/fail2ban/issues/268
     * 
     * @var string
     */
    public $defaultFormat = '%t %l %P(| )%a(| )%M';

    /** 
     * @var array 
     */
    public $patterns = [
        '%%' => '(?P<percent>\%)',
        '%a' => '?(\[client (?P<remoteIp>{{PATTERN_IP_ALL}}):[\d]+\])',
        '%A' => '(?P<localIp>{{PATTERN_IP_ALL}})',
        '%l' => '\[(?P<severity>[\w:]+)\]',
        '%P' => '?(\[pid (?P<pid>\d+)\])',
        '%t' => '\[(?P<time>(?:Mon|Tue|Wed|Thu|Fri|Sat|Sun) (?:Jan|Feb|Mar|Apr|May|Jun|Jul|Aug|Sep|Oct|Nov|Dec) \d{2} \d{2}:\d{2}:\d{2}\.\d{6} \d{4})\]',
        '%M' => '(?P<message>.+?)',
     ]; 
}
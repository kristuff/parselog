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
 * @version    0.1.0
 * @copyright  2017-2020 Kristuff
 */

namespace Kristuff\Parselog;

class ApacheErrorLogParser extends LogParser
{
    /** 
     * Default format from software doc [%t] [%l] [pid %P] %F: %E: [client %a] %M
     * 
     * @var string
     */
    public $defaultFormat = '%t %l %P %a %M';

    /** 
     * @var array 
     */
    public $patterns = [
        '%%' => '(?P<percent>\%)',
        '%a' => '\[client (?P<remoteIp>{{PATTERN_IP_ALL}}):[\d]+\]',
        '%A' => '(?P<localIp>{{PATTERN_IP_ALL}})',
        '%l' => '\[(?P<severity>[\w:]+)\]',
        '%P' => '\[pid (?P<pid>\d+)\]',
        '%t' => '\[(?P<time>(?:Mon|Tue|Wed|Thu|Fri|Sat|Sun) (?:Jan|Feb|Mar|Apr|May|Jun|Jul|Aug|Sep|Oct|Nov|Dec) \d{2} \d{2}:\d{2}:\d{2}\.\d{6} \d{4})\]',
        '%M' => '(?P<message>[\w\s\.:\'\-\/_"]+)',
     ]; 
}
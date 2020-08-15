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

/**
 * Sample log line (fail2ban v0.10.2)
 * 2020-08-15 10:11:15,839 fail2ban.actions        [6924]: NOTICE  [_apache_hack] Ban 51.159.19.61
 */
class Fail2BanLogParser extends LogParser
{
    /** 
     * @var string
     */
    public $defaultFormat = '%t %s %p %l %m';

    /** 
     * @var array 
     */
    public $patterns = [
     // '%d' => '(?P<date>[\d \-,:]+)',
        '%t' => '(?P<time>[\d \-:]+)(,\d+)',
        '%s' => '(?P<service>[\w\d\. :]+(|\s+))',
        '%p' => '\[(?P<pid>\d+)\]:',
        '%l' => '(?P<level>(Level \d+|DEBUG|INFO|NOTICE|WARNING|ERROR|CRITICAL)(|\s+))',
        '%m' => '(?P<message>.+)',
    ];
}
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

class SyslogParser extends LogParser
{
    /** 
     * @var string
     */
    public $defaultFormat = '%t %h %s %m';

    /** 
     * @var array 
     */
    public $patterns = [
        '%t' => '(?P<time>[\w\d+/ :]+)',
        '%h' => '(?P<hostname>.+)',
        '%s' => '(?P<service>(\S+|\[\d+\])):',
        '%m' => '(?P<message>.+)',
    ];
}
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

namespace Kristuff\Parselog\Core;

use Kristuff\Parselog\LogParser;

class LogEntryFactory implements LogEntryFactoryInterface
{
    
    public function create(array $data): LogEntryInterface
    {
        $entry = new LogEntry();

        foreach (array_filter(array_keys($data), 'is_string') as $key) {
            $entry->{$key} = trim($data[$key]);
        }

        if (isset($entry->time)) {
            $stamp = strtotime($entry->time);
            
            if (false !== $stamp) {
                $entry->stamp = $stamp;
            }
        }

        return $entry;
    }
}
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

namespace Kristuff\Parselog\Core;

class LogEntryFactory implements LogEntryFactoryInterface
{
    /**
     * Creates and return an object that implements LogEntryInterface with given data
     * 
     * @access public
     * @param array     $data        
     * 
     * @return \Kristuff\Parselog\Core\LogEntryInterface
     */
    public function create(array $data): LogEntryInterface
    {
        $entry = new LogEntry();

        foreach (array_filter(array_keys($data), 'is_string') as $key) {
            $entry->{$key} = trim($data[$key]);
        }

        return $entry;
    }


}
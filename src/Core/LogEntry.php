<?php declare(strict_types=1);

/**
 *  ___             _
 * | _ \__ _ _ _ __| |___  __ _
 * |  _/ _` | '_(_-< / _ \/ _` |
 * |_| \__,_|_| /__/_\___/\__, |
 *                        |___/
 * 
 * (c) Kristuff <kristuff@kristuff.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @version    0.7.2
 * @copyright  2017-2021 Kristuff
 */

namespace Kristuff\Parselog\Core;

class LogEntry implements LogEntryInterface
{
    /** @var int|null */
    public $stamp;
}
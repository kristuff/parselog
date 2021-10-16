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

interface LogEntryFactoryInterface
{
    /**
     * Creates and return an object that extends LogEntryInterface with given data
     * 
     * @access public
     * @param array     $data        
     * 
     * @return \Kristuff\Parselog\Core\LogEntryInterface
     */
    public function create(array $data): LogEntryInterface;
}
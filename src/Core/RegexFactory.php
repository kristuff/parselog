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
 * @version    0.7.1
 * @copyright  2017-2021 Kristuff
 */

namespace Kristuff\Parselog\Core;

/** 
 * 
 */
class RegexFactory
{
    /**
     * Internal pattern, will be replaced by all valid ip patterns (ipV4 + ipV6)
     */
    const PATTERN_IP_ALL        = '{{PATTERN_IP_ALL}}'; 
    
    /** IPV4 pattern */
    const PATTERN_IP_V4         = '(((25[0-5]|2[0-4][0-9]|[01]?[0-9]?[0-9])\.){3}(25[0-5]|2[0-4][0-9]|[01]?[0-9]?[0-9]))';
   
    /** IPV6 full pattern */
    const PATTERN_IP_V6_FULL    ='([0-9A-Fa-f]{1,4}(:[0-9A-Fa-f]{1,4}){7})'; // 1:1:1:1:1:1:1:1
    
    /** IPV6 null pattern */
    const PATTERN_IP_V6_NULL    = '(::)';

    /** IPV6 leading pattern */
    const PATTERN_IP_V6_LEADING = '(:(:[0-9A-Fa-f]{1,4}){1,7})'; // ::1:1:1:1:1:1:1
    
    /** IPV6 mid pattern */
    const PATTERN_IP_V6_MID     = '(([0-9A-Fa-f]{1,4}:){1,6}(:[0-9A-Fa-f]{1,4}){1,6})'; // 1:1:1::1:1:1
    
    /** IPV6 trailing pattern */
    const PATTERN_IP_V6_TRAILING = '(([0-9A-Fa-f]{1,4}:){1,7}:)'; // 1:1:1:1:1:1:1::

    
    // pattern for one number
    const PATTERN_NUMBER     = '[0-9]';

    // pattren for numbers
    const PATTERN_NUMBERS    = '[0-9]+';

}
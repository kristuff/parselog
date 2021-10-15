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
 * @version    0.7.0
 * @copyright  2017-2021 Kristuff
 */

namespace Kristuff\Parselog;

use Kristuff\Parselog\Core\LogEntryFactoryInterface;
use Kristuff\Parselog\Software\SoftwareLogParser;

/**
 * 
 */
class LogParserFactory 
{
    const TYPE_APACHE_ACCESS    = 'apache_access';
    const TYPE_APACHE_ERROR     = 'apache_error';
    const TYPE_SYSLOG           = 'syslog';
    const TYPE_FAIL2BAN         = 'fail2ban';
    const TYPE_MARIADB_ERROR    = 'mariadb_error';

    /** 
     * Gets a new LogParser instance based on given logType
     * 
     * @access public
     * @static
     * @param string                    $logType        The internal log type
     * @param string                    $format         The log format. Default is null
     * @param LogEntryFactoryInterface  $factory        The custom log entry factory. Default is null (use default factory)       
     * 
     * @return \Kristuff\Parselog\LogParser|null        
     */
    public static function getParser(string $logType, string $format = null, LogEntryFactoryInterface $factory = null): ?SoftwareLogParser
    {
        switch ($logType){

            case self::TYPE_APACHE_ACCESS: 
                return new \Kristuff\Parselog\Software\ApacheAccessLogParser($format, $factory); 

            case self::TYPE_APACHE_ERROR: 
                return new \Kristuff\Parselog\Software\ApacheErrorLogParser($format, $factory); 

            case self::TYPE_SYSLOG: 
                return new \Kristuff\Parselog\Software\SyslogParser($format, $factory); 

            case self::TYPE_FAIL2BAN:
                return new \Kristuff\Parselog\Software\Fail2BanLogParser($format, $factory); 

            case self::TYPE_MARIADB_ERROR:
                return new \Kristuff\Parselog\Software\MariadbErrorLogParser($format, $factory); 

                
        }
        return null;
    }

}
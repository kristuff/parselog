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

namespace Kristuff\Parselog\Software;

use Kristuff\Parselog\Core\LogEntryFactoryInterface;

/**
 * https://mariadb.com/kb/en/error-log/
 * 
 * Until MariaDB 10.1.4, the format consisted of the date (yymmdd) and time, followed by the type of error (Note, Warning or Error) and the error message, for example:
 * 160615 16:53:08 [Note] InnoDB: The InnoDB memory heap is disabled
 * 
 * From MariaDB 10.1.5, the date format has been extended to yyyy-mm-dd, and the thread ID has been added, for example:
 * 2016-06-15 16:53:33 139651251140544 [Note] InnoDB: The InnoDB memory heap is disabled
 * 
 * 
 * 2021-10-15  5:04:02 3163 [Warning] Aborted connection 3163 to db: 'xxx' user: 'xxx' host: 'localhost' (Got timeout reading communication packets)
 */
class MariadbErrorLogParser extends SoftwareLogParser
{
    /**
     * Constructor
     * 
     * @access public
     * @param string                    $format    
     * @param LogEntryFactoryInterface  $factory        
     * 
     * @return void
     */
    public function __construct(string $format = null, LogEntryFactoryInterface $factory = null)
    {
        $this->software       = 'MariaDB';
        $this->prettyName     = 'MariaDB Error';

        $this->addFormat('default', '%t %i %l %m');
        $this->defaultFormat      = '%t %i %l %m';
        
        $this->addPath("/var/log/mysql/");
        $this->addFile("error.log");

        $this->addPattern('%t',  '(?P<time>[0-9\-]+\s+[0-9:]+)');
        $this->addPattern('%i',   '(?P<tid>\d+)');
        $this->addPattern('%l',  '\[(?P<level>.+?)\]');
        $this->addPattern('%m',  '(?P<message>.+)');

        parent::__construct($format, $factory);
    }
}
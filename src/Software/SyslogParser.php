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
 * @version    0.3.0
 * @copyright  2017-2020 Kristuff
 */

namespace Kristuff\Parselog\Software;

use Kristuff\Parselog\Core\LogEntryFactoryInterface;

/**
 *  Aug 15 10:39:01 domain CRON[25038]: (root) CMD (  [ -x /usr/lib/php/sessionclean ] && if [ ! -d /run/systemd/system ]; then /usr/lib/php/sessionclean; fi)
 *  Oct  2 14:51:43 kristuff systemd-logind[342]: New session 827 of user XXX
 */
class SyslogParser extends SoftwareLogParser
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
        $this->software       = 'Syslog';
        $this->prettyName     = 'Syslog';
      //  $this->timeFormat     = 'M j h:i:s';

        $this->addFormat('default', '%t %h %s %m');
        $this->defaultFormat      = '%t %h %s %m';
        
        $this->addPath("/var/log/");
        $this->addFile("syslog");
        //todo

        $this->addPattern('%t',  '(?P<time>(?:Jan|Feb|Mar|Apr|May|Jun|Jul|Aug|Sep|Oct|Nov|Dec) (\s\d|\d{2}) \d{2}:\d{2}:\d{2})');
        $this->addPattern('%h',  '(?P<hostname>.+?)');
        $this->addPattern('%s',  '(?P<service>(\S+|\[\d+\])):');
        $this->addPattern('%m',  '(?P<message>.+)');

        parent::__construct($format, $factory);
    }
}
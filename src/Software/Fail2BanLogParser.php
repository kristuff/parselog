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

namespace Kristuff\Parselog\Software;

use Kristuff\Parselog\Core\LogEntryFactoryInterface;

/**
 * Sample log line (fail2ban v0.10.2)
 * 2020-08-15 10:11:15,839 fail2ban.actions        [6924]: NOTICE  [_apache_hack] Ban 1.2.3.4
 * 2020-08-14 10:44:57,101 fail2ban.utils          [6924]: Level 39 7f3d4c0a78c8 -- exec: [\'f2bV_matches=$0 \n/usr/sbin/abuseipdb -R "1.2.3.4" -c "11" -m "$f2bV_matches"', \'Aug 14 10:44:54 kristuff postfix/smtpd[15598]: NOQUEUE: reject: RCPT from unknown[1.2.3.4] 454 4.7.1 <XXX@XXX.XX>: Relay access denied; from=<XXX@XXX.XX> to=<XXX@XXX.XX> proto=ESMTP helo=<WIN-6HF4HIGXJRE>\']
 */
class Fail2BanLogParser extends SoftwareLogParser
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
        $this->software       = 'Fail2ban';
        $this->prettyName     = 'Fail2ban';
        $this->addFormat('default', '%t %s %p %l %j %m');
        $this->defaultFormat      = '%t %s %p %l %j %m';
        $this->timeFormat   = 'Y-m-d';

        $this->addPath("/var/log/");
        $this->addFile("fail2ban.log");

        $this->addPattern('%t', '(?P<time>[\d \-:]+)(,\d+)');
        $this->addPattern('%s', '(?P<service>[\w\d\. :]+(|\s+))');
        $this->addPattern('%p', '\[(?P<pid>\d+)\]:');
        $this->addPattern('%l ', '(?P<level>(Level \d+|DEBUG|INFO|NOTICE|WARNING|ERROR|CRITICAL)(:|\s+))');
        $this->addPattern('%j ', '(\[(?P<jail>\S+)\] )?');
        $this->addPattern('%m', '(?P<message>.+)');

        parent::__construct($format, $factory);
    }
}
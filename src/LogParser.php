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
 * @version    0.2.0
 * @copyright  2017-2020 Kristuff
 */

namespace Kristuff\Parselog;

use Kristuff\Parselog\Core\LogEntryInterface;
use Kristuff\Parselog\Core\LogEntryFactory;
use Kristuff\Parselog\Core\LogEntryFactoryInterface;

/** 
 * LogParser
 */
class LogParser
{
    /** 
     * @access private
     * @var string 
     */
    private $pcreFormat;

    /** 
     * @access private
     * @var string
     */
    private $logFormat = '';

    /**
     * @access private
     * @var LogEntryFactoryInterface 
     */
    private $factory;

    /** 
     * @access protected
     * @var array 
     */
    protected $patterns = [];
   
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
        $this->setFormat($format ?? '');
        $this->factory = $factory ?: new LogEntryFactory();
    }

    /**
     * Sets/Adds pattern 
     * 
     * @access public
     * @param string    $placeholder    
     * @param string    $pattern        
     * 
     * @return void
     */
    public function addPattern(string $placeholder, string $pattern): void
    {
        $this->patterns[$placeholder] = $pattern;
    }

    /**
     * Gets the current format (defined by user or default)
     * 
     * @access public
     * 
     * @return string
     */
    public function getFormat(): string
    {
        return $this->logFormat;
    }

    /**
     * Sets the log format  
     * 
     * @access public
     * @param string    $format
     * 
     * @return void
     */
    public function setFormat(string $format): void
    {
        // store log format update IP pattern
        $this->logFormat = $format ;
        $this->updateIpPatterns();

        // strtr won't work for "complex" header patterns
        // $this->pcreFormat = strtr("#^{$format}$#", $this->patterns);
        $expr = "#^{$format}$#";

        foreach ($this->patterns as $pattern => $replace) {
            $expr = preg_replace("/{$pattern}/", $replace, $expr);
        }

        $this->pcreFormat = $expr;
    }

    /**
     * Parses one single log line.
     * 
     * @access public
     * @param string    $line
     * 
     * @return LogEntryInterface
     * @throws FormatException
     */
    public function parse(string $line): LogEntryInterface
    {
        if (!preg_match($this->getPCRE(), $line, $matches)) {
            throw new FormatException($line);
        }
        
        return $this->factory->create($matches);
    }

    /**
     * Gets the PCRE filter.
     * 
     * @access public
     * @return string
     */
    public function getPCRE(): string
    {
        return (string) $this->pcreFormat;
    }

    /**
     * Replaces {{PATTERN_IP_ALL}} with the IPV4/6 patterns.
     * 
     * @access public
     * @return void
     */
    private function updateIpPatterns(): void
    {
        // Set IPv4 & IPv6 recognition patterns
        $ipPatterns = implode('|', [
            'ipv4' => '(((25[0-5]|2[0-4][0-9]|[01]?[0-9]?[0-9])\.){3}(25[0-5]|2[0-4][0-9]|[01]?[0-9]?[0-9]))',
            'ipv6full' => '([0-9A-Fa-f]{1,4}(:[0-9A-Fa-f]{1,4}){7})', // 1:1:1:1:1:1:1:1
            'ipv6null' => '(::)',
            'ipv6leading' => '(:(:[0-9A-Fa-f]{1,4}){1,7})', // ::1:1:1:1:1:1:1
            'ipv6mid' => '(([0-9A-Fa-f]{1,4}:){1,6}(:[0-9A-Fa-f]{1,4}){1,6})', // 1:1:1::1:1:1
            'ipv6trailing' => '(([0-9A-Fa-f]{1,4}:){1,7}:)', // 1:1:1:1:1:1:1::
        ]);

        foreach ($this->patterns as &$value) {
            $value = str_replace('{{PATTERN_IP_ALL}}', $ipPatterns, $value);
        }
    }
}
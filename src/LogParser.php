<?php

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

namespace Kristuff\Parselog;

use Kristuff\Parselog\Core\LogFormat;
use Kristuff\Parselog\Core\LogEntryInterface;
use Kristuff\Parselog\Core\LogEntryFactory;
use Kristuff\Parselog\Core\LogEntryFactoryInterface;
use Kristuff\Parselog;

/** 
 * LogParser
 */
class LogParser
{
    /** 
     * @var string 
     */
    private $pcreFormat;

    /** 
     * @var LogFormat
     */
    private $logFormat = '';

     /** 
     * @var array 
     */
    public $patterns = [];

    /** 
     * @var string
     */
    public $defaultFormat = '';

    /**
     *  @var LogEntryFactoryInterface 
     */
    private $factory;


    public function __construct(string $format = null, LogEntryFactoryInterface $factory = null)
    {
        $this->logFormat = $format ?? $this->defaultFormat;
        $this->updateIpPatterns();
        $this->setFormat($this->logFormat);
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
        $this->updateIpPatterns();
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
        if (!preg_match($this->pcreFormat, $line, $matches)) {
            throw new FormatException($line);
        }

        return $this->factory->create($matches);
    }

    //todo
    public function getPCRE(): string
    {
        return (string) $this->pcreFormat;
    }

    /**
     * Replaces {{PATTERN_IP_ALL}} with the IPV4/6 patterns.
     * //todo
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
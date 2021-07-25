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
 * @version    0.6.0
 * @copyright  2017-2021 Kristuff
 */

namespace Kristuff\Parselog;

use DateTime;
use Kristuff\Parselog\Core\LogEntryInterface;
use Kristuff\Parselog\Core\LogEntryFactory;
use Kristuff\Parselog\Core\LogEntryFactoryInterface;
use Kristuff\Parselog\Core\RegexFactory;

/** 
 * LogParser
 */
class LogParser extends RegexFactory
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
    protected $logFormat = '';

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
     * The time format 
     * 
     * @access protected
     * @var string
     */
    protected $timeFormat = null;

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
     * Sets/Adds named pattern 
     * 
     * @access public
     * @param string    $placeholder
     * @param string    $propertyName
     * @param string    $pattern            The pattern expression
     * @param bool      $required           False if the column way be missing from output. Default is true.
     *                                      Note this feature won't work with the first column. 
     * 
     * @return void
     */
    public function addNamedPattern(string $placeholder, string $propertyName, string $pattern, bool $required = true): void
    {
        // First field must be a required field
        if ($required === false && count($this->patterns) === 0){
            throw new \Kristuff\Parselog\InvalidArgumentException(
                "Invalid value 'false' for argument 'required' given: First pattern must be a required pattern.");
        }

        // required or optional column ?
        // Adjust pattern for nullable columns and add space before placeholder
        // - $format = '%t %l %P %E: %a %M';
        // + $format = '%t %l( %P)?( %E:)?(%a)? %M';
        $key = $required ? $placeholder :  ' ' . $placeholder ; 
        $val = '(?P<'. $propertyName . '>' . ($required ? $pattern : '( ' . $pattern . ')?') . ')'; 

        $this->addPattern($key, $val);
    }

    /**
     * Sets/Adds pattern 
     * 
     * @access public
     * @param string    $placeholder
     * @param string    $propertyName
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

        // Remove backslashes from format
        $format = str_replace("\\", '', $format);

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
        
        $entry = $this->factory->create($matches);

        if (isset($entry->time)) {
            $entry->stamp = $this->getTimestamp($entry->time);
        }
      
        return $entry;
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
     * Converts time to previously set format.
     *
     * @access protected
     * @param string        $time
     *
     * @return int|null
     */
    protected function getTimestamp($time): ?int
    {
        // try to get stamp from string
        if (isset($this->timeFormat)){
            $dateTime = DateTime::createFromFormat($this->timeFormat, $time);

            if (false !== $dateTime) {
                return $dateTime->getTimestamp();
            }
        }

        // try to get stamp from string
        $stamp = strtotime($time);
        if (false !== $stamp) {
            return $stamp;
        }

        return null;
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
            'ipv4'          => self::PATTERN_IP_V4,
            'ipv6full'      => self::PATTERN_IP_V6_FULL,        // 1:1:1:1:1:1:1:1
            'ipv6null'      => self::PATTERN_IP_V6_NULL,        // ::
            'ipv6leading'   => self::PATTERN_IP_V6_LEADING,     // ::1:1:1:1:1:1:1
            'ipv6mid'       => self::PATTERN_IP_V6_MID,         // 1:1:1::1:1:1
            'ipv6trailing'  => self::PATTERN_IP_V6_TRAILING,    // 1:1:1:1:1:1:1::
        ]);

        foreach ($this->patterns as &$value) {
            $value = str_replace('{{PATTERN_IP_ALL}}', $ipPatterns, $value);
        }
    }
}
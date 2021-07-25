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

namespace Kristuff\Parselog\Software;

use Kristuff\Parselog\Core\LogEntryFactoryInterface;
use Kristuff\Parselog\LogParser;

/**
 * Abstract base class for software parser
 */
abstract class SoftwareLogParser extends LogParser
{
    /** 
     * The default log format 
     * 
     * @access protected
     * @var string
     */
    protected $defaultFormat = '';

    /** 
     * The software name
     * 
     * @access protected
     * @var string 
     */
    protected $software = '';

    /** 
     * The log pretty name
     * 
     * @access protected
     * @var string 
     */
    protected $prettyName = '';

    /** 
     * @access protected
     * @var array 
     */
    protected $knownFormats = [];

    /** 
     * The log files names
     * 
     * @access protected
     * @var array 
     */
    protected $files = [];

    /** 
     * The log files paths
     * 
     * @access protected
     * @var array 
     */
    protected $paths = [];

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
        parent::__construct($format ?? $this->defaultFormat, $factory);
    }

    /**
     * Gets the pretty name 
     * 
     * @access public
     * 
     * @return string
     */
    public function getPrettyName(): string
    {
        return $this->prettyName;
    }

    /**
     * Gets the software name 
     * 
     * @access public
     * 
     * @return string
     */
    public function getSoftware(): string
    {
        return $this->software;
    }
    
    /**
     * Add a format to the known formats list
     * 
     * @access protected
     * @param string    $name        Common name
     * @param string    $format      The log format
     * 
     * @return void
     */
    protected function addFormat(string $name, string $format): void
    {
        $this->knownFormats[$name] = $format;
    }

    /**
     * Gets the list of known log formats
     * 
     * @access public
     * 
     * @return array                An indexed array name/format
     */
    public function getKnownFormats(): array
    {
        return $this->knownFormats;
    }

    /**
     * Add a file to the known files list
     * 
     * @access protected
     * @param string    $fielName    The log file name
     * 
     * @return void
     */
    protected function addFile(string $fileName): void
    {
        $this->files[] = $fileName;
    }

    /**
     * Gets the list of known files names for current parser
     *  
     * 
     * @access public
     * 
     * @return array
     */
    public function getFiles(): array
    {
        return $this->files;
    }

    /**
     * Add a path to the known paths list
     * 
     * @access protected
     * @param string    $path        The log path
     * 
     * @return void
     */
    protected function addPath(string $path): void
    {
        $this->paths[] = $path;
    }

    /**
     * Gets the list of known paths for current parser
     * 
     * @access public
     * 
     * @return array
     */
    public function getPaths(): array
    {
        return $this->paths;
    }
}
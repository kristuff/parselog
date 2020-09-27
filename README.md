# parselog
> based on [kassner/log-parser](https://github.com/kassner/log-parser) primarily designed to parse web access logs, **Parselog** extends to other logs like web error, syslog, fail2ban, ... *(in progress)*

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/kristuff/parselog/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/kristuff/parselog/?branch=master)
[![Build Status](https://travis-ci.org/kristuff/parselog.svg?branch=master)](https://travis-ci.org/kristuff/parselog)
[![codecov](https://codecov.io/gh/kristuff/parselog/branch/master/graph/badge.svg)](https://codecov.io/gh/kristuff/parselog)
[![Latest Stable Version](https://poser.pugx.org/kristuff/parselog/v/stable)](https://packagist.org/packages/kristuff/parselog)
[![License](https://poser.pugx.org/kristuff/parselog/license)](https://packagist.org/packages/kristuff/parselog)

# Index
- [Features](#Features) 
- [Requirments](#Requirments) 
- [Api Documentation](#Api-Documentation) 
- [License](#License) 

# Features
- Generic customizable log parser
- Predefined log parser: ✓ `Apache Access`, ✓ `Apache Error`, ✓ `Fail2ban`, ✓ `Syslog`
- IPv4 & IPv6 recognition patterns

# Requirments
- PHP >= 7.1
- Composer (for install)

# Api Documentation

*draft...*
- [LogParser overview](#LogParser-overview)
    - [LogParser class ](#LogParser-class ) 
    - [Basic usage](#Basic-usage) 
    - [Use custom LogEntry](#Use-custom-LogEntry) 
- [Software parsers](#Software-parsers) 
  - [ApacheAccessLogParser](#ApacheAccessLogParser) 
  - [ApacheErrorLogParser](#ApacheErrorLogParser) 
  - [SyslogParser](#SyslogParser) 
  - [Fail2BanLogParser](#Fail2BanLogParser) 


### LogParser overview

The library comes with a generic `LogParser` class you can configure from scratch to parse something, and predefined [software parsers](#Software-parsers) class you can use with no or less configuration.

#### LogParser class 

<details>
  <summary>Click to see the class methods:</summary>

| Method                                    | Parameters        | Description       |
| ----------                                | ---------------   | -------------     |

</details>

#### Basic usage 

```php
$parser = new \Kristuff\Parselog\LogParser();
$parser->addPattern('col1', 'YOUR PATTERN EXPRESSION'); 
$parser->addPattern('col2', 'YOUR PATTERN EXPRESSION'); 
$parser->setFormat('col1 col2');

$lines = file('/path/to/log/file', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

foreach ($lines as $line) {
    $entry = $parser->parse($line);
}
```
The `$entry` object will hold all data parsed. If the line does not match the defined format, a `\Kristuff\Parselog\FormatException` will be thrown.

```php
//todo
```

#### Use custom LogEntry
By default, the `LogParser::parse()` method returns a `\Kristuff\Parselog\Core\LogEntry` object. To use your own entry class, you will have to: 

-   create two new classes, your entry object that implements `\Kristuff\Parselog\Core\LogEntryInterface` interface and a factory, that implements `\Kristuff\Parselog\Core\LogEntryInterface` interface and that is responsible of creating it: 

        ```php
        class MyEntry implements \Kristuff\Parselog\Core\LogEntryInterface
        {
        }

        class MyEntryFactory implements \Kristuff\Parselog\Core\LogEntryFactoryInterface
        {
            public function create(array $data): \Kristuff\Parselog\Core\LogEntryInterface
            {
                // @TODO implement your code here to return a instance of MyEntry
            }
        }
        ```

-   and then provide the factory as the second argument to the `LogParser` or `SoftwareLogParser` constructor:

        ```php
        $factory = new MyEntryFactory();
        $parser = new \Kristuff\Parselog\Sofware\ApacheAccessLogParser(null, $factory);
        $entry = $parser->parse('193.191.216.76 - www-data [27/Jan/2014:04:51:16 +0100] "GET /wp-content/uploads/2013/11/whatever.jpg HTTP/1.1" 200 58678');
        ```

        `$entry` will be an instance of `MyEntry`.

### Software parsers

All software parsers extand the `\Kristuff\Parselog\Software\SoftwareLogParser` class, contain software configuration and provide helper functions to get the default log files, to get the defaults formats, ... 
You can create sotfware parser in two ways: 

-   use the dedicated existing class in `\Kristuff\Parselog\Software` like `ApacheAccessLogParser`. Current implementation are:
    -  `\Kristuff\Parselog\Software\ApacheAccessLogParser`
    -  `\Kristuff\Parselog\Software\ApacheErrorLogParser`
    -  `\Kristuff\Parselog\Software\Fail2BanLogParser`
    -  `\Kristuff\Parselog\Software\SyslogParser`
    

-   Or create a `SoftwareLogParser` instance from the `\Kristuff\Parser\LogParserFactory::getParser()` method:
    ```php
    use Kristuff\Parser\LogParserFactory;

    $parser = LogParserFactory::getParser(LogParserFactory::TYPE_APACHE_ACCESS);
    ```
    The `\Kristuff\Parser\LogParserFactory::getParser()` method takes the logtype as argument. Valide values (`string`) are:
    - `LogParserFactory::TYPE_APACHE_ACCESS`      // "apache_access"
    - `LogParserFactory::TYPE_APACHE_ERROR`       // "apache_error"
    - `LogParserFactory::TYPE_APACHE_FAIL2BAN`    // "fail2ban"
    - `LogParserFactory::TYPE_APACHE_SYSLOG`      // "syslog"


The `\Kristuff\Parselog\Software\SoftwareLogParser` class extends the `\Kristuff\Parselog\LogParser` and come with aditionally methods:

<details>
  <summary>Click to see the class methods:</summary>

| Method                                    | Parameters        | Description       |
| ----------                                | ---------------   | -------------     |
| `SoftwareLogParser::getSoftware()`        |- | Get The sofware name of current parser. Returns `string`  |
| `SoftwareLogParser::getFiles()`           |- | Get a list of possible files name of current parser Returns `array` |
| `SoftwareLogParser::getPaths()`           |- | Get a list of possible log paths of current parser. Returns `array` |
| `SoftwareLogParser::getKnownFormats()`    |- | Get a list of known formats for current parser. Returns an indexed `array` with name as key and format as value |

</details>



#### ApacheAccessLogParser    

Create an `ApacheAccessLogParser` instance:

```php
// use default format and entry factory
$parser = new \Kristuff\Parser\Software\ApacheAccessLogParser(); 

// use explicit format and default entry factory
$parser = new \Kristuff\Parser\Software\ApacheAccessLogParser('%h %l %u %t \"%r\" %>s %O \"%{Referer}i\" \"%{User-Agent}i\"'); 
```

Create an `SoftwareLogParser` instance from factory:
```php
use Kristuff\Parser\LogParserFactory;

$parser = LogParserFactory::getParser(LogParserFactory::TYPE_APACHE_ACCESS);
```
##### Apache access log format
The default `ApacheAccessLogParser` format is  `"%t %l %P %F: %E: %a %M"`.
The library supports Apache access log format since version 2.2. Here is the full list of [log format strings](https://httpd.apache.org/docs/2.4/en/mod/mod_log_config.html#formats) supported by Apache 2.4, and whether they are supported by the library:

<details>
  <summary>Click to see the list:</summary>

| Supported?    | Placeholder   | Property name     | Description |
|:----------:   |:-------------:|---------------    | -------------|
| **Yes**       | %%            | percent           | The percent sign. |
| **Yes**       | %a            | remoteIp          | Client IP address of the request (remoteIp).|
| **Yes**       | %A            | localIp           | Local IP-address. |
| No            | %B            | -                 | Size of response in bytes, excluding HTTP headers. |
| **Yes**       | %b            | responseBytes     | Size of response in bytes, excluding HTTP headers. In CLF format, i.e. a '-' rather than a 0 when no bytes are sent. |
| No            | %{VARNAME}C | - | The contents of cookie VARNAME in the request sent to the server. Only version 0 cookies are fully supported. |
| **Yes**       | %D            | timeServeRequest  | The time taken to serve the request, in microseconds. |
| No            | %{VARNAME}e   | -                 | The contents of the environment variable VARNAME |
| No            | %f            | -                 | Filename.          |
| **Yes**       | %h            | host              | Remote hostname.    |
| **Yes**       | %H            | requestProtocol   | The request protocol (this is Apache specific) |
| **Yes**       | %{VARNAME}i   | header{VARNAME}   | The contents of VARNAME: header line(s) in the request sent to the server. Changes made by other modules (e.g. mod_headers) affect this. If you're interested in what the request header was prior to when most modules would have modified it, use mod_setenvif to copy the header into an internal environment variable and log that value with the %{VARNAME}e described above. |
| No            | %k            | -                 | Number of keepalive requests handled on this connection.|
| **Yes**       | %l            | logname           | Remote logname (from identd, if supplied). This will return a dash unless mod_ident is present and IdentityCheck is set On. |
| No            | %L            | -                 | The request log ID from the error log. |
| **Yes**       | %m            | requestMethod     | The request method |
| No            | %{VARNAME}n   | -                 | The contents of note VARNAME from another module. |
| No            | %{VARNAME}o   | -                 | The contents of VARNAME: header line(s) in the reply.. |
| **Yes**       | %p            | port              | The canonical port of the server serving the request |
| No            | %{format}p    | -                 | The canonical port of the server serving the request or the server's actual port or the client's actual port. Valid formats are canonical, local, or remote. |
| No            | %P            | -                 | The process ID of the child that serviced the request. |
| No            | %{format}P    | -                 | The process ID or thread id of the child that serviced the request. Valid formats are pid, tid, and hextid. hextid requires APR 1.2.0 or higher. |
| No??????????  | %q  | - | The query string (prepended with a ? if a query string exists, otherwise an empty string)|
| **Yes**       | %r            | request           | First line of request |
| No            | %R            | -                 | The handler generating the response (if any). |
| No            | %s            | -                 | Status. For requests that got internally redirected, this is the status of the *original* request --- %>s for the last. |
| **Yes** | %>s | status            | Status |
| **Yes** | %t  | time              | Time the request was received (standard english format)   |
| No      | %{format}t    | -       | The time, in the form given by format.                    |
| **Yes** | %T  | requestTime       | The time taken to serve the request, in seconds. |
| **Yes** | %u  | user | Remote user if the request was authenticated. May be bogus if return status (%s) is 401 (unauthorized). |
| **Yes** | %U  | URL  | The URL path requested, not including any query string. |
| **Yes** | %v  | serverName            | The canonical ServerName of the server serving the request. |
| **Yes** | %V  | canonicalServerName   | The server name according to the UseCanonicalName setting. |
| No            | %X            | -                 | Connection status when response is completed: X = connection aborted before the response completed. + = connection may be kept alive after the response is sent. - = connection will be closed after the response is sent. |
| **Yes**       | %I            | receivedBytes     | Bytes received, including request and headers, cannot be zero. You need to enable mod_logio to use this. |
| **Yes**       | %O            | sentBytes         | Bytes sent, including headers, cannot be zero. You need to enable mod_logio to use this. |
| No            | %S            | -                 | Bytes transferred (received and sent), including request and headers, cannot be zero. This is the combination of %I and %O. You need to enable mod_logio to use this. |
</details>

#### ApacheErrorLogParser

Create an `ApacheErrorLogParser` instance:

```php
// use default format and entry factory
$parser = new \Kristuff\Parser\Software\ApacheAccessLogParser(); 

// use explicit format and default entry factory
$parser = new \Kristuff\Parser\Software\ApacheAccessLogeError('YOUR FORMAT'); 
```

Create an `SoftwareLogParser` instance from factory:
```php
use Kristuff\Parser\LogParserFactory;
$parser = LogParserFactory::getParser(LogParserFactory::TYPE_APACHE_ERROR);
```

The library supports Apache error log format version 2.2 and 2.4 with same parser. 
From software doc, the common format is:
```
ErrorLogFormat "[%t] [%l] [pid %P] %F: %E: [client %a] %M"
```
The default `ApacheErrorLogParser` format is `"'%t %l %P %E: %a %M"` (format must excludes brackets). Here is a partial list of [log format strings](https://httpd.apache.org/docs/2.4/en/mod/core.html#errorlogformat) supported by Apache 2.4, and whether they are supported by the library:

<details>
  <summary>Click to see the list:</summary>

| Supported?    | Placeholder   | Property name     | Description |
|:----------:   |:-------------:|---------------    | -------------|
| **Yes**       | %%            | percent           | The percent sign. |
| **Yes**       | %a            | remoteIp          | Client IP address of the request (remoteIp).|
| **Yes**       | %A            | localIp           | Local IP-address. |
| **Yes**       | %E:           | errorCode         | APR/OS error status code and string. |
| No            | %F:           | -                 | Source file name and line number of the log call. |
| **Yes**       | %l            | level             | Loglevel of the message. |
| **Yes**       | %M            | message           | The actual log message. |
| **Yes**       | %P            | pid               | Process ID of current process. |

</details>





#### SyslogParser

Create an `SyslogParser` instance:

```php
// use default format and entry factory
$parser = new \Kristuff\Parser\Software\SyslogParser(); 
```

Create an `SoftwareLogParser` instance from factory:
```php
use Kristuff\Parser\LogParserFactory;
$parser = LogParserFactory::getParser(LogParserFactory::TYPE_SYSLOG);
```

<details>
  <summary>Click to see the list:</summary>

| Supported?    | Placeholder   | Property name     | Description |
|:----------:   |:-------------:|---------------    | -------------|

</details>





#### Fail2BanLogParser

Create an `Fail2BanLogParser` instance:

```php
// use default format and entry factory
$parser = new \Kristuff\Parser\Software\Fail2banLogParser(); 
```

Create an `SoftwareLogParser` instance from factory:
```php
use Kristuff\Parser\LogParserFactory;
$parser = LogParserFactory::getParser(LogParserFactory::TYPE_FAIL2BAN);
```

<details>
  <summary>Click to see the list:</summary>

| Supported?    | Placeholder   | Property name     | Description |
|:----------:   |:-------------:|---------------    | -------------|

</details>



# License

The MIT License (MIT)

Copyright (c) 2017-2020 Kristuff

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in
all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.
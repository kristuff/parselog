# Parselog
> based on [kassner/log-parser](https://github.com/kassner/log-parser) primarily designed to parse web access logs, **Parselog** extends to other logs like web error, syslog, fail2ban, ... *(in progress)*

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/kristuff/parselog/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/kristuff/parselog/?branch=master)
[![Build Status](https://travis-ci.org/kristuff/parselog.svg?branch=master)](https://travis-ci.org/kristuff/parselog)
[![codecov](https://codecov.io/gh/kristuff/parselog/branch/master/graph/badge.svg)](https://codecov.io/gh/kristuff/parselog)
[![Latest Stable Version](https://poser.pugx.org/kristuff/parselog/v/stable)](https://packagist.org/packages/kristuff/parselog)
[![License](https://poser.pugx.org/kristuff/parselog/license)](https://packagist.org/packages/kristuff/parselog)

- [Features](#Features) 
- [Requirments](#Requirments) 
- [Api Documentation](#Api-Documentation) 
- [License](#License) 

# Features
- Generic customizable log parser
- Predefined software log parsers: ✓ `Apache Access`, ✓ `Apache Error`, ✓ `Fail2ban`, ✓ `Syslog`
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


TODO

</details>

#### Basic usage 

To use the `\Kristuff\Parselog\LogParser` class, you need to define the log format, then parse data using `LogParser::parse()` method. 

```php
$data = 'fooValue2 123456'; // the data to parse
$parser = new \Kristuff\Parselog\LogParser(); // create a LogParser instance
$parser->setFormat('(?P<foo>(fooValue1|fooValue2)) (?P<bar>[0-9]+)'); // set format including pattern
$entry = $parser->parse($data);// parse data

```

The `LogParser::parse()` method returns a `\Kristuff\Parselog\Core\LogEntry` object. That `$entry` object will hold all data parsed. 
```php
echo $entry->foo; // fooValue1
echo $entry->bar; // 123456

```
If the line does not match the defined format, a `\Kristuff\Parselog\FormatException` will be thrown.

#### Define log format
Defining the log format can be done in different ways. In section above, we defined the full pattern using the `LogParser::setFormat()` method. To define the log format more easily, you can use the `LogParser::addPattern()` or `LogParser::addNamedpattern()` methods.  

This 3 blocks of code produce the same result:

```php
// set format including pattern
$parser->setFormat('(?P<foo>(fooValue1|fooValue2)) (?P<bar>[0-9]+)');

// OR add patterns, then define format
$parser->addPattern('%1', '(?P<foo>(fooValue1|fooValue2))'); 
$parser->addPattern('%2', '(?P<bar>[0-9]+)'); 
$parser->setFormat('%1 %2');

// OR add named patterns, then define format
$parser->addNamedPattern('%1', 'foo', '(fooValue1|fooValue2)'); 
$parser->addNamedPattern('%2', 'bar', '[0-9]+'); 
$parser->setFormat('%1 %2');

```

*TODO $parser->addNamedPattern with optional column*


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

-   Use the dedicated existing class in `\Kristuff\Parselog\Software` like `ApacheAccessLogParser`. Current implementation are:
    -  `ApacheAccessLogParser`
    -  `ApacheErrorLogParser`
    -  `Fail2BanLogParser`
    -  `SyslogParser`
    

-   Or create a `SoftwareLogParser` instance from the `\ Kristuff\Parslog\LogParserFactory::getParser()` method:
    ```php
    use  Kristuff\Parslog\LogParserFactory;

    $parser = LogParserFactory::getParser(LogParserFactory::TYPE_APACHE_ACCESS);
    ```
    The `\ Kristuff\Parslog\LogParserFactory::getParser()` method takes the logtype as argument. Valide values (`string`) are:
    - `LogParserFactory::TYPE_APACHE_ACCESS`      // "apache_access"
    - `LogParserFactory::TYPE_APACHE_ERROR`       // "apache_error"
    - `LogParserFactory::TYPE_APACHE_FAIL2BAN`    // "fail2ban"
    - `LogParserFactory::TYPE_APACHE_SYSLOG`      // "syslog"


The `\Kristuff\Parselog\Software\SoftwareLogParser` class extends the `\Kristuff\Parselog\LogParser` and come with aditionally methods:

<details>
  <summary>Click to see the class methods:</summary>

| Method                                    | Parameters        | Description       |
| ----------                                | ---------------   | -------------     |
| `SoftwareLogParser::getSoftware()`        |- | Get the sofware name of current parser. Returns `string`  |
| `SoftwareLogParser::getFiles()`           |- | Get a list of possible files name of current parser Returns `array` |
| `SoftwareLogParser::getPaths()`           |- | Get a list of possible log paths of current parser. Returns `array` |
| `SoftwareLogParser::getKnownFormats()`    |- | Get a list of known formats for current parser. Returns an indexed `array` with name as key and format as value |

</details>



#### ApacheAccessLogParser    

Create an `ApacheAccessLogParser` instance:

```php
// use default format and entry factory
$parser = new \ Kristuff\Parslog\Software\ApacheAccessLogParser(); 

// use explicit format and default entry factory
$parser = new \ Kristuff\Parslog\Software\ApacheAccessLogParser('%h %l %u %t \"%r\" %>s %O \"%{Referer}i\" \"%{User-Agent}i\"'); 
```

Create an `SoftwareLogParser` instance from factory:
```php
use  Kristuff\Parslog\LogParserFactory;

$parser = LogParserFactory::getParser(LogParserFactory::TYPE_APACHE_ACCESS);
```
##### Apache access log format
The default `ApacheAccessLogParser` format is  `%h %l %u %t "%r" %>s %O`. You can retreive this format using the constant `ApacheAccessLogParser::FORMAT_COMMON`. Other registered formats are following:

```php
ApacheAccessLogParser::FORMAT_COMBINED       // %h %l %u %t "%r" %>s %O "%{Referer}i" "%{User-Agent}i"
ApacheAccessLogParser::FORMAT_COMBINED_VHOST // %v:%p %h %l %u %t "%r" %>s %O "%{Referer}i" "%{User-Agent}i"
ApacheAccessLogParser::FORMAT_COMMON_VHOST   // %v:%p %h %l %u %t "%r" %>s %O "%{Referer}i" "%{User-Agent}i"
ApacheAccessLogParser::FORMAT_REFERER        // %{Referer}i 
ApacheAccessLogParser::FORMAT_AGENT          // %{User-Agent}i 
```

The library works with Apache access log format since version 2.2. Here is the full list of [log format strings](https://httpd.apache.org/docs/2.4/en/mod/mod_log_config.html#formats) supported by Apache 2.4, and whether they are supported by the library:

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
| TODO!  | %q  | - | The query string (prepended with a ? if a query string exists, otherwise an empty string)|
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

##### Basic usage
Create an `ApacheErrorLogParser` instance:

```php
// use default format and entry factory
$parser = new \ Kristuff\Parslog\Software\ApacheAccessLogParser(); 

// use explicit format and default entry factory
$parser = new \ Kristuff\Parslog\Software\ApacheAccessLogeError('YOUR FORMAT'); 
```

Create an `SoftwareLogParser` instance from factory:
```php
use  Kristuff\Parslog\LogParserFactory;
$parser = LogParserFactory::getParser(LogParserFactory::TYPE_APACHE_ERROR);
```

##### Apache error log format
The default `ApacheErrorLogParser` format is  `[%{u}t] [%l] [pid %P] [client %a] %F: %E: %M` (format can include brackets). You can retreive this format using the constant `ApacheErrorLogParser::FORMAT_DEFAULT_APACHE_2_4`. Other registered formats are following:

```php
ApacheErrorLogParser::FORMAT_DEFAULT_APACHE_2_2   //[%t] [%l] [client %a] %F: %E: %M    
ApacheErrorLogParser::FORMAT_DEFAULT_APACHE_2_4   //[%{u}t] [%l] [pid %P] [client %a] %F: %E: %M    
ApacheErrorLogParser::FORMAT_MPM_APACHE_2_4       //[%{u}t] [%-m:%l] [pid %P] [client %a] %F: %E: %M    
ApacheErrorLogParser::FORMAT_MPM_TID_APACHE_2_4   //[%{u}t] [%-m:%l] [pid %P:tid %T] [client %a] %F: %E: %M    
```

The library supports Apache error log format version 2.2 and 2.4 with same parser. Here is a partial list of [log format strings](https://httpd.apache.org/docs/2.4/en/mod/core.html#errorlogformat) supported by Apache 2.4, and whether they are supported by the library:

<details>
  <summary>Click to see the list:</summary>

| Supported?    | Placeholder   | Property name     | Description |
|:----------:   |:-------------:|---------------    | -------------|
| **Yes**       | %%            | percent           | The percent sign. |
| **Yes**       | %a            | remoteIp          | Client IP address of the request (remoteIp).|
| **Yes**       | %A            | localIp           | Local IP-address. |
| **Yes**       | %E:           | errorCode         | APR/OS error status code and string. |
| **Yes**       | %F:           | fileName          | Source file name and line number of the log call. |
| **Yes**       | %l            | level             | Loglevel of the message. |
| **Yes**       | %M            | message           | The actual log message. |
| **Yes**       | %P            | pid               | Process ID of current process. |
| **Yes**       | %T            | tid               | Thread ID of current thread. |
| **Yes**       | %t 	          | time              | The current time
| **Yes**       | %{u}t         | time              | The current time including micro-seconds

</details>





#### SyslogParser

##### Basic usage
Create an `SyslogParser` instance:

```php
// use default format and entry factory
$parser = new \ Kristuff\Parslog\Software\SyslogParser(); 
```

Create an `SoftwareLogParser` instance from factory:
```php
use Kristuff\Parslog\LogParserFactory;
$parser = LogParserFactory::getParser(LogParserFactory::TYPE_SYSLOG);
```

##### Syslog format
The default `SyslogParser` format is  `%t %h %s%p: %m`. The fields are detailed below:

<details>
  <summary>Click to see the list:</summary>

| Supported?    | Placeholder   | Property name     | Description |
|:----------:   |:-------------:|---------------    | -------------|
| **Yes**       | %t            | time              | The current time 
| **Yes**       | %h            | hostname          | The hostname 
| **Yes**       | %s            | service           | The service/application that raises event 
| **Yes**       | %p            | pid               | Process ID. May be empty. 
| **Yes**       | %m            | message           | The log message

</details>




#### Fail2BanLogParser

##### Basic usage
Create an `Fail2BanLogParser` instance:

```php
// use default format and entry factory
$parser = new \ Kristuff\Parslog\Software\Fail2banLogParser(); 
```

Create an `SoftwareLogParser` instance from factory:
```php
use  Kristuff\Parslog\LogParserFactory;
$parser = LogParserFactory::getParser(LogParserFactory::TYPE_FAIL2BAN);
```

##### Fail2Ban log format

Here are some log line examples from Fail2ban (version 0.10.2):

```
2020-08-15 10:11:15,839 fail2ban.actions        [6924]: NOTICE  [_apache_hack] Ban 1.2.3.4
2020-08-14 10:44:57,101 fail2ban.utils          [536]: Level 39 7f4d265d09f0 -- returned 1
```

The default `Fail2BanLogParser` format is  `%t %s %p %l %j %m`. The fields are detailed below:

<details>
  <summary>Click to see the list:</summary>

| Supported?    | Placeholder   | Property name     | Description |
|:----------:   |:-------------:|---------------    | -------------|
| **Yes**       | %t            | time              | The current time. 
| **Yes**       | %s            | service           | The service that raises event. 
| **Yes**       | %p            | pid               | Process ID.
| **Yes**       | %l            | level             | The level/severity of the message. Usually a keyword (INFO, NOTICE, ...) but may appear as `Level ` followed by the error number 
| **Yes**       | %j            | jail              | The related jail (may be empty). 
| **Yes**       | %m            | message           | The log message.

</details>

##### Limitations / Known issues
- Field `time` does not register milliseconds


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
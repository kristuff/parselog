Parselog Api Doc 
-----------------

***draft***

## LogParser overview

The library comes with a generic `LogParser` you can configure from scratch to parse something, and predefined [software parsers](#Software-Parsers) you can use with no or less configuration.
 
```php
$parser = new \Kristuff\Parser\LogParser();
$parser->addPattern('col1', 'YOUR PATTERN EXPRESSION'); 
$parser->addPattern('col2', 'YOUR PATTERN EXPRESSION'); 
$parser->setFormat('col1 col2');

$lines = file('/path/to/log/file', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

foreach ($lines as $line) {
    $entry = $parser->parse($line);
}
```

## LogEntry overview

## Software Parsers

### ApacheAccess    

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
| **Y**         | %>s           | status            | Status |
| **Y**         | %t            | time              | Time the request was received (standard english format)   |
| No            | %{format}t    | -                 | The time, in the form given by format.                    |
| **Y**         | %T            | requestTime       | The time taken to serve the request, in seconds. |
| **Y**         | %u            | user              | Remote user if the request was authenticated. May be bogus if return status (%s) is 401 (unauthorized). |
| **Y**         | %U            | URL               | The URL path requested, not including any query string. |
| **Y**         | %v            | serverName        | The canonical ServerName of the server serving the request. |
| **Y**         | %V            | canonicalServerName | The server name according to the UseCanonicalName setting. |
| No            | %X            | -                 | Connection status when response is completed: X = connection aborted before the response completed. + = connection may be kept alive after the response is sent. - = connection will be closed after the response is sent. |
| **Y**         | %I            | receivedBytes     | Bytes received, including request and headers, cannot be zero. You need to enable mod_logio to use this. |
| **Y**         | %O            | sentBytes         | Bytes sent, including headers, cannot be zero. You need to enable mod_logio to use this. |
| No            | %S            | -                 | Bytes transferred (received and sent), including request and headers, cannot be zero. This is the combination of %I and %O. You need to enable mod_logio to use this. |
</details>

### ApacheError

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
From software doc, the common format is follwong:
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

### Fail2ban

Create an `Fail2banLogParser` instance:

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

### Syslog

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


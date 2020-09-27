Doc ***draft***

ApacheAccess    

Here is the full list of [log format strings](https://httpd.apache.org/docs/2.4/en/mod/mod_log_config.html#formats) supported by Apache, and whether they are supported by the library:

<details>
  <summary>Click to see the list</summary>

| Supported?    | Placeholder   | Property name | Description |
|:----------:   |:-------------:|---------------|-------------|
| **Yes**       | %%            | percent       |The percent sign. |
| **Yes**       | %a            | remoteIp      |Client IP address of the request (remoteIp).|
| **Yes**       | %A            | localIp       |Local IP-address. |
| No            | %B            | -             |Size of response in bytes, excluding HTTP headers. |
| **Yes**       | %b            | responseBytes |Size of response in bytes, excluding HTTP headers. In CLF format, i.e. a '-' rather than a 0 when no bytes are sent. |
| **Yes**       | %D            | timeServeRequest | The time taken to serve the request, in microseconds. |
| No            | %f            | -             |Filename.          |
| **Yes**       | %h            | host          |Remote hostname.    |
| ???? | %H | - |The request protocol (this is Apache specific) |
| No            | %k            | -             | Number of keepalive requests handled on this connection.|
| **Yes**       | %l            | logname       | Remote logname (from identd, if supplied). This will return a dash unless mod_ident is present and IdentityCheck is set On. |
| No            | %L            | -             | The request log ID from the error log. |
| **Yes**       | %m            | requestMethod | The request method |
| No            | %{VARNAME}n   | -             | The contents of note VARNAME from another module. |
| No            | %{VARNAME}o   | -             | The contents of VARNAME: header line(s) in the reply.. |
| **Yes**       | %p            | port          | The canonical port of the server serving the request |
| No            | %{format}p    | -             | The canonical port of the server serving the request or the server's actual port or the client's actual port. Valid formats are canonical, local, or remote. |
| No            | %P            | -             | The process ID of the child that serviced the request. |
| No            | %{format}P    | -             | The process ID or thread id of the child that serviced the request. Valid formats are pid, tid, and hextid. hextid requires APR 1.2.0 or higher. |
| No??????????  | %q            | - | The query string (prepended with a ? if a query string exists, otherwise an empty string) |
| **Yes**       | %r            | request       | First line of request |
| No            | %R            | -             | The handler generating the response (if any). |
| No            | %s            | -             | Status. For requests that got internally redirected, this is the status of the *original* request --- %>s for the last. |
| **Y**         | %>s           | status        | Status |
| **Y**         | %t            | time          | Time the request was received (standard english format)   |
| No            | %{format}t    | -             | The time, in the form given by format.                    |
| **Y**         | %T            | requestTime   | The time taken to serve the request, in seconds. |



|


| **Y**         | %u            | user          | Remote user if the request was authenticated. May be bogus if return status (%s) is 401 (unauthorized). |
| **Y**         | %U            | URL           | The URL path requested, not including any query string. |
| **Y**         | %v            | serverName    | The canonical ServerName of the server serving the request. |
| **Y**         | %V            | canonicalServerName | The server name according to the UseCanonicalName setting. |

| Y | %I | receivedBytes | Bytes received, including request and headers, cannot be zero. You need to enable mod_logio to use this. |
| Y | %O | sentBytes | Bytes sent, including headers, cannot be zero. You need to enable mod_logio to use this. |



| N | %X | - | Connection status when response is completed: X = connection aborted before the response completed. + = connection may be kept alive after the response is sent. - = connection will be closed after the response is sent. |
| N | %{Foobar}C | - | The contents of cookie Foobar in the request sent to the server. Only version 0 cookies are fully supported. |
| N | %{Foobar}e | - | The contents of the environment variable FOOBAR |
| Y | %{Foobar}i | *Header | The contents of Foobar: header line(s) in the request sent to the server. Changes made by other modules (e.g. mod_headers) affect this. If you're interested in what the request header was prior to when most modules would have modified it, use mod_setenvif to copy the header into an internal environment variable and log that value with the %{VARNAME}e described above. |

| X | %S | scheme | This is `nginx` specific: https://nginx.org/en/docs/http/ngx_http_core_module.html#var_scheme |

</details>
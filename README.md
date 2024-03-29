# Parselog
> based on [kassner/log-parser](https://github.com/kassner/log-parser) primarily designed to parse web access logs, **Parselog** extends to other logs like apache error, syslog, fail2ban, MariaDB error ... *(in progress)*

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/kristuff/parselog/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/kristuff/parselog/?branch=master)
[![Build Status](https://app.travis-ci.com/kristuff/parselog.svg?branch=master)](https://app.travis-ci.com/kristuff/parselog)
[![Latest Stable Version](https://poser.pugx.org/kristuff/parselog/v/stable)](https://packagist.org/packages/kristuff/parselog)
[![License](https://poser.pugx.org/kristuff/parselog/license)](https://packagist.org/packages/kristuff/parselog)

# Features
- Generic customizable log parser
- Predefined software log parsers: ✓ `Apache Access`, ✓ `Apache Error`, ✓ `Fail2ban`, ✓ `Syslog`, ✓ `MariaDB Error`
- IPv4 & IPv6 recognition patterns

# Requirments
- PHP >= 7.1
- Composer (for install)

# Install

Deploy with composer:

```.language-json
...
"require": {
    "kristuff/abuseipdb": "^0.7.2-stable"
},
```

# Resources
- [Api documentation](https://kristuff.fr/projects/parselog/doc)

# License

The MIT License (MIT)

Copyright (c) 2017-2021 Kristuff

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
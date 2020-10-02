<?php

namespace Kristuff\Parselog\Tests\Provider;

class IpAddressV4 extends \PHPUnit\Framework\TestCase
{
    public function successProvider()
    {
        return [
            /* IPv4 */
            ['192.168.1.1'],
            ['192.168.001.01'],
            ['172.16.0.1'],
            ['192.168.0.255'],
            ['8.8.8.8'],
            // not sure about those 2. They are valid ip-format, but can't be assigned as server address
            ['0.0.0.0'],
            ['255.255.255.255'],

        ];
    }

    public function invalidProvider()
    {
        return [
            /* IPv4 */
            // over 255
            ['192.168.1.256'],
            ['256.256.256.256'],
            ['321.432.543.654'],
            // incomplete
            ['192.168.1.'],
            ['192.168.1'],
            ['192.168.'],
            ['192.168'],
            ['192.'],
            ['192'],
            [''],
            // malformed
            ['1921.68.1.1'],
            ['192.681.1.'],
            ['.1921.68.1.1'],
            ['....'],
            ['1.9.2.'],
            ['192.168.1.1/24'],
            // letters
            ['abc'],
            ['192.168.1.x'],
            ['insert-ip-address-here'],
            ['a.b.c.d'],
            [' '],
        ];
    }
}

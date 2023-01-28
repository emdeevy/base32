<?php

namespace emdeevy\test;

use PHPUnit\Framework\TestCase;
use InvalidArgumentException;

final class Base32 extends TestCase
{
    public function testEncodeReturnsCorrectEncoding(): void
    {
        $this->assertEquals(
            'MY',
            \emdeevy\base32\Base32::encode('f')
        );
        $this->assertEquals(
            'MZXQ',
            \emdeevy\base32\Base32::encode('fo')
        );
        $this->assertEquals(
            'MZXW6',
            \emdeevy\base32\Base32::encode('foo')
        );
        $this->assertEquals(
            'MZXW6YQ',
            \emdeevy\base32\Base32::encode('foob')
        );
        $this->assertEquals(
            'MZXW6YTB',
            \emdeevy\base32\Base32::encode('fooba')
        );
        $this->assertEquals(
            'MZXW6YTBOI',
            \emdeevy\base32\Base32::encode('foobar')
        );
    }

    public function testDecodeReturnsCorrectDecoding(): void
    {
        $this->assertEquals(
            'f',
            \emdeevy\base32\Base32::decode('MY')
        );
        $this->assertEquals(
            'fo',
            \emdeevy\base32\Base32::decode('MZXQ')
        );
        $this->assertEquals(
            'foo',
            \emdeevy\base32\Base32::decode('MZXW6')
        );
        $this->assertEquals(
            'foob',
            \emdeevy\base32\Base32::decode('MZXW6YQ')
        );
        $this->assertEquals(
            'fooba',
            \emdeevy\base32\Base32::decode('MZXW6YTB')
        );
        $this->assertEquals(
            'foobar',
            \emdeevy\base32\Base32::decode('MZXW6YTBOI')
        );
    }

    public function testEncodeThrowsExceptionOnEmptyInput(): void
    {
        $this->expectException(InvalidArgumentException::class);
        \emdeevy\base32\Base32::encode('');
    }

    public function testDecodeThrowsExceptionOnEmptyInput(): void
    {
        $this->expectException(InvalidArgumentException::class);
        \emdeevy\base32\Base32::decode('');
    }
}

?>
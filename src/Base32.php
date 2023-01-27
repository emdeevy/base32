<?php

declare(strict_types = 1);

namespace emdeevy\base32;

use InvalidArgumentException;

class Base32
{

    protected const ALPHABET = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567=';

    /**
     * Encodes a string in base32 format
     *
     * @param non-empty-string $string The input string to be encoded
     * @return string The base32 encoded string
     * @throws InvalidArgumentException if the input string is empty
     */
    public static function encode(string $string): string
    {
        // Check if input string is empty
        $string !== '' || throw new InvalidArgumentException('Attempt of empty string encoding');

        // Handle padding
        $padding_length = (8 - (strlen($string) * 8) % 5) % 8;
        $string .= str_repeat("\0", $padding_length);
        // Initialize variables for encoding
        $encoded = '';
        $val = 0;
        $bit_length = 0;

        // Iterate through each character of the input string
        for ($i = 0; $i < strlen($string); $i++) {
            // Shift the value to the left by 8 bits
            $val = ($val << 8) | ord($string[$i]);
            // Increase the bit length by 8
            $bit_length += 8;
            // Continue while there are at least 5 bits
                while ($bit_length >= 5) {
                    // Append the encoded character to the encoded string
                    $encoded .= self::ALPHABET[($val >> ($bit_length - 5)) & 31];
                    // Decrease the bit length by 5
                    $bit_length -= 5;
                }
        }

        // Remove padding from the encoded string
        if ($padding_length) {
            $encoded = substr($encoded, 0, -$padding_length);
        }

        // Return the encoded string
        return $encoded;
    }

    /**
     * Decodes a base32 encoded string.
     *
     * @param string $encoded The base32 encoded string
     * @throws InvalidArgumentException if the input string is empty
     * @return string The decoded string
     */
    public static function decode(string $encoded): string
    {
        // Validate input string is not empty
        $encoded !== '' || throw new InvalidArgumentException('Attempt of empty string decoding');

        // Initialize variables for decoding
        $decoded = '';
        $val = 0;
        $bit_length = 0;

        // Iterate through the encoded string
        for ($i = 0; $i < strlen($encoded); $i++) {
            // Shift the value left by 5 bits and add the new character's value
            $val = ($val << 5) | strpos(self::ALPHABET, $encoded[$i]);
            $bit_length += 5;
            // If the bit length is 8 or more, add the decoded character to the decoded string
            if ($bit_length >= 8) {
                $decoded .= chr(($val >> ($bit_length - 8)) & 255);
                $bit_length -= 8;
            }
        }

        // Return the decoded string
        return $decoded;
    }

}

?>
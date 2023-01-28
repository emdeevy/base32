<?

namespace emdeevy\base32;

use InvalidArgumentException;

class Base32
{

    protected const ALPHABET = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567=';
    protected const PADDING_INDEX = 32;

    /**
     * Encodes a given string into base32. Ridiculously commented to keep track with the algorithm.
     *
     * @param string $string Text string to be encoded
     * @param bool $padding Flag whether the output should have padding or not
     *
     * @return string Base32 encoded string
     */
    public static function encode(string $string, bool $padding = false): string
    {
        $string !== '' || throw new InvalidArgumentException(); // we don't encode empty strings

        $output = ''; // initialize encoded output string
        $index_iterator = 0; // iterator that goes through the array mapping the characters with their decimal equivalent
        $processing_chunk_size = 0; // the size of the chunk we are currently working with
        $processing_chunk_bits = 0; // the bits of the chunk we are currently working with

        $original_input_length = strlen($string); // length of the original string from the parameter
        $_input = $string . str_repeat(chr(0), 4); // the original string appended with 4 zeros, to make sure it is at least 40 bits and make it easy to determine when to stop the encoding process
        $decimals = unpack('C*', $_input); // unpack the string into an array, mapping its characters with their decimal equivalent

        while ($index_iterator < $original_input_length || $processing_chunk_size !== 0) { // while whether there are still characters to be processed or the current processing chunk is not empty, do:
            if ($processing_chunk_size < 5) { // if the current processing chunk falls under 5 bits, add the bits of the next character to the current processing chunk
                $processing_chunk_size += 8; // increase the current processing chunk size by 8
                $processing_chunk_bits <<= 8; // add 8 zeros to the right current processing chunk
                $processing_chunk_bits += $decimals[++$index_iterator]; // add the next character's bits to the zeros we added
            }

            // Now we need to get the first 5 bits from the processing chunk, turn it into a character from the base32 alphabet and append it to the decoded string
            $remaining_chunk_size = $processing_chunk_size - 5; // the remaining chunk size after we take the first 5 bits
            $remaining_chunk_bits = $processing_chunk_bits & ((1 << $remaining_chunk_size) - 1); // using a remaining-bits-size minus 1 amount of ones to the right, we can bitwise 'AND' to get the remaining chunk bits

            $outside_character = ($index_iterator - (int)($processing_chunk_size > 8)) > $original_input_length; // we either prepared the index for the next character or we didn't, if we did, check against index minus one, if we didn't, check against current index, whether the current character is out of the original input's length bounds, true if so, false otherwise


            if($outside_character && ($processing_chunk_bits == 0)) { // if current character is out of the original input's length bounds, and the processing chunk is filled with zeroes, we pad
                $output .= ($padding) ? static::ALPHABET[static::PADDING_INDEX] : ''; // if the flag not to pad is set, well, we don't pad
            }
            else {
                $output .= static::ALPHABET[$processing_chunk_bits >> $remaining_chunk_size]; // this isn't padding, it's just a character, so we get it from the base32 alphabet using the decimal equivalent of the first 5 bits as index
            }

            $processing_chunk_bits = $remaining_chunk_bits; // update current processing chunk bits to the remaining chunk bits after we used the first 5 bits
            $processing_chunk_size = $remaining_chunk_size; // update the current processing chunk size according to what it currently is
        }

        return $output; // we're done

    }

}

?>
<?php
/**
 * Created by PhpStorm.
 * User: Amine
 * Date: 07/07/2015
 * Time: 10:59
 */

class GSMEncoder {


    private $ErrorCode;

    private function setErrorCode( $errorcode ) {
        $this->ErrorCode = $errorcode;
    }

    public function getErrorCode() {
        return $this->ErrorCode;
    }

    public function getErrorMessage() {
        if($this->getErrorCode()==0) {
            return 'Erreur #0: Votre message est trop court.';
        }

        if($this->getErrorCode()==1) {
            return 'Erreur #1: Votre message est trop long.';
        }

        if($this->getErrorCode()==2) {
            return 'Erreur #2: Une erreur s\'est produite.';
        }
        return 'Pas d\'erreurs.';
    }

    public function generateEncodedMessage($message) {

        // Le message doit contenir 2 caractères au minimum
        if( strlen($message) < 2 ) {
            $this->setErrorCode(0);
            return false;
        }

        // 160 au max
        if( strlen($message) > 160 ) {
            $this->setErrorCode(1);
            return false;
        }

        // Convertir le message de string vers 7bits ensuite vers 8bits puis en hex
        $encodedMessage = $this->bit7tohex( $this->strto7bit( $message ) );

        return $encodedMessage;
    }

    private function asc2bin($input, $length=8) {

        $bin_out = '';
        // Loop through every character in the string
        for($charCount=0; $charCount < strlen($input); $charCount++) {
            $charAscii = ord($input{$charCount}); // ascii value of character
            $charBinary = decbin($charAscii); // decimal to binary
            $charBinary = str_pad($charBinary, $length, '0', STR_PAD_LEFT);
            $bin_out .= $charBinary;
        }

        // Return complete generated string
        return $bin_out;
    }

    // String to 7 bits array
    private function strto7bit($message) {
        $message = trim($message);
        $length = strlen( $message );
        $i = 1;
        $bitArray = array();

        // Loop through every character in the string
        while ($i <= $length) {
            // Convert this character to a 7 bits value and insert it into the array
            $bitArray[] = $this->asc2bin( substr( $message ,$i-1,1) ,7);
            $i++;
        }

        // Return array containing 7 bits values
        return $bitArray;
    }

    // Convert 8 bits binary string to hex values (like F2)
    private function bit8tohex($bin, $padding=false, $uppercase=true) {
        $hex = '';
        // Last item for counter (for-loop)
        $last = strlen($bin)-1;
        // Loop for every item
        for($i=0; $i<=$last; $i++) {
            $hex += $bin[$last-$i] * pow(2,$i);
        }

        // Convert from decimal to hexadecimal
        $hex = dechex($hex);
        // Add a 0 (zero) if there is only 1 value returned, like 'F'
        if($padding && strlen($hex) < 2 ) {
            $hex = '0'.$hex;
        }

        // If we want the output returned as UPPERCASE do this
        if($uppercase) {
            $hex = strtoupper($hex);
        }

        // Return the hexadecimal value
        return $hex;
    }

    // Convert 7 bits binary to hex, 7 bits > 8 bits > hex
    private function bit7tohex($bits) {

        $i = 0;
        $hexOutput = '';
        $running = true;

        // For every 7 bits character array item
        while($running) {

            if(count($bits)==$i+1) {
                $running = false;
            }

            $value = $bits[$i];

            if($value=='') {
                $i++;
                continue;
            }

            // Convert the 7 bits value to the 8 bits value
            // Merge a part of the next array element and a part of the current one

            // Default: new value is current value
            $new = $value;

            if(array_key_exists(($i+1), $bits)) {
                // There is a next array item so make it 8 bits
                $neededChar = 8 - strlen($value);
                // Get the char;s from the next array item
                $charFromNext = substr($bits[$i+1], -$neededChar);
                // Remove used bit's from next array item
                $bits[$i+1] = substr($bits[$i+1], 0, strlen($bits[$i+1])-$neededChar );
                // New value is characters from next value and current value
                $new = $charFromNext.$value;
            }

            if($new!='') {
                // Always make 8 bits
                $new = str_pad($new, 8, '0', STR_PAD_LEFT);
                // The 8 bits to hex conversion
                $hexOutput .= $this->bit8tohex($new, true);
            }

            $i++;
        }

        // Return the 7bits->8bits->hexadecimal generated value
        return $hexOutput;
    }
} 
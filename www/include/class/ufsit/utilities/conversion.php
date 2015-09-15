<?php
namespace ufsit\utilities;
    class Conversion{
    /*
     * Converts a number (in string form) from one base, to another.
     * $numberInput: a number in string form.
     * $fromBaseInput: a string representing the character range of the input number's base.
     * $toBaseInput: a string representing the character range of the output number's base.
     * source: http://php.net/manual/en/function.base-convert.php#106546
     */
    public static function base($numberInput, $fromBaseInput, $toBaseInput){
        if ($fromBaseInput==$toBaseInput) return $numberInput;
        $fromBase = str_split($fromBaseInput,1); //Get an array of all of the valid characters in the input base
        $toBase = str_split($toBaseInput,1); //Create an array of all of the valid characters in the output base
        $number = str_split($numberInput,1); //All of the "digits" of the input number, in an array.
        $fromLen=strlen($fromBaseInput); 
        $toLen=strlen($toBaseInput);
        $numberLen=strlen($numberInput);
        $retval='';
        
        if ($toBaseInput == '0123456789'){ //If the output base is decimal, get calculate the value of the input and return it.
            $retval=0;
            for ($i = 0;$i < $numberLen; $i++){
                $retval = bcadd($retval, bcmul(array_search($number[$i], $fromBase),bcpow($fromLen,$numberLen-($i + 1))));
                /* retval += baseValueOf(digitAt[$i]) * ($baseOfInput)^(magnitude of $i-th digit). */
            }
            return $retval;
        }
        //If the output base is not decimal...
        
        //Get the decimal value of input number
        if ($fromBaseInput != '0123456789'){
            $base10=Conversion::base($numberInput, $fromBaseInput, '0123456789');
        }
        else{
            $base10 = $numberInput;
        }
        
        //If this is a small number, and the value is less than the base of the output,
        //then we can display the number as a single digit of the output base.
        if ($base10<strlen($toBaseInput)){
            return $toBase[$base10];
        }
        
        //Otherwise,
        while($base10 != '0'){
            $retval = $toBase[bcmod($base10,$toLen)].$retval;
            //$retval = $outputBaseDigitCorrespondingToValue($inputNumber % $outputBase) + $retval;
            
            $base10 = bcdiv($base10,$toLen,0); //$inputNumber = $inputNumber / $outputBase (drop remainder). 
        }
        return $retval;
    }
}
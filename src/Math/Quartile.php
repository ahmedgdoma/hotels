<?php
/**
 * Created by PhpStorm.
 * User: ahmed
 * Date: 8/12/20
 * Time: 10:03 PM
 */

namespace App\Math;


class Quartile
{
    public static $scores;
    public function __construct($scores)
    {
        self::$scores = $scores;
    }
// set an array of values

// we want to get quartile 1, 2 and 3


// quartile 1 25% / 0.25
    public function Quartile_25() {
        return $this->Quartile(0.25);
    }

// quartile 2 50% / 0.5
    public function Quartile_50() {
        return $this->Quartile(0.5);
    }

// quartile 3 75% / 0.75
    public function Quartile_75() {
        return $this->Quartile(0.75);
    }

// do the math
// pass in the array of values and the quartile you are looking
    public function Quartile($Quartile) {

        // quartile position is number in array + 1 multiplied by the quartile i.e. 0.25, 0.5, 0.75
        $pos = (count(self::$scores) ) * $Quartile;

        // if the position is a whole number
        // return that number as the quarile placing
        if ( fmod($pos, 1) == 0)
        {
            return self::$scores[$pos];
        }
        else
        {
            // get the decimal i.e. 5.25 = .25
            $fraction = $pos - floor($pos);

            // get the values in the array before and after position
            $lower_num = self::$scores[floor($pos)-1];
            $upper_num = self::$scores[ceil($pos)-1];

            // get the difference between the two
            $difference = $upper_num - $lower_num;

            // the quartile value is then the difference multipled by the decimal
            // add to the lower number
            return $lower_num + ($difference * $fraction);
        }
    }
}
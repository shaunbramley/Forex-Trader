<?php

/**
 * Class get
 */
final class get {

    /**
     * @param float  $a
     * @param float  $b
     * @param \_pair $pair
     * @param bool   $abs
     *
     * @return float
     */
    public static function pip_difference(float $a, float $b, _pair $pair, $abs = true): float {
        $multiplier = 10000; // For currency pairs displayed to four decimal places, one pip is equal to 0.0001
        if ($pair->base_currency === 'JPY' || $pair->quote_currency === 'JPY') {
            // Yen-based currency pairs are an exception and are displayed to only two decimal places (0.01)
            $multiplier = 100;
        }

        if ($abs) {
            return (abs($a - $b) * $multiplier);
        } else {
            return (($a - $b) * $multiplier);
        }
    }


    /**
     * @param string $string
     *
     * @return int|float|string
     */
    public static function int_float_from_string($string) {
        if (is_numeric($string)) {
            $float_val = floatval($string);
            if ($float_val == (int) $float_val) {
                return (int) $float_val;
            } else {
                return (float) $float_val;
            }
        }

        return $string;
    }
}
<?php
class inside_bar extends _signal {

    /**
     * @param array  $data
     * @param string $direction
     *
     * @return bool
     */
    public static function isValidSignal(array $data, string $direction): bool {
        $last_two_periods = array_slice($data, -2);

        if (count($last_two_periods) == 2) {
            /**@var avg_price_data $first_period, $last_period*/
            $first_period = $last_two_periods[0];
            $last_period = $last_two_periods[1];

            $first_body = abs($first_period->open - $first_period->close);
            $last_candle = abs($last_period->high - $last_period->low);

            $first_body_top = ($first_period->open >= $first_period->close ? $first_period->open : $first_period->close);
            $first_body_bottom = ($first_period->open <= $first_period->close ? $first_period->open : $first_period->close);

            if ($last_candle <= ($first_body / 2)) {
                if ($first_body_top >= $last_period->high && $first_body_bottom <= $last_period->low) {
                    return true;
                }
            }
        }

        return false;
    }
}
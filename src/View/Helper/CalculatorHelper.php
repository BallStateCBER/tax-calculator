<?php
namespace App\View\Helper;

use Cake\View\Helper;

class CalculatorHelper extends Helper
{
    /**
     * Returns $number formatted as money
     *
     * Rounds to the nearest dollar and avoids "-$0" for values between 0 and -1
     *
     * @param int|float $number Number
     * @return string
     */
    public function moneyFormat($number)
    {
        if ($number < 0) {
            $formatted = number_format(-1 * $number);

            return $formatted == 0 ? '$0' : '-$' . $formatted;
        }

        return '$' . number_format($number);
    }

    /**
     * Returns either a single money-formatted string or a "$X to $Y" string if a range is specified
     *
     * @param int|float $min Minimum value
     * @param int|float $max Maximum value
     * @return string
     */
    public function formatMinMaxValue($min, $max)
    {
        $min = $this->moneyFormat($min);
        $max = $this->moneyFormat($max);

        return ($min === $max) ? $min : "$min to $max";
    }
}

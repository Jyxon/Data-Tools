<?php
/**
 * Copyright (C) Jyxon, Inc. All rights reserved.
 * See LICENSE for license details.
 */
namespace Jyxon\DataTools\Operation;

/**
 * This class creates operational functions for paths.
 */
class CalculationTool
{
    /**
     * Gets the percentage of a factor based on a base number.
     *
     * @param float $baseNumber
     * @param float $factorNumber
     * @param float $basePercentage
     *
     * @return float
     */
    public function getPercentageOfFactor(float $baseNumber, float $factorNumber, float $basePercentage = 1): float
    {
        return ($basePercentage / $baseNumber) * $factorNumber;
    }

    /**
     * Gets the factor of a percentage based on a base number.
     *
     * @param float $baseNumber
     * @param float $percentage
     * @param float $basePercentage
     *
     * @return float
     */
    public function getFactorOfPercentage(float $baseNumber, float $percentage, float $basePercentage = 1): float
    {
        return ($baseNumber / $basePercentage) * $percentage;
    }

    /**
     * Adds a percentage to a base number.
     *
     * @param float $baseNumber
     * @param float $percentage
     * @param float $basePercentage
     *
     * @return float
     */
    public function addPercentage(float $baseNumber, float $percentage, float $basePercentage = 1): float
    {
        return ($baseNumber / $basePercentage) * ($basePercentage + $percentage);
    }

    /**
     * Subtracts a percentage of a base number.
     *
     * @param float $baseNumber
     * @param float $percentage
     * @param float $basePercentage
     *
     * @return float
     */
    public function subtractPercentage(float $baseNumber, float $percentage, float $basePercentage = 1): float
    {
        return ($baseNumber / $basePercentage) * ($basePercentage - $percentage);
    }

    /**
     * Returns the average of an array.
     *
     * @param array $numbers
     *
     * @return float
     */
    public function average(array $numbers): float
    {
        return array_sum($numbers) / count($numbers);
    }

    /**
     * Returns the median of an array of numbers.
     *
     * @param array $numbers
     *
     * @return float
     */
    public function median(array $numbers): float
    {
        asort($numbers);
        if (count($numbers) % 2) {
            return $numbers[(count($numbers) / 2) + .5];
        }

        $lowHalf = $numbers[(count($numbers) / 2)];
        $highHalf = $numbers[(count($numbers) / 2) + 1];
        return ($lowHalf == $highHalf ? $lowHalf : $lowHalf + (($lowHalf - $highHalf) / 2));
    }

    /**
     * Returns an array of basic variables required for distance calculations.
     *
     * @param float $lat1
     * @param float $lon1
     * @param float $lat2
     * @param float $lon2
     *
     * @return float[]
     */
    private function getBaseDistanceVariables(float $lat1, float $lon1, float $lat2, float $lon2)
    {
        return [
            'radius' => 6371e3,
            'radLat1' => deg2rad($lat1),
            'radLat2' => deg2rad($lat2),
            'radLat' => deg2rad($lat2 - $lat1),
            'radLon' => deg2rad($lon2 - $lon1)
        ];
    }

    /**
     * Returns horizon distance between 2 points on earth.
     * Using the Haversine formula.
     *
     * @param float $lat1
     * @param float $lon1
     * @param float $lat2
     * @param float $lon2
     *
     * @return float Distance in KM
     */
    public function distance(float $lat1, float $lon1, float $lat2, float $lon2)
    {
        $base = $this->getBaseDistanceVariables($lat1, $lon1, $lat2, $lon2);
        $a = sin($base['radLat']/2) * sin($base['radLat']/2) +
             cos($base['radLat1']) * cos($base['radLat2']) *
             sin($base['radLon']/2) * sin($base['radLon']/2);
        $c = 2 * atan2(sqrt($a), sqrt(1-$a));
        return $base['radius'] * $c;
    }

    /**
     * Calculates the correlation between 2 sets of data.
     *
     * @param float[] $x
     * @param float[] $y
     *
     * @return float
     */
    public function correlation(array $x, array $y): float
    {
        if (count($x) == count($y)) {
            $x = array_values($x);
            $y = array_values($y);
            $avgX = $this->average($x);
            $avgY = $this->average($y);
            $a = [];
            $b = [];
            $axb = [];
            $powa = [];
            $powb = [];
            foreach ($x as $key => $aEntry) {
                $a[$key] = $aEntry - $avgX;
                $powa[$key] = $a[$key] ** 2;
                $b[$key] = $y[$key] - $avgY;
                $powb[$key] = $b[$key] ** 2;
                $axb[$key] = $a[$key] * $b[$key];
            }

            return array_sum($axb) / sqrt(array_sum($powa) * array_sum($powb));
        }

        return 0;
    }

    /**
     * Calculates the offset between 2 arrays.
     *
     * @param float[] $x
     * @param float[] $y
     *
     * @return float
     */
    public function offset(array $x, array $y): float
    {
        if (count($x) == count($y)) {
            $x = array_values($x);
            $y = array_values($y);
            $test = [min($x), min($y), max($x), max($y)];
            $min = min($test);
            $max = max($test);
            $offsetIndent = 1 / ($max - $min);
            $totalOffset = 0;
            foreach ($x as $key => $val) {
                $diff = abs($val - $y[$key]);
                $totalOffset += $diff * $offsetIndent;
            }

            return $totalOffset / count($x);
        }

        return 0;
    }

    /**
     * Checks wether the provided array are similar or not.
     *
     * @param float[] $x
     * @param float[] $y
     *
     * @return float
     */
    public function similarity(array $x, array $y): float
    {
        if (count($x) == count($y)) {
            $x = array_values($x);
            $y = array_values($y);
            $similarity = 0;
            foreach ($x as $key => $val) {
                if ($val == $y[$key]) {
                    $similarity++;
                }
            }

            return $this->getPercentageOfFactor(count($x), $similarity);
        }

        return 0;
    }

    /**
     * Runs the similarity function with sorted arrays.
     *
     * @param float[] $x
     * @param float[] $y
     *
     * @return float
     */
    public function sortedSimilarity(array $x, array $y): float
    {
        asort($x);
        asort($y);
        return $this->similarity($x, $y);
    }
}

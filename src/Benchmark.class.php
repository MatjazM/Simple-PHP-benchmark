<?php

/**
 * Simple PHP benchmark class
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @author Matjaž Mrgole
 */

class Benchmark {

	/**
	 * @var array
	 */
	private $startTime = array();
	/**
	 * @var array
	 */
	private $savedTimes = array();

	/**
	 * Starts benchmark
	 *
	 * @throws Exception
	 * @param string $group savedTimes[$group] [optional]
	 * @return void
	 */
	public function startTime($group = '') {
		if (!isset($this->startTime[$group])) {
			$this->startTime[$group] = microtime(true);
		} else {
			throw new Exception('You must first call endTime method.');
		}
	}

	/**
	 * Ends benchmark
	 *
	 * @throws Exception
	 * @param string $group savedTimes[$group] [optional]
	 * @return void
	 */
	public function endTime($group = '') {
		if (isset($this->startTime[$group])) {
			$this->savedTimes[$group][] = microtime(true) - $this->startTime[$group];
			$this->startTime[$group] = null;
		} else {
			throw new Exception('You must first call startTime method.');
		}
	}

	/**
	 * Shows particular benchmark (selected with group and index)
	 *
	 * @throws Exception
	 * @param string $group savedTimes[$group][] [optional]
	 * @param integer $index savedTimes[][$index]
	 * @param integer $size How many decimals for number format [optional]
	 * @return string Formatted benchmark time
	 */
	public function showTime($group = '', $index, $size = 0) {
		if (isset($this->savedTimes[$group][$index])) {
			return (string) number_format($this->savedTimes[$group][$index], $size);
		} else {
			throw new Exception('Benchmark with group ' . $group . ' and index ' . $index . ' doesn\'t exist.');
		}
	}

	/**
	 * Calculates benchmark group average, standard deviation and standard error
	 *
	 * @throws Exception
	 * @param string $group
	 * @return array [average of benchmark, standard error of benchmark]
	 */
	public function showAverage($group) {
		if (isset($this->savedTimes[$group])) {
			sort($this->savedTimes[$group]);
			$sizeOfArray = sizeof($this->savedTimes[$group]);
			$tenPercent = round($sizeOfArray * 0.1);
			$slicedArray = array_slice($this->savedTimes[$group], $tenPercent, $sizeOfArray - (2 * $tenPercent));

			$sum = array_sum($slicedArray);
			$sizeOfSlicedArray = sizeof($slicedArray);
			$standardError = $this->standardError($this->standardDeviation($slicedArray, true), $sizeOfSlicedArray);
			$standardError = $this->roundToSignificantDigit($standardError, 2, true);

			return array($this->roundMeanAccToUncertainty($sum / $sizeOfSlicedArray, $standardError), $standardError);
		} else {
			throw new Exception('Benchmark with group ' . $group . ' doesn\'t exist.');
		}
	}

	/**
	 * Rounds to $number to $n significant digits
	 *
	 * @param float $number
	 * @param integer $n Number of significant digits
	 * @param boolean $roundCeil If true, floor is ceil for rounding, otherwise, we use ceil round
	 * @return float Rounded number
	 */
	public function roundToSignificantDigit($number, $n, $roundCeil = false) {
		if ($number == 0) {
			return 0;
		}

		$d = ceil(log10($number < 0 ? -$number: $number));
		$power = $n - $d;

		$magnitude = pow(10, $power);
		$shifted = ($roundCeil == false) ? round($number * $magnitude) : ceil($number * $magnitude);

		return $shifted / $magnitude;
	}

	/**
	 * Calculates standard deviation based on $values
	 *
	 * @param array $values
	 * @return float Standard deviation
	 */
	private function standardDeviation($values) {
		$mean = array_sum($values) / sizeof($values);
	    $variance = 0.0;

	    foreach ($values as $i) {
	        $variance += pow($i - $mean, 2);
	    }

	    $variance /= ((sizeof($values) < 30) ? count($values) - 1 : count($values));
	    return (float) sqrt($variance);
	}

	/**
	 * Calculates standard error
	 *
	 * @param float $standardDeviation
	 * @param integer $sampleSize
	 * @return float Standard error
	 */
	private function standardError($standardDeviation, $sampleSize) {
		return $standardDeviation / sqrt($sampleSize);
	}

	/**
	 * Rounds $mean, to the same number of digits as $uncertainty
	 *
	 * @param float $mean
	 * @param float $uncertainty
	 * @return float Rounder mean
	 */
	private function roundMeanAccToUncertainty($mean, $uncertainty) {
		$noOfDigits = array(strlen(strchr($uncertainty, '.', true)), strlen(substr(strrchr($uncertainty, '.'), 1)));

		return round($mean, $noOfDigits[0] + $noOfDigits[1] - 1);
	}
}
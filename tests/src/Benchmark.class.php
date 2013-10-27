<?php

/**
 * Unit testing file for Benchmark.class.php
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @author Matjaž Mrgole
 */

require_once 'tests/autoload.php';

class BenchmarkTest extends PHPUnit_Framework_TestCase {

	/**
	 * @var Benchmark object
	 */
	private $benchmark;

	/**
	 * Creates Benchmark object
	 *
	 * @return void
	 */
	protected function setUp() {
		$this->benchmark = new Benchmark();
	}

	/**
	 * Destroys Benchmark object
	 *
	 * @returns void
	 */
	protected function tearDown() {
		unset($this->benchmark);
	}

	/**
	 * Calling endTime() before startTime()
	 *
	 * @expectedException RunException
	 * @return void
	 */
	public function testEndTimeException() {
		$this->benchmark->endTime();
	}

	/**
	 * Calling startTime() after startTime()
	 *
	 * @expectedException RunException
	 * @return void
	 */
	public function testStartTimeException() {
		$this->benchmark->startTime();
		$this->benchmark->startTime();
	}

	 /**
	 * Calling startTime() after startTime()
	 *
	 * @expectedException RunException
	 * @return void
	 */
	public function testShowTimeException() {
		$this->benchmark->showTime('', 0);
	}

	 /**
	 * Calling startTime() after startTime()
	 *
	 * @expectedException RunException
	 * @return void
	 */
	public function testShowAverageException() {
		$this->benchmark->showAverage('');
	}

	/**
	 * Test showTime method
	 *
	 * @return void
	 */
	public function testShowTime() {
		$this->benchmark->startTime('testGroup');
		$this->benchmark->endTime('testGroup');

		$this->benchmark->startTime('testGroup');
		$this->benchmark->endTime('testGroup');

		$compare1 = $this->benchmark->showTime('testGroup', 0);
		$compare2 = $this->benchmark->showTime('testGroup', 0, 2);

		$this->assertStringMatchesFormat('%d', $compare1);
		$this->assertStringMatchesFormat('%f', $compare2);
	}

	/**
	 * Test showAverage method
	 *
	 * @return void
	 */
	public function testShowAverage() {
		$this->benchmark->startTime('testGroup');
		$this->benchmark->endTime('testGroup');

		$this->benchmark->startTime('testGroup');
		$this->benchmark->endTime('testGroup');

		$compare = $this->benchmark->showAverage('testGroup');

		$this->assertStringMatchesFormat('%f', '' . $compare[0]);
		$this->assertStringMatchesFormat('%f', '' . $compare[1]);
	}

	/**
	 * Test roundToSignificantDigit method
	 *
	 * @return void
	 */
	public function testRoundToSignificantDigit() {
		$actual1 = $this->benchmark->roundToSignificantDigit(0.012, 1);
		$expected1 = 0.01;

		$actual2 = $this->benchmark->roundToSignificantDigit(0.012, 1, true);
		$expected2 = 0.02;

		$actual3 = $this->benchmark->roundToSignificantDigit(10495.12, 3, true);
		$expected3 = 10500;

		$actual4 = $this->benchmark->roundToSignificantDigit(0, 3, true);
		$expected4 = 0;

		$this->assertEquals($expected1, $actual1);
		$this->assertEquals($expected2, $actual2);
		$this->assertEquals($expected3, $actual3);
		$this->assertEquals($expected4, $actual4);
	}
}
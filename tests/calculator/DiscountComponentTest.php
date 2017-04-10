<?php

namespace EventRegistration\Calculator\Tests;

use \EventRegistration\Calculator;
use \EventRegistration\Calculator\CostComponent;
use \EventRegistration\Calculator\DiscountComponent;

class DiscountedEventRegistrationCostCalculatorTest extends \SapphireTest
{

	protected static $fixture_file = array(
		'../fixtures/Group.yml',
		'../fixtures/Family.yml'
	);

	// helper for asserting calculator results
	protected function assertCalculation($reg, $expected, $message = "") {
		$calculator = new Calculator($reg, array("Cost", "Discount"));
		$this->assertEquals($expected, $calculator->calculate(), $message);
	}

	public function testGroupDiscount() {
		$reg = $this->objFromFixture('EventRegistration', 'group');
		$this->assertCalculation($reg, 90, "10 qualifying attendeeds = 10% discount");
		
		// TODO:
		// 1/10 on free ticket = no group discount
		// 9 qualifying attendees = no discount
	}

	public function testFamilyDiscount() {
		$reg = $this->objFromFixture('EventRegistration', 'family_sm');
		$this->assertCalculation($reg, 120, "1 parent 1 child = 20% discount");

		// different combinations of parents
	}

}
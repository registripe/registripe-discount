<?php

namespace EventRegistration\Calculator;

class SelectionDiscountCalculator extends SelectionCalculator {

	protected $fraction = 0; // decimal fraction

	public function setDecimalFraction ($fraction) {
		$this->fraction = $fraction;
	}

	public function calculate($value) {
			if ($this->fraction > 0) {
				$value = $value * (1 - $this->fraction);
			}
			return $value;
	}

}

<?php

namespace EventRegistration\Calculator;

class GroupDiscount extends Discount {

	public $name = "Group";
	protected $minSize = 2;

	public function setMinSize($size) {
		$this->minSize = $size;
		return $this;
	}

	// the number of attendees is greater than the min size
	public function registrationQualifies(\EventRegistration $reg) {
		return ($this->getDiscountableAttendees($reg)->count() >= $this->minSize);
	}

}

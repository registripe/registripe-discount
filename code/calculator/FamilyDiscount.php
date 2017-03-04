<?php

namespace EventRegistration\Calculator;

class FamilyDiscount extends Discount {

	public $name = "Family";
	
	// at least one parent and one child ticket
	public function registrationQualifies(\EventRegistration $reg) {
		$hasparent = false;
		$haschild = false;
		$attendees = parent::getDiscountableAttendees($reg);
		foreach ($attendees as $attendee) {
			$hasparent = $hasparent || !$attendee->Ticket()->Child;
			$haschild = $haschild || $attendee->Ticket()->Child;
			if ($hasparent && $haschild) {
				return true;
			}
		}
		return false;
	}

}

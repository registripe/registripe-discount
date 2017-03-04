<?php

namespace EventRegistration\Calculator;

class Discount {

	// TODO: move away to config of some sort
	public static function available_discounts() {
		return [
			(new GroupDiscount(0.1))->setMinSize(10),
			new FamilyDiscount(0.2)
		];
	}
	
	public $amount;
	public $name = "Discount";

	public function __construct($amount, $name = null) {
		$this->amount = $amount;
		$this->name = $name ? $name : $this->name;
	}

	/**
	 * Check if a registration qualifies for this discount
	 */
	public function registrationQualifies(\EventRegistration $reg) {
		return true;
	}

	/**
	 * Check if an attendee qualifies for this discount
	 */
	public function attendeeQualifies(\EventAttendee $attendee) {
		return true;
	}

	public function applyTo($amount) {
		return $amount * $this->amount;
	}

	public function getDiscountableAttendees(\EventRegistration $reg) {
		return $reg->Attendees()
			->innerJoin("EventTicket", "\"EventAttendee\".\"TicketID\" = \"EventTicket\".\"ID\"")
			->where("PriceAmount > 0");
	}

}

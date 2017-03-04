<?php

namespace EventRegistration\Calculator;

class DiscountComponent extends AbstractComponent {

	protected $qualifyingDiscounts = array();

	public function __construct(\EventRegistration $registration) {
		parent::__construct($registration);
		$this->qualifyingDiscounts = $this->getQualifyingDiscounts($registration);
	}

	public function calculateAttendee(\EventAttendee $attendee, $cost) {
		// find best discount and apply it to cost
		if($discount = $this->bestDiscountFor($attendee)){
			$amount = $discount->applyTo($cost);
			$attendee->DiscountAmount = $amount;
			$attendee->DiscountName = $discount->name;
			$cost -= $amount;
		}
		return $cost;
	}

	/*
	 * Work out which discounts can be applied to the registration
	 */
	protected function getQualifyingDiscounts($registration) {
		$discounts = Discount::available_discounts();
		foreach ($discounts as $key => $discount) {
			if (!$discount->registrationQualifies($registration)) {
				unset($discounts[$key]);
			}
		}
		// sort by biggest discount
		usort($this->qualifyingDiscounts, function ($a, $b) {
			return $a->amount < $b->amount;
		});
		return $discounts;
	}

	/*
	 * Work out best discount for a given attendee
	 */
	protected function bestDiscountFor(\EventAttendee $attendee) {
		if(count($this->qualifyingDiscounts)) { // this is pre-sorted by discount
			foreach($this->qualifyingDiscounts as $discount) {
				if($discount->attendeeQualifies($attendee)){
					return $discount;
				}
			}
		}
		return 0;
	}

}

<?php

class TicketsDiscountConstraint extends DiscountConstraint{

	public function qualify(EventDiscount $discount) {
		$tickets = $discount->Tickets();
		// check if discount has tickets constraint
		if (!$tickets || !$tickets->exists()) {
			return true;
		}
		$constraintTicketids = $tickets->map('ID','ID')->toArray();
		$discountableTicketids = $this->getPayingTickets()->map('TicketID', 'TicketID')->toArray();
		// intersection of ids will help us know if any tickets are present to qualify 
		return count(array_intersect($constraintTicketids, $discountableTicketids)) > 0;
	}

}

class TicketsDiscountConstraint_DiscountExtension extends DiscountConstraintExtension {

	private static $many_many = array(
		"Tickets" => "EventTicket"
	);

	public function updateCMSConstraintFields(FieldList $fields) {
		$ticketsField = ListboxField::create('Tickets', singleton('EventTicket')->i18n_plural_name())
				->setMultiple(true)
				->setSource($this->owner->Event()->Tickets()->map()->toArray());
		$fields->push($ticketsField);
	}

}

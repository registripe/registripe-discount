<?php

class QuantityDiscountConstraint extends DiscountConstraint{
	
	public function filterList(DataList $list) {
		$quantity = $this->getPayingTickets()->count();
		return $list->filterAny(array(
			"MinTicketQuantity" => 0,
			"MinTicketQuantity:LessThanOrEqual" => $quantity
		))->filterAny(array(
			"MaxTicketQuantity" => 0,
			"MaxTicketQuantity:GreaterThanOrEqual" => $quantity
		));
	}
	
}

class QuantityDiscountConstraint_DiscountExtension extends DiscountConstraintExtension {

	private static $db = array(
		"MinTicketQuantity" => "Int",
		"MaxTicketQuantity" => "Int"
	);

	public function updateCMSConstraintFields(FieldList $fields) {
		$fields->push(
			FieldGroup::create("Number of tickets",
				NumericField::create("MinTicketQuantity", "Minimum"),
				NumericField::create("MaxTicketQuantity", "Maximum")
			)->setDescription("'0' means no limit")
		);
	}

}

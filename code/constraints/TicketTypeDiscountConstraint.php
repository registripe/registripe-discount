<?php

class TicketTypeDiscountConstraint extends DiscountConstraint{

	public function filterList(DataList $discounts) {
		return $discounts
			->where(
				"TicketType IS NULL OR " .
				"TicketType = '" .
					$this->discountable->Ticket()->ClassName.
				"'"
			);
	}

}

class TicketTypeDiscountConstraint_DiscountExtension extends DiscountConstraintExtension {

	private static $db = array(
		'TicketType' => 'Varchar'
	);
	
	public function updateCMSConstraintFields(FieldList $fields){
		$fields->push(
			DropdownField::create(
				"TicketType",
				"Ticket Type",
				$this->availableTicketTypes()
			)->setHasEmptyDefault(true)
		);
	}

	protected function availableTicketTypes() {
		$types = ClassInfo::subclassesFor("EventTicket");
		foreach($types as $type => $name){
			$types[$type] = singleton($type)->i18n_singular_name();
		}
		return $types;
	}

}
<?php

class EventDiscount extends DataObject {

	private static $db = array(
		"Title" => "Varchar(255)",
		"Percent" => "Percentage"
	);

	private static $has_one = array(
		"Event" => "RegistrableEvent"
	);

	private static $summary_fields = array(
		"Title" => "Title",
		"Percent.Nice" => "Percent"
	);

	private static $singular_name = "Discount";
	private static $plural_name = "Discounts";

	public function getCMSFields() {
		$fields = FieldList::create(array(
			TextField::create("Title"),
			PercentageField::create("Percent")
		));
		$constraintFields = FieldList::create();
		$this->extend("updateCMSConstraintFields", $constraintFields);

		if ($constraintFields->count()) {
			$constraintFields->unshift(HeaderField::create("ConstraintHeading", "Constraints"));
			$fields->merge($constraintFields);
		}

		$this->extend("updateCMSfields", $fields);
		return  $fields;
	}

	public function qualify($discountable) {
		$constraints = self::config()->constraints;
		foreach($constraints as $constraint){
			$constraint = Injector::inst()->create($constraint."DiscountConstraint", $discountable);
			if(!$constraint->qualify($this)){
				return false;
			}
		}
		return true;
	}

}
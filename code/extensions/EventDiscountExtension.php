<?php

class EventDiscountExtension extends DataExtension {

	public static $has_many = array(
		"Discounts" => "EventDiscount"
	);

	public function updateCMSFields(FieldList $fields) {
		$fields->addFieldToTab("Root.Discounts",
			GridField::create("Discounts", "Discounts", $this->owner->Discounts(),
				GridFieldConfig_RecordEditor::create()
			)
		);
	}

}
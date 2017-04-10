<?php

/**
 * Perform filtering of Discount list
 */
abstract class DiscountConstraint{

	// the dataobject being discounted
	protected $discountable;

	public function __construct($discountable) {
		$this->discountable = $discountable;
	}

	/**
	 * Get configured constraint names
	 *
	 * @return array
	 */
	public static function constraint_names() {
		return array_unique(EventDiscount::config()->constraints);
	}

	/**
	 * Set up Discount constraint extensions
	 *
	 * @return void
	 */
	public static function set_up_constraints() {
		// apply extensions in reverse order for CMS fields to be in order
		foreach(array_reverse(self::constraint_names()) as $constraint){
			Object::add_extension("EventDiscount", $constraint."DiscountConstraint_DiscountExtension");
		}
	}

	/**
	 * Applies listFilter of each constraint to a given discounts DataList
	 *
	 * @param DataList $discounts
	 * @param DataObject $discountable
	 * @return DataList
	 */
	public static function apply_list_filters(DataList $discounts, $discountable) {
		foreach(self::constraint_names() as $constraintClass){
				$constraint = Injector::inst()->create($constraintClass."DiscountConstraint", $discountable);
				$discounts = $constraint->filterList($discounts);
		}
		return $discounts;
	}

	/**
	 * Use ORM filtering on a given DataList to constrain qualifying
	 * discounts in db queries.
	 * 
	 * Filtering MUST let through discounts that have null constraints.
	 * E.g. filter MinQuantity must be either == 0 OR <= $quantity.
	 * Otherwise you'll filter out discounts unnecessarily.
	 *
	 * @param DataList $discounts
	 * @return DataList
	 */
	public function filterList(DataList $discounts){
		return $discounts;
	}

	/**
	 * Use logic to check if discount qualifies.
	 * 
	 * This defaults to a crude check using filterList,
	 * but can be overidden for php-based logic. May reduce SQL queries needed.
	 * 
	 * @param  Discount $discount
	 * @return boolean
	 */
	public function qualify(EventDiscount $discount) {
		return $this->filterList(
			EventDiscount::get()
		)->byID($discount->ID);
	}

	/**
	 * Helper for getting tickets that are not free
	 *
	 * @return DataList
	 */
	protected function getPayingTickets() {
		return $this->discountable->TicketSelections()
			->innerJoin("EventTicket", "\"TicketSelection\".\"TicketID\" = \"EventTicket\".\"ID\"")
			->filter("Price:not", 0);
	}

}

/**
 * Subclass to add data fields to discount model.
 * Handle data definition and CMS setup in these files.
 */
abstract class DiscountConstraintExtension extends DataExtension {

	/**
	 * Update constraints part of CMS fields
	 *
	 * @param FieldList $fields
	 * @return void
	 */
	abstract function updateCMSConstraintFields(FieldList $fields);

}
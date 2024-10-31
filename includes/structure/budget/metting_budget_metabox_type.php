<div class="pto-pto-budget-items-type-details">
	<div class="pto-pto-budget-items-type-details-radio">
		<div class="pto-pto-budget-items-type-details_radios">
			<input type="radio" name="budget_items_type" class="items-type-input" value="expense" <?php if ($budget_type == "" ||  $budget_type == "expense") {
				echo "checked";
			} ?>>
			<label class="pto-budget-items-type-radio-labele">Expense</label>
		</div>
		<div class="pto-pto-budget-items-type-details_radios">
			<input type="radio" name="budget_items_type" class="items-type-input" value="revenue" <?php if ($budget_type == "revenue") {
				echo "checked";
			} ?>>
			<label class="pto-budget-items-type-radio-labele">Revenue</label>
		</div>
	</div>
</div>
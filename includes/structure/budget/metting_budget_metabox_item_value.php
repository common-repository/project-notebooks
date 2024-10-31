<div class="pto-budget-items-type-item-value">
	<div class="pto-budget-items-type-item-value-input">
		<?php
		$pr_id = "";
		if (isset($_GET['proj_id'])) {
			$pr_id  = intval($_GET['proj_id']);
			$budgets = get_post_meta($pr_id, "pto_total_budgets", true);
		} else {
			$pr_ids = get_post_meta($post->ID, "pto_associated_project_id", true);
			$pr_id = $pr_ids;
			$budgets = get_post_meta($pr_id, "pto_total_budgets", true);
		}

		$args = array(
			'post_type' => 'pto-budget-items',
			'posts_per_page' => 5
			// Several more arguments could go here. Last one without a comma.
		);
		$obituary_query = new WP_Query($args);
		$expense = 0;
		$revenue = 0;
		while ($obituary_query->have_posts()) : $obituary_query->the_post();
			$project_id = get_post_meta($post->ID, "pto_associated_project_id", true);
			if ($project_id == $pr_id) {
				$budgetss = get_post_meta($post->ID, "budget_items_type_value", true);
				$budgetss_type = get_post_meta($post->ID, "budget_items_type", true);
				if ($expense) {
					if ($budgetss_type == "expense") {
						$expense = $expense + $budgetss;
					} else if ($budgetss_type == "revenue") {
						$revenue = $revenue + $budgetss;
					}
				}
			}
		endwhile;
		// Reset Post Data
		wp_reset_postdata();
		?>
		<input type="hidden" name="total_budgets_assign_expence" id="total_budgets_assign_expence" value="<?php print_r($expense); ?>">
		<input type="hidden" name="total_budgets_assign_revenue" id="total_budgets_assign_revenue" value="<?php print_r($revenue); ?>">
		<input type="hidden" name="total_budgets" id="total_budgets" value="<?php print_r($budgets); ?>">
		<label class="pto-budget-items-type-item-value-label">$</label>
		<input type="hidden" name="budget_item_value" step="0.00" value="<?php if ($budget_type_value == "") {
			echo esc_html("0.00");
			} else {
				print_r($budget_type_value);
			} ?>">
			<input type="number" name="budget_item_value" id="budget_item_value" value="<?php if ($budget_type_value == "") {
				echo esc_html("0.00");
				} else {
					print_r($budget_type_value);
				} ?>">
				<input type="hidden" name="budget_item_value_old" id="budget_item_value_old" value="<?php if ($budget_type_value == "") {
					echo esc_html("0.00");
					} else {
						print_r($budget_type_value);
					} ?>">

				</div>
			</div>
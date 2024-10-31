<?php
$url_add = site_url() . "/wp-admin/post-new.php?post_type=" . $post_type . "&proj_id=$project_id";
$moth_data = array();
foreach ($get_progect_listing_cpt as $cpt_ids) {
	$post_type_get = get_post_type($cpt_ids);
	if ($post_type_get == $post_type) {
		$date = get_the_date('M Y', $cpt_ids);
		$moth_data[$date] = $date;
	}
}
?>
<div class="heder-section justify-content-between">
	<div class="pto-project-tab-addnew-btn-block-header">
		<div class="action-heder-addnew-btn-block">
			<a class="add_new outline_btn button-primary <?php
			if ($post_type == "pto-budget-items") {
				echo "button_check";
			}
		?>" href="javascript:void(window.open('<?php echo esc_html($url_add); ?>'))"><i class="fa fa-plus"></i> Add New </a>
		
		<div class="pto-project-cust-tab">

			<?php if ($publishcnt != 0) { ?>
				<span class="header-publish-cpt-data pto-cpt-status <?php if ($status == "publish") {
					echo "active";
				} ?>" post-type="<?php echo esc_html($post_type); ?>" data-type="publish">Published (<?php echo esc_html($publishcnt); ?>)</span>

			<?php }
			if ($trushcnt != 0) { ?>
				<span class="header-trush-cpt-data pto-cpt-status <?php if ($status == "trash") {
					echo "active";
				} ?>" post-type='<?php echo esc_html($post_type); ?>' data-type="trash">Trash (<?php echo esc_html($trushcnt); ?>)</span>

			<?php }
			if ($archive != 0) { ?>
				<span class="header-trush-cpt-data pto-cpt-status <?php if ($status == "archive") {
					echo "active";
				} ?>" post-type='<?php echo esc_html($post_type); ?>' data-type="archive">Archive (<?php echo esc_html($archive); ?>)</span>

			<?php } ?>
			<?php if ($draft != 0) { ?>
				<span class="header-trush-cpt-data pto-cpt-status <?php if ($status == "draft") {
					echo "active";
				} ?>" post-type='<?php echo esc_html($post_type); ?>' data-type="draft">Draft (<?php echo esc_html($draft); ?>)</span>

			<?php } ?>
		</div>
	
	</div>
</div>
<div class="pto-project-filter-block-header">
	<div class="pto-project-cpt-filter">
		<div class="pto-project-cpt-filter_action">
			<select id="<?php echo esc_html($post_type . "-select"); ?>">
				<option value="">Bulk actions</option>
				<option value="published">Published</option>
				<option value="draft">Draft</option>
				<option value="archive">Archive</option>
				<option value="trush">Move to Trash</option>
			</select>
			<input type="button" class="button action" id="pto_button_fileter_apply" type-cpt="<?php echo esc_html($post_type); ?>" select="<?php echo esc_html($post_type . "-select"); ?>" value="Apply">
		</div>
		<div class="pto-project-cpt-filter_action_month">
			<select id="<?php echo esc_html($post_type . "-select-month"); ?>">
				<option value="">All Dates</option>
				<?php
				foreach ($moth_data as $publish_date) {
					if ($publish_dates   == $publish_date) {
						?>
						<option value='<?php echo esc_html_e($publish_date); ?>' selected><?php echo esc_html_e($publish_date); ?></option>;
						<?php
					} else {
						?>
						<option value='<?php echo esc_html_e($publish_date); ?>'><?php echo esc_html_e($publish_date); ?></option>;
						<?php
					}
				}
				?>
			</select>
			<input type="button" class="button action" id="pto_button_fileter_month_apply" type-cpt="<?php echo esc_html($post_type); ?>" select="<?php echo esc_html($post_type . "-select-month"); ?>" value="Apply">
		</div>
	</div>
	<div class="d-flex align-items-center totla-budget-main-block">
		<?php
		if ($post_type == "pto-budget-items") {
			$budgets = get_post_meta($project_id, "pto_total_budgets", true);
			?>
			<div class="d-flex align-items-center">
				<div class="totla-budget">
					<span>Starting Budget: $</span><input type="number" name="total-budgets" step="0.01" id="total-budgets" value="<?php echo esc_html($budgets); ?>">
				</div>
			</div>
			<?php
		}
		?>
	</div>
</div>
</div>
<?php
$tbl_class = "";
if ($post_type == "pto-tasks") {
	$tbl_class = "task-status-details";
}
$pt = "'" . $post_type . "'";
$cpt_html_header = "";
$cpt_html_header .= '<table class="wp-list-table widefat fixed ' . $tbl_class . ' striped table-view-list posts" id="' . $post_type . '_table">
<thead>
<tr>
<td width="3%"><input type="checkbox" name="checkall_' . $post_type . '" id="checkall_' . $post_type . '" class="checkbox_all_check"></td>';
if ($cpt_header_meta != "") {
	if (array_key_exists("main_fields", $cpt_header_meta)) {
		$main_fields = $cpt_header_meta['main_fields'];
	}
	if (array_key_exists("meta_key", $cpt_header_meta)) {
		$meta_key = $cpt_header_meta['meta_key'];
	}
	$i = 0;
	if (!empty($main_fields)) {
		foreach ($main_fields as  $value) {
			$cpt_html_header .= '<td  class="manage-column column-title column-primary sorted desc" onclick="sortTable(' . $i . ',' . $pt . ')"><div class="pto_cpt_get_details_header_title_details" >
			<span>' .  $value . '</span>
			</div></td>';
			$i++;
		}
	}
	if (!empty($meta_key)) {
		foreach ($meta_key as  $value) {
			$cpt_html_header .= '<td  class="manage-column column-title column-primary sorted desc" onclick="sortTable(' . $i . ',' . $pt . ')"><div class="pto_cpt_get_details_header_title_details_meta">
			<span>' .  $value . '</span>
			</div></td>';
			$i++;
		}
	}
}
$cpt_html_header .= '</tr></thead>';

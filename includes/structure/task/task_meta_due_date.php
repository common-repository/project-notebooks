<div class="pto-task-cpt-due-date">
	<div class="pto-task-cpt-due-date-title">
		<span class="pto-task-cpt-due-date-title_span">Select a date and time</span>
	</div>
	<div class="pto-task-cpt-due-date-datepicker">
		<input type="text" id="datepicker" placeholder="mm/dd/yyyy" name="duedate" id="duedate_filed" value="<?php if (!empty($post_meta2)) {
			echo esc_html($post_meta2['due_date']);
		} ?>" min="<?php echo esc_html_e(date("Y-m-d")); ?>" autocomplete="off">
	</div>
	<div class="pto-task-cpt-due-date-timepicker">

		<input type="time" name="time" id="duetime_filed" value="<?php if (!empty($post_meta2)) {
			echo esc_html($post_meta2['date_time']);
		} ?>">
	</div>
	<div class="pto-task-cpt-due-date-reminder">
		<div class="pto-task-cpt-due-date-reminder">
			<input type="checkbox" name="select_reminder" id="select-reminder" <?php if (!empty($post_meta)) {
				echo "checked";
			} ?>>
			<label class="pto-task-cpt-due-date-reminder_label">Send reminders to users?</label>
		</div>
		<div class="pto-task-cpt-due-date-reminder-checkbox" id="list-of-reminder" style="<?php if (empty($post_meta)) {
			echo "display: none";
		} ?>">
		<div class="pto-task-cpt-due-date-reminder">
			<input type="checkbox" name="select_reminder_option[]" class="reminder-checked" value="2week" <?php if (!empty($post_meta)) {
				if (in_array("2week", $post_meta)) {
					echo "checked";
				}
			} ?>>
			<label class="pto-task-cpt-due-date-reminder_label">2 weeks prior</label>
		</div>
		<div class="pto-task-cpt-due-date-reminder">
			<input type="checkbox" name="select_reminder_option[]" class="reminder-checked" value="1week" <?php if (!empty($post_meta)) {
				if (in_array("1week", $post_meta)) {
					echo "checked";
				}
			} ?>>
			<label class="pto-task-cpt-due-date-reminder_label">1 week prior</label>
		</div>
		<div class="pto-task-cpt-due-date-reminder">
			<input type="checkbox" name="select_reminder_option[]" class="reminder-checked" value="1day" <?php if (!empty($post_meta)) {
				if (in_array("1day", $post_meta)) {
					echo "checked";
				}
			} ?>>
			<label class="pto-task-cpt-due-date-reminder_label">1 day prior</label>
		</div>
		<div class="pto-task-cpt-due-date-reminder">
			<input type="checkbox" name="select_reminder_option[]" class="reminder-checked" id="due-custom" value="custom" <?php if (!empty($post_meta)) {
				if (isset($post_meta['range'])) {
					echo "checked";
				}
			} ?>>
			<label class="pto-task-cpt-due-date-reminder_label">Custom</label>
			<div class="due-date-custom" style="<?php if (empty($post_meta)) {
				echo "display: none";
			} elseif (isset($post_meta['range'])) {
				echo "display: block";
			} else {
				echo "display: none";
			} ?>">
			<input type="number" name="custom_range" step="0.01" value="<?php if (!empty($post_meta)) {
				if (isset($post_meta['range'])) {
					if (isset($post_meta['range'][0])) {
						echo esc_html($post_meta['range'][0]);
					}
				}
			} ?>">
			<?php
			$per = "";
			if (!empty($post_meta)) {
				if (isset($post_meta['range'])) {
					if ($post_meta['range'][1]) {
						$per = $post_meta['range'][1];
					}
				}
			}
			?>
			<select name="range_due">
				<option value="hours" <?php if ($per == "hours") {
					echo "selected";
				} ?>>Hours</option>
				<option value="minute" <?php if ($per == "minute") {
					echo "selected";
				} ?>>Minute</option>
				<option value="days" <?php if ($per == "days") {
					echo "selected";
				} ?>>Days</option>
				<option value="week" <?php if ($per == "week") {
					echo "selected";
				} ?>>Weekly</option>
				<option value="month" <?php if ($per == "month") {
					echo "selected";
				} ?>>Month</option>
			</select>
		</div>
	</div>
</div>
</div>
</div>
<?php

if ($post_meta == "") {
	if (isset($_GET['status'])) {
		$post_meta = sanitize_text_field($_GET['status']);
	}
}
?>
<?php
if (isset($_GET['post'])) {
	$id = sanitize_text_field($_GET['post']);
	$p_id = get_post_meta($id, 'pto_associated_project_id', true);
}
?>
<input type="hidden" name="proj_id" value="<?php if (isset($_GET['proj_id'])) {
	echo intval($_GET['proj_id']);
	} elseif ($p_id) {
		echo esc_html($p_id);
	} ?>">
	<div class="pto-task-cpt-status">
		<div class="pto-task-cpt-status">


			<?php
			$p_id = get_the_ID();
			if ($post->post_type == "pto-tasks") {
				$get_duedate = get_post_meta($p_id, 'pto_task_due_date', true);
				$current_date = date("Y-m-d H:i");
				if (!empty($get_duedate)) {
					$duedate_time = $get_duedate['due_date'] . ' ' . $get_duedate['date_time'];
					$due_date = $get_duedate['due_date'];
					if ((strtotime($duedate_time)) < (strtotime($current_date))) {
						$get_taskstatus = get_post_meta($p_id, 'pto_task_status', true);
						if ($get_taskstatus == 'not-started' || $get_taskstatus == 'in-progress') {
							update_post_meta($p_id, "pto_task_status", 'overdue');
							$post_meta = "overdue";
						}
					}
				}
				if ($post_meta == "") {
					$post_meta = "not-started";
				}

				$no_exists_value = get_option('pto_task_status');
				foreach ($no_exists_value as $key => $pto_task_status) {
					?>
					<div class="pto-task-cpt-status-radio">
						<input type="radio" name="taskstatus" value="<?php echo esc_html($key); ?>" <?php if ($post_meta == $key) {
							echo "checked";
						} ?>>
						<lable class="taskstatus-detail"><?php echo esc_html($pto_task_status); ?></lable>
					</div>
					<?php
				}
			} else {

				global $post;

				if (isset($_GET['proj_id'])) {
					$p_id = sanitize_text_field($_GET['proj_id']);
					$no_exists_value =  get_post_meta($p_id, 'pto_kanban_status', true);

					foreach ($no_exists_value as $key => $pto_task_status2) {
						foreach ($pto_task_status2 as $key2 => $pto_task_status) {

							?>
							<div class="pto-task-cpt-status-radio">
								<input type="radio" name="taskstatus" value="<?php echo esc_html($key); ?>" <?php if ($post_meta == $key) {
									echo "checked";
								} ?>>
								<lable class="taskstatus-detail"><?php echo esc_html($pto_task_status); ?></lable>
							</div>
							<?php
						}
					}
				} else {
					$dat =  get_post_meta($post->ID, 'pto_associated_project_id', true);
					$no_exists_value =  get_post_meta($dat, 'pto_kanban_status', true);

					foreach ($no_exists_value as $key => $pto_task_status2) {
						foreach ($pto_task_status2 as $key2 => $pto_task_status) {;
							?>
							<div class="pto-task-cpt-status-radio">
								<input type="radio" name="taskstatus" value="<?php echo esc_html($key); ?>" <?php if ($post_meta == $key) {
									echo "checked";
								} ?>>
								<lable class="taskstatus-detail"><?php echo esc_html($pto_task_status); ?></lable>
							</div>
							<?php
						}
					}
				}
			}


			?>
		</div>
	</div>
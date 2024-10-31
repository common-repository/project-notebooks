<div class='pto-admin-setting-email-system'>
	<div class="pto-admin-setting-email-system-header">
		<h2>Project System Emails <i class="fa fa-info-circle fas-tooltip" title="This email gets sent to all administrators you invite as a 'Project Notebook Administrator'"></i></h2>
	</div>
	<div class="pto-admin-setting-email-system-data TEST">
		<p><?php esc_html_e('Set the wording for your administrator invitation emails', PTO_NB_MYPLTEXT); ?></p>
		<?php
		// email system setup for admin
		$content = get_option('email_system', true);
		if ($content == 1) {
			$content  = "";
		}
		wp_editor($content, 'email_system', $settings = array(
			'textarea_name' => 'email_system',
			'textarea_rows' => 20
		));
		?>
	</div>
	<br>
	<div class="pto-admin-setting-email-system-header">
		<h2>Project Task Emails <i class="fa fa-info-circle fas-tooltip" title="This email gets sent to all project administrators when a task becomes due."></i></h2>
	</div>
	<div class="pto-admin-setting-email-system-data TEST">
		<p><?php esc_html_e('Set the wording for your user reminder emails', PTO_NB_MYPLTEXT); ?></p>
		<?php
		// email system setup for admin
		$content = get_option('task_email_system', true);
		if ($content == 1) {
			$content  = "";
		}
		wp_editor($content, 'task_email_system', $settings = array(
			'textarea_name' => 'task_email_system',
			'textarea_rows' => 20
		));
		?>
	</div>
</div>
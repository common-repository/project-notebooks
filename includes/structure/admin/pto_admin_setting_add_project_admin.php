<div class='pto-admin-setting-user-create'>
	<div class="pto-admin-setting-user-header">
		<h2><?php esc_html_e('Set Project Plugin Administrators', PTO_NB_MYPLTEXT); ?> <i class="fa fa-info-circle fas-tooltip" title="Allow other users to help manage all of your organizations project notebooks by assigning them here."></i></h2>
	</div>
	<div class="pto-admin-setting-user-details">
		<div class="pto-admin-setting-user-search">
			<!-- <input type="search" name="usersearch" class="useradminsearch" autocomplete="nope"> -->
			<a type="button" name="useradd" value="Add Plugin Administrators" class="button button-primary" onclick="jQuery('#user_adddata').addClass('pto-modal-open'); jQuery('#user_adddata #user_type').val(2);jQuery('.pto-add-new-status input').val('');jQuery('ul.pto-project-user-section-desc-details-ul').html('');jQuery('.user-title').html('Add new plugin administrators');"> Add Plugin Administrators </a>
			<div class="pto-admin-search-result-data" style="display:none;">
			</div>
		</div>
		<div class="pto-admin-setting-user-data">
			<?php
			/* get all user details */
			$users = get_users();
			?>
			<!-- show administator data for user -->
			<table class="pto-admin-setting-user-table-project wp-list-table widefat fixed  striped table-view-list posts" width="100%">
				<thead>
					<tr>
						<th class="manage-column column-title column-primary sorted desc"> <?php esc_html_e('ID', PTO_NB_MYPLTEXT); ?></th>
						<th class="manage-column column-title column-primary sorted desc"><?php esc_html_e('Name', PTO_NB_MYPLTEXT); ?></th>
						<th class="manage-column column-title column-primary sorted desc"><?php esc_html_e('Username', PTO_NB_MYPLTEXT); ?></th>
						<th class="manage-column column-title column-primary sorted desc"><?php esc_html_e('Email', PTO_NB_MYPLTEXT); ?></th>
						<th class="manage-column column-title column-primary sorted desc"><?php esc_html_e('Action', PTO_NB_MYPLTEXT); ?></th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($users as $user) {
						if (array_key_exists("project_plugin_administrators", $user->caps)) { // shoe conitionaly uer data 
							?>
							<tr <?php echo "class='own_user_" . intval($user->ID) . "'"; ?>>
								<td><?php echo esc_html($user->ID); ?></td>
								<td><?php echo esc_html($user->data->display_name); ?></td>
								<td><?php echo esc_html($user->data->user_nicename); ?></td>

								<td><?php echo esc_html($user->data->user_email); ?></td>
								<td><span class="delete-user"><a href="javascript:void(0)" class="delete_user_project" id="<?php echo esc_html($user->ID); ?>" type="2">Delete</a></span>&nbsp;&nbsp; |&nbsp; <span class="resend-invitation"> <a href="javascript:void(0)" class="resend-invite" id="<?php echo esc_html($user->ID); ?>" data-user-type="project_plugin_administrators">Resend Invitation</a></span></td>
							</tr>
						<?php }
					} ?>
				</tbody>
			</table>
		</div>
	</div>
</div>
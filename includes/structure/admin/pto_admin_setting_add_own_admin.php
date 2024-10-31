<div class='pto-admin-setting-user-create'>
	<div class="pto-admin-setting-user-header">
		<h2><?php esc_html_e('Set "Create Your Own Project" Administrators', PTO_NB_MYPLTEXT); ?> <i class="fa fa-info-circle fas-tooltip" title="Users added here will be able to create their own notebooks from the 'All Projects' listing page via a permission enabled 'Add New' button."></i></h2>
	</div>
	<div class="pto-admin-setting-user-details">
		<?php 
			update_option( "user_per","allo-user-specific-own-project" );
		?>
		<div class="permiosion-role" >
		<div class="pto-admin-setting-user-search">
			<!-- <input type="search" name="usersearch" class="useradminsearch" autocomplete="nope"> -->
			<a type="button" name="useradd" value="Add Project Administrators" class="button button-primary" onclick="jQuery('#user_adddata').addClass('pto-modal-open'); jQuery('#user_adddata #user_type').val(1);jQuery('.pto-add-new-status input').val('');jQuery('ul.pto-project-user-section-desc-details-ul').html('');jQuery('.user-titl').html('Create Your Own Project Users');">
			Add Project Administrators
			</a>
			<div class="pto-admin-search-result-data" style="display:none;">
			</div>
		</div>
		<div class="pto-admin-setting-user-data">
			<?php
			/* get all user details */
			$users = get_users();
			?>
			<!-- show owner role user data -->
			<table class="pto-admin-setting-user-table wp-list-table widefat fixed  striped table-view-list posts" width="100%">
				<thead>
					<tr>
						<th class="manage-column column-title column-primary sorted desc"><?php esc_html_e('ID', PTO_NB_MYPLTEXT); ?></th>
						<th class="manage-column column-title column-primary sorted desc"><?php esc_html_e('Name', PTO_NB_MYPLTEXT); ?></th>
						<th class="manage-column column-title column-primary sorted desc"><?php esc_html_e('Username', PTO_NB_MYPLTEXT); ?></th>
						<th class="manage-column column-title column-primary sorted desc"><?php esc_html_e('Email', PTO_NB_MYPLTEXT); ?></th>
						<th class="manage-column column-title column-primary sorted desc"><?php esc_html_e('Action', PTO_NB_MYPLTEXT); ?></th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($users as $user) {
							if (array_key_exists("project_manager", $user->caps)) { // conditionly data show for user 
								?>
								<tr <?php echo esc_html("class='own_user_" . $user->ID . "'"); ?>>
									<td><?php echo esc_html($user->ID); ?></td>
									<td><?php echo esc_html($user->data->display_name); ?></td>
									<td><?php echo esc_html($user->data->user_nicename); ?></td>
									<td><?php echo esc_html($user->data->user_email); ?></td>
									<td><span class="delete-user"><a href="javascript:void(0)" class="delete_user_project" id="<?php echo esc_html($user->ID); ?>" type="1">Delete</a></span>&nbsp;&nbsp; | &nbsp;&nbsp;<span class="resend-invitation"><a href="javascript:void(0)" class="resend-invite" id="<?php echo esc_html($user->ID); ?>" data-user-type="project_manager">Resend Invitation</a></span></td>
								</tr>
							<?php }
						} ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
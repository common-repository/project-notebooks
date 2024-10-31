<div class="pto-project-user-section">
    <div class="pto-project-user-section-desc-details">
        <ul class="pto-project-user-section-desc-details-ul">
            <?php
            $name = sanitize_text_field($_POST['name'] . "*");
            $user_type = sanitize_text_field($_POST['user_type']);
            $users = get_users(array('search' => $name));
            foreach ($users as $user) {
                $role_array = array();
                foreach ($user->roles as $key => $roles) {
                    $role_array[$roles] = $roles; //role array create
                }
                // conditionaly user check
                if ($user_type == 2) {
                    if (!array_key_exists("project_plugin_administrators", $role_array)) { // user role check
                        if (!array_key_exists("administrator", $role_array)) {
                            //html structure add
                            ?>
                            <div class="pto_admin_username">
                                <div class="pto_admin_user_checkbox"><input type="checkbox" class="pto_admin_user <?php echo "checked_" . intval($user->ID); ?>" id="<?php echo esc_html($user->ID); ?>" name="<?php echo esc_html($user->display_name); ?>"></div>
                                <div class="pto_admin_user_search">
                                    <div class="pto_user_name_admin"><?php echo esc_html($user->display_name); ?></div>
                                    <div class="pto_user_email_admin"><?php echo esc_html($user->user_email); ?></div>
                                </div>
                            </div>
                            <?php
                        }
                    }
                } else if ($user_type == 1) {
                    if (!array_key_exists("project_plugin_administrators", $role_array)) { //owner role add
                        if (!array_key_exists("administrator", $role_array)) { // check admin or not
                            if (!array_key_exists("project_manager", $role_array)) { // check role alredy exist or not
                                ?>
                                <div class="pto_admin_username">
                                    <div class="pto_admin_user_checkbox"><input type="checkbox" class="pto_admin_user <?php echo "checked_" . intval($user->ID); ?>" id="<?php echo intval($user->ID); ?>" name="<?php echo esc_html($user->display_name); ?>"></div>
                                    <div class="pto_admin_user_search">
                                        <div class="pto_user_name_admin"><?php echo esc_html($user->display_name); ?></div>
                                        <div class="pto_user_email_admin"><?php echo esc_html($user->user_email); ?></div>
                                    </div>
                                </div>
                                <?php
                            }
                        }
                    }
                }
            }
            ?>
        </ul>
    </div>
</div>
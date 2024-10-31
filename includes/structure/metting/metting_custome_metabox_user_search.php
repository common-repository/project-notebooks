<div class="pto-project-user-section">
    <div class="pto-project-user-section-desc-details">
        <ul class="pto-project-user-section-desc-details-ul">
            <?php
            $name = sanitize_text_field($_POST['name'] . "*");
            $ids = sanitize_text_field($_POST['ids']);
            
            $users = get_users(array('search' => $name));
            foreach ($users as $user) {
                $i = 0;
                foreach ($ids as $id) {
                    if ($id == $user->data->ID) {
                        $i  = 1;
                    }
                }
                if ($i == 0) {
                    ?>
                    <li>
                        <div class="pto-project-user-section_desc_details_login_name">
                            <div class="pto-project-user-section_desc_details-input">
                                <input type="checkbox" name="users[]" id="single-users-<?php echo intval($user->data->ID); ?>" class="single-users" ids="single-user-<?php echo intval($user->data->ID); ?>" value="<?php echo intval($user->data->ID); ?>">
                            </div>
                            <div class="pto-project-user-section_desc_details_login_data">
                                <div class="pto-project-user-section-desc-details_user_image">
                                    <img src="http://1.gravatar.com/avatar/75d23af433e0cea4c0e45a56dba18b30?s=32&d=mm&r=g" class="avatar avatar-32 photo" height="32" width="32">
                                </div>
                                <div class="pto-project-user-section-desc-details_user-desc">
                                    <div class="pto-project-user-section-desc-details_user_name">
                                        <p><?php echo esc_html_e($user->data->user_login); ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="pto-project-user-section_desc_details_user_name">
                            <?php
                            $first_name = get_user_meta($user->ID, 'first_name', true);
                            $last_name = get_user_meta($user->ID, 'last_name', true);
                            $full_name =  $first_name . " " . $last_name;
                            if ($full_name != " ") {
                                echo esc_html_e($full_name);
                            } else {
                                echo esc_html_e($user->data->display_name);
                            }
                            ?>
                        </div>
                        <div class="pto-project-user-section_desc_details_user_email">
                            <p><?php echo esc_html_e($user->data->user_email); ?></p>
                        </div>
                    </li>
                    <?php
                }
            }
            ?>
        </ul>
    </div>
</div>
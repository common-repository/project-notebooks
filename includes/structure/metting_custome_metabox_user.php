 <input type="text" placeholder="Search.." id="usersearch" name="search" style="width: 100%;">
 <div class="alluserdetails">
 </div>
 <div id="selected-check" style="display: none;">
   <ul class="pto-project-user-section-desc-details-ul">
   </ul>
</div>
<div class="pto-project-user-section">
   <?php if (isset($_GET['post'])) { ?>
       <div class="pto-project-user-section-header-title">
           <ul class="pto-project-user-section-title">
               <li><input type="checkbox" name="checkalluser" id="checkall"><span class="pto-project-user-section_titles_detail">Username</span></li>
               <li><span class="pto-project-user-section_titles_detail"><?php esc_html_e('Name', PTO_NB_MYPLTEXT); ?></span></li>
               <li><span class="pto-project-user-section_titles_detail"><?php esc_html_e('Email', PTO_NB_MYPLTEXT); ?></span></li>
           </ul>
       </div>
   <?php } ?>
   <div class="pto-project-user-section-desc-details" id="selected-attende">
       <ul class="pto-project-user-section-desc-details-ul">
           <?php
           if (isset($_GET['post'])) {
            $post_id = sanitize_text_field($_GET['post']);
            $post_meta = get_post_meta($post_id, 'pto_user_assign_key', true);
            if (is_array($post_meta)) {
                foreach ($post_meta as $user_id) {
                    $user = get_userdata($user_id);
                    ?>
                    <li id='select-<?php echo esc_html($user->data->ID); ?>'>
                       <div class="pto-project-user-section_desc_details_login_name">
                           <div class="pto-project-user-section_desc_details-input">
                               <input type="checkbox" name="user[]" class="single-user" value="<?php echo esc_html($user->data->ID); ?>">
                           </div>
                           <div class="pto-project-user-section_desc_details_login_data">
                               <div class="pto-project-user-section-desc-details_user_image">
                                   <img src="http://1.gravatar.com/avatar/75d23af433e0cea4c0e45a56dba18b30?s=32&d=mm&r=g" class="avatar avatar-32 photo" height="32" width="32">
                               </div>
                               <div class="pto-project-user-section-desc-details_user-desc">
                                   <div class="pto-project-user-section-desc-details_user_name">
                                       <p><?php echo esc_html($user->data->user_login); ?></p>
                                       <span><a href="javascript:void(0)" onclick="delete_user('select-<?php echo esc_html($user->data->ID); ?>',<?php echo esc_html($user->data->ID); ?>,<?php echo esc_html($post_id); ?>)">Delete</a></span>
                                   </div>
                               </div>
                           </div>
                       </div>
                       <div class="pto-project-user-section_desc_details_user_name">
                           <p><?php echo esc_html($user->data->display_name); ?></p>
                       </div>
                       <div class="pto-project-user-section_desc_details_user_email">
                           <p><?php echo esc_html($user->data->user_email); ?></p>
                       </div>
                   </li>
                   <?php
               }
           }
       }
       ?>
   </ul>
</div>
</div>
<!-- project request get all user -->
<h1>All Project Notebooks Request</h1>
<table class="wp-list-table widefat fixed striped table-view-list project-request-list-tbl">
    <thead>
        <tr>
            <th scope="col" id="name" class="manage-column column-name"><?php esc_html_e('Project Name', PTO_NB_MYPLTEXT); ?></th>
            <th scope="col" id="email" class="manage-column column-email"><a href="#"><span><?php esc_html_e('Author Email', PTO_NB_MYPLTEXT); ?></span><span class="sorting-indicator"></span></a></th>
            <th scope="col" id="username" class="manage-column column-username"><?php esc_html_e('Author User Name', PTO_NB_MYPLTEXT); ?></th>
            <th scope="col" id="action" class="manage-column column-action"><?php esc_html_e('Action', PTO_NB_MYPLTEXT); ?></th>
        </tr>
    </thead>
    <tbody id="the-list" data-wp-lists="list:user">
        <?php
        $current_user_id = get_current_user_id(); // get current user
        // get the meta from project wise request
        
        global $wpdb;
        $results = $wpdb->get_results("SELECT * FROM `wp_usermeta` WHERE `meta_key` LIKE 'pto_project_request_id'"); // execution
        foreach ($results as $res) {
            $user_id = $res->user_id;
            $proj_ids = $res->meta_value;
            if (is_serialized($proj_ids)) {
                $projid = unserialize($proj_ids);
            } else {
                $projid = array();
            }
            foreach ($projid as $project_post_id) {
                $the_query = get_post($project_post_id);
                $project_post_single_id = $project_post_id; // get project id
                $author_id = get_post_field('post_author', $project_post_single_id); // get author
                $user_info = get_userdata($user_id); // get user data
                //user email and user name set
                if (!empty($user_info)) {
                    $username = $user_info->user_login;
                    $useremail = $user_info->user_email;
                    ?>
                    <tr class="ac_req_<?php echo esc_html($user_id) ?>_<?php echo esc_html($project_post_single_id) ?>">
                        <td><?php echo esc_html(get_the_title($project_post_single_id)); ?> </td>
                        <td class="email column-email" data-colname="AuthorEmail"><a href="mailto:<?php echo esc_html($useremail); ?>"><?php echo esc_html($useremail); ?></a></td>
                        <td class="role column-username" data-colname="Authorusername"><?php echo esc_html($username); ?></td>
                        <td class="role column-action" data-colname="Action"><a href="#" data-userid="<?php echo esc_html($user_id); ?>" data-id="<?php echo intval($project_post_single_id); ?>" class="accepts-req button-primary button">Accept</a>&nbsp;&nbsp;<a href="#" data-id="<?php echo esc_html($project_post_single_id); ?>" class="decline-req button-danger button" data-userid="<?php echo esc_html($user_id); ?>">Decline</a></td>
                    </tr>
                    <?php
                }
            }
        }
        ?>
    </tbody>
</table>
<?php

/*
 * Defining Namespace
*/

namespace ptoffice\classes;

class WPNB_FrontendPTO
{
    /*
     * Constructor for intiation.
    */
    public function __construct()
    {
        $this->init();
    }

    /**
     * Initiating necessary functions
     */
    function init()
    {

        /* get alll project listing for frontend */
        add_shortcode('project-all-listing',  array($this, 'wpnb_get_all_projects_frontend'));
        add_action('wp_ajax_nopriv_wpnb_get_all_pto_projects', array($this, 'wpnb_get_all_pto_projects'));
        add_action('wp_ajax_wpnb_get_all_pto_projects', array($this, 'wpnb_get_all_pto_projects'));

        add_action('wp_ajax_nopriv_wpnb_get_metting_filter', array($this, 'wpnb_get_metting_filter'));
        add_action('wp_ajax_wpnb_get_metting_filter', array($this, 'wpnb_get_metting_filter'));

        add_action('wp_ajax_nopriv_wpnb_get_notes_filter', array($this, 'wpnb_get_notes_filter'));
        add_action('wp_ajax_wpnb_get_notes_filter', array($this, 'wpnb_get_notes_filter'));
    }
    /**
     get alll project listing for frontend 
     Post : Project
     **/
     public function wpnb_get_all_projects_frontend()
     {
        ob_start();
        $current_user = wp_get_current_user();
        $user_roles = $current_user->roles;
        $permision_assign = 0;
        ?>
        <div class="main-project-lists">
            <div class="main-project-lists-row">
                <div class="projects-list">
                    <div class="projects-list-tab-row">
                        <ul class="pto-project-tabs">
                            <li class="tab-link current" data-tab="all-projects">
                                <h2 class="pto-header-two"><?php esc_html_e('All Projects', PTO_NB_MYPLTEXT); ?></h2>
                            </li>
                            <li class="tab-link my-projects-tab" data-tab="my-projects">
                                <h2 class="pto-header-two"><?php esc_html_e('My Projects', PTO_NB_MYPLTEXT); ?></h2>
                            </li>
                        </ul>
                        <?php
                        if (is_user_logged_in()) {
                            $user_permision = get_option("user_per");
                            if ($user_permision == "allo-user-create-own-project") {
                                ?>
                                <a href="<?php echo esc_url(site_url()); ?>/wp-admin/post-new.php?post_type=pto-project" class="add-new-btn front-primary-btn pto-button-setting"><?php esc_html_e('Add new', PTO_NB_MYPLTEXT); ?></a>
                            <?php } else {
                                global $current_user;
                                $user_roles = $current_user->roles;
                                $cnt = 0;
                                foreach ($user_roles as $role) {
                                    if ($role == "project_manager" || $role == "project_plugin_administrators" || $role == "administrator") {
                                        ?>
                                        <a href="<?php echo esc_url(site_url()); ?>/wp-admin/post-new.php?post_type=pto-project" class="add-new-btn front-primary-btn pto-button-setting"><?php esc_html_e('Add new', PTO_NB_MYPLTEXT); ?></a>
                                        <?php
                                    }
                                }
                            }
                        } ?>

                    </div>

                    <div class="tab-data all-projects-show-box">
                        <div id="all-projects" class="tab-content current">
                            <ul class="projects-list-block">
                                <?php
                                $get_user_post = array();
                                if (is_user_logged_in()) {
                                    $current_user_id = get_current_user_id();
                                    $get_user_post = get_user_meta($current_user_id, 'pto_project_request_id', true);
                                }
                                if (empty($get_user_post)) {
                                    $get_user_post = array();
                                }
                                $args = array(
                                    'post_type' => 'pto-project',
                                    'orderby'    => 'ID',
                                    'post_status' => 'publish',
                                    'order'    => 'DESC',
                                    'posts_per_page' => -1, // this will retrive all the post that is published 

                                );
                                $result = new \WP_Query($args);
                                $request_allow_or_not = get_option("request_access");
                                if ($result->have_posts()) {
                                    while ($result->have_posts()) {
                                        $result->the_post();
                                        $post_id =  get_the_ID();
                                        $get_show_project_link = get_post_meta($post_id, "show_projects_chk", true);
                                        $c_user_id = get_current_user_id();
                                        $author_id = get_post_field('post_author', $post_id);
                                        $get_user_req_post = get_post_meta($post_id, 'pto_project_user_id', true);
                                        $img = get_the_post_thumbnail_url(get_the_ID());
                                        $get_project_due_date = get_post_meta($post_id, "pto_project_due_date", "true");
                                        $duedate =  strtotime($get_project_due_date);
                                        $current_date =  strtotime(date("m/d/Y"));
                                        $due_check = 0;
                                        if (empty($img)) {
                                            $img = PTO_NB_PLUGIN_PATH . "assets/images/noimg-low.png";
                                        }
                                        if (empty($get_user_req_post)) {
                                            $get_user_req_post = array();
                                        }

                                        if ($due_check == 0) {
                                            if ($get_show_project_link == 1) {
                                                ?>
                                                <li class="single-project-list">
                                                    <div class="single-project-block">
                                                        <div class="small-priject-banner-img">
                                                            <?php

                                                            if (!empty($img)) { ?>
                                                                <img src="<?php echo esc_html($img);  ?>">
                                                            <?php } ?>
                                                        </div>
                                                        <div class="single-project-info">
                                                            <?php if (is_user_logged_in()) {
                                                                if (($c_user_id == $author_id) || (in_array($c_user_id, $get_user_req_post))) {
                                                                    $permision_assign = 1; ?>
                                                                    <a href="<?php echo esc_url(site_url()); ?>/wp-admin/post.php?post=<?php echo esc_html($post_id); ?>&action=edit" class='edit-button'><input type="submit" class=" front-primary-btn pto-button-setting" value="Edit"></a>
                                                                <?php } else {
                                                                    if (array_key_exists($post_id, $get_user_post)) {
                                                                        ?>
                                                                        <a class="edit-button"><input type="submit" class="front-primary-btn pto-button-setting" value="Requested"></a>
                                                                        <?php
                                                                    } else {


                                                                        $ur_check = 0;
                                                                        if (in_array("project_plugin_administrators", $user_roles)) {
                                                                            $ur_check = 1;
                                                                        }
                                                                        if (in_array("administrator", $user_roles)) {
                                                                            $ur_check = 1;
                                                                        }
                                                                        if ($ur_check == 1) {
                                                                            ?>
                                                                            <a href="<?php echo esc_url(site_url()); ?>/wp-admin/post.php?post=<?php echo esc_html($post_id); ?>&action=edit" class='edit-button'><input type="submit" class=" front-primary-btn pto-button-setting" value="Edit"></a>
                                                                            <?php
                                                                        } else {
                                                                            
                                                                            if( $request_allow_or_not == "on" ){
                                                                                ?>
                                                                                <a class='edit-button'><input type="submit" class="project-access-btn front-primary-btn pto-button-setting" data-id="<?php echo esc_html($post_id); ?>" id="project-access-btn" value="Request access"></a>
                                                                                <?php
                                                                            }
                                                                        }
                                                                    }
                                                                }
                                                            } ?>
                                                            <a href="<?php echo esc_url(get_permalink($post_id)); ?>">
                                                            
                                                            <h4 class="post-title pto-header-four"><?php the_title(); ?></h4></a>
                                                            <?php echo "</a>"; ?>
                                                        </div>
                                                    </div>
                                                </li>
                                                <?php
                                            }
                                        }
                                    }
                                }
                                if ($permision_assign == 1) {

                                    $user = wp_get_current_user();

                                    $user->add_cap('edit_posts');
                                    $user->add_cap('edit_others_posts');
                                    $user->add_cap('publish_posts');
                                    $user->add_cap('manage_categories');
                                    $user->add_cap('edit_published_posts');
                                    //$user->add_role('editor');
                                }
                                wp_reset_postdata();
                                ?>
                            </ul>
                        </div>
                        <div id="my-projects" class="tab-content">
                            <ul class="projects-list-block">
                                <?php
                                $c_user_id = get_current_user_id();
                                $args = array(
                                    'post_type' => 'pto-project',
                                    'orderby'    => 'ID',
                                    'post_status' => 'publish',
                                    'order'    => 'DESC',
                                    'posts_per_page' => -1, // this will retrive all the post that is published 
                                );
                                $result = new \WP_Query($args);

                                if ($result->have_posts()) {
                                    while ($result->have_posts()) {
                                        $result->the_post();
                                        $post_id =  get_the_ID();
                                        $c_user_id = get_current_user_id();
                                        $author_id = get_post_field('post_author', $post_id);
                                        $get_user_req_post = get_post_meta($post_id, 'pto_project_user_id', true);
                                        if (empty($get_user_req_post)) {
                                            $get_user_req_post = array();
                                        }
                                        $req_ids = array();
                                        $img = get_the_post_thumbnail_url(get_the_ID());
                                        if (empty($img)) {
                                            $img = PTO_NB_PLUGIN_PATH . "assets/images/noimg-low.png";
                                        }
                                        foreach ($get_user_req_post as $reqest_id) {
                                            $req_ids[$reqest_id] = $reqest_id;
                                        }
                                        $get_show_project_link = get_post_meta($post_id, "show_projects_chk", true);

                                        if ($get_show_project_link == "1") {

                                            if (($c_user_id == $author_id) || (array_key_exists($c_user_id, $req_ids))) {
                                                $get_project_due_date = get_post_meta($post_id, "pto_project_due_date", "true");
                                                $duedate =  strtotime($get_project_due_date);
                                                $current_date =  strtotime(date("m/d/Y"));
                                                $due_check = 0;
                                                if ($due_check == 0) {
                                                    ?>
                                                    <li class="single-project-list">
                                                        <div class="single-project-block">
                                                            <div class="small-priject-banner-img">
                                                                <img src="<?php echo esc_html($img);  ?>">
                                                            </div>
                                                            <div class="single-project-info">


                                                                <a href="<?php echo esc_url(site_url()); ?>/wp-admin/post.php?post=<?php echo esc_html($post_id); ?>&action=edit" class='edit-button'><input type="submit" class=" front-primary-btn pto-button-setting" id="project-access-btn" value="Edit"></a>

                                                                <a href=" <?php echo esc_url(get_post_permalink(get_the_ID())); ?> ">
                                                                    <h4 class="post-title pto-header-four"><?php the_title(); ?></h4>
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </li>
                                                    <?php
                                                }
                                            }
                                        }
                                    }
                                }
                                wp_reset_postdata();
                                ?>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="search-projects-block">
                    <div class="search-projects">
                        <h3 class="pto-header-three" for="search"><?php esc_html_e('Projects Search', PTO_NB_MYPLTEXT); ?></h3>
                        <div class="search-projects-form-box">
                            <div class="cust-field input-field">
                                <label for="search" class="pto_text_setting"><?php esc_html_e('Search for a project name', PTO_NB_MYPLTEXT); ?></label>
                                <input type="search" id="search-project" data class="s-project" name="search-project">
                            </div>

                            <input type="submit" value='submit' class="search-project-btn front-primary-btn pto-button-setting" id="search-project-btn" style="margin-top:5px;">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }

    /**
     get alll project listing for frontend for search base
     Post : Project
     **/
     public function wpnb_get_all_pto_projects()
     {
        if (!wp_verify_nonce($_POST['nonce'], 'ajax-nonce')) {
            die('Busted!');
        }
        if (is_user_logged_in()) {
            $current_user_id = get_current_user_id();
            $get_user_post = get_user_meta($current_user_id, 'pto_project_request_id', true);
            global $current_user;
            $user_roles = $current_user->roles;
        }

        if( empty( $get_user_post ) ){
            $get_user_post = array();
        }
        $search_project = sanitize_text_field($_POST['searchproject']);

        $peojecthtml = '<ul class="projects-list-block">';
        $args = array(
            'post_type'   => 'pto-project',
            'orderby'     => 'ID',
            'post_status' => 'publish',
            'order'       => 'DESC',
            's'           => $search_project,
            'posts_per_page' => -1 // this will retrive all the post that is published 
        );

        $result = new \WP_Query($args);
        $request_allow_or_not = get_option("request_access");
        if ($result->have_posts()) {
            while ($result->have_posts()) {
                $result->the_post();
                $post_id =  get_the_ID();
                $title = get_the_title();

                $get_show_project_link = get_post_meta($post_id, "show_projects_chk", true);
                $c_user_id = get_current_user_id();
                $author_id = get_post_field('post_author', $post_id);
                $get_user_req_post = get_post_meta($post_id, 'pto_project_user_id', true);
                if( empty( $get_user_req_post ) ){
                    $get_user_req_post = array();
                }
                
                $img = get_the_post_thumbnail_url(get_the_ID());

                if (empty($img)) {
                    $img = PTO_NB_PLUGIN_PATH . "assets/images/noimg-low.png";
                }
                $get_project_due_date = get_post_meta($post_id, "pto_project_due_date", "true");
                $duedate =  strtotime($get_project_due_date);
                $current_date =  strtotime(date("m/d/Y"));
                $due_check = 0;
                if ($due_check == 0) {
                    if ($get_show_project_link == 1) {
                        $peojecthtml .= '<li class="projects-list-block">';
                        $peojecthtml .= '<div class="single-project-block"><div class="small-priject-banner-img">';
                        $peojecthtml .= '<img src="' . $img .  '">';
                        $peojecthtml .= '</div>';
                        $peojecthtml .= '<div class="single-project-info">';
                        if (is_user_logged_in()) {
                            if ($c_user_id == $author_id || in_array($c_user_id, $get_user_req_post)) {
                                $peojecthtml .= '<a class="edit-button" href="/wp-admin/post.php?post=' . $post_id . ';&action=edit"><input type="submit" class=" front-primary-btn pto-button-setting" value="You\'re a manager"></a>';
                            } else {

                                if (array_key_exists($post_id, $get_user_post)) {
                                    $peojecthtml .= '<a class="edit-button"><input type="submit" class="project-access-btn front-primary-btn pto-button-setting" value="Requested"></a>';
                                } 
                                else {
                                    $ur_check = 0;
                                    if (in_array("project_plugin_administrators", $user_roles)) {
                                        $ur_check = 1;
                                    }
                                    if (in_array("administrator", $user_roles)) {
                                        $ur_check = 1;
                                    }
                                    if ($ur_check == 1) {
                                        $peojecthtml .= '<a href="/wp-admin/post.php?post=' . $post_id . ';&action=edit"><input type="submit" class=" front-primary-btn pto-button-setting" value="Edit"></a>';
                                    } else {
                                        if( $request_allow_or_not  == "on" ){
                                            $peojecthtml .= '<a class="edit-button"><input type="submit" class="project-access-btn front-primary-btn pto-button-setting"  data-id="' . $post_id . '" id="project-access-btn" value="Request access"></a> ';
                                        }
                                    }
                                }
                            }
                        }
                        $peojecthtml .= '<h4 class="post-title pto-header-four"><a href="' . get_post_permalink($post_id) . '">' . get_the_title() . '</a></h4>';
                        $peojecthtml .= '</div></div>';
                        $peojecthtml .= '</li>';
                    }
                }
            }
        }

        wp_reset_postdata();
        $peojecthtml .= '</ul>';
        $project_html_structure = '<ul class="projects-list-block">';
        $c_user_id = get_current_user_id();
        $args = array(
            'post_type'   => 'pto-project',
            'orderby'     => 'ID',
            'post_status' => 'publish',
            'author'      => $c_user_id,
            'order'       => 'DESC',
            's'           => $search_project,
            'posts_per_page' => -1 // this will retrive all the post that is published 
        );
        $result = new \WP_Query($args);
        if ($result->have_posts()) {
            while ($result->have_posts()) {
                $result->the_post();
                $title = get_the_title();
                $post_id =  get_the_ID();
                $c_user_id = get_current_user_id();
                $author_id = get_post_field('post_author', $post_id);
                $get_user_req_post = get_post_meta($post_id, 'pto_project_user_id', true);
                $req_ids = array();
                $img = get_the_post_thumbnail_url(get_the_ID());
                if (empty($img)) {
                    $img = PTO_NB_PLUGIN_PATH . "assets/images/noimg-low.png";
                }

                foreach ($get_user_req_post as $reqest_id) {
                    $req_ids[$reqest_id] = $reqest_id;
                }
                $get_show_project_link = get_post_meta($post_id, "show_projects_chk", true);

                $get_project_due_date = get_post_meta($post_id, "pto_project_due_date", "true");
                $duedate =  strtotime($get_project_due_date);
                $current_date =  strtotime(date("m/d/Y"));
                $due_check = 0;
                if ($due_check == 0) {
                    if ($get_show_project_link == "1") {
                        if (($c_user_id == $author_id) || (array_key_exists($c_user_id, $req_ids))) {
                            $project_html_structure .= '<li class="single-project-list">';
                            $project_html_structure .= '<div class="single-project-block"><div class="small-priject-banner-img">';
                            $project_html_structure .= '<img src="' . $img .  '">';
                            $project_html_structure .= '</div>';
                            if (is_user_logged_in()) {
                                $project_html_structure .= '<div class="single-project-info">               
                                <a href="/wp-admin/post.php?post=' . $post_id . ';&action=edit"><input type="submit" class="front-primary-btn pto-button-setting"  value="Edit"></a>
                                <h4 class="post-title pto-header-four">' . $title . '</h4>
                                </div>';
                            }
                            $project_html_structure .= '</div></li>';
                        }
                    }
                }
            }
        }
        wp_reset_postdata();
        $project_html_structure .= '</ul>';
        $data['all_project_data'] =  $peojecthtml;
        $data['my_project_data'] =  $project_html_structure;

        echo json_encode($data);
        die();
    }
    /**
     *  Metting filter get data
     **/
    public function wpnb_get_metting_filter()
    {   
        $type = "pto-meeting";
        if (!wp_verify_nonce($_POST['nonce'], 'ajax-nonce')) {
            die('Busted!');
        }
        include "metting-data.php";
        die();
    }
    public function wpnb_get_notes_filter()
    {
        $type = "pto-note";
        if (!wp_verify_nonce($_POST['nonce'], 'ajax-nonce')) {
            die('Busted!');
        }
        include "metting-data.php";
        die();
    }
    
}

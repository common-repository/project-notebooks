<?php

/**
 * PTO class for initiating necessary actions and core functions.
 */

/*
 * Defining Namespace
*/

namespace ptoffice\classes;

class WPNB_Ptoffice
{

    /**
    * Constructor for intiation.
    * @since    1.0.0
    * @access   public
    **/
    public function __construct(){
        $this->init();
    }

    /**
    * Initiating necessary functions
    * @since    1.0.0
    * @access   public
    **/
    public function init(){
        // /* script enq */
        add_action('admin_enqueue_scripts', array($this, 'wpnb_scrept_enq'));
        add_action('wp_enqueue_scripts', array($this, 'wpnb_scrept_enq_front'));
        /* role add */
        add_action('admin_init', array($this, 'wpnb_PTO_roles'));
        add_action('admin_init',  array($this, 'wpnb_restrict_access_admin_panel'), 1);
        // add_filter('show_admin_bar',  array($this, 'wpnb_hide_admin_bar'));
        /* project inside cpt filter */
        add_action('wp_ajax_nopriv_wpnb_pto_cpt_filter_action', array($this, 'wpnb_pto_cpt_filter_action'));
        add_action('wp_ajax_wpnb_pto_cpt_filter_action', array($this, 'wpnb_pto_cpt_filter_action'));

        add_action('wp_ajax_nopriv_wpnb_pto_get_mettingpost_content', array($this, 'wpnb_pto_get_mettingpost_content'));
        add_action('wp_ajax_wpnb_pto_get_mettingpost_content', array($this, 'wpnb_pto_get_mettingpost_content'));

        add_action('wp_ajax_nopriv_wpnb_pto_get_notepost_content', array($this, 'wpnb_pto_get_notepost_content'));
        add_action('wp_ajax_wpnb_pto_get_notepost_content', array($this, 'wpnb_pto_get_notepost_content'));

        add_action("wp_head",  array($this,  "wpnb_wp_header_css"));
        add_action('load-edit.php',  array($this, 'wpnb_posts_for_current_author'));

        add_filter( 'plugin_action_links', array($this,'misha_settings_link'), 10, 2 );
    }

     /**
    * Plugin setting menu 
    * @since    1.0.0
    * @access   public
    **/
    public function misha_settings_link( $links_array, $plugin_file_name ){
        // $plugin_file_name is plugin-folder/plugin-name.php

        // if you use this action hook inside main plugin file, use basename(__FILE__) to check
        if( strpos( $plugin_file_name, PTO_NB_MYPL_BASE_FILE ) ) {
            // we can add one more array element at the beginning with array_unshift()
            // array_unshift( $links_array, 'Settings' );
            $link_setting_tab = "<a href='". admin_url() ."/edit.php?post_type=pto-project&page=projects_archive'>Settings</a>";
            $links_array["Settings"] = $link_setting_tab;
        }
        return $links_array;
    }

    /**
    * Post for current post author
    * @since    1.0.0
    * @access   public
    **/
    public function wpnb_posts_for_current_author(){
       
        global $user_ID;
        /*if current user is an 'administrator' do nothing*/
        //if ( current_user_can( 'add_users' ) ) return;

        /*if current user is an 'administrator' or 'editor' do nothing*/

        $user = new \WP_User($user_ID);
        $cnt = 0;
        $posttype = "";
        if(isset($_GET["post_type"])){
            $posttype = sanitize_text_field($_GET["post_type"]);
        }
        if( !empty($posttype) && $posttype == "pto-project" ){
            if (in_array("administrator", $user->roles)) {
                $cnt = 1;
            }
            if (in_array("project_plugin_administrators", $user->roles)) {
                $cnt = 1;
            }

            if ($cnt == 0) {
                if (!isset($_GET['author'])) {
                    $url =  add_query_arg('author', $user_ID);
                    header("Location:$url");
                    // wp_redirect(add_query_arg('author', $user_ID));
                    exit;
                }
            }
        }
    }

   
    /**
        Other role redirect to home page 
    **/
    public function wpnb_restrict_access_admin_panel(){
        global $current_user;
        $user_role = wp_get_current_user();
        $role_check = false;
        if (array_key_exists("project_plugin_administrators", $user_role->caps)) {
            $role_check = true;
        } else if (array_key_exists("administrator", $user_role->caps)) {
            $role_check = true;
        }
        if ($role_check == false) {
            // wp_redirect( get_bloginfo('url') );
        }
    }

    /**
    * Role add in wordpress 
    * @since    1.0.0
    * @access   public
    **/
    public function wpnb_PTO_roles(){
        $current_user = wp_get_current_user();
        /*  project manager role create */
        add_role('project_manager', 'Project Manager', array(
            'read' => true,
            'delete_posts' => true,
            'create_posts'      => true,
            'edit_others_posts' => true,

        ));
        add_role(
            'project_plugin_administrators', //  System name of the role.
            __('Project Plugin Administrators'), // Display name of the role.
            array(
                'read'            => true, // Allows a user to read
                'create_posts'      => true, // Allows user to create new posts
                'edit_posts'        => true, // Allows user to edit their own posts
                'edit_others_posts' => true, // Allows user to edit others posts too
                'publish_posts' => true, // Allows the user to publish posts
                'manage_categories' => true, // Allows user to manage post categories
            )
        );

    }

    /**
    * Change role
    * @since    1.0.0
    * @access   public
    **/
    public function change_role($id, $new_role){
        global $table_prefix;
        if (is_array($new_role)) {
            $new_role_array = $new_role;
        } else {
            $new_role_array = array($new_role => true);
        }
        return update_user_meta($id, $table_prefix . 'capabilities', $new_role_array);
    }

    /**
    * css and js add
    * @since    1.0.0
    * @access   public
    **/
    public function wpnb_scrept_enq(){
        global $post;
        $get_type = "";
        if (isset($_GET['post_type'])) {
            $get_type = sanitize_text_field($_GET['post_type']);
        }
        if ("pto-meeting" === get_post_type() || "pto-note"  === get_post_type() ||  "pto-tasks" === get_post_type() ||  "pto-kanban" === get_post_type() ||  "pto-budget-items" === get_post_type()   ||  "pto-project" === get_post_type() ||  $get_type == "pto-project") {
            wp_enqueue_script("jquery-ui-core");
            wp_enqueue_script( 'jquery-ui-datepicker' );
            wp_enqueue_style('jquery-ui-css',  PTO_NB_MYPL_PLUGIN_BASEDIR_PATH . '/assets/kanbanboard/jquery-ui.css',  '1.0.20', true);
            wp_enqueue_style('pto-admin-custom-css', PTO_NB_MYPL_PLUGIN_BASEDIR_PATH .  '/assets/css/pto-admin-custom.css');
            //wp_enqueue_style('pto-admin-frontend-custom-css', PTO_NB_MYPL_PLUGIN_BASEDIR_PATH .  '/assets/css/pto-project-frontend-css.css');
            wp_enqueue_style('pto-admin-frontend-custom-responsive-css', PTO_NB_MYPL_PLUGIN_BASEDIR_PATH . '/assets/css/pto-project-responsive-frontend.css');
            
            $url = network_site_url();
            /**
             * Enqueue necessary scripts
             */
            wp_enqueue_script('jquery-spectrum', PTO_NB_MYPL_PLUGIN_BASEDIR_PATH . '/assets/kanbanboard/spectrum.min.js', array('jquery'), '1.0.0', true);
           // wp_enqueue_script('jquery-ui', PTO_NB_MYPL_PLUGIN_BASEDIR_PATH .  '/assets/kanbanboard/jquery-ui.js', array('jquery'), '1.0.0', true);
            wp_enqueue_script('font-awesome-js', PTO_NB_MYPL_PLUGIN_BASEDIR_PATH .  '/assets/js/font-awesome.js', array(), '1.0.0', true);
            wp_enqueue_script('donetyping-jquery-js', PTO_NB_MYPL_PLUGIN_BASEDIR_PATH .  '/assets/js/donetyping-jquery.js', array('jquery'), '1.0.0', true);
            wp_enqueue_script('pto-fontawesome', PTO_NB_MYPL_PLUGIN_BASEDIR_PATH .  '/assets/js/font-awesome.js', array(), '1.0.0', true);
            wp_enqueue_script('pto-admin-custom-js', PTO_NB_MYPL_PLUGIN_BASEDIR_PATH .  '/assets/js/pto-admin-custom.js?' . time(), array('jquery'), null, true);
            wp_enqueue_script('pto-frontend-custom-js', PTO_NB_MYPL_PLUGIN_BASEDIR_PATH .  'assets/js/pto-frontend-custom.js', array('jquery'), null, true);
            wp_enqueue_script('pto-sweetalert-admin-custom-js', PTO_NB_MYPL_PLUGIN_BASEDIR_PATH . '/assets/js/sweetalert.min.js', array('jquery'), null, true);
            wp_localize_script('pto-admin-custom-js', 'custom', array('ajax_url' => admin_url('admin-ajax.php'), 'nonce' => wp_create_nonce('ajax-nonce')));
        }
    }

    /**
    * Enqueue neccessary script
    * @since    1.0.0
    * @access   public
    **/
    public function wpnb_scrept_enq_front(){
        //wp_enqueue_style('pto-admin-custom-css', plugin_dir_url(dirname(__FILE__)) . 'assets/css/pto-admin-custom.css');
        wp_enqueue_script("jquery-ui-core");
        wp_enqueue_script( 'jquery-ui-datepicker' );
        wp_enqueue_style('pto-admin-frontend-custom-css', plugin_dir_url(dirname(__FILE__)) . 'assets/css/pto-project-frontend-css.css');
        wp_enqueue_style('pto-admin-frontend-custom-responsive-css', plugin_dir_url(dirname(__FILE__)) . 'assets/css/pto-project-responsive-frontend.css');
        $url = network_site_url();
        /**
         * Enqueue necessary scripts
        */
        wp_enqueue_script('jquery-spectrum', PTO_NB_MYPL_PLUGIN_BASEDIR_PATH . '/assets/kanbanboard/spectrum.min.js', array(), '1.0.0', true);
        //wp_enqueue_script('jquery-ui', PTO_NB_MYPL_PLUGIN_BASEDIR_PATH . '/assets/kanbanboard/jquery-ui.js', array(), '1.0.0', true);
        wp_enqueue_style('jquery-ui-css', PTO_NB_MYPL_PLUGIN_BASEDIR_PATH . '/assets/kanbanboard/jquery-ui.css',  '1.0.20', true);
        wp_enqueue_script('font-awesome-js', PTO_NB_MYPL_PLUGIN_BASEDIR_PATH . '/assets/js/font-awesome.js', array(), '1.0.0', true);
        wp_enqueue_script('donetyping-jquery-js', PTO_NB_MYPL_PLUGIN_BASEDIR_PATH . '/assets/js/donetyping-jquery.js', array(), '1.0.0', true);
        wp_enqueue_script('pto-fontawesome', PTO_NB_MYPL_PLUGIN_BASEDIR_PATH . '/assets/js/font-awesome.js', array(), '1.0.0', true);
        wp_enqueue_script('pto-admin-custom-js', plugin_dir_url(dirname(__FILE__)) . 'assets/js/pto-admin-custom.js?' . time(), array('jquery'), null, true);
        wp_enqueue_script('pto-frontend-custom-js', plugin_dir_url(dirname(__FILE__)) . 'assets/js/pto-frontend-custom.js?time=' . time(), array('jquery'), null, true);
        wp_enqueue_script('pto-sweetalert-admin-custom-js', plugin_dir_url(dirname(__FILE__)) . 'assets/js/sweetalert.min.js', array('jquery'), null, true);
        wp_localize_script('pto-admin-custom-js', 'custom', array('ajax_url' => admin_url('admin-ajax.php'), 'nonce' => wp_create_nonce('ajax-nonce')));
    }

    /**
    * Cpt create filter for inner section 
    * @since    1.0.0
    * @access   public
    **/
    public function wpnb_pto_cpt_filter_action(){
        if (!wp_verify_nonce($_POST['nonce'], 'ajax-nonce')) {
            die();
        }
        if ($_POST) {
            $all_post_id = sanitize_text_field($_POST['newids']);
            $seprate_post_id = explode(",", $all_post_id);
            $action_name = sanitize_text_field($_POST['action_name']);
            foreach ($seprate_post_id as $post_id) {
                if ($action_name == "published") {
                    wp_update_post(array(
                        'ID'    =>  $post_id,
                        'post_status'   =>  'publish'
                    ));
                }
                if ($action_name == "draft") {
                    wp_update_post(array(
                        'ID'    =>  $post_id,
                        'post_status'   =>  'draft'
                    ));
                }
                if ($action_name == "archive") {
                    wp_update_post(array(
                        'ID'    =>  $post_id,
                        'post_status'   =>  'archive'
                    ));
                }
                if ($action_name == "trush") {
                    wp_trash_post($post_id);
                }
            }
        }
        die();
    }


    /**
    * Header css
    * @since    1.0.0
    * @access   public
    */
    public function wpnb_wp_header_css(){

        $header_setting = get_option("header_setting");
        $text_setting = get_option("text_setting");
        $button_setting = get_option("button_setting");

        if (empty($header_setting)) {
            $header_setting = array();
        }

        if (empty($text_setting)) {
            $text_setting = array();
        }
        if (empty($button_setting)) {
            $button_setting = array();
        }
        ?>
        <style type="text/css">
        <?php
        if (array_key_exists("h1", $header_setting)) {

            $h_one = $header_setting['h1'];
        ?>h1.pto-header-one {
            font-size: <?php echo esc_html($h_one . "px;"); ?>
        }

        <?php
    }
    if (array_key_exists("h2", $header_setting)) {
        $h2 = $header_setting['h2'];
    ?>h2.pto-header-two {
        font-size: <?php echo esc_html($h2 . "px;"); ?>
    }

    <?php
    }
    if (array_key_exists("h3", $header_setting)) {
        $h3 = $header_setting['h3'];
    ?>h3.pto-header-three {
        font-size: <?php echo esc_html($h3 . "px;"); ?>
    }

    <?php
    }

    if (array_key_exists("h4", $header_setting)) {

        $h4 = $header_setting['h4'];
    ?>h4.pto-header-four {
        font-size: <?php echo esc_html($h4 . "px;"); ?>
    }

    <?php
    }
    if (array_key_exists("h5", $header_setting)) {
        $h5 = $header_setting['h5'];
    ?>h5.pto-header-five {
        font-size: <?php echo esc_html($h5 . "px;"); ?>
    }

    <?php
    }
    if (array_key_exists("h6", $header_setting)) {
        $h6 = $header_setting['h6'];
    ?>h6.pto-header-six {
        font-size: <?php echo esc_html($h6 . "px;"); ?>
    }

    <?php
    }
    if (array_key_exists("text_color", $text_setting)) {
    ?>.main-project-lists .pto_text_setting {

        color: <?php echo esc_html($text_setting['text_color'] . " !important"); ?>;
        font-size: <?php echo esc_html($text_setting['text_size'] . "px !important"); ?>
    }

    <?php
    }
    if (array_key_exists("button_color", $button_setting)) {
    ?>.main-project-lists input.pto-button-setting,
    .pto-project-single-page-sec input.pto-button-setting,
    .projects-list-tab-row .pto-button-setting {
        background-color: <?php echo esc_html($button_setting['button_color'] . " !important"); ?>;
        color: <?php echo esc_html($button_setting['button_text_color'] . " !important"); ?>
    }

    <?php
    }
    ?>
    </style>
    <?php
    }

    /**
    * Get meeting post content
    * @since    1.0.0
    * @access   public
    **/
    public function wpnb_pto_get_mettingpost_content(){
        if (!wp_verify_nonce($_POST['nonce'], 'ajax-nonce')) {
            die('Busted!');
        }
        $m_id = sanitize_text_field($_POST['m_id']);
        $content_post = get_post($m_id);
        $content = $content_post->post_content;
        $arr = array();
        if (!empty($content)) {
            $c_date = apply_filters('the_content', $content);
            $arr['return_html'] = $c_date;
        } else {
            $arr['return_html'] = 'No data found.';
        }
        echo json_encode($arr);
        die();
    }
    
    /**
    * Get meeting note post content
    * @since    1.0.0
    * @access   public
    **/
    public function wpnb_pto_get_notepost_content(){
        if (!wp_verify_nonce($_POST['nonce'], 'ajax-nonce')) {
            die('Busted!');
        }
        $m_id = sanitize_text_field($_POST['n_id']);
        $content_post = get_post($m_id);
        $content = $content_post->post_content;
         $arr = array();
        if (!empty($content)) {
            $c_date = apply_filters('the_content', $content);
            $arr['return_html'] = $c_date;
        } else {
            $arr['return_html'] = 'No data found.';
        }
         echo json_encode($arr);
        die();
    }
}

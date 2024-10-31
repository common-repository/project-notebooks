<?php

/**
 * PTO class for initiating necessary actions and core functions.
 */

/*
 * Defining Namespace
*/

namespace ptoffice\classes;

class WPNB_AdminPTOSetting
{

     /**
     * Constructor for intiation.
     * @since    1.0.0
     * @access   public
    */
    public function __construct(){
        $this->init();
    }

    /**
     * Initiating necessary functions
     * @since    1.0.0
     * @access   public
    **/
    public function init(){
        /* remove user */
        add_action('wp_ajax_nopriv_wpnb_pto_users_deletd', array($this, 'wpnb_pto_users_deletd'));
        add_action('wp_ajax_wpnb_pto_users_deletd', array($this, 'wpnb_pto_users_deletd'));

        /* add new users */
        add_action('wp_ajax_nopriv_wpnb_pto_new_users_add', array($this, 'wpnb_pto_new_users_add'));
        add_action('wp_ajax_wpnb_pto_new_users_add', array($this, 'wpnb_pto_new_users_add'));

        /* add new users */
        add_action('wp_ajax_nopriv_wpnb_pto_new_users_add_get', array($this, 'wpnb_pto_new_users_add_get'));
        add_action('wp_ajax_wpnb_pto_new_users_add_get', array($this, 'wpnb_pto_new_users_add_get'));

        /*Resend user invitation*/
        add_action('wp_ajax_nopriv_wpnb_pto_resend_invitation', array($this, 'wpnb_pto_resend_invitation'));
        add_action('wp_ajax_wpnb_pto_resend_invitation', array($this, 'wpnb_pto_resend_invitation'));

        /* email_system_save */
        add_action('wp_ajax_nopriv_wpnb_pto_new_email_system_save', array($this, 'wpnb_pto_new_email_system_save'));
        add_action('wp_ajax_wpnb_pto_new_email_system_save', array($this, 'wpnb_pto_new_email_system_save'));

        /* wordpres  backedn editor dropdwon add*/
        add_filter("mce_external_plugins", array($this, "wpnb_owt_attach_fns_custom_buttons"));

        //side bar menu hide 
        add_action('admin_init', array($this, 'wpnb_remove_adminmenu_for_pmuser'), 10);

        // button add for wp editor 
        add_filter("mce_buttons", array($this, "wpnb_owt_attach_custom_tinymce_buttons"));
    }


    /**
    * WP-EDITOR IN ADD BUTOON 
    * Username and Email daynamic show 
    * @since    1.0.0
    * @access   public
    **/
    public function wpnb_owt_attach_custom_tinymce_buttons($buttons)
    {
        if (!in_array("editor_console", $buttons)) {
            $buttons[] = "editor_console";
        }
        if (!in_array("editor_alert", $buttons)) {
            $buttons[] = "editor_alert";
        }
        if (!in_array("editor_popup", $buttons)) {
            $buttons[] = "editor_popup";
        }
        if (!in_array("editor_dropdown", $buttons)) {
            $buttons[] = "editor_dropdown";
        }
        return $buttons;
    }

    /**
    * Project setting in email sysytem save data 
    * Email system data store in option 
    * Meta key : email_system
    * @since    1.0.0
    * @access   public
     **/
    public function wpnb_pto_new_email_system_save(){
        if (!wp_verify_nonce($_POST['nonce'], 'ajax-nonce')) {
            die('Busted!');
        }
        $header_setting = array(
            "h1" => sanitize_text_field($_POST['h1']),
            "h2" => sanitize_text_field($_POST['h2']),
            "h3" => sanitize_text_field($_POST['h3']),
            "h4" => sanitize_text_field($_POST['h4']),
            "h5" => sanitize_text_field($_POST['h5']),
            "h6" =>sanitize_text_field( $_POST['h6'])
        );
        update_option('header_setting', $header_setting);
        $text_setting = array(
            "text_size" => sanitize_text_field($_POST['text_size']),
            "text_color" => sanitize_text_field($_POST['text_color'])
        );
        update_option('text_setting', $text_setting);
        $button_setting = array(
            "button_color" => sanitize_text_field($_POST['button_color']),
            "button_text_color" => sanitize_text_field($_POST['button_text_color'])
        );
        update_option('button_setting', $button_setting);
        if (isset($_POST['email_system'])) {

            update_option('email_system', wp_kses_post($_POST['email_system']));
        }
        if (isset($_POST['user_per'])) {
            update_option('user_per', sanitize_text_field($_POST['user_per']));
        }
        if (isset($_POST['task_email_system'])) {
            update_option('task_email_system', wp_kses_post($_POST['task_email_system']));
        } else {
            update_option('task_email_system', "");
        }
        if (isset($_POST['request_access'])) {
            update_option('request_access', sanitize_text_field($_POST['request_access']));
        } else {
            update_option('request_access', "");
        }
        die();
    }

    /**
    * Editor.js includ for wordpess backend editor in menu
    * @since    1.0.0
    * @access   public
    **/
    public function wpnb_owt_attach_fns_custom_buttons($plugin_array){
        if (isset($_GET['page'])) {
            if ($_GET['page'] == "projects_archive") {
                // $plugin_array["mce_editor_js"] = PTO_NB_PLUGIN_PATH . "/assets/js/editor.js";
            }
        }
        return $plugin_array;
    }

    /**
    * Project setting in user delete
    * Remove role and permision for backend plugin seeting
    * Role : project_plugin_administrators => admin , project_manager => project creater 
    * @since    1.0.0
    * @access   public
    **/
    public function wpnb_pto_users_deletd(){
        if (!wp_verify_nonce($_POST['nonce'], 'ajax-nonce')) {
            die('Busted!');
        }
        if (isset($_POST['user_id'])) {
            $user = get_user_by('id',sanitize_text_field( $_POST['user_id']));
            if ($_POST['ids_type'] == 2) {
                $user->remove_role('project_plugin_administrators');
                $role = get_role('administrator');
                foreach ($role->capabilities as $key => $caps) {
                    $user->remove_cap($key); // admin cpability add
                }
            } elseif ($_POST['ids_type'] == 1) {
                $user->remove_cap('create_posts');
                $user->remove_cap('edit_posts');
                $user->remove_cap('edit_others_posts');
                $user->remove_cap('publish_posts');
                $user->remove_cap('manage_categories');
                $user->remove_cap('project_manager');
            }
        }
        die();
    }

    /**
    * Project setting in user add
    * Role : project_plugin_administrators , project_manager
    * @since    1.0.0
    * @access   public
    **/
    public function wpnb_pto_new_users_add_get(){
        if (!wp_verify_nonce($_POST['nonce'], 'ajax-nonce')) {
            die('Busted!');
        }
        if (isset($_POST['user_type'])) {
            $users = get_users(); // get all user
            $html = "";
            if ($_POST['user_type'] == 2) // 2 is a project administrator
            {
                foreach ($users as $user) {
                    if (array_key_exists("administrator", $user->caps) || array_key_exists("project_plugin_administrators", $user->caps)) {
                        $author_obj = get_user_by('id', $user->ID);
                        //return html structure for user data
                        $html .= "<tr class='own_user_" . $user->ID . "'>";
                        $html .= "<td>$user->ID</td>";
                        $html .= "<td>" . $author_obj->data->user_nicename . "</td>";
                        $html .= "<td>" . $author_obj->data->display_name . "</td>";
                        $html .= "<td>" . $author_obj->data->user_email . "</td>";
                        $html .= "<td><span class='delete-user'><a href='javascript:void(0)' class='delete_user_project' id='".$user->ID."'>Delete</a></span>&nbsp;&nbsp;<span class='resend-invitation'><a href='javascript:void(0)'>Resend Invitation</a></span></td>";
                        $html .= "</tr>";
                    }
                }
            } else if ($_POST['user_type'] == 1) { // 1 is a project manager 
                foreach ($users as $user) {
                    if (array_key_exists("project_manager", $user->caps)) {
                        $author_obj = get_user_by('id', $user->ID);
                        $html .= "<tr class='own_user_" . $user->ID . "'>";
                        $html .= "<td>$user->ID</td>";
                        $html .= "<td>" . $author_obj->data->user_nicename . "</td>";
                        $html .= "<td>" . $author_obj->data->display_name . "</td>";
                        $html .= "<td>" . $author_obj->data->user_email . "</td>";
                        $html .= "<td><span class='delete-user'><a href='javascript:void(0)' class='delete_user_project' id='<?php echo $user->ID; ?>''>Delete</a></span>&nbsp;&nbsp;<span class='resend-invitation'><a href='javascript:void(0)'>Resend Invitation</a></span></td>";
                        $html .= "</tr>";
                    }
                }
            }
        }
        print_r($html);
    }

    /**
     * Project setting tab in add new users 
     * New user role add and send the mail for user role wise
     * Role : project_plugin_administrators , project_manager
     * @since    1.0.0
     * @access   public
     **/
    public function wpnb_pto_new_users_add(){
        if (!wp_verify_nonce($_POST['nonce'], 'ajax-nonce')) {
            die('Busted!');
        }
        include 'admin_add_user_notification.php';  // user notification mail setups 
        $emails = '';
        $subject = ' Administrator invitation emails'; // email sujects
        $dynamicData = '';
        $template = 'email_system'; //templete name
        if (isset($_POST['ids'])) {
            $ids = sanitize_text_field($_POST['ids']);
            $user_ids = explode(",", $ids);
            $user_type = sanitize_text_field($_POST['user_type']);
            if ($user_type == 2) {
                foreach ($user_ids as $users) {
                    $user = get_user_by('id', $users);
                    $user->add_role('project_plugin_administrators'); // admin rol
                    $role = get_role('administrator');
                    foreach ($role->capabilities as $key => $caps) {
                        $user->add_cap($key); // admin cpability add
                    }
                    $user->add_cap('manage_options');
                    $emails = $user->user_email;

                    $dynamicData = array(
                        'username' => $user->display_name,
                        'useremail' => $user->user_email
                    );
                    if (!empty($subject) && !empty($dynamicData) && !empty($template)) {
                        new EmailTemplate($emails, $subject, $dynamicData, $template);
                    }
                }
            } else if ($user_type == 1) {
                foreach ($user_ids as $users) {
                    $user = get_user_by('id', $users); // get user 
                    $user->add_role('project_manager'); // role add
                    $user->remove_cap('create_posts'); // capability remove
                    // add other capability
                    $user->add_cap('edit_posts');
                    $user->add_cap('edit_others_posts');
                    $user->add_cap('publish_posts');
                    $user->add_cap('manage_categories');
                    $user->add_cap('project_manager');
                    $user->add_cap('edit_published_posts');
                    $user->add_cap('delete_others_posts');

                    $emails = $user->user_email;
                    $dynamicData = array(
                        'username' => $user->display_name,
                        'useremail' => $user->user_email
                    );
                    if (!empty($subject) && !empty($dynamicData) && !empty($template)) {
                        new EmailTemplate($emails, $subject, $dynamicData, $template);
                    }
                }
            }
        }
        die();
    }
    /**
    * Project setting user email resend invitation 
    * New user role add and resend the mail for user role wise
    * Role : project_plugin_administrators , project_manager
    * @since    1.0.0
    * @access   public
    **/
    public function wpnb_pto_resend_invitation(){
        if (!wp_verify_nonce($_POST['nonce'], 'ajax-nonce')) {
            die('Busted!');
        }
        include 'admin_add_user_notification.php';  // user notification mail setups 
        $emails = '';
        $subject = 'Administrator invitation emails'; // email subject
        $dynamicData = '';
        $template = 'email_system'; // email Templete
        if (isset($_POST['user_id'])) {
            $user_id = sanitize_text_field($_POST['user_id']);
            $user_type = sanitize_text_field($_POST['user_type']);
            if ($user_type == 'project_plugin_administrators') {
                $user = get_user_by('id', $user_id);
                $emails = $user->user_email;
                $dynamicData = array(
                    'username' => $user->display_name,
                    'useremail' => $user->user_email
                );
                if (!empty($subject) && !empty($dynamicData) && !empty($template)) {
                    new EmailTemplate($emails, $subject, $dynamicData, $template);
                }
            } else if ($user_type == 'project_manager') {
                $user_id = sanitize_text_field($_POST['user_id']);
                $user_type =sanitize_text_field( $_POST['user_type']);
                $user = get_user_by('id', $user_id);
                $emails = $user->user_email;
                $dynamicData = array(
                    'username' => $user->display_name,
                    'useremail' => $user->user_email
                );
                if (!empty($subject) && !empty($dynamicData) && !empty($template)) {
                    new EmailTemplate($emails, $subject, $dynamicData, $template);
                }
            }
        }
        die();
    }
    /**
    * Remove sidebar menu fro other role and without permision don't acess this
    * Role : project_manager
    * @since    1.0.0
    * @access   public
    **/
    public function wpnb_remove_adminmenu_for_pmuser(){
        $current_user = wp_get_current_user();
        $current_user->add_cap('upload_files');
        $get_type = "";
        if (isset($_GET['post_type'])) {
            $get_type = "pto-project";
        }
        if (isset($_GET['post'])) {
            $get_type =  get_post_type(sanitize_text_field($_GET['post']));
            $p_id = intval($_GET['post']);
            if ($get_type != "pto-project") {
                $get_id = get_post_meta($p_id, "pto_associated_project_id", true);
                $p_id = $get_id;
                $get_type =  get_post_type($p_id);
            }
        }
        $current_user = wp_get_current_user();
        $u_role = $current_user->roles;
        if (!in_array('project_plugin_administrators',  $u_role)) {
            if ("pto-meeting" === get_post_type() || "pto-note"  === get_post_type() ||  "pto-tasks" === get_post_type() ||  "pto-kanban" === get_post_type() ||  "pto-budget-items" === get_post_type()   ||  "pto-project" === get_post_type() ||  $get_type == "pto-project") {
                if (in_array('project_manager',  $u_role)) {
                //other role remove side bar menu

                } else if (in_array('project_plugin_administrators',  $u_role)) {
                    $role = get_role('administrator');
                    foreach ($role->capabilities as $key => $caps) {
                        $current_user->add_cap($key); // admin cpability add
                    }
                } else if (in_array('administrator',  $u_role)) {
                } else {
                    if (isset($_GET['post'])) {
                        $proj_user_id = get_post_meta($p_id, 'pto_project_user_id', true);
                        if (empty($proj_user_id)) {
                            $proj_user_id = array();
                        }
                        $author_id = get_post_field('post_author', $p_id);
                        array_push($proj_user_id, $author_id);
                        $get_current_id = get_current_user_id();

                        if (!in_array($get_current_id, $proj_user_id)) {
                            if( !current_user_can('manage_network') ){
                        		echo esc_html_e("You don't have any access to this post.");
                            	die();
                        	}
                        } else {
                            $current_user->add_cap('edit_posts');
                            $current_user->add_cap('edit_others_posts');
                            $current_user->add_cap('publish_posts');
                            $current_user->add_cap('manage_categories');
                            $current_user->add_cap('edit_published_posts');
                        }
                    } else {
                        $actual_link = esc_url("http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]");
                        if (strpos($actual_link, 'admin-ajax.php') !== false) {
                        }else {
                            if (strpos($actual_link, 'edit.php?post_type=pto-project') !== false) {
                            	if( !current_user_can('manage_network') ){
                            		echo esc_html_e("You don't have any access to this post.");
                                	die();
                            	}
                            } else {
                                $current_user->add_cap('edit_posts');
                                $current_user->add_cap('edit_others_posts');
                                $current_user->add_cap('publish_posts');
                                $current_user->add_cap('manage_categories');
                                $current_user->add_cap('edit_published_posts');
                            }
                            if (strpos($actual_link, 'post-new.php?post_type=pto-project') !== false) {
                                $user_permision =  get_option("user_per", true);
                                if (empty($user_permision)) {
                                    $user_permision = array();
                                }
                                if ($user_permision == "allo-user-create-own-project") {

                                } else {
                                    if( !current_user_can('manage_network') ){
                            			echo esc_html_e("You don't have any access to this post.");
	                                	die();
	                            	}
                                }
                            } else {
                                $current_user->add_cap('edit_posts');
                                $current_user->add_cap('edit_others_posts');
                                $current_user->add_cap('publish_posts');
                                $current_user->add_cap('manage_categories');
                                $current_user->add_cap('edit_published_posts');
                            }
                            if (strpos($actual_link, 'edit.php?post_type=pto-project&page=projects_archive') !== false) {
                                if( !current_user_can('manage_network') ){
                            		echo esc_html_e("You don't have any access to this post.");
                                	die();
                            	}
                            } else {
                                $current_user->add_cap('edit_posts');
                                $current_user->add_cap('edit_others_posts');
                                $current_user->add_cap('publish_posts');
                                $current_user->add_cap('manage_categories');
                                $current_user->add_cap('edit_published_posts');
                            }
                        }
                        //other role remove side bar menu
                    }
                    remove_menu_page('edit.php'); // Posts
                    //remove_menu_page('edit.php?post_type=pto-project'); // Projects custom Posts
                    remove_menu_page('index.php'); // Posts
                    remove_menu_page('upload.php'); // Media
                    remove_menu_page('link-manager.php'); // Links
                    remove_menu_page('edit-comments.php'); // Comments
                    remove_menu_page('edit.php?post_type=page'); // Pages
                    remove_menu_page('plugins.php'); // Plugins
                    remove_menu_page('themes.php'); // Appearance
                    remove_menu_page('users.php'); // Users
                    remove_menu_page('tools.php'); // Tools
                    remove_menu_page('options-general.php'); // Settings
                    remove_menu_page('edit.php'); // Posts
                    remove_menu_page('upload.php'); // Media
                    ?>
                    <style>
                        ul#adminmenu li {
                            //display: none;
                        }
                        ul#adminmenu li:last-child {
                            display: block;
                        }
                    </style>
                    <?php
                }
            }
        }
    }
}

<?php

/**
 * PTO class for initiating necessary actions and core functions.
 */

/*
 * Defining Namespace
*/

namespace ptoffice\classes;

class WPNB_Posthooks
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
    public function init()
    {
        /* metting post data save */
        add_action('save_post_pto-meeting', array($this, 'wpnb_pto_meeting_post_save'), 99, 2);
        /* metting post data save */
        add_action('save_post_pto-project', array($this, 'wpnb_pto_project_post_save'), 20, 2);
        /* notes post data save */
        add_action('save_post_pto-note', array($this, 'wpnb_pto_note_post_save'), 20, 2);
        /* task post data save */
        add_action('save_post_pto-tasks', array($this, 'wpnb_pto_task_post_save'), 20, 2);
        add_action('save_post_pto-kanban', array($this, 'wpnb_pto_kanban_post_save'), 20, 2);
        /* task budget data save */
        add_action('save_post_pto-budget-items', array($this, 'wpnb_pto_budget_post_save'), 20, 2);
        /* get all user from meeting in attendee */
        add_action('wp_ajax_nopriv_wpnb_pto_get_users_data_in_metting', array($this, 'wpnb_pto_get_users_data_in_metting'));
        add_action('wp_ajax_wpnb_pto_get_users_data_in_metting', array($this, 'wpnb_pto_get_users_data_in_metting'));
        /* get all user from meeting in attendee */
        add_action('wp_ajax_nopriv_wpnb_pto_get_users_data_in_admin', array($this, 'wpnb_pto_get_users_data_in_admin'));
        add_action('wp_ajax_wpnb_pto_get_users_data_in_admin', array($this, 'wpnb_pto_get_users_data_in_admin'));
        /* metting cpt in  attende reomve */
        add_action('wp_ajax_nopriv_wpnb_pto_single_user_delete_in_metting', array($this, 'wpnb_pto_single_user_delete_in_metting'));
        add_action('wp_ajax_wpnb_pto_single_user_delete_in_metting', array($this, 'wpnb_pto_single_user_delete_in_metting'));
        /* get all user from meeting in attendee */
        add_action('wp_ajax_nopriv_wpnb_pto_trash_cpt', array($this, 'wpnb_pto_trash_cpt'));
        add_action('wp_ajax_wpnb_pto_trash_cpt', array($this, 'wpnb_pto_trash_cpt'));
        /* admin menu remove in side bar */
        add_action('admin_menu', array($this, 'wpnb_pto_remove_menu_items'));
        /* metting cpt in  attende reomve */
        add_action('wp_ajax_nopriv_wpnb_pto_single_post_task_status', array($this, 'wpnb_pto_single_post_task_status'));
        add_action('wp_ajax_wpnb_pto_single_post_task_status', array($this, 'wpnb_pto_single_post_task_status'));
        /* kan ban drag drop functionality */
        add_action('wp_ajax_nopriv_wpnb_pto_drag_single_post_task_status', array($this, 'wpnb_pto_drag_single_post_task_status'));
        add_action('wp_ajax_wpnb_pto_drag_single_post_task_status', array($this, 'wpnb_pto_drag_single_post_task_status'));
        /* budget value store */
        add_action('wp_ajax_nopriv_wpnb_pto_budget_add_value', array($this, 'wpnb_pto_budget_add_value'));
        add_action('wp_ajax_wpnb_pto_budget_add_value', array($this, 'wpnb_pto_budget_add_value'));
        /* project cpt templete override */
        add_filter('template_include', array($this, 'wpnb_custom_template_include'), 99);
        // add_filter( 'admin_post_thumbnail_html', array($this,'wpnb_change_featured_image_text') );
    }
    // public function wpnb_change_featured_image_text($content){
    //     if ( 'pto-project' === get_post_type() ) {
    //         $content = str_replace( 'Set featured image', __( 'Set banner image.', 'pto' ), $content );
    //         $content = str_replace( 'Remove featured image', __( 'Remove banner image', 'pto' ), $content );
    //     }
    //     return $content;
    // }
    /**
     * budget cpt vlaute meta store 
     **/
    public function wpnb_pto_budget_add_value()
    {
        if (!wp_verify_nonce($_POST['nonce'], 'ajax-nonce')) {
            die('Busted!');
        }
        if (isset($_POST)) {
            $post_id = intval($_POST['post_id']);
            $val = intval($_POST['val']);
            update_post_meta($post_id, "pto_total_budgets", $val);
            $nombre_format_francais = number_format($val, 2, '.',  ',');
            $return_arr = array();
            $return_arr['price'] = $nombre_format_francais;
            echo json_encode($return_arr);
            die();
        }
    }

    /**
     *  remove budget item and value  deta
     */

    public function wpnb_pto_budget_post_save($post_id, $post)
    {
        if ($post->post_type == "pto-budget-items") {
            if (isset($_POST['budget_items_type'])) {
                $budget_type = sanitize_text_field($_POST['budget_items_type']);
                update_post_meta($post_id, "budget_items_type", $budget_type);
            }
            if (isset($_POST['budget_item_value'])) {
                $budget_type_value = floatval($_POST['budget_item_value']);
                update_post_meta($post_id, "budget_items_type_value", $budget_type_value);
            }

            if (isset($_POST['proj_id'])) {
                update_post_meta($post_id, "pto_associated_project_id", intval($_POST['proj_id']));
                $project_id = intval($_POST['proj_id']);
                wp_enqueue_script("jquery-ui-core");
                ?>
                <script type="text/javascript">
                    opener.reload_cpt("pto-budget-items", <?php echo intval($post_id); ?>, <?php echo "'" . intval($project_id) . "'"; ?>);
                    window.close();
                </script>
                <?php
                die();
            }
        }
    }
    /** 
    note cpt data store 
    Meta key : pto_associated_project_id
     **/

    public function wpnb_pto_note_post_save($post_id, $post)
    {
        if ($post->post_type == "pto-note") {
            $project_id = "";
            if (isset($_POST['proj_id'])) {
                $project_id = intval($_POST['proj_id']);
                update_post_meta($post_id, "pto_associated_project_id", intval($_POST['proj_id']));
                wp_enqueue_script("jquery-ui-core");
                ?>
                <script type="text/javascript">
                    opener.reload_cpt("pto-note", <?php echo intval($post_id) ?>, <?php echo intval($project_id); ?>);
                    window.close();
                </script>
                <?php
                die();
            }
        }
    }

    /**
     *  remove sidebar deta
     */

    public function wpnb_pto_remove_menu_items()
    {
        remove_menu_page('edit.php?post_type=pto-meeting');
        remove_menu_page('edit.php?post_type=pto-note');
        remove_menu_page('edit.php?post_type=pto-tasks');
        remove_menu_page('edit.php?post_type=pto-budget-items');
        remove_menu_page('edit.php?post_type=pto-kanban');
    }

    /**
     *  pto project save user storeproject_plugin_
     */

    public function wpnb_pto_project_post_save($post_id, $post)
    {
        if ($post->post_type == "pto-project") {
            if (isset($_POST['total-budgets'])) {
                update_post_meta($post_id, "pto_total_budgets",floatval($_POST['total-budgets']));
            }
            if (isset($_POST['keyinformation'])) {
                $key_info = wp_kses_post($_POST['keyinformation']);
                update_post_meta($post_id, "keyinformation", $key_info);
            }
            if (isset($_POST['pto_project_due_date'])) {
                $pto_project_due_date = sanitize_text_field($_POST['pto_project_due_date']);
                update_post_meta($post_id, "pto_project_due_date", $pto_project_due_date);
            }
            if (isset($_POST['chosse_project'])) {
                $project = filter_var_array($_POST['chosse_project']);
                $pro_arr = array();
                foreach ($project as $pro) {
                    $pro_arr[$pro] = sanitize_text_field($pro);
                }
                update_post_meta($post_id, "cpt_selected_data", $pro_arr);
            } else {
                update_post_meta($post_id, "cpt_selected_data", "");
            }
            if (isset($_POST['pto_project_upload'])) {
                $img_list = filter_var_array($_POST['pto_project_upload']);
                $all_attech = "";
                foreach ($img_list as $img) {
                    $all_attech .= $img . ",";
                }
                $newarraynama = rtrim($all_attech, ",");

                update_post_meta($post_id, "cpt_project_all_attech", sanitize_text_field($newarraynama));
            }
            if (isset($_POST['show_completed_chk'])) {
                update_post_meta($post_id, "show_completed_chk", 1);
            } else {
                update_post_meta($post_id, "show_completed_chk", '');
            }

            if (isset($_POST['show_project'])) {
                update_post_meta($post_id, "show_projects_chk", '1');
            } else {
                update_post_meta($post_id, "show_projects_chk", '0');
            }
            if (isset($_POST['pto-budget-items-view']))
                update_post_meta($post_id, "pto-budget-items-view", "on");
            else
                update_post_meta($post_id, "pto-budget-items-view", "off");

            if (isset($_POST['pto-meeting-view']))
                update_post_meta($post_id, "pto-meeting-view", "on");
            else
                update_post_meta($post_id, "pto-meeting-view", "off");

            if (isset($_POST['pto-note-view']))
                update_post_meta($post_id, "pto-note-view", "on");
            else
                update_post_meta($post_id, "pto-note-view", "off");

            if (isset($_POST['pto-tasks-view']))
                update_post_meta($post_id, "pto-tasks-view", "on");
            else
                update_post_meta($post_id, "pto-tasks-view", "off");
            if (isset($_POST['pto-kanban-view']))
                update_post_meta($post_id, "pto-kanban-view", "on");
            else
                update_post_meta($post_id, "pto-kanban-view", "off");

            if (isset($_POST['pto-key-view']))
                update_post_meta($post_id, "pto-key-view", "on");
            else
                update_post_meta($post_id, "pto-key-view", "off");
        }
    }

    /**
     *  pto meeting save user store
     */

    function wpnb_pto_meeting_post_save($post_id, $post)
    {
        if ($post->post_type == "pto-meeting") {
            $project_id = "";
            if (isset($_POST['proj_id'])) {
                update_post_meta($post_id, "pto_associated_project_id", intval($_POST['proj_id']));
                $project_id = intval($_POST['proj_id']);
            }
            if (isset($_POST['user'])) {
                $user = filter_var_array($_POST['user']);
                $all_user_ids = array();
                foreach ($user as $u) {
                    $all_user_ids[$u] = sanitize_text_field($u);
                }
                $get_assign = get_post_meta($post_id, 'pto_user_assign_key', true);
                if ($get_assign == "") {
                    $get_assign = array();
                }
                foreach ($all_user_ids as $u) {
                    $get_assign[$u] = $u;
                }
                // Check if the custom field has a value.
                update_post_meta($post_id, "pto_user_assign_key", $get_assign);
            }

            if (isset($_POST['proj_id'])) {
                wp_enqueue_script("jquery-ui-core");
                ?>
                <script type="text/javascript">
                    opener.reload_cpt("pto-meeting", <?php echo "'" . intval($post_id) . "'"; ?>, <?php echo "'" . intval($project_id) . "'"; ?>);
                    window.close();
                </script>
                <?php
                die();
            }
        }
    }


    /**
     *  pto kanban data save 
     */
    public function wpnb_pto_kanban_post_save($post_id, $post)
    {
        if ($post->post_type == "pto-kanban") {
            /* post task status */
            if (isset($_POST['taskstatus'])) {
                update_post_meta($post_id, "pto_kanban_status", sanitize_text_field($_POST['taskstatus']));
            }
            /* user assign store */
            if (isset($_POST['user'])) {
                $user = filter_var_array($_POST['user']);
                $all_user_ids = array();
                foreach ($user as $u) {
                    $all_user_ids[$u] = sanitize_text_field($u);
                }
                $get_assign = get_post_meta($post_id, 'pto_user_assign_key', true);
                if ($get_assign == "") {
                    $get_assign = array();
                }
                foreach ($all_user_ids as $u) {
                    $get_assign[$u] = $u;
                }
                // Check if the custom field has a value.
                update_post_meta($post_id, "pto_user_assign_key", $get_assign);
            }
             $reminder_arr = "";
            $am_pm = "";
            $time = "";
            $date_time_arr = [];
            $project_id = "";
            if (isset($_POST['duedate']) != "" && isset($_POST['time']) != "") {
                $duedate = sanitize_text_field($_POST['duedate']);
                $time = sanitize_text_field($_POST['time']);
                $date_time_arr["due_date"] = $duedate;
                if ($time != "") {
                    $date_time_arr["date_time"] = $time;
                }
                if ($time != "" && $duedate != "") {
                    update_post_meta($post_id, "pto_task_due_date", $date_time_arr);
                }
            }
            
            if (isset($_POST['select_reminder_option'])) {
                $reminder_date = [];
                $reminder_arr = filter_var_array($_POST['select_reminder_option']);
                $cus_check = 0;
                $tmp_arr = [];
                foreach ($reminder_arr as $reminder) {
                    if ($reminder == "custom") {
                        $temp_arr["range"][0] = sanitize_text_field($_POST['custom_range']);
                        $temp_arr["range"][1] = sanitize_text_field($_POST['range_due']);
                    } else {
                        $temp_arr[$reminder] = sanitize_text_field($reminder);
                    }
                }
                update_post_meta($post_id, "pto_task_due_date_time", $temp_arr);
            }
            if (isset($_POST['proj_id'])) {
                $project_id = intval($_POST['proj_id']);
                update_post_meta($post_id, "pto_associated_project_id", intval($_POST['proj_id']));
                wp_enqueue_script("jquery-ui-core");
                ?>
                <script type="text/javascript">
                    opener.reload_cpt("pto-tasks", <?php echo "'" . intval($post_id) . "'"; ?>, <?php echo "'" . intval($project_id) . "'"; ?>);
                    opener.kanbanview_load(<?php echo "'" . intval($project_id) . "'"; ?>);
                    window.close();
                </script>
                <?php
                die();
            }
        }
    }

    /**
     *  pto meeting save user store
     */
    public function wpnb_pto_task_post_save($post_id, $post)
    {
        if ($post->post_type == "pto-tasks") {
            $get_duedate = get_post_meta($post_id, 'pto_task_due_date', true);
            $current_date = date("Y-m-d H:i");
            if (!empty($get_duedate)) {
                $duedate_time = $get_duedate['due_date'] . ' ' . $get_duedate['date_time'];
                $due_date = $get_duedate['due_date'];
                if ((strtotime($duedate_time)) < (strtotime($current_date))) {
                    $get_taskstatus = get_post_meta($post_id, 'pto_task_status', true);
                    if ($get_taskstatus == 'not-started' || $get_taskstatus == 'in-progress' || sanitize_text_field($_POST['taskstatus']) == 'not-started' || sanitize_text_field($_POST['taskstatus']) == 'in-progress') {
                        update_post_meta($post_id, "pto_task_status", 'overdue');
                        $post_meta = "overdue";
                        $_POST['taskstatus'] = "overdue";
                    }
                }
            }
            /* post task status */
            if (isset($_POST['taskstatus'])) {
                update_post_meta($post_id, "pto_task_status", sanitize_text_field($_POST['taskstatus']));
            }
            /* user due date store */
            $reminder_arr = "";
            $am_pm = "";
            $time = "";
            $date_time_arr = [];
            $project_id = "";
            if (isset($_POST['duedate']) != "" && isset($_POST['time']) != "") {
                $duedate = sanitize_text_field($_POST['duedate']);
                $time = sanitize_text_field($_POST['time']);
                $date_time_arr["due_date"] = $duedate;
                if ($time != "") {
                    $date_time_arr["date_time"] = $time;
                }
                if ($time != "" && $duedate != "") {
                    update_post_meta($post_id, "pto_task_due_date", $date_time_arr);
                }
            }
            
            if (isset($_POST['select_reminder_option'])) {
                $reminder_date = [];
                $reminder_arr = filter_var_array($_POST['select_reminder_option']);
                $cus_check = 0;
                $tmp_arr = [];
                foreach ($reminder_arr as $reminder) {
                    if ($reminder == "custom") {
                        $temp_arr["range"][0] = sanitize_text_field($_POST['custom_range']);
                        $temp_arr["range"][1] = sanitize_text_field($_POST['range_due']);
                    } else {
                        $temp_arr[$reminder] = sanitize_text_field($reminder);
                    }
                }

                update_post_meta($post_id, "pto_task_due_date_time", $temp_arr);
            }
            /* user assign store */
            if (isset($_POST['user'])) {
                $user = filter_var_array($_POST['user']);
                $all_user_ids = array();
                foreach ($user as $u) {
                    $all_user_ids[$u] = sanitize_text_field($u);
                }
                $get_assign = get_post_meta($post_id, 'pto_user_assign_key', true);
                if ($get_assign == "") {
                    $get_assign = array();
                }
                foreach ($all_user_ids as $u) {
                    $get_assign[$u] = $u;
                }
                // Check if the custom field has a value.
                update_post_meta($post_id, "pto_user_assign_key", $get_assign);
            }
            if ($post->post_type == "pto-tasks") {
                if (isset($_POST['proj_id'])) {
                    $project_id = intval($_POST['proj_id']);
                    update_post_meta($post_id, "pto_associated_project_id", intval($_POST['proj_id']));
                    wp_enqueue_script("jquery-ui-core");
                    ?>
                    <script type="text/javascript">
                        opener.kanbanview_load(<?php echo "'" . intval($project_id) . "'"; ?>);
                        opener.reload_cpt("pto-tasks", <?php echo "'" . intval($post_id) . "'"; ?>, <?php echo "'" . intval($project_id) . "'"; ?>);
                        window.close();
                    </script>
                    <?php
                    die();
                }
            }
        }
    }

    /**
     *  metting cpt in search user
     */
    public  function wpnb_pto_get_users_data_in_metting()
    {
        if (!wp_verify_nonce($_POST['nonce'], 'ajax-nonce')) {
            die('Busted!');
        }
        include "structure/metting/metting_custome_metabox_user_search.php";
        die();
    }


    /**
     *  metting cpt in search user
     */

    public function wpnb_pto_get_users_data_in_admin()
    {
        if (!wp_verify_nonce($_POST['nonce'], 'ajax-nonce')) {
            die('Busted!');
        }
        include "structure/admin/admin_user_search.php";
        die();
    }

    /**
     *  metting cpt in delete user
     */

    public function wpnb_pto_single_user_delete_in_metting()
    {
        if (!wp_verify_nonce($_POST['nonce'], 'ajax-nonce')) {
            die('Busted!');
        }
        $user_id = intval($_POST['id']);
        $post_id = intval($_POST['post_id']);
        $post_meta = get_post_meta($post_id, 'pto_user_assign_key', true);
        unset($post_meta[$user_id]);
        update_post_meta($post_id, "pto_user_assign_key", $post_meta);
    }

    /**
     *  pto cpt trash 
     */

    public function wpnb_pto_trash_cpt()
    {
        if (!wp_verify_nonce($_POST['nonce'], 'ajax-nonce')) {
            die('Busted!');
        }
        if ($_POST) {
            $post_id = intval($_POST['post_id']);
            wp_trash_post($post_id);
            die();
        }
    }


    /** 
        pto task cpt manage status data 
        store meta  : pto_kanban_status
     **/

        public function wpnb_pto_single_post_task_status()
        {

            if (!wp_verify_nonce($_POST['nonce'], 'ajax-nonce')) {
                die('Busted!');
            }
            $post_id = intval($_POST['post_id']);
            $post_type = get_post_type($post_id);
            if (isset($_POST['status'])) {
                $status = sanitize_text_field($_POST['status']);
            } else {
                $status = "completed";
            }
            if ($post_type == "pto-tasks") {
                $get_taskstatus = update_post_meta($post_id, 'pto_task_status', $status);
            } else {

                update_post_meta($post_id, "pto_kanban_status", $status);
            }
            die();
        }


    /**
     * PTO CPT Drag Update
     * */

    public function wpnb_pto_drag_single_post_task_status()
    {
        if (!wp_verify_nonce($_POST['nonce'], 'ajax-nonce')) {
            die('Busted!');
        }
        $post_id = intval($_POST['post_id']);
        $col_id = intval($_POST['id']);
        $col_val = sanitize_text_field($_POST['vals']);
        $output = array_combine($col_id, $col_val);
        $kanban_col_arr = array();
        $i = 0;
        foreach ($output as $key => $val) {
            $kanban_col_arr[$i] = array(
                $key => $val,
            );
            $i++;
        }
        update_post_meta($post_id, 'pto_kanban_status',  $kanban_col_arr);
        die();
    }
    /** 
        project cpt  templete cretae 
     **/
        public function wpnb_custom_template_include($template)
        {
        // For ID 93, load in file by using it's PATH (not URL)
            global $post;

            if (!empty($post)) {
                if ($post->post_type == "pto-project") {
                    $file = PTO_NB_PLUGIN_PATHS . 'single-pto-project.php';
                    $template = $file;
                }
            }
        // ALWAYS return the $template, or *everything* will be blank.
            return $template;
        }
    }

<?php

/**
 * MYPL class for initiating necessary actions and core functions.
 */

/*
 * Defining Namespace
*/

namespace ptoffice\classes;

class WPNB_Duplicator
{

    /**
    * Constructor for iniation.
    * @since    1.0.0
    * @access   public
    **/
    public function __construct(){
        $this->init();
    }

    /**
    * Initiating necessary functions.
    * @since    1.0.0
    * @access   public
    **/
    public function init(){
        global $post;
        $get_type = "";
        if (isset($_GET['post_type'])) {
            $get_type = sanitize_text_field($_GET['post_type']);
        }
        if ("pto-meeting" === get_post_type() || "pto-note"  === get_post_type() ||  "pto-tasks" === get_post_type() ||  "pto-kanban" === get_post_type() ||  "pto-budget-items" === get_post_type()   ||  "pto-project" === get_post_type() ||  $get_type == "pto-project") {
            add_filter('post_row_actions', array($this, 'wpnb_rd_duplicate_post_link'), 10, 2);
            add_filter('page_row_actions', array($this, 'wpnb_rd_duplicate_post_link'), 10, 2);
        }
        // for "page" post type

        add_action('admin_action_wpnb_rd_duplicate_post_as_draft_project', array($this, 'wpnb_rd_duplicate_post_as_draft_project'));
        add_action('admin_notices', array($this, 'wpnb_rudr_duplication_admin_notice_project'));
    }

    /**
    * Duplicate Post Link
    * @since    1.0.0
    * @access   public
    */
    public function wpnb_rd_duplicate_post_link($actions, $post){
        if (!current_user_can('edit_posts')) {
            return $actions;
        }
        $url = wp_nonce_url(add_query_arg(array(
            'action' => 'wpnb_rd_duplicate_post_as_draft_project',
            'post' => $post->ID,
        ), 'admin.php'), basename(__FILE__), 'duplicate_nonce');
        $actions['duplicate'] = '<a href="' . $url . '" title="Duplicate this item" rel="permalink">Duplicate</a>';
        return $actions;
    }

    /**
    * Duplicate post as draft project
    * @since    1.0.0
    * @access   public
    **/
    public function wpnb_rd_duplicate_post_as_draft_project(){
        global $wpdb;
        // check if post ID has been provided and action
        if (empty($_GET['post'])) {
            wp_die('No post to duplicate has been provided!');
        }

        // Get the original post id
        $post_id = absint($_GET['post']);

        // And all the original post data then
        $post = get_post($post_id);

        /*
        * if you don't want current user to be the new post author,
        * then change next couple of lines to this: $new_post_author = $post->post_author;
        */
        $current_user = wp_get_current_user();
        $new_post_author = $current_user->ID;

        // if post data exists ( I am sure it is, but just in a case), create the post duplicate

        if ($post) {
            // new post data array

            $args = array(
                'comment_status' =>  sanitize_text_field($post->comment_status),
                'ping_status' => sanitize_text_field($post->ping_status),
                'post_author' =>sanitize_text_field( $new_post_author),
                'post_content' =>sanitize_text_field( $post->post_content) ,
                'post_excerpt' => sanitize_text_field($post->post_excerpt) ,
                'post_name' =>  sanitize_text_field($post->post_name) ,
                'post_parent' => sanitize_text_field($post->post_parent),
                'post_password' => sanitize_text_field($post->post_password),
                'post_status' => 'publish',
                'post_title' => sanitize_text_field($post->post_title . " ( Copy)"),
                'post_type' => sanitize_text_field($post->post_type),
                'to_ping' => sanitize_text_field($post->to_ping),
                'menu_order' => sanitize_text_field($post->menu_order)
            );
            // insert the post by wp_insert_post( ) function
            $new_post_id = wp_insert_post($args);
            /*
            * get all current post terms ad set them to the new post draft
            */
            $taxonomies = get_object_taxonomies(get_post_type($post)); // returns array of taxonomy names for post type, ex array( "category", "post_tag");
            if ($taxonomies) {
                foreach ($taxonomies as $taxonomy) {
                    $post_terms = wp_get_object_terms($post_id, $taxonomy, array(
                        'fields' => 'slugs'
                    ));
                    wp_set_object_terms($new_post_id, $post_terms, $taxonomy, false);
                }
            }
            // duplicate all post meta
            $post_meta = get_post_meta($post_id);
            if ($post_meta) {
                foreach ($post_meta as $meta_key => $meta_values) {
                    if ('_wp_old_slug' == $meta_key) { // do nothing for this meta key
                        continue;
                    }
                }
                $post_type = get_post_type($new_post_id);
                if ("pto-meeting" === $post_type || "pto-note"  === $post_type ||  "pto-tasks" === $post_type ||  "pto-kanban" === $post_type ||  "pto-budget-items" === $post_type  ||  "pto-project" === $post_type) {
                    $base_prefix = $wpdb->get_blog_prefix();
                    $post_meta = $base_prefix . "postmeta";
                    $result_attribute = $wpdb->get_results("SELECT '' ," . intval($new_post_id) . ",meta_key, meta_value FROM " .  esc_sql($post_meta) . " WHERE `post_id` =" . intval($post_id));
                    foreach ($result_attribute as $item_arr) {
                        if ($item_arr->meta_key == "cpt_selected_data") {
                        } else if ($item_arr->meta_key == "pto_kanban_status") {
                        } else if ($item_arr->meta_key == "pto_task_due_date") {
                        } else if ($item_arr->meta_key  == "pto_project_user_id") {
                        } else if( $item_arr->meta_key == "pto_sub_menu_cpt_add" ){
                            $sub_post_ids = get_post_meta( $post_id , "pto_sub_menu_cpt_add" ,true ); 
                            if( empty(  $sub_post_ids ) ){
                                 $sub_post_ids = array();
                            }
                            $new_all_post = $this->project_sub_item_duplicate(json_decode($sub_post_ids,true),$new_post_id);
                            update_post_meta( $new_post_id , "pto_sub_menu_cpt_add" , $new_all_post );
                        }else {
                            update_post_meta($new_post_id, "$item_arr->meta_key", "$item_arr->meta_value");
                        }
                    }
                      $wpdb->query($wpdb->prepare("INSERT INTO " . esc_sql($post_meta) . " SELECT '' ," . intval($new_post_id) . ",`meta_key`, `meta_value` FROM " . esc_sql($post_meta) . " WHERE `post_id` = %d AND meta_key = 'pto_sub_menu_cpt_add'",array($post_id)));
                      $wpdb->query($wpdb->prepare("INSERT INTO " . esc_sql($post_meta) . " SELECT '' ," . intval($new_post_id) . ",`meta_key`, `meta_value` FROM " . esc_sql($post_meta) . " WHERE `post_id` = %d AND meta_key = 'pto_user_assign_key'",array($post_id)));
                      
                    $wpdb->query($wpdb->prepare("INSERT INTO " . esc_sql($post_meta) . " SELECT '' ," . intval($new_post_id) . ",`meta_key`, `meta_value` FROM " . esc_sql($post_meta) . " WHERE `post_id` = %d AND meta_key = 'cpt_selected_data'",array($post_id)));
                    $wpdb->query($wpdb->prepare("INSERT INTO " . esc_sql($post_meta) . " SELECT '' ," . intval($new_post_id) . ",meta_key, meta_value FROM " . esc_sql($post_meta) . " WHERE `post_id` =%d AND meta_key ='pto_kanban_status'",array($post_id)));
                    $wpdb->query($wpdb->prepare("INSERT INTO " . esc_sql($post_meta) . " SELECT '' ," . intval($new_post_id) . ",meta_key, meta_value FROM " . esc_sql($post_meta) . " WHERE `post_id`=%d AND meta_key ='pto_task_due_date'",array($post_id)));
                    $wpdb->query($wpdb->prepare("INSERT INTO " . esc_sql($post_meta) . " SELECT '' ," . intval($new_post_id) . ",meta_key, meta_value FROM " . esc_sql($post_meta) . " WHERE `post_id` =%d AND meta_key ='pto_project_user_id'",array($post_id)));
                }
               
            }
            if (isset($_GET['project_id'])) {
                $project_data = get_post_meta(sanitize_text_field($_GET['project_id']), "pto_sub_menu_cpt_add", true);
                $get_assign3 = json_decode($project_data);
                $get_assign3 = (array) $get_assign3;
                $get_assign3[$new_post_id] = $new_post_id;
                $get_assign3 = json_encode($get_assign3);
                update_post_meta(intval($_GET['project_id']), "pto_sub_menu_cpt_add", $get_assign3);
            }
            if (isset($_GET['cpt_type'])) {
                $cpt_type = "'" . sanitize_text_field($_GET['cpt_type']) . "'";
                ?>
                <script type="text/javascript">
                    window.opener.reload_cpt(<?php echo sanitize_text_field($cpt_type); ?>);
                    window.close();
                </script>
                <?php
            } else {
                wp_safe_redirect(add_query_arg(array(
                    'post_type' => ('post' !== get_post_type($post) ? get_post_type($post) : false),
                    'saved' => 'post_duplication_created'
                    // just a custom slug here
                ), admin_url('edit.php')));
            }
            exit;
        } else {
            wp_die('Post creation failed, could not find original post.');
        }
    }

    /**
    * Duplicate admin notice project
    * @since    1.0.0
    * @access   public
    **/
    public function wpnb_rudr_duplication_admin_notice_project(){
        // Get the current screen
        $screen = get_current_screen();
        if ('edit' !== $screen->base) {
            return;
        }
        global $post;
        $get_type = "";
        if (isset($_GET['post_type'])) {
            $get_type = sanitize_text_field($_GET['post_type']);
        }
        if ("pto-meeting" === get_post_type() || "pto-note"  === get_post_type() ||  "pto-tasks" === get_post_type() ||  "pto-kanban" === get_post_type() ||  "pto-budget-items" === get_post_type()   ||  "pto-project" === get_post_type() ||  $get_type == "pto-project") {
            //Checks if settings updated
            if (isset($_GET['saved']) && 'post_duplication_created' == $_GET['saved']) {
                echo '<div class="notice notice-success is-dismissible"><p>Post copy created.</p></div>';
            }
        }
    }
    /**
    * @since    1.0.0
    * @access   public
    * @var Post_ids multiplase id pass
    **/
    public function project_sub_item_duplicate($post_ids,$main_post_id){
        global $wpdb;
        // check if post ID has been provided and action
        if (empty($_GET['post'])) {
            wp_die('No post to duplicate has been provided!');
        }
        $new_allpost_id = array();
        foreach( $post_ids as $id ){
            $post_id = absint($id);
            $post = get_post($post_id);
            $current_user = wp_get_current_user();
            $new_post_author = $current_user->ID;
            if ($post) {
                $args = array(
                    'comment_status' => "'" . $post->comment_status."'",
                    'ping_status' => "'" .$post->ping_status. "'" ,
                    'post_author' => $new_post_author,
                    'post_content' => $post->post_content ,
                    'post_excerpt' => $post->post_excerpt ,
                    'post_name' =>  $post->post_name ,
                    'post_parent' => "'" .$post->post_parent. "'" ,
                    'post_password' =>$post->post_password,
                    'post_status' => 'publish',
                    'post_title' => $post->post_title . " ( Copy)",
                    'post_type' => $post->post_type ,
                    'to_ping' => $post->to_ping,
                    'menu_order' => $post->menu_order
                );
                // insert the post by wp_insert_post( ) function
                $new_post_id = wp_insert_post($args);
                $taxonomies = get_object_taxonomies(get_post_type($post)); // returns array of taxonomy names for post type, ex array( "category", "post_tag");
                if ($taxonomies) {
                    foreach ($taxonomies as $taxonomy) {
                        $post_terms = wp_get_object_terms($post_id, $taxonomy, array(
                            'fields' => 'slugs'
                        ));
                        wp_set_object_terms($new_post_id, $post_terms, $taxonomy, false);
                    }
                }
                // duplicate all post meta
                $post_meta = get_post_meta($post_id);
                if ($post_meta) {
                    foreach ($post_meta as $meta_key => $meta_values) {
                        if ('_wp_old_slug' == $meta_key) { // do nothing for this meta key
                            continue;
                        }
                    }
                    $post_type = get_post_type($new_post_id);
                    if ("pto-meeting" === $post_type || "pto-note"  === $post_type ||  "pto-tasks" === $post_type ||  "pto-kanban" === $post_type ||  "pto-budget-items" === $post_type  ) {
                        $base_prefix = $wpdb->get_blog_prefix();
                        $post_meta = $base_prefix . "postmeta";
                        $sql = "SELECT '' ," . intval($new_post_id) . ",meta_key, meta_value FROM " . esc_sql($post_meta) . " WHERE `post_id` = %d";
                        $sql = $wpdb->prepare($sql, array($post_id));
                        $result_attribute = $wpdb->get_results($sql);
                        foreach ($result_attribute as $item_arr) {
                            if($item_arr->meta_key == "pto_user_assign_key" ){
                                  $wpdb->query($wpdb->prepare("INSERT INTO " . esc_sql($post_meta) . " SELECT '' ," . intval($new_post_id) . ",meta_key, meta_value FROM " . esc_sql($post_meta) . " WHERE `post_id` =%d AND meta_key ='pto_user_assign_key'",array($post_id)));
                            }else if($item_arr->meta_key == "pto_task_due_date"){
                                $wpdb->query($wpdb->prepare("INSERT INTO " . esc_sql($post_meta) . " SELECT '' ," . intval($new_post_id) . ",meta_key, meta_value FROM " . esc_sql($post_meta) . " WHERE `post_id` =%d AND meta_key ='pto_task_due_date'",array($post_id)));
                            }else if($item_arr->meta_key == "pto_associated_project_id" ){
                                update_post_meta($new_post_id, "$item_arr->meta_key", $main_post_id);
                            }else{
                                update_post_meta($new_post_id, "$item_arr->meta_key", "$item_arr->meta_value");
                            }

                        }

                    }
                $new_allpost_id[$new_post_id]= $new_post_id;
                }
            }else{
                
            }
        }
        return json_encode($new_allpost_id);
    }
}

<?php

/**
 * PTO class for initiating necessary actions and core functions.
 */

/*
 * Defining Namespace
*/
/**
 * The core plugin class.
 *
 * @since      1.0.0
 * @package    PTO Notebook
 *
 * @author     MJS Software
 */
namespace ptoffice\classes;

class WPNB_Cptarchive
{
    /*
     * Constructor for iniation.
    */
    public function __construct(){
        $this->init();
    }
    /**
     * Initiating necessary functions
     */
    public function init(){
        /* archive functionality add in custom cpt */
        add_filter('post_row_actions', array($this, 'wpnb_custom_post_action_links'), 10, 2);
        add_action('admin_action_wpnb_pto_archive_project', array($this, 'wpnb_pto_archive_project'));
        add_action('init', array($this, 'rudr_custom_status_creation'));
        add_action('admin_footer-edit.php', array($this, 'wpnb_rudr_status_into_inline_edit'));
        add_action('init', array($this, 'wpnb_my_custom_status_creation'));
        add_action('post_submitbox_misc_actions', array($this, 'wpnb_add_to_post_status_dropdown'));
        add_filter('display_post_states', array($this, 'wpnb_custom_display_post_states'), 10, 2);
    }

    /**
     * Custom display post states
     * @since    1.0.0
     * @access   public
    */
    public function wpnb_custom_display_post_states($states, $post){
          if($post->post_type == "pto-project" || $post->post_type == "pto-note" || $post->post_type == "pto-tasks" || $post->post_type == "pto-meeting" || $post->post_type == "pto-budget-items" || $post->post_type == "pto-kanban"){
            /* Receive the post status object by post status name */
            $post_status_object = get_post_status_object($post->post_status);
    
            /* Checks if the label exists */
            if (in_array($post_status_object->label, $states, true)) {
                return $states;
            }
    
            /* Adds the label of the current post status */
            if ($post_status_object->label == "Archive") {
                $states[$post_status_object->name] = "Archived";
            } else {
    
                $states[$post_status_object->name] = $post_status_object->label;
            }
    
          }
          return $states;
    }



    /* 
    * Archive all functions 
    * @since    1.0.0
    * @access   public
    */

    public function wpnb_my_custom_status_creation(){
        global $post;
        if( !empty( $post ) ){
            if($post->post_type == "pto-project" || $post->post_type == "pto-note" || $post->post_type == "pto-tasks" || $post->post_type == "pto-meeting" || $post->post_type == "pto-budget-items" || $post->post_type == "pto-kanban"){
                register_post_status('Archive', array(
                    'label' => _x('Archive', 'post'),
                    'label_count' => _n_noop('Archive <span class="count">(%s)</span>', 'Archive <span class="count">(%s)</span>'),
                    'public' => true,
                    'exclude_from_search' => false,
                    'show_in_admin_all_list' => true,
                    'show_in_admin_status_list' => true
                ));
            }    
        }
    }

    /**
    * Add to dropdown menu 
    * @since    1.0.0
    * @access   public
    */
    public function wpnb_add_to_post_status_dropdown(){
        global $post;
        if($post->post_type == "pto-project" || $post->post_type == "pto-note" || $post->post_type == "pto-tasks" || $post->post_type == "pto-meeting" || $post->post_type == "pto-budget-items" || $post->post_type == "pto-kanban"){
            $selected = "";
            if ($post->post_status == 'archive') {
                $selected = 'selected';
            }
            $status =   ($post->post_status == 'archive') ? "jQuery( '#post-status-display' ).text( 'Archive' ); jQuery( 
            'select[name=\"post_status\"]' ).val( 'archive');" : '';
            echo "<script>
            jQuery(document).ready( function() {
                jQuery( 'select[name=\"post_status\"]' ).append( '<option value=\"archive\" " . sanitize_text_field($selected) . ">Archive</option>' );
                " . sanitize_text_field($status) . "
                });
                </script>";
        }
    }

    /**
    * Status into inline edit
    * @since    1.0.0
    * @access   public
    */
    public function wpnb_rudr_status_into_inline_edit(){
        // ultra-simple example

        global $post;
        if( !empty( $post ) ){
            if($post->post_type == "pto-project" || $post->post_type == "pto-note" || $post->post_type == "pto-tasks" || $post->post_type == "pto-meeting" || $post->post_type == "pto-budget-items" || $post->post_type == "pto-kanban"){
            $selected = "";
            if (!empty($post)) {
                if ($post->post_status == 'archive') {
                    $selected = 'selected';
                }
            }

            echo "<script>
            jQuery(document).ready( function() {
                jQuery( 'select[name=\"_status\"]' ).append( '<option value=\"archive\" " . sanitize_text_field($selected) . ">Archive</option>' );
                });
                </script>";
            }
        }
        
    }

    /**
    * Custom status creation 
    * @since    1.0.0
    * @access   public
    */
    public function rudr_custom_status_creation(){
        global $post;
        if( !empty( $post ) ){
            if($post->post_type == "pto-project" || $post->post_type == "pto-note" || $post->post_type == "pto-tasks" || $post->post_type == "pto-meeting" || $post->post_type == "pto-budget-items" || $post->post_type == "pto-kanban"){
                register_post_status('Archive', array(
                    'label' => _x('Archive', 'post'), // I used only minimum of parameters
                    'label_count' => _n_noop('Archive <span class="count">(%s)</span>', 'Archive <span class="count">(%s)</span>'),
                    'public' => true
                ));
            }
        }
    }

    /**
    * Custom post action links
    * @since    1.0.0
    * @access   public
    */
    public function wpnb_custom_post_action_links($actions, $post){
        if($post->post_type == "pto-project" || $post->post_type == "pto-note" || $post->post_type == "pto-tasks" || $post->post_type == "pto-meeting" || $post->post_type == "pto-budget-items" || $post->post_type == "pto-kanban"){
            /* check for post type. Here we can also add for any custom post type. */
            $url = wp_nonce_url(add_query_arg(array(
                'action' => 'wpnb_pto_archive_project',
                'post' => $post->ID,
            ), 'admin.php'), basename(__FILE__), 'duplicate_nonce');
    
            if ($post->post_status == "archive") {
                $actions['Unarchive'] = '<a href="' . $url . '&st=1" title="unarchive" rel="permalink">Unarchive</a>';
            } else {
                $actions['archive'] = '<a href="' . $url . '" title="Archive" rel="permalink">Archive</a>';
            }
        }
        return $actions;
    }

    /**
    * PTO archive project
    * @since    1.0.0
    * @access   public
    */
    public function wpnb_pto_archive_project(){
          global $post;
          if( isset($_GET['post']) ){
              $post_id = sanitize_text_field($_GET['post']);
              $post = get_post($post_id);
            }
        if( !empty( $post ) ){
           
            
            if($post->post_type == "pto-project" || $post->post_type == "pto-note" || $post->post_type == "pto-tasks" || $post->post_type == "pto-meeting" || $post->post_type == "pto-budget-items" || $post->post_type == "pto-kanban"){
        $post_id  = sanitize_text_field($_GET['post']);
        if (isset($_GET['st'])) {
            wp_update_post(array(
                'ID'    =>  $post_id,
                'post_status'   =>  'publish'
            ));
            $post = get_post($post_id);
            // let's get a post title by ID
            $type = $post->post_type;
        } else {
            wp_update_post(array(
                'ID'    =>  $post_id,
                'post_status'   =>  'Archive'
            ));
            $post = get_post($post_id);
            // let's get a post title by ID
            $type = $post->post_type;
        }
       
        if (isset($_GET['projetct'])) {
           
            ?>
            <script type="text/javascript">
                window.opener.reload_cpt(<?php echo "'" . sanitize_text_field($type) . "'"; ?>);
                window.close();
            </script>
            <?php
        } else {
            $url = "'edit.php?post_type=$type'";
            ?>
                <script type="text/javascript">
                     window.location.href = <?php echo sanitize_text_field( $url ); ?>;
                </script>
            <?php
        }
            }
        }
    }
    /* end  archive function */
}

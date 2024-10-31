<?php

/**
 * PTO class for initiating necessary actions and core functions.
 */

/*
 * Defining Namespace
*/

namespace ptoffice\classes;

class WPNB_Cptcreate
{
    /*
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
    */
    public function init(){
        /* gutenberg remove */
        add_filter('use_block_editor_for_post_type', array($this, 'wpnb_pto_prefix_disable_gutenberg'), 10, 2);
        /* Meeting cpt */
        add_action('init', array($this, 'wpnb_pto_meeting_post_type'));
        /* Note cpt creat */
        add_action('init', array($this, 'wpnb_pto_note_post_type'));
        /* Task management cpt */
        add_action('init', array($this, 'wpnb_task_manage_post_type'));
        /* Budget management cpt */
        add_action('init', array($this, 'wpnb_budget_budget_post_type'));
        /* texonomy and metting cdn */
        add_action('init', array($this, 'wpnb_pto_note_custom_taxonomy'), 0);
        /* add meta box in metting */
        add_action('add_meta_boxes', array($this, 'wpnb_pto_cpt_metting_in_userdata'));
        /* add metabox in sidebar */
        add_action('add_meta_boxes', array($this, 'wpnb_pto_cpt_tsk_in_cpt_detail_status'));
        /* add meta box in metting */
        add_action('add_meta_boxes', array($this, 'wpnb_pto_cpt_task_in_userdata'));
        /* add meta box in metting */
        add_action('add_meta_boxes', array($this, 'wpnb_pto_cpt_budget_in_userdata'));
        /* add meta notes in metting */
        add_action('add_meta_boxes', array($this, 'wpnb_pto_cpt_notes'));
        add_action('add_meta_boxes', array($this, 'wpnb_pto_cpt_tsk_in_cpt_detail_duedate'));
        /* Meeting cpt */
        add_action('init', array($this, 'wpnb_pto_kanban_cpt'));
    }

    /** 
    * Notes cpt in add meta box 
    * Add project cpt data
    * @since    1.0.0
    * @access   public
    **/
    public function wpnb_pto_cpt_notes(){
        add_meta_box('custom_meta_box-notes-input', 'Project Ids', array($this, 'project_id_hidden'), 'pto-note', 'normal', 'low');
    }

    /** 
    * Notes cpt in add meta box 
    * Hidden  add project cpt data
    * @since    1.0.0
    * @access   public
    **/
    public function project_id_hidden(){
        global $post;
        $project_id = "";
        $post_id = "";
        if (isset($_GET['proj_id'])) {
            $project_id = intval($_GET['proj_id']);
            $post_id = $post->ID;
        } else {
            $post_id = $post->ID;
            $project_id = get_post_meta($post_id, 'pto_associated_project_id', true);
        }
        update_post_meta($post_id, "pto_associated_project_id", $project_id);
        ?>
        <input type="hidden" name="proj_id" value="<?php echo intval($project_id); ?>">
        <?php
    }

    /**
    * Cpt budget type metabox create
    * Budget iteme cpt and Item cpt in add meta box
    * @since    1.0.0
    * @access   public
    **/
    public function wpnb_pto_cpt_budget_in_userdata()
     {
        add_meta_box('custom_meta_box-budget', 'Budget Type', array($this, 'pto_show_side_metabox_type'), 'pto-budget-items', 'side', 'default');
        add_meta_box('custom_meta_box-item-value', 'Item Value', array($this, 'pto_show_side_metabox_type_item_value'), 'pto-budget-items', 'side', 'default');
    }

    /**
    *cpt item value store design
    *include file for html structure
    * @since    1.0.0
    * @access   public
    **/
    public function pto_show_side_metabox_type_item_value(){
        global $post;
        $project_id = "";
        $post_id = "";
        $budget_type_value = "";
        if (isset($_GET['post'])) {
            $post_id = intval($_GET['post']);
            $budget_type_value = get_post_meta($post_id, 'budget_items_type_value', true);
        }
        if (isset($_GET['proj_id'])) {
            $project_id = intval($_GET['proj_id']);
            $post_id = $post->ID;
        } else {
            $post_id = $post->ID;
            $project_id = get_post_meta($post_id, 'pto_associated_project_id', true);
        }
        ?>
        <input type="hidden" name="proj_id" value="<?php echo intval($project_id); ?>">
        <?php
        include "structure/budget/metting_budget_metabox_item_value.php";
    }

    /**
    * Cpt budget type metabox create
    * Budget item cpt meta box structure
    * @since    1.0.0
    * @access   public
    **/
    public function pto_show_side_metabox_type(){
        $budget_type = "";
        if (isset($_GET['post'])) {
            $post_id = intval($_GET['post']);
            $budget_type = get_post_meta($post_id, 'budget_items_type', true);
        }
        include "structure/budget/metting_budget_metabox_type.php";
    }

    /**
    * Cpt task user metabox create
    * @since    1.0.0
    * @access   public
    **/
    public function wpnb_pto_cpt_task_in_userdata(){
        add_meta_box('custom_meta_box-task', 'Assign Users', array($this, 'wpnb_pto_show_custom_meta_box_metting'), 'pto-tasks', 'normal',  'high');
        add_meta_box('custom_meta_box-task', 'Assign Users', array($this, 'wpnb_pto_show_custom_meta_box_kanban'), 'pto-kanban', 'normal',  'high');
        add_meta_box('custom_meta_box-3', 'Task Due Date', array($this, 'wpnb_pto_task_meta_due_date'), 'pto-kanban', 'side', 'default');
    }

    /**
    * Cpt task user metabox create status
    * Add meta due date for project
    * @since    1.0.0
    * @access   public
    **/
    public function wpnb_pto_cpt_tsk_in_cpt_detail_duedate(){
        add_meta_box('custom_meta_box-3', 'Task Due Date', array($this, 'wpnb_pto_task_meta_due_date'), 'pto-tasks', 'side', 'default');
    }

    /**
    * Task meta due date
    * @since    1.0.0
    * @access   public
    */
    public function wpnb_pto_task_meta_due_date(){
        $post_meta = $post_meta2 = array();
        if (isset($_GET['post'])) {
            $post_id =intval( $_GET['post']);
            $post_meta = get_post_meta($post_id, 'pto_task_due_date_time', true);
            $post_meta2 = get_post_meta($post_id, 'pto_task_due_date', true);
        }
        include "structure/task/task_meta_due_date.php";
    }

    /**
    * Cpt task user metabox create due date
    * Kan ban and task cpt status add
    * @since    1.0.0
    * @access   public
    */

    public function wpnb_pto_cpt_tsk_in_cpt_detail_status(){
        add_meta_box('custom_status_task', 'Task Status', array($this, 'pto_task_meta_status'), 'pto-tasks', 'side', 'default');
        add_meta_box('custom_status_task_kanban', 'Task Status', array($this, 'pto_task_meta_status'), 'pto-kanban', 'side', 'default');
    }

    /**
    * Cpt task user metabox create due date
    * Kan ban and task cpt status add display
    * @since    1.0.0
    * @access   public
    **/
    public function pto_task_meta_status(){
        global $post;
        $post_meta = "";
        if (isset($_GET['post'])) {
            if ($post->post_type == "pto-tasks") {
                $post_meta = get_post_meta(sanitize_text_field($_GET['post']), 'pto_task_status', true);
            } else {
                $post_meta = get_post_meta(sanitize_text_field($_GET['post']), 'pto_kanban_status', true);
            }
        }
        include "structure/task/task_meta_status.php";
    }

    /**
    *  Cpt metting user metabox create
    * @since    1.0.0
    * @access   public
    **/
    public function wpnb_pto_cpt_metting_in_userdata(){
        add_meta_box('custom_meta_box-2', 'Attendees',  array($this, 'wpnb_pto_show_custom_meta_box_metting'), 'pto-meeting',  'normal', 'high');
    }


    /**
    * Metting user metabox data show code
    * Cpt in assign user 
    * @since    1.0.0
    * @access   public
    **/

    public function wpnb_pto_show_custom_meta_box_metting(){
        include "structure/metting/metting_custome_metabox_user.php";
    }

    /**
    * @since    1.0.0
    * @access   public
    **/
    public function wpnb_pto_show_custom_meta_box_kanban(){
        include "structure/kanbanview/pto_user_search.php";   
    }


    /**
    * Remmove gutenberg editor
    * @since    1.0.0
    * @access   public
    */
    public function wpnb_pto_prefix_disable_gutenberg($current_status, $post_type){
    }

    /**
    * Metting post cpt create
    * @since    1.0.0
    * @access   public
    */
    public function wpnb_pto_meeting_post_type(){
        /*  get theme name */
        $my_theme = get_option('stylesheet');
        // Set UI labels for Custom Post Type
        $labels = array(
            'name' => _x('Meetings', 'Post Type General Name'),
            'singular_name' => _x('Meeting', 'Post Type Singular Name'),
            'menu_name' => __('Meetings', $my_theme),
            'parent_item_colon' => __('Parent Meeting', $my_theme),
            'all_items' => __('All Meetings', $my_theme),
            'view_item' => __('View Meeting', $my_theme),
            'add_new_item' => __('Add New Meeting', $my_theme),
            'add_new' => __('Add New', $my_theme),
            'edit_item' => __('Edit Meeting', $my_theme),
            'update_item' => __('Update Meeting', $my_theme),
            'search_items' => __('Search Meeting', $my_theme),
            'not_found' => __('Not Found', $my_theme),
            'not_found_in_trash' => __('Not found in Trash', $my_theme),
        );
        // Set other options for Custom Post Type
        $args = array(
            'label' => __('Meeting', $my_theme),
            'description' => __('Meeting news and reviews', $my_theme),
            'labels' => $labels,
            // Features this CPT supports in Post Editor
            'supports' => array(
                'title', 'editor',
            ),
            // You can associate this CPT with a taxonomy or custom taxonomy.
            'taxonomies' => array(
                'genres'
            ),
            /* A hierarchical CPT is like Pages and can have
            * Parent and child items. A non-hierarchical CPT
            * is like Posts.
            */
            'hierarchical' => false,
            'public' => true,
            'show_ui' => true,
            'show_in_menu' => true,
            'show_in_nav_menus' => true,
            'show_in_admin_bar' => true,
            'menu_position' => 5,
            'can_export' => true,
            'has_archive' => true,
            'exclude_from_search' => false,
            'publicly_queryable' => true,
            'capability_type' => 'post',
            'show_in_rest' => true,
            'has_archive' => true,
        );
        // Registering your Custom Post Type
        register_post_type('pto-meeting', $args);
    }


    /**
    *  Cpt note create
    * @since    1.0.0
    * @access   public    
    */
    public function wpnb_pto_note_post_type(){
        /*  get theme name */
        $my_theme = get_option('stylesheet');
        // Set UI labels for Custom Post Type
        $labels = array(
            'name' => _x('Notes', 'Post Type General Name'),
            'singular_name' => _x('note', 'Post Type Singular Name'),
            'menu_name' => __('Notes', $my_theme),
            'parent_item_colon' => __('Parent Note', $my_theme),
            'all_items' => __('All Notes', $my_theme),
            'view_item' => __('View Notes', $my_theme),
            'add_new_item' => __('Add New Note', $my_theme),
            'add_new' => __('Add New', $my_theme),
            'edit_item' => __('Edit Note', $my_theme),
            'update_item' => __('Update Note', $my_theme),
            'search_items' => __('Search Note', $my_theme),
            'not_found' => __('Not Found', $my_theme),
            'not_found_in_trash' => __('Not found in Trash', $my_theme),
        );
        // Set other options for Custom Post Type
        $args = array(
            'label' => __('Notes', $my_theme),
            'description' => __('Note news and reviews', $my_theme),
            'labels' => $labels,
            // Features this CPT supports in Post Editor
            'supports' => array(
                'title', 'editor',
            ),
            // You can associate this CPT with a taxonomy or custom taxonomy.
            'taxonomies' => array(
                'genres'
            ),
            /* A hierarchical CPT is like Pages and can have
            * Parent and child items. A non-hierarchical CPT
            * is like Posts.
            */
            'hierarchical' => false,
            'public' => true,
            'show_ui' => true,
            'show_in_menu' => true,
            'show_in_nav_menus' => true,
            'show_in_admin_bar' => true,
            'menu_position' => 5,
            'can_export' => true,
            'has_archive' => true,
            'exclude_from_search' => false,
            'publicly_queryable' => true,
            'capability_type' => 'post',
            'show_in_rest' => true,
            'has_archive' => true,
        );
        // Registering your Custom Post Type
        register_post_type('pto-note', $args);
    }

    /**
    * pto-note cpt note taxonomy
    * @since    1.0.0
    * @access   public
    */
    public function wpnb_pto_note_custom_taxonomy(){
        $labels = array(
            'name' => _x('Categories', 'taxonomy general name'),
            'singular_name' => _x('Type', 'taxonomy singular name'),
            'search_items' => __('Search Categories'),
            'all_items' => __('All Categories'),
            'parent_item' => __('Parent Type'),
            'parent_item_colon' => __('Parent Type:'),
            'edit_item' => __('Edit Type'),
            'update_item' => __('Update Type'),
            'add_new_item' => __('Add New Type'),
            'new_item_name' => __('New Type Name'),
            'menu_name' => __('Categories'),
        );
        register_taxonomy('NoteCategories', array(
            'pto-note'
        ), array(
            'hierarchical' => true,
            'labels' => $labels,
            'show_ui' => true,
            'show_admin_column' => true,
            'query_var' => true,
            'rewrite' => array(
                'slug' => 'note-categories'
            ),
        ));
    }

    /**
    * Task manage post type
    * @since    1.0.0
    * @access   public
    **/
    public function wpnb_task_manage_post_type(){
        $my_theme = get_option('stylesheet');
        // Set UI labels for Custom Post Type
        $labels = array(
            'name' => _x('Tasks', 'Post Type General Name'),
            'singular_name' => _x('Task', 'Post Type Singular Name'),
            'menu_name' => __('Tasks', $my_theme),
            'parent_item_colon' => __('Parent task', $my_theme),
            'all_items' => __('All Tasks', $my_theme),
            'view_item' => __('View Tasks', $my_theme),
            'add_new_item' => __('Add New task', $my_theme),
            'add_new' => __('Add New', $my_theme),
            'edit_item' => __('Edit task', $my_theme),
            'update_item' => __('Update task', $my_theme),
            'search_items' => __('Search task', $my_theme),
            'not_found' => __('Not Found', $my_theme),
            'not_found_in_trash' => __('Not found in Trash', $my_theme),
        );

        // Set other options for Custom Post Type
        $args = array(
            'label' => __('Tasks', $my_theme),
            'description' => __('task news and reviews', $my_theme),
            'labels' => $labels,
            // Features this CPT supports in Post Editor
            'supports' => array(
                'title',
                'editor',

            ),
            // You can associate this CPT with a taxonomy or custom taxonomy.
            'taxonomies' => array(
                'genres'
            ),
            /* A hierarchical CPT is like Pages and can have
                * Parent and child items. A non-hierarchical CPT
                * is like Posts.
            */
            'hierarchical' => false,
            'public' => true,
            'show_ui' => true,
            'show_in_menu' => true,
            'show_in_nav_menus' => true,
            'show_in_admin_bar' => true,
            'menu_position' => 5,
            'can_export' => true,
            'has_archive' => true,
            'exclude_from_search' => false,
            'publicly_queryable' => true,
            'capability_type' => 'post',
            'show_in_rest' => true,
            'has_archive' => true,

        );

        // Registering your Custom Post Type
        register_post_type('pto-tasks', $args);
    }


    /**
    * Create budget post type
    * @since    1.0.0
    * @access   public
    **/
    public function wpnb_budget_budget_post_type(){
        $my_theme = get_option('stylesheet');
        // Set UI labels for Custom Post Type
        $labels = array(
            'name' => _x('Budget Items', 'Post Type General Name'),
            'singular_name' => _x('budget', 'Post Type Singular Name'),
            'menu_name' => __('Budget Items', $my_theme),
            'parent_item_colon' => __('Parent budget', $my_theme),
            'all_items' => __('All Budget Items', $my_theme),
            'view_item' => __('View Budget Items', $my_theme),
            'add_new_item' => __('Add New budget', $my_theme),
            'add_new' => __('Add New', $my_theme),
            'edit_item' => __('Edit budget', $my_theme),
            'update_item' => __('Update budget', $my_theme),
            'search_items' => __('Search budget', $my_theme),
            'not_found' => __('Not Found', $my_theme),
            'not_found_in_trash' => __('Not found in Trash', $my_theme),
        );

        // Set other options for Custom Post Type
        $args = array(
            'label' => __('Budget Items', $my_theme),
            'description' => __('budget news and reviews', $my_theme),
            'labels' => $labels,
            // Features this CPT supports in Post Editor
            'supports' => array(
                'title',
                'editor',

            ),
            // You can associate this CPT with a taxonomy or custom taxonomy.
            'taxonomies' => array(
                'genres'
            ),
            /* A hierarchical CPT is like Pages and can have
            * Parent and child items. A non-hierarchical CPT
            * is like Posts.
            */
            'hierarchical' => false,
            'public' => true,
            'show_ui' => true,
            'show_in_menu' => true,
            'show_in_nav_menus' => true,
            'show_in_admin_bar' => true,
            'menu_position' => 5,
            'can_export' => true,
            'has_archive' => true,
            'exclude_from_search' => false,
            'publicly_queryable' => true,
            'capability_type' => 'post',
            'show_in_rest' => true,
            'has_archive' => true,
        );
        // Registering your Custom Post Type
        register_post_type('pto-budget-items', $args);
    }

    /**
    * PTO Kanban CPT
    * @since    1.0.0
    * @access   public
    */
    public function wpnb_pto_kanban_cpt(){
        $my_theme = get_option('stylesheet');
        // Set UI labels for Custom Post Type
        $labels = array(
            'name' => _x('Kanban', 'Post Type General Name'),
            'singular_name' => _x('Task', 'Post Type Singular Name'),
            'menu_name' => __('Kanban', $my_theme),
            'parent_item_colon' => __('Parent task', $my_theme),
            'all_items' => __('All Kanban', $my_theme),
            'view_item' => __('View Kanban', $my_theme),
            'add_new_item' => __('Add New task', $my_theme),
            'add_new' => __('Add New', $my_theme),
            'edit_item' => __('Edit task', $my_theme),
            'update_item' => __('Update task', $my_theme),
            'search_items' => __('Search task', $my_theme),
            'not_found' => __('Not Found', $my_theme),
            'not_found_in_trash' => __('Not found in Trash', $my_theme),
        );

        // Set other options for Custom Post Type
        $args = array(
            'label' => __('Kanban', $my_theme),
            'description' => __('task news and reviews', $my_theme),
            'labels' => $labels,
            // Features this CPT supports in Post Editor
            'supports' => array(
                'title',
                'editor',

            ),
            // You can associate this CPT with a taxonomy or custom taxonomy.
            'taxonomies' => array(
                'genres'
            ),
            /* A hierarchical CPT is like Pages and can have
            * Parent and child items. A non-hierarchical CPT
            * is like Posts.
            */
            'hierarchical' => false,
            'public' => true,
            'show_ui' => true,
            'show_in_menu' => true,
            'show_in_nav_menus' => true,
            'show_in_admin_bar' => true,
            'menu_position' => 5,
            'can_export' => true,
            'has_archive' => true,
            'exclude_from_search' => false,
            'publicly_queryable' => true,
            'capability_type' => 'post',
            'show_in_rest' => true,
            'has_archive' => true,

        );

        // Registering your Custom Post Type
        register_post_type('pto-kanban', $args);
    }
}

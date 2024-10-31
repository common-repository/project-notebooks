<?php
/**
 * PTO class for initiating necessary actions and core functions.
 */

/*
 * Defining Namespace
*/
namespace ptoffice\classes;

class WPNB_ProjectCptCreate
{
    /**
    * Constructor for iniation.
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
        /* project cpt in all metaboxes add */
        add_action('add_meta_boxes' , array( $this ,'wpnb_pto_cpt_all_meta_boxes' ) ) ;

        /* cpt created */
        add_action('init' , array( $this ,'wpnb_project_post_type' ) ) ;
        add_action('init' , array( $this ,'wpnb_pto_project_custom_taxonomy') , 0);

        /*admin submenu add in project */
        add_action('admin_menu' , array( $this ,'wpnb_add_submenu_page_to_post_type' ) ) ;

         /* rewrite rule flush */
         add_action( 'wp_loaded',  array( $this, 'pto_sign_up_flush_rewrite_rules' ) );

        /* project inside cpt render */
        add_action('wp_ajax_nopriv_wpnb_render_meta_box_content_metting' , array( $this ,'wpnb_render_meta_box_content_metting' ) ) ;
        add_action('wp_ajax_wpnb_render_meta_box_content_metting' , array( $this ,'wpnb_render_meta_box_content_metting' ) ) ;

        /* note cpt render */
        add_action('wp_ajax_nopriv_wpnb_render_meta_box_content_notes' , array( $this ,'wpnb_render_meta_box_content_notes' ) ) ;
        add_action('wp_ajax_wpnb_render_meta_box_content_notes' , array( $this ,'wpnb_render_meta_box_content_notes' ) ) ;

        /* task cpt render */
        add_action('wp_ajax_nopriv_wpnb_render_meta_box_content_tasks' , array( $this ,'wpnb_render_meta_box_content_tasks' ) ) ;
        add_action('wp_ajax_wpnb_render_meta_box_content_tasks' , array( $this ,'wpnb_render_meta_box_content_tasks' ) ) ;

        /* budget cpt render */
        add_action('wp_ajax_nopriv_wpnb_render_meta_box_content_budgets' , array( $this ,'wpnb_render_meta_box_content_budgets' ) ) ;
        add_action('wp_ajax_wpnb_render_meta_box_content_budgets' , array( $this ,'wpnb_render_meta_box_content_budgets' ) ) ;
        
        /* get all user for persnal cpt */
        add_action('wp_ajax_nopriv_wpnb_get_all_pm_users' , array( $this ,'wpnb_get_all_pm_users' ) ) ;
        add_action('wp_ajax_wpnb_get_all_pm_users' , array( $this ,'wpnb_get_all_pm_users' ) ) ;

        /* get all project manager data */
        add_action('wp_ajax_nopriv_wpnb_get_all_project_manager' , array( $this ,'wpnb_get_all_project_manager' ) ) ;
        add_action('wp_ajax_wpnb_get_all_project_manager' , array( $this ,'wpnb_get_all_project_manager' ) ) ;

        /* delet pm post */ 
        add_action('wp_ajax_nopriv_wpnb_delete_pmuser_from_post' , array( $this ,'wpnb_delete_pmuser_from_post' ) ) ;
        add_action('wp_ajax_wpnb_delete_pmuser_from_post' , array( $this ,'wpnb_delete_pmuser_from_post' ) ) ;
        
        /* get all request project in setting tab */
        add_action('wp_ajax_nopriv_wpnb_get_all_pto_request_projects' , array( $this ,'wpnb_get_all_pto_request_projects' ) ) ;
        add_action('wp_ajax_wpnb_get_all_pto_request_projects' , array( $this ,'wpnb_get_all_pto_request_projects' ) ) ;

        /* project request accept for seting */
        add_action('wp_ajax_nopriv_wpnb_get_pto_request_projects_accept' , array( $this ,'wpnb_get_pto_request_projects_accept' ) ) ;
        add_action('wp_ajax_wpnb_get_pto_request_projects_accept' , array( $this ,'wpnb_get_pto_request_projects_accept' ) ) ;

        /* project request decline for seting */
        add_action('wp_ajax_nopriv_wpnb_get_pto_request_projects_decline' , array( $this ,'wpnb_get_pto_request_projects_decline' ) ) ;
        add_action('wp_ajax_wpnb_get_pto_request_projects_decline' , array( $this ,'wpnb_get_pto_request_projects_decline' ) ) ;
        
        /* project cpt key meta box */
        add_action('add_meta_boxes' , array( $this ,'wpnb_pto_cpt_project_in_key_information'),111);

        /* kanban view */ 
        add_action('wp_ajax_nopriv_wpnb_task_kanban_view' , array( $this ,'wpnb_task_kanban_view' ) ) ;
        add_action('wp_ajax_wpnb_task_kanban_view' , array( $this ,'wpnb_task_kanban_view' ) ) ;

        /* kanban view data store */
        add_action('wp_ajax_nopriv_wpnb_task_kanban_view_status' , array( $this ,'wpnb_task_kanban_view_status' ) ) ;
        add_action('wp_ajax_wpnb_task_kanban_view_status' , array( $this ,'wpnb_task_kanban_view_status' ) ) ;

        /* status key remove */
        add_action('wp_ajax_nopriv_wpnb_task_kanban_view_status_delete' , array( $this ,'wpnb_task_kanban_view_status_delete' ) ) ;
        add_action('wp_ajax_wpnb_task_kanban_view_status_delete' , array( $this ,'wpnb_task_kanban_view_status_delete' ) ) ;

        /* popups design */
        add_action('admin_footer' , array($this ,'wpnb_my_admin_footer_function_popups' ) ) ; 
        add_action('add_meta_boxes' ,  array($this ,'wpnb_add_custom_meta_boxes' ) ) ; 

        /* project attechment key remove */
        add_action('wp_ajax_nopriv_wpnb_cpt_project_attechment' , array( $this ,'wpnb_cpt_project_attechment' ) ) ;
        add_action('wp_ajax_wpnb_cpt_project_attechment' , array( $this ,'wpnb_cpt_project_attechment' ) ) ; 

        /* pto post restore data */
        add_action('wp_ajax_nopriv_wpnb_pto_restore_cpt_project' , array( $this ,'wpnb_pto_restore_cpt_project' ) ) ;
        add_action('wp_ajax_wpnb_pto_restore_cpt_project' , array( $this ,'wpnb_pto_restore_cpt_project' ) ) ; 
        
        /* feture meta iamge boxes */
        add_action('do_meta_boxes' , array( $this ,'wpnb_km_change_featured_image_metabox_title' ));

        /* update task show data */
        add_action('wp_ajax_nopriv_wpnb_update_task_show_data' , array( $this ,'wpnb_update_task_show_data' ) ) ;
        add_action('wp_ajax_wpnb_update_task_show_data' , array( $this ,'wpnb_update_task_show_data' ) ) ;

        /* body in class add*/
        add_filter('admin_body_class' , array( $this ,'wpnb_pto_admin_body_class' ) );

        /* user capbility add */
        add_action("init", array($this,"wpnb_pto_project_usercap_add"));
        add_action( 'wp_loaded',  array($this, 'flush_rewrite_rules'));
    }

    /**
    * cpt restore for project inner cpt
    * Meta key : pto_sub_menu_cpt_add
    * @since    1.0.0
    * @access   public
    **/
    public function wpnb_pto_restore_cpt_project(){
        if ( ! wp_verify_nonce( $_POST['nonce'], 'ajax-nonce' ) ) {
            die ( 'Busted!');
        }
        if( $_POST ){
            if( isset( $_POST['restore_id'] ) ){
                wp_publish_post(sanitize_text_field($_POST['restore_id']));
            }
            if( isset( $_POST['delet_id'] ) ){
             $project_id = sanitize_text_field($_POST['project_id']);
             $all_post_data = get_post_meta( $project_id , "pto_sub_menu_cpt_add" , true );
             $all_post_data = json_decode( $all_post_data );
             $all_post_data = (array) $all_post_data; 
             unset( $all_post_data[ sanitize_text_field($_POST['delet_id'])] );
             $get_assign3 = json_encode( $all_post_data );
             update_post_meta( $project_id , "pto_sub_menu_cpt_add" , $get_assign3 );
             wp_delete_post( sanitize_text_field($_POST['delet_id']) );
            }
        }
    }


    /**
    * Rewrite rule flush
    * @since    1.0.0
    * @access   public
    **/
    public function pto_sign_up_flush_rewrite_rules() {
        if ( ! get_option( 'notebook_flush_rewrite_rules_flag' ) ) {
            add_option( 'notebook_flush_rewrite_rules_flag', true );
            flush_rewrite_rules();
        }
    }
    

    /**
    * Fontend side body in class add 
    * @since    1.0.0
    * @access   public
    **/
    public function wpnb_pto_admin_body_class( $classes ){
        global $post;
        if($post !=  ""){
            if($post->post_type == "pto-project" || $post->post_type == "pto-note" || $post->post_type == "pto-tasks" || $post->post_type == "pto-meeting" || $post->post_type == "pto-budget-items" || $post->post_type == "pto-kanban"){
                $classes .= "pto-custom-style";
            }
        }
        return $classes;
    }

    /**
    * Project cpt attechment add 
    * Meta  Key  : cpt_project_all_attech
    * @since    1.0.0
    * @access   public
    **/ 
    public function wpnb_cpt_project_attechment(){
        if ( ! wp_verify_nonce( $_POST['nonce'], 'ajax-nonce' ) ) {
            die ( 'Busted!');
        }
        // file upload code
        if( isset( $_POST['imag_name'] ) ){
            $all_attech= get_post_meta( intval($_POST['post_id']) , "cpt_project_all_attech" , true );
            $all_attech = explode( "," , $all_attech );
            $all_images_data = "";
            $post_id = sanitize_text_field($_POST['post_id']);
            foreach( $all_attech as $imgname ){
                if( $imgname != $_POST['imag_name'] ){
                    $all_images_data .= $imgname . ",";
                }
            }
            $newarraynama = rtrim( $all_images_data , "," );
            // print_r($newarraynama);
            update_post_meta( $post_id , "cpt_project_all_attech" , $newarraynama ); 
        }
        die();
    }
    
    /** 
    * Project attechment metaboxes code added  
    * @since    1.0.0
    * @access   public
    */
    public function wpnb_add_custom_meta_boxes() { 
        add_meta_box('wp_custom_attachment' ,'Project Attachments' , array( $this ,'wp_custom_attachment' ),'pto-project' ,'side' ,'low' );  
    }

    /**
    * Project attechment code added  
    * @since    1.0.0
    * @access   public
    **/
    public function wp_custom_attachment() {  
        include "structure/project/pto_project_attechment.php";
    }

    /**
    * Kanbanview project cpt in status key deleted 
    * @since    1.0.0
    * @access   public
    **/
    public function wpnb_task_kanban_view_status_delete(){
        if ( ! wp_verify_nonce( $_POST['nonce'], 'ajax-nonce' ) ) {
            die ( 'Busted!');
        }
        $po_id = sanitize_text_field($_POST['po_id']);
        $no_exists_value = get_post_meta( $po_id ,'pto_kanban_status' , true );
        if( isset( $_POST['status_key'] ) ){
            $i = 0;
            $arr_temp =array();
            foreach( $no_exists_value as $data ){
                if( !$data[ $_POST['status_key'] ]){
                    $arr_temp[ $i ] = $data;
                }
                $i++;
            }
            update_post_meta( $po_id ,'pto_kanban_status' ,  $arr_temp , "" , "yes" );
        }
        echo json_encode($arr_temp);
        die();
    }
    

    /**
    * Popup design in footer 
    * @since    1.0.0
    * @access   public
    **/
    public function wpnb_my_admin_footer_function_popups() {
        include "structure/adminfooter/pto_admin-footer.php";
    }

    /**
    * Kanban status changes 
    * @since    1.0.0
    * @access   public
    **/
    public function wpnb_task_kanban_view_status(){
        $post_id = sanitize_text_field($_POST['post_id']);
        $no_exists_value  = array();
        if(isset($_POST['pto_old_status'])){ 
            $data = filter_var_array($_POST['pto_old_status']);
            $i = 0;
            foreach( $data as $keyval ){
                foreach( $keyval as $key => $status ){
                    $key_status = strtolower( $status );
                    $key_status = str_replace( " " , "-" , $key_status );
                    $no_exists_value[ $i ][ $key_status ]= sanitize_text_field($status);
                    $i++;
                }
            }
        }
        if(isset($_POST['status_val'])){
            if( empty( $no_exists_value ) ){
                $status = sanitize_text_field($_POST['status_val']);
                $key_status = strtolower( sanitize_text_field($_POST['status_val']) );
                $key_status = str_replace( " " , "-" , $key_status );
                $no_exists_value = get_post_meta( $post_id ,'pto_kanban_status' , true );
                if( !isset( $no_exists_value[ $key_status ] ) ){                    
                    $no_exists_value[ $i ][ $key_status ] = $status;
                }
            }else{
                if( $_POST['status_val'] != "" ){
                    $status = sanitize_text_field($_POST['status_val']);
                    $key_status = strtolower( sanitize_text_field($_POST['status_val']) );
                    $key_status = str_replace( " " , "-" , $key_status );
                    if( !isset( $no_exists_value[ $key_status ] ) ){                    
                        $no_exists_value[ $i ][ $key_status ] = $status;
                    }
                }
            }
        }
        $data =  update_post_meta( $post_id ,'pto_kanban_status' ,  $no_exists_value , "" , "yes" );
        echo json_encode($no_exists_value);
        die();
    }
    /**
    * Pto all meta boxeds 
    * @since    1.0.0
    * @access   public
    **/
    public function wpnb_pto_cpt_all_meta_boxes(){
        /* budgets cpts metabox  */
        add_meta_box('custom_meta_box-buget-items' , // $id
         "Budget <i class='fa fa-info-circle fas-tooltip' title='Manage your project’s budget with this simple to use `Budget` section. Keep track of all your expenses (and income) from year to year, so you know what and how much to budget for next year'></i>" , // 
            array( $this ,'wpnb_render_meta_box_content_budgets') , // $callback
           'pto-project' , // $page
           'normal' , // $context
           'low'// $priority
       );   
        /* metting cpts metabox  */
        add_meta_box('custom_meta_box-metting' , // $id
           'Meeting Minutes <i class="fa fa-info-circle fas-tooltip" title="Record all your meeting minutes and display them publicly on your website using the `Show meeting minutes tab on public view` checkbox."></i>' , // $title
            array( $this ,'wpnb_render_meta_box_content_metting') , // $callback
           'pto-project' , // $page
           'normal' , // $context
           'low'// $priority
       );

        /* task cpts metabox  */
        add_meta_box('custom_meta_box-tasks' , // $id
           "Tasks <i class='fa fa-info-circle fas-tooltip ' title='Keep track of all to-do`s - complete with assigned users, due dates and more!'></i>" , // $title
            array( $this ,'wpnb_render_meta_box_content_tasks') , // $callback
           'pto-project' , // $page
           'normal' , // $context
           'low'// $priority
       );

        /* notes cpts metabox  */
        add_meta_box('custom_meta_box-notes' , // $id
         'Notes <i class="fa fa-info-circle fas-tooltip" title="Want to write down some general notes for your project or commitee? Keep tabs on everything with this `Notes` section."></i>' , // $title
            array( $this ,'wpnb_render_meta_box_content_notes') , // $callback
           'pto-project' , // $page
           'normal' , // $context
           'low'// $priority
       );

        /* choose cpts metabox  */
        add_meta_box('custom_meta_box-list' , // $id
         'Choose the options for this project <i class="fa fa-info-circle fas-tooltip" title="Build your own custom notebook by selecting only those sections that matter for your specific notebook."></i>' , // $title
            array( $this ,'pto_cpt_project_meta_choose') , // $callback
           'pto-project' , // $page
           'normal' , // $context
           'high'// $priority
       );   

        /* kanban cpts metabox  */       
        add_meta_box('custom_meta_box-notes-kanban' , // $id
           'Kanban Board <i class="fa fa-info-circle fas-tooltip" title="The `Kanban Board` is great way to set, track and view milestones while you run your project. Simply click and drag created items along your customizable stages."></i>' , // $title
            array( $this ,'wpnb_task_kanban_view') , // $callback
           'pto-project' , // $page
           'normal' , // $context      
           'low'// $priority
       );
        /* duedate cpts metabox  */   
        add_meta_box('custom_meta_project_due_date' , // $id
           'Project Due Date ' , // $title
            array( $this ,'pto_project_due_date') , // $callback
           'pto-project' , // $page
           'side' , // $context      
           'low'// $priority
       );

        add_meta_box('custom_meta_project_manager' , // $id
           'Project Manager <i class="fa fa-info-circle fas-tooltip" title="Allow others to help you manage your project notebook here. Users assigned here will only have access to this specific project notebook."></i>' , // $title
            array( $this ,'pto_project_manager') , // $callback
           'pto-project' , // $page
           'normal' , // $context      
           'low'// $priority
       );

        add_meta_box('custom_meta_project_show_setting' , // $id
           'Project Display Setting' , // $title
            array( $this ,'wpnb_pto_project_show_setting') , // $callback
           'pto-project' , // $page
           'side' , // $context      
           'low'// $priority
       );
    }

    /** 
    * @since    1.0.0
    * @access   public
    **/
    public function pto_project_due_date(){
        global $post;
        $post_id =  $post->ID;
        $get_project_due_date = get_post_meta( $post_id , "pto_project_due_date" , "true" );
        $c_date = date( "m/d/Y" );
        ?>
        <span class='set-project-due-date'>Set Project Due Date</span>
        <input type="text" class="datepicker" name="pto_project_due_date"  value="<?php echo esc_html($get_project_due_date); ?>" placeholder="mm/dd/yyyy" autocomplete='nope'>
        <?php
    }
    /**
    * Kanban view 
    * @since    1.0.0
    * @access   public
    **/
    public function wpnb_task_kanban_view(){
        if( isset( $_POST['project_id'] ) ){

            $post_id = sanitize_text_field($_POST['project_id']);
        }else{
            global $post;
            $post_id = $post->ID;
        }   
        include "structure/kanbanview/kanbanview_board.php";
    }
    
    /**
    *  Project cpt create
    * @since    1.0.0
    * @access   public
    */
    public function wpnb_project_post_type(){

        $my_theme = get_option('stylesheet');
        $notbook_icon = PTO_NB_MYPL_PLUGIN_BASEDIR_PATH . "/assets/images/notbook_icon.png";
        // Set UI labels for Custom Post Type
        $labels = array(
            'name' => _x('Project Notebooks' ,'Post Type General Name') ,
            'singular_name' => _x('Project' ,'Post Type Singular Name') ,
            'menu_name' => __('Project Notebooks' , $my_theme) ,
            'parent_item_colon' => __('Parent Project' , $my_theme) ,
            'all_items' => __('All Project Notebooks' , $my_theme) ,
            'view_item' => __('View Project Notebooks' , $my_theme) ,
            'add_new_item' => __('Add New Project Notebook' , $my_theme) ,
            'add_new' => __('Add New' , $my_theme) ,
            'edit_item' => __('Edit Project Notebook' , $my_theme) ,
            'update_item' => __('Update Project Notebook' , $my_theme) ,
            'search_items' => __('Search Project Notebook' , $my_theme) ,
            'not_found' => __('Not Found' , $my_theme) ,
            'not_found_in_trash' => __('Not found in Trash' , $my_theme) ,
            'set_featured_image'       => "Set banner image",
            'remove_featured_image'    => "Remove banner image",

        );
        // Set other options for Custom Post Type
        $args = array(
           'label' => __('Pto Project' , $my_theme) ,
           'description' => __('Project news and reviews' , $my_theme) ,
           'labels' => $labels,
            // Features this CPT supports in Post Editor
           'supports' => array(
               'title' ,
               'thumbnail' ,
               'author'
           ) ,
            // You can associate this CPT with a taxonomy or custom taxonomy.
           'taxonomies' => array(
               'themes_categories'
           ) ,
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
            'capability_type' =>'post' ,
            'show_in_rest' => true,
            'has_archive' => true,
            'menu_icon' => $notbook_icon,
        );
        // Registering your Custom Post Type
        register_post_type('pto-project' , $args);
    }
    
    /**
    *  Project cpt taxonomy
    * @since    1.0.0
    * @access   public
    */
    public function wpnb_pto_project_custom_taxonomy(){
        $labels = array(
           'name' => _x('Categories' ,'taxonomy general name') ,
           'singular_name' => _x('Type' ,'taxonomy singular name') ,
           'search_items' => __('Search Categories') ,
           'all_items' => __('All Categories') ,
           'parent_item' => __('Parent Type') ,
           'parent_item_colon' => __('Parent Type:') ,
           'edit_item' => __('Edit Type') ,
           'update_item' => __('Update Type') ,
           'add_new_item' => __('Add New Categories') ,
           'new_item_name' => __('New Type Name') ,
           'menu_name' => __('Categories') ,
       );
        register_taxonomy('project-categories' , array(
           'pto-project'
       ) , array(
           'hierarchical' => true,
           'labels' => $labels,
           'show_ui' => true,
           'show_admin_column' => true,
           'query_var' => true,
           'rewrite' => array(
               'slug' =>'project-categories'
           ) ,
       ));
    }

    /**
    * Project cpt in add project setting tab in admin bar
    * @since    1.0.0
    * @access   public
    */
    public function wpnb_add_submenu_page_to_post_type(){
        add_submenu_page('edit.php?post_type=pto-project' , __('Project Setting' ,'rushhour') , __('Project Notebooks Settings' ,'rushhour') ,'manage_options' ,'projects_archive' , array(
            $this,
            'pto_project_setting'
        ));
        add_submenu_page('edit.php?post_type=pto-project' , __('Project Request' ,'pto-plugin-planner') , __('Project Notebooks Requests' ,'pto-plugin-planner') ,'manage_options' ,'' , array(
            $this,
            'pto_project_request_setting'
        ));
    }

    /**
    *Render meta metting box 
    * @since    1.0.0
    * @access   public
    **/
    public function wpnb_render_meta_box_content_metting(){
        global $wpdb;
        $get_assign2 = "";
        $sub_cpt_data  = "";
        $db_name =  $wpdb->prefix."postmeta";
        
        $status =  $publish_date = "";
        if(isset($_POST['status'])){
            $status = sanitize_text_field($_POST['status']);
        }
        if(isset($_POST['publish_date'])){
            $publish_date = sanitize_text_field($_POST['publish_date']);
        }
        if(isset($_POST['project_id'])){
            $sql = "select * from $db_name where post_id = %d";
            $sql = $wpdb->prepare($sql, array(sanitize_text_field($_POST['project_id'])));
            $meta_data = $wpdb->get_results( $sql );
            foreach( $meta_data as $meta_key ){
                if( $meta_key->meta_key ){
                    $key = $meta_key->meta_key;
                    if( $key == "pto_sub_menu_cpt_add" ){
                        $get_assign2 = $meta_key->meta_value; 
                    }  
                }
            }
        }
        if( $get_assign2 != "" ){
            $get_assign3 = json_decode( $get_assign2 );
            $get_assign3 = (array) $get_assign3;    
            if( !in_array( $_POST['post_id'] , $get_assign3 ) ){
                $get_assign3[sanitize_text_field($_POST['post_id'])] = sanitize_text_field($_POST['post_id']);
                $cpt_id = json_encode( $get_assign3 );
                update_post_meta( intval($_POST['project_id']) , "pto_sub_menu_cpt_add" , $cpt_id );  
            }       
        }else{
            if( isset($_POST['post_id'] ) ){    
                $cpt_id = array();
                $cpt_id[sanitize_text_field($_POST['post_id'])]= sanitize_text_field($_POST['post_id']); 
                $cpt_id = json_encode( $cpt_id );
                update_post_meta( intval($_POST['project_id']) , "pto_sub_menu_cpt_add" , $cpt_id ); 
            }   
        }
        global $post;
        if (isset( $_POST['id'] ) ){
            $post_id = intval($_POST['id']);
        }
        else{
            $post_id = $post->ID;
        }
        if(empty($status)){
            $status = "publish";    
        }
        $post_type = "pto-meeting";
        $filer_arr = array(
           'post_type' => $post_type,
           'filter' => array(
            "metadata" => array(
                "pto_associated_project_id" => $post_id,
            )
        ) ,
           'fields' => array(
               'main_fields' => array(
                "title" => "Meeting Name ",
                "date" => "Date"
            ) ,
               'meta_key' => array(
                "pto_user_assign_key" => "Attendees"
            ) ,
           ),
           'rules' => array(
            "status" => $status,
            'publish_date' => $publish_date
        )
       );
       ?>
       <div id="<?php echo esc_html($post_type); ?>">
        <?php
        include_once ('ptoffice_cptlist.php');
        new WPNB_PtoCptList($filer_arr);
        ?>
    </div>
    <?php
    if (isset($_POST['id']))
    {
        die();
    }
}

    /** 
    * Notes render for project cpts
    * @since    1.0.0
    * @access   public
    **/
    public function wpnb_render_meta_box_content_notes(){
        global $wpdb;
        $get_assign2 = "";
        $sub_cpt_data  = "";
        $db_name =  $wpdb->prefix."postmeta";        
        if( isset( $_POST['project_id'] ) ){
            $sql = "select * from $db_name where post_id = %d";
            $sql = $wpdb->prepare($sql, array(intval($_POST['project_id'])));
            $meta_data = $wpdb->get_results( $sql );
            foreach( $meta_data as $meta_key ){
                if( $meta_key->meta_key ){
                    $key = $meta_key->meta_key;
                    if( $key == "pto_sub_menu_cpt_add" ){
                        $get_assign2 = $meta_key->meta_value; 
                    } 
                }
            }
        }
        $status =  $publish_date = "";
        if( isset( $_POST['status'] ) ){
            $status = sanitize_text_field($_POST['status']);
        }
        if( $get_assign2 != "" ){
            $get_assign3 = json_decode( $get_assign2 );
            $get_assign3 = (array) $get_assign3;              
            if( !in_array( $_POST['post_id'] , $get_assign3 ) ){
                $get_assign3[$_POST['post_id']] = intval($_POST['post_id']);
                $cpt_id = json_encode( $get_assign3 );
                update_post_meta( intval($_POST['project_id']) , "pto_sub_menu_cpt_add" , $cpt_id );  
            }
        }else{
            if( isset( $_POST['post_id'] ) ){    
                $cpt_id = array();
                $cpt_id[intval($_POST['post_id'])]= intval($_POST['post_id']);  
                $cpt_id = json_encode( $cpt_id );
                update_post_meta( intval($_POST['project_id']) , "pto_sub_menu_cpt_add" , $cpt_id ); 
            }   
        }
        global $post;
        if ( isset($_POST['id'] ) ){
            $post_id = intval($_POST['id']);
        }
        else{
            $post_id = $post->ID;
        }
        if( isset( $_POST['publish_date'] ) ){
            $publish_date = sanitize_text_field($_POST['publish_date']);
        }
        if(empty($status)){
            $status = "publish";    
        }
        $post_type = "pto-note";
        $filer_arr = array(
           'post_type' => $post_type,
           'filter' => array(
            "metadata" => array(
                "pto_associated_project_id" => $post_id,
            )
        ) ,
           'fields' => array(
               'main_fields' => array(
                "title" => "Note Name ",
                "description" => "Description",
                "categories" => "Categories",
            ) ,

           ) ,
           'action' => array(
               'duplicate' => "Duplicate",
               'archive' => "Archive",
           ),
           'rules' => array(
            "status" => $status,
            'publish_date' => $publish_date
        )
       );
       ?>
       <div id="<?php echo esc_html($post_type); ?>">
        <?php
        include_once ('ptoffice_cptlist.php');
        new WPNB_PtoCptList($filer_arr);
        ?>
    </div>
    <?php
    if ( isset($_POST['id'] )){
        die();
    }
}

    /**
    * Cpt metting user metabox create
    * @since    1.0.0
    * @access   public
    **/
    public function pto_cpt_project_in_cpt_detail(){
    }
    
    /**
    *  Metting user project data show code
    * @since    1.0.0
    * @access   public
    **/
    public function pto_cpt_project_meta_choose(){
        include "structure/project/project_meta_choose.php";
    }
    
    /**
    *  cpt metting key info metabox create
    * @since    1.0.0
    * @access   public
    **/
    public function wpnb_pto_cpt_project_in_key_information(){
        add_meta_box('custom_meta_box-key_view' , // $id
           'Key Information <i class="fa fa-info-circle fas-tooltip" title="Want the next project chair to see important information for your notebook? Keep it all here for them to see "Key Information" as they begin taking over."></i>' , // $title
           array(
            $this,
            'pto_cpt_project_meta_key'
            ) , // $callback
           'pto-project' , // $page
           'normal' , // $context
           'low'
            // $priority
       );
    }
    
    /**
    *  metting user project data show code
    * @since    1.0.0
    * @access   public
    **/
    public function pto_cpt_project_meta_key(){
        global $post;
        $post_meta = get_post_meta($post->ID,'keyinformation' , true);
        $key_check = get_post_meta( $post->ID , 'pto-key-view' , true );
        if ($post_meta){
            $content = $post_meta;
        }else{
            $content = "";
        }
        wp_editor($content,'keyinfo' , $settings = array(
           'textarea_name' =>'keyinformation' ,
           'textarea_rows' => 10
       ));
       ?>
       <div class="pto-publish-tab-frontend">
        <input type="checkbox" name="pto-key-view" <?php if( $key_check == "on" ){ echo "checked"; } ?>>

        <label>Show key information tab on public view &nbsp;<i class="fa fa-info-circle fas-tooltip" title="Checking this option will show this section's details on the front-end view of this project notebook. If you do not wish for this section to be visible on the front-end, leave this option unchecked."></i></label>
    </div>
    <?php
}

    /**
    * Project setting tab in project cpt
    * @since    1.0.0
    * @access   public
     */
    public function pto_project_setting(){
        include "structure/admin/pto_admin_setting.php"; 
    }

    /**
    * project seting reuest tab
    * @since    1.0.0
    * @access   public
    **/
    public function pto_project_request_setting(){

        include "structure/admin/pto_project_request_setting.php";
    }

    /**
    * Content task bar
    * @since    1.0.0
    * @access   public
    **/
    public function wpnb_render_meta_box_content_tasks(){
        global $wpdb;
        $get_assign2 = "";
        $sub_cpt_data  = "";
        $db_name =  $wpdb->prefix."postmeta";
        if( isset( $_POST['project_id'] ) ) {
            $sql = "select * from $db_name where post_id = %d";
            $sql = $wpdb->prepare($sql, array(intval($_POST['project_id'])));
            
            $meta_data = $wpdb->get_results( $sql );
            foreach( $meta_data as $meta_key ){
                if( $meta_key->meta_key ){
                    $key = $meta_key->meta_key;
                    if( $key == "pto_sub_menu_cpt_add" ){
                        $get_assign2 = $meta_key->meta_value; 
                    }
                }
            }
        }
        if( $get_assign2 != "" ){
            $get_assign3 = json_decode($get_assign2);
            $get_assign3 = (array) $get_assign3;              
            if( !in_array( $_POST['post_id'] , $get_assign3 ) ){
                $get_assign3[intval($_POST['post_id'])] = intval($_POST['post_id']);
                $cpt_id = json_encode( $get_assign3 );
                update_post_meta( intval($_POST['project_id']) , "pto_sub_menu_cpt_add" , $cpt_id );  
            }
        }else{
            if(isset($_POST['post_id'])){    
                $cpt_id = array();
                $cpt_id[intval($_POST['post_id'])]= intval($_POST['post_id']);  
                $cpt_id = json_encode( $cpt_id );
                update_post_meta( intval($_POST['project_id']) , "pto_sub_menu_cpt_add" , $cpt_id ); 
            }   
        }
        
        global $post;
        if (isset($_POST['id'])){
            $post_id = intval($_POST['id']);
        }
        else{
            $post_id = $post->ID;
        }
        $status =  $publish_date = "";
        if( isset( $_POST['status'] ) ){
            $status = sanitize_text_field($_POST['status']);
        }
        if( isset( $_POST['publish_date'] ) ){
            $publish_date = sanitize_text_field($_POST['publish_date']);
        }
        $post_type = "pto-tasks";
        if(empty($status)){
            $status = "publish";    
        }
        $filer_arr = array(
           'post_type' => $post_type,
           'filter' => array(
            "metadata" => array(
                "pto_associated_project_id" => $post_id,
            )
        ) ,
           'fields' => array(
               'main_fields' => array(
                "title" => "Task Name",
            ) ,
               'meta_key' => array(                   
                "pto_user_assign_key" => "Assigned To",
                "pto_task_due_date" => "Due Date",
                "pto_task_status" => "Status"

            )
           ),
           'action' => array(
               'duplicate' => "Duplicate",
               'archive' => "Archive",
               'taskstatus' => "Mark as Complete",
           ),
           'rules' => array(
            "status" => $status,
            'publish_date' => $publish_date
        )
       );
       ?>
       <div id="<?php echo esc_html($post_type); ?>">
        <?php
        include_once ('ptoffice_cptlist.php');
        new WPNB_PtoCptList($filer_arr);
        ?>
    </div>
    <?php
    if ( isset( $_POST['id'] ) ){
        die();
    }
}

    /**
    * Budget render meta 
    * @since    1.0.0
    * @access   public
    **/
    public function wpnb_render_meta_box_content_budgets(){ 
        global $wpdb;
        $get_assign2 = "";
        $sub_cpt_data  = "";
        $db_name =  $wpdb->prefix."postmeta";   
        if( isset( $_POST['project_id'] ) ){
            $sql = "select * from $db_name where post_id = %d";
            $sql = $wpdb->prepare($sql, array(intval($_POST['project_id'])));
            $meta_data = $wpdb->get_results($sql);
            foreach( $meta_data as $meta_key ){
                if( $meta_key->meta_key ){
                    $key = $meta_key->meta_key;
                    if( $key == "pto_sub_menu_cpt_add" ){
                        $get_assign2 = $meta_key->meta_value; 
                    }  
                }
            }
        }
        if($get_assign2 != "" ){
            $get_assign3 = json_decode( $get_assign2 );
            $get_assign3 = (array) $get_assign3;              
            if( !in_array( $_POST['post_id'] , $get_assign3 ) ){
                $get_assign3[$_POST['post_id']] = intval($_POST['post_id']);
                $cpt_id = json_encode( $get_assign3 );
                update_post_meta( intval($_POST['project_id']) , "pto_sub_menu_cpt_add" , $cpt_id );  
            }
        }else{
            if(isset( $_POST['post_id'] ) ){    
                $cpt_id = array();
                $cpt_id[$_POST['post_id']]= intval($_POST['post_id']);  
                $cpt_id = json_encode( $cpt_id );
                update_post_meta( intval($_POST['project_id']) , "pto_sub_menu_cpt_add" , $cpt_id ); 
            }   
        }
        
        global $post;
        if ( isset( $_POST['id'] ) ){
            $post_id = intval($_POST['id']);
        }
        else{
            $post_id = $post->ID;
        }
        $status =  $publish_date = "";
        if( isset( $_POST['status'] ) ){
            $status = sanitize_text_field($_POST['status']);
        }
        if( isset( $_POST['publish_date'] ) ){
            $publish_date = sanitize_text_field($_POST['publish_date']);
        }

        $post_type = "pto-budget-items";
        if(empty($status)){
            $status = "publish";    
        }
        $filer_arr = array(
           'post_type' => $post_type,
           'filter' => array(
            "metadata" => array(
                "pto_associated_project_id" => $post_id,
            )
        ) ,
           'fields' => array(
               'main_fields' => array(
                "title" => "Budget Item Name",
                "description" => "Description"

            ) ,
               'meta_key' => array(                   
                "budget_items_type_value" => "Amount"

            )
           ),
           'action' => array(
               'duplicate' => "Duplicate",
               'archive' => "Archive",
           ),
           'rules' => array(
            "status" => $status,
            'publish_date' => $publish_date
        )
       );
       ?>
       <div id="<?php echo esc_html($post_type); ?>">
        <?php
        include_once ('ptoffice_cptlist.php');
        new WPNB_PtoCptList($filer_arr);
        ?>
    </div>
    <?php
    if ( isset( $_POST['id'] ) ){
        die();
    }
}

    /**
     * Project manager in project cpt 
     * @since    1.0.0
     * @access   public
    **/
    public function pto_project_manager(){
     ?>
        <div class="pto-admin-setting-user-search">
            <!--<input type="search" name="pm_search" class="pm_search" autocomplete="nope">-->
            <button type="button" name="useradd" class="add-pm-user outline_btn button-primary" value="Add New" onclick="jQuery('#pm_adddata').addClass('pto-modal-open'); "><i class="fa fa-plus"></i> Add New</button>
            <div class="pto-admin-search-result-data" style="display:none;">
            </div>
        </div>
        <table class="wp-list-table widefat fixed striped table-view-list users project-pm-list-tbl" id="pto-manager_table">
            <thead>
                <tr>

                    <th scope="col" id="username" class="manage-column column-username column-primary sortable desc" onclick="sortTable(0 ,'pto-manager' )"><span class='header-check'><?php esc_html_e('Username' ,PTO_NB_MYPLTEXT);?></span><span class="sorting-indicator"></span></th>
                    <th scope="col" id="name" class="manage-column sortable desc column-name" onclick="sortTable(1 ,'pto-manager' )"><span class='header-check'><?php esc_html_e('Name' ,PTO_NB_MYPLTEXT);?></span><span class="sorting-indicator"></span></th>
                    <th scope="col" id="email" class="manage-column sortable desc column-email  column-primary sortable desc" onclick="sortTable(2 ,'pto-manager' )"><span class='header-check'><?php esc_html_e('Email' ,PTO_NB_MYPLTEXT);?></span><span class="sorting-indicator"></span></th>
                    <th scope="col" id="role" class="manage-column sortable desc column-role" onclick="sortTable(3 ,'pto-manager' )"><span class='header-check'><?php esc_html_e('Role' ,PTO_NB_MYPLTEXT);?></span><span class="sorting-indicator"></span></th>
                </tr>
            </thead>
            <tbody id="the-list" data-wp-lists="list:user">
                <?php
                $post_id = get_the_ID();
                $post_data = get_post_meta( $post_id ,'pto_project_user_id' , true );
                if( !empty( $post_data ) ){
                    foreach( $post_data as $user_data ){
                        $user_info = get_userdata( $user_data );
                        if( !empty( $user_info ) ){
                            $user_roles = $user_info->roles;
                            $roles = implode(', ' , $user_roles );
                            $rol = str_replace("_", " " , $roles );
                            ?>
                            <tr id="user-<?php echo esc_html($user_info->ID);?>">

                                <td class="username column-username has-row-actions column-primary" data-colname="Username">
                                    <img alt="" src="http://1.gravatar.com/avatar/7673643b0658d255493a1bb5af372efb?s=32&amp;d=mm&amp;r=g"  class="avatar avatar-32 photo" height="32" width="32" loading="lazy"> <strong><a href="http://localhost/projectplanner/ptotest/wp-admin/profile.php?wp_http_referer=%2Fprojectplanner%2Fptotest%2Fwp-admin%2Fusers.php"><?php echo esc_html($user_info->user_login); ?></strong><br>
                                        <div class="row-actions"><span class="remove"><a class="pm_delete" href="#" data-pid="<?php echo esc_html($post_id);?>" data-userid="<?php echo esc_html($user_info->ID); ?>">Remove</a></span></div>
                                        <button type="button" class="toggle-row"><span class="screen-reader-text">Show more details</span></button>
                                    </td>
                                    <td class="name column-name" data-colname="Name"><span aria-hidden="true">
                                     <?php
                                     $first_name = get_user_meta( $user_info->ID ,'first_name' , true );
                                     $last_name = get_user_meta( $user_info->ID ,'last_name' , true );
                                     $full_name =  $first_name . " " . $last_name;
                                     if( $full_name != " " ){
                                        echo esc_html($full_name);   
                                    }else{
                                        echo esc_html($user_info->display_name);
                                    }
                                    ?>
                                </span><span class="screen-reader-text">Unknown</span></td>
                                <td class="email column-email" data-colname="Email"><a href="mailto:<?php echo esc_html($user_info->user_email); ?>"><?php echo esc_html($user_info->user_email); ?></a></td>
                                <td class="role column-role" data-colname="Role"><?php echo esc_html($rol); ?></td>
                            </tr>
                            <?php
                        }
                    }
                }
                ?>
            </tbody>
        </table>
    <?php
    }

    /**
    * Get all pm user 
    * @since    1.0.0
    * @access   public
    **/
    public function wpnb_get_all_pm_users(){
        if ( ! wp_verify_nonce( $_POST['nonce'], 'ajax-nonce' ) ) {
         die ( 'Busted!');
     }
     $pid = sanitize_text_field($_POST['pid']);
     $search_user = sanitize_text_field($_POST['search_user'] .'*');
     $p_meta = get_post_meta( $pid , "pto_project_user_id" , true );
     $pm_users = get_users( array('search' => $search_user ,'exclude' => $p_meta ) );

     foreach ( $pm_users as $user ) {
                //echo'<span>' . sanitize_text_field( $user->display_name ) .'</span>';
        ?>
        <div class="pto_admin_username">
            <div class="pto_pm_user_checkbox"><input type="checkbox" class="pto_pm_user checked_<?php echo esc_html($user->ID); ?>" id="<?php echo esc_html($user->ID); ?>" name="<?php echo esc_html($user->display_name); ?>"></div>
            <div class="pto_pm_user_search">
                <div class="pto_user_name_admin"><?php echo esc_html($user->display_name); ?></div>
                <div class="pto_user_email_admin"><?php echo esc_html($user->user_email); ?></div>
            </div>
        </div>
        <?php
    }
    die();
    }

    /**
    * @since    1.0.0
    * @access   public
    **/
    public function wpnb_get_all_project_manager(){
        if ( ! wp_verify_nonce( $_POST['nonce'], 'ajax-nonce' ) ) {
         die ( 'Busted!');
        }
        $user_ids = filter_var_array($_POST['selected_user_id']);
        $post_id = intval($_POST['post_id']);
        $temp_arr = array();
        foreach( $user_ids as $all_user ){
            $user = get_userdata($all_user);
            $user->add_cap( 'edit_posts' ); 
            $user->add_cap( 'edit_others_posts' );
            $user->add_cap( 'publish_posts' );
            $user->add_cap( 'manage_categories' );
            $user->add_cap( 'edit_published_posts' );
            $temp_arr = array();
            $get_user_post = get_user_meta( $all_user,'pto_project_post_id' ,true );
            if( !empty( $get_user_post ) ){
                $temp_arr = $get_user_post;
                $temp_arr[$post_id] = $post_id;
            }else{ 
                $temp_arr[$post_id] = $post_id;
            }  
            update_user_meta( $all_user,'pto_project_post_id' ,  $temp_arr );
        }

        $post_meta = get_post_meta( $post_id , "pto_project_user_id" , true );
        if( !empty( $post_meta ) ){
            $cnt = 0;
            $total_arr = array();
            if( !is_array( $user_ids ) ){
                $total_arr[] =  $user_ids;
            }else{
                $total_arr =  array_merge( $user_ids , $post_meta );  
            }
            update_post_meta( $post_id , "pto_project_user_id" , $total_arr);

        }else{
            $total_arr = array();
            if( !is_array( $user_ids ) ){
                $total_arr[] =  $user_ids;
            }else{
                $total_arr = $user_ids;  
            }
            update_post_meta( $post_id , "pto_project_user_id" , $total_arr );
        }
        $post_data = get_post_meta( $post_id ,'pto_project_user_id' , true );
        if( !empty( $post_data ) ){
            foreach( $post_data as $user_data ){
                $user_info = get_userdata( $user_data );
                if( !empty( $user_info ) ){
                    $user_roles = $user_info->roles;
                    $roles = implode(' ,' , $user_roles );
                    $rol = str_replace( "_" , " " , $roles );
                    ?>
                    <tr id="user-<?php echo esc_html($user_info->ID);?>">
                        <td class="username column-username has-row-actions column-primary" data-colname="Username">
                            <img alt="" src="http://1.gravatar.com/avatar/7673643b0658d255493a1bb5af372efb?s=32&amp;d=mm&amp;r=g" class="avatar avatar-32 photo" height="32" width="32" loading="lazy"> <strong><a href="http://localhost/projectplanner/ptotest/wp-admin/profile.php?wp_http_referer=%2Fprojectplanner%2Fptotest%2Fwp-admin%2Fusers.php"><?php echo esc_html($user_info->user_login); ?></strong><br>
                                <div class="row-actions"><span class="remove"><a class="pm_delete" href="#" data-pid="<?php echo esc_html($post_id);?>" data-userid="<?php echo esc_html($user_info->ID); ?>">Remove</a></span></div>
                                <button type="button" class="toggle-row"><span class="screen-reader-text">Show more details</span></button>
                        </td>
                        <td class="name column-name" data-colname="Name"><span aria-hidden="true"><?php echo esc_html($user_info->display_name); ?></span><span class="screen-reader-text">Unknown</span></td>
                        <td class="email column-email" data-colname="Email"><a href="mailto:<?php echo esc_html($user_info->user_email); ?>"><?php echo esc_html($user_info->user_email); ?></a></td>
                        <td class="role column-role" data-colname="Role"><?php echo esc_html($rol); ?></td>
                    </tr>
                    <?php
                }
            }
        }  
        die();
    }


    /**
    * @since    1.0.0
    * @access   public
    **/
    public function wpnb_delete_pmuser_from_post(){
        if ( ! wp_verify_nonce( $_POST['nonce'], 'ajax-nonce' ) ) {
             die ( 'Busted!');
         }
         $pid = intval($_POST['pid']);
         $uid = intval($_POST['uid']);
        /**
         * Remove user from project
         */
        $post_metadata = get_post_meta( $pid,'pto_project_user_id' , true );
        $data_uid = array_search( $uid , $post_metadata );
        unset( $post_metadata[ $data_uid ] );
        $user = new \WP_User( $uid );
        $user->remove_cap( 'edit_published_posts' );
        $user->remove_cap( 'create_posts' );
        $user->remove_cap( 'edit_posts' );
        $user->remove_cap( 'edit_others_posts' );
        $user->remove_cap( 'publish_posts' );
        $user->remove_cap( 'manage_categories' );  
        $caps = $user->allcaps;
        foreach($caps as $cap) {
            $user->remove_cap($cap);
        }
        update_post_meta( $pid,'pto_project_user_id' , $post_metadata );
        die();
    }

    /**
     * project setting meta
     * @since    1.0.0
     * @access   public
    **/
    public function wpnb_pto_project_show_setting(){
        $p_id = get_the_ID();
        $get_projects_chk = get_post_meta( $p_id ,'show_projects_chk' , true );
        $allow_members = get_post_meta($p_id ,'allow_members' , true );
        ?>
        <div class="check_project_list">
            <input type="checkbox" id="show_project" name="show_project" <?php if($get_projects_chk == 1){ echo'checked'; } ?> value="show in project listing module">
            <label for="show_project"> Show in project listing module</label><br>
        </div>
        <?php
    }

    /**
     * project wpnb_get_all_pto_request_projects meta
     * @since    1.0.0
     * @access   public
    **/
    public function wpnb_get_all_pto_request_projects(){
        if ( ! wp_verify_nonce( $_POST['nonce'], 'ajax-nonce' ) ) {
            die ( 'Busted!');
        }
        $proj_id = intval($_POST['proj_id']);
        $current_user_id = get_current_user_id();
        $get_user_post = get_user_meta( $current_user_id ,'pto_project_request_id' ,true);
        
        if( !empty( $get_user_post ) ){
            $get_user_post[ $proj_id ] =  $proj_id;
            update_user_meta( $current_user_id ,"pto_project_request_id", $get_user_post );
        }else{
            $total_proj_arr = array();
            $total_proj_arr[ $proj_id ] = $proj_id;
            update_user_meta( $current_user_id , "pto_project_request_id" , $total_proj_arr );
        }
        die();
    }

    /**
    * Project project request accept 
    * @since    1.0.0
    * @access   public
    **/
    public function wpnb_get_pto_request_projects_accept(){
        if ( ! wp_verify_nonce( $_POST['nonce'], 'ajax-nonce' ) ) {
            die ( 'Busted!');
        }
        $proj_id = intval($_POST['req_project_id']);
        $proj_user_id =intval( $_POST['req_user_id']);
        $get_user_req_post = get_post_meta( $proj_id,'pto_project_user_id' ,true);
        if( !empty( $get_user_req_post ) ){
            $get_user_req_post[] =  $proj_user_id;
            $total_proj_arr  =  $get_user_req_post;
        }else{
            $total_proj_arr = array( $proj_user_id );  
        }
        update_post_meta( $proj_id ,'pto_project_user_id' , $total_proj_arr );
        /**
         * remove project request after approve
         */
        $get_user_post = get_user_meta( $proj_user_id ,'pto_project_request_id' ,true);
        $find_pid = array_search( $proj_id, $get_user_post );
        unset( $get_user_post[ $find_pid ] );
        update_user_meta( $proj_user_id , "pto_project_request_id" , $get_user_post );

        $user_info = get_userdata( $proj_user_id );
        $usermail = $user_info->user_email;
        $to = $usermail;
        $subject ='Your Request approved to access the project.';
        $body ='Congrats. you are able to access the project.';
        $headers = array('Content-Type: text/html; charset=UTF-8');
        wp_mail( $to, $subject, $body, $headers );
        die();
    }

    /**
     * project project request decline 
     * @since    1.0.0
     * @access   public
    **/
    public function wpnb_get_pto_request_projects_decline(){
        if ( ! wp_verify_nonce( $_POST['nonce'], 'ajax-nonce' ) ) {
            die ( 'Busted!');
        }
        $proj_id = intval($_POST['req_project_id']);
        $proj_user_id = intval($_POST['req_user_id']);
        
        $get_meta = get_user_meta( $proj_user_id , "pto_project_request_id" , true );
        unset( $get_meta[ $proj_id ] );
        update_user_meta( $proj_user_id , "pto_project_request_id" , $get_meta );
        $user_info = get_userdata( $proj_user_id );
        $usermail = $user_info->user_email;
        $to = $usermail;
        $subject ='Your Request decline to access the project.';
        $body ='you are not able to access the project.';
        $headers = array('Content-Type: text/html; charset=UTF-8');
        wp_mail( $to, $subject, $body, $headers );
        die();
    }

    /**
    * Change the featured image metabox title text
    * @since    1.0.0
    * @access   public
    **/
    public function wpnb_km_change_featured_image_metabox_title() {
        remove_meta_box('postimagediv' ,'project' ,'side' );
        add_meta_box('postimagediv' , __('Banner Image' ,'km' ),'post_thumbnail_meta_box' ,'pto-project' ,'side' );
    }

    /**
    * Task date udpate 
    * @since    1.0.0
    * @access   public
    **/
    public function wpnb_update_task_show_data(){
        if ( ! wp_verify_nonce( $_POST['nonce'], 'ajax-nonce' ) ) {
            die ( 'Busted!');
        }
        if($_POST){  
            if( isset( $_POST['task_show'] ) ){
                $post_id = intval($_POST['post_id']);
                if( $_POST['task_show'] == "" ){
                    update_post_meta( $post_id, "show_completed_chk" , "" );
                }else{
                    update_post_meta( $post_id , "show_completed_chk" , sanitize_text_field($_POST['task_show']) );
                }
            }
            die();
        }
    }

    /**
    * Give edit access to project user 
    * @since    1.0.0
    * @access   public
    **/
    public function wpnb_pto_project_usercap_add(){
        if(isset($_GET['post'])){
            $post_id = intval($_GET['post']);
            $get_current_user_id = get_current_user_id();
            $user = get_userdata( $get_current_user_id ); 
            $user_roles = $user->roles;
            foreach($user_roles as $role){
                if($role == "Administrator" || $role == "project_plugin_administrators"){
                }else{
                ?>
                <style>
                     a.page-title-action{
                        display:none !important;
                    }
                </style>
                <?php
                }    
            }
        // Check if the role you're interested in, is present in the array.
        }       
        $user = wp_get_current_user();
        $data = get_option( "user_per" , true );
        if($data == "allo-user-create-own-project" ||  in_array( "project_manager" , $user->roles )){
            $user->add_cap( 'edit_published_posts' );
            $user->add_cap( 'create_posts' );
            $user->add_cap( 'edit_posts'     );
            $user->add_cap( 'edit_others_posts' );
            $user->add_cap( 'publish_posts' );
            $user->add_cap( 'manage_categories' );  
            $user->add_cap( 'delete_published_posts' );
        }
    }

    /**
    * @since    1.0.0
    * @access   public
    **/
    public function flush_rewrite_rules() {
        if ( ! get_option( 'store_flush_rewrite_rules_flag' ) ) {
            add_option( 'store_flush_rewrite_rules_flag', true );
            flush_rewrite_rules();
        }
    }
}


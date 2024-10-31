<?php

/**
 * PTO class for initiating necessary actions and core functions.
 */

/*
 * Defining Namespace
*/

namespace ptoffice\classes;

class WPNB_ProjectSetting
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
        add_action('init', array($this, 'wpnb_pto_task_status_option_create'));
        add_action('init', array($this,  'wpnb_pto_kanban_status_option_create'));
    }

    /**
    * Task status option create
    * @since    1.0.0
    * @access   public
    **/
    public function wpnb_pto_task_status_option_create(){
        $no_exists_value = get_option('pto_task_status');
        if ($no_exists_value == "") {
            $pto_task_status = array();
            $pto_task_status['not-started'] = "Not Started";
            $pto_task_status['in-progress'] = "In Progress";
            $pto_task_status['on-hold'] = "On Hold";
            $pto_task_status['overdue'] = "Overdue";
            $pto_task_status['completed'] = "Completed";
            update_option('pto_task_status',  $pto_task_status, '', 'yes');
        }
    }

    /**
    * Create Kanban option
    * @since    1.0.0
    * @access   public
    **/
    public function wpnb_pto_kanban_status_option_create(){
        $no_exists_value = get_option('pto_kanban_status');
        if ($no_exists_value == "") {
        }
    }
}

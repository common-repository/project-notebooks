<?php

/**
 * Plugin Name: PT Project Notebooks - Take meeting minutes, create budgets, track task management and more
 * Description: A unique information manager, PT Project Notebooks allows you to assign, plan, organize, share and manage any event, committee, or meeting with a simple-to-use interface. Perfect for committee chairs or event coordinators. Don’t recreate the wheel event after event - use PT Project Notebooks.
 * Version: 1.0.9
 * Author: MJS Software
 * Author URI: https://mjssoftware.com
 * Text Domain: MJS Software
 **/

/* 
* Defining Namespace
*/

namespace ptoffice;

/* 
* If this file is called directly or plugin is already defined, abort. 
*/

if (!defined('WPINC')) {
    die;
}

define('PTO_NB_PLUGIN_PATH', plugin_dir_url(__FILE__));
define('PTO_NB_PLUGIN_PATHS', plugin_dir_path(__FILE__));

define('PTO_NB_MYPL_BASE_FILE', "ptoffice.php");

/* 
* Include constant file
*/
include_once('constant.php');

/* 
* Include main class ptoffice
*/
include_once('includes/class-ptoffice.php');
include_once('includes/class-cptduplicate.php');
include_once('includes/class-archivecpt.php');
include_once('includes/class-project-setting.php');
include_once('includes/class-cptcreate.php');
include_once('includes/cpt-hooksdetails.php');
include_once('includes/cpt-project.php');
include_once('includes/structure/admin/pto_admin_settings.php');
include_once('includes/structure/frontend/pto_frontend.php');
include_once('includes/pto-projectplanner-cron-plugin.php');

/* 
Declare Classes
*/

use ptoffice\classes\WPNB_Ptoffice;
use ptoffice\classes\WPNB_Duplicator;
use ptoffice\classes\WPNB_Cptarchive;
use ptoffice\classes\WPNB_ProjectSetting;
use ptoffice\classes\WPNB_Cptcreate;
use ptoffice\classes\WPNB_Posthooks;
use ptoffice\classes\WPNB_ProjectCptCreate;
use ptoffice\classes\WPNB_AdminPTOSetting;
use ptoffice\classes\WPNB_FrontendPTO;
use ptoffice\classes\WPNB_PtoProjectPlannerPluginCron;


if (class_exists('ptoffice\classes\WPNB_PtoProjectPlannerPluginCron')) {
    new WPNB_PtoProjectPlannerPluginCron();
}
if (class_exists('ptoffice\classes\WPNB_Ptoffice')) {
    new WPNB_Ptoffice();
}
if (class_exists('ptoffice\classes\WPNB_ProjectCptCreate')) {
    new WPNB_ProjectCptCreate();
}
if (class_exists('ptoffice\classes\WPNB_Duplicator')) {
    new WPNB_Duplicator();
}
if (class_exists('ptoffice\classes\WPNB_Cptarchive')) {
    new WPNB_Cptarchive();
}
if (class_exists('ptoffice\classes\WPNB_ProjectSetting')) {
    new WPNB_ProjectSetting();
}
if (class_exists('ptoffice\classes\WPNB_Cptcreate')) {
    new WPNB_Cptcreate();
}
if (class_exists('ptoffice\classes\WPNB_Posthooks')) {
    new WPNB_Posthooks();
}
if (class_exists('ptoffice\classes\WPNB_AdminPTOSetting')) {
    new WPNB_AdminPTOSetting();
}
if (class_exists('ptoffice\classes\WPNB_FrontendPTO')) {
    new WPNB_FrontendPTO();
}

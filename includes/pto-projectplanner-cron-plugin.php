<?php
namespace ptoffice\classes;
class WPNB_PtoProjectPlannerPluginCron 
{
    /**
    * @since    1.0.0
     * @access   public
    **/
	public  function __construct() {
        // Run this on plugin activation
        add_action( 'isa_add_every_three_minutes_event',  array($this, 'isa_send_reminder_func') );
        add_filter( 'cron_schedules',  array($this, 'isa_add_every_three_minutes') );
        // register_deactivation_hook( PLUGIN_WITH_CLASSES__FILE__, [ $this, 'isa_deactivation' ] );
	}

    /**
     * @since    1.0.0
     * @access   public
    **/
	public function isa_activation(){
		if( !wp_next_scheduled( 'isa_add_every_three_minutes_event' ) ){
			wp_schedule_event( time(), 'every_three_minutes', 'isa_add_every_three_minutes_event' );
		}
	}

    /**
    * The WP Cron event callback function
    * @since    1.0.0
    * @access   public
    **/
	public function isa_send_reminder_func() {
        /** Send reminder mail */
        $args = array(
        	'post_type' => 'pto-tasks',
        	'posts_per_page' => -1,
        );
        $task_query = new \WP_Query($args);
        if($task_query->have_posts() ){

        	while ( $task_query->have_posts() ) {
        		$task_query->the_post();
        		$id = get_the_ID();
        		$task_name = get_the_title($id);

        		$duedate = get_post_meta( $id , "pto_task_due_date" , true );
        		$ddate = $duedate['due_date'];
        		$dtime = $duedate['date_time'];
        		$duetime = get_post_meta( $id , "pto_task_due_date_time" , true );
        		$date_range = $duetime['range'][1];
        		$date_val = $duetime['range'][0];


        		$task_date_time = $ddate . '  ' . $dtime;
        		$beforeweek = strtotime(date("Y-m-d", strtotime($ddate)) . " -1 week");
        		$befortwoeweek = strtotime(date("Y-m-d", strtotime($ddate)) . " -2 week");
        		$beforeoneday = strtotime(date("Y-m-d", strtotime($ddate)) . " -1 day");

        		$oneweek = date("Y-m-d", $beforeweek);
        		$twoweek = date("Y-m-d", $befortwoeweek);
        		$oneday = date("Y-m-d", $beforeoneday);
        		$today = date("Y-m-d");


        		$assignuser = get_post_meta( $id , 'pto_user_assign_key' , true );
        		$user_id = implode(",",$assignuser);
        		$user_info = get_userdata($user_id);
        		foreach($user_info as $user_data){

        			$reminder_mailcontent = get_option( 'task_email_system',true );
        			if(!empty($reminder_mailcontent)){

        				$to = $user_data->user_email;
        				$user_name = $user_data->user_login;

        				if(in_array('2week' , $duetime)){
        					if($oneweek == $today){

        						$arra = array("/{{Name}}/", "/{{task name}}/", "/{{date}}/");
        						$arra2 = array($user_name, $task_name, $task_date_time);                                      
        						$mail = preg_replace($arra, $arra2, $reminder_mailcontent);

        						$subject = 'two week Task Reminders mails';
        						$body = $mail;                    
        						$headers = array('Content-Type: text/html; charset=UTF-8');     

        						if(!empty($to)){
        							wp_mail( $to, $subject, $body, $headers );
        						} 
        					}
        				}
        				if(in_array('1week' , $duetime)){
        					if($twoweek == $today ){

        						$arra = array("/{{Name}}/", "/{{task name}}/", "/{{date}}/");
        						$arra2 = array($user_name, $task_name, $task_date_time);                                      
        						$mail = preg_replace($arra, $arra2, $reminder_mailcontent);

        						$subject = 'One week Task Reminders mails';
        						$body = $mail;                    
        						$headers = array('Content-Type: text/html; charset=UTF-8'); 

        						if(!empty($to)){
        							wp_mail( $to, $subject, $body, $headers );
        						} 
        					}
        				}
        				if(in_array('1day' , $duetime)){

        					if($oneday == $today){

        						$arra = array("/{{Name}}/", "/{{task name}}/", "/{{date}}/");
        						$arra2 = array($user_name, $task_name, $task_date_time);                                
        						$mail = preg_replace($arra, $arra2, $reminder_mailcontent);

        						$subject = 'One day Task Reminders mails';
        						$body = $mail;
        						$headers = array('Content-Type: text/html; charset=UTF-8'); 
        						if(!empty($to)){
        							wp_mail( $to, $subject, $body, $headers );
        						}
        					}
        				}

        				if (array_key_exists("range",$duetime)){
        					if($date_range == "hours"){
        						$beforedays = strtotime(date("Y-m-d", strtotime($ddate)) ."-". $date_val. " hour");
        						$beforeday = date("Y-m-d", $beforedays);

        					}

        					if($date_range == "minute"){
        						$beforedays = strtotime( '-'.$date_val.'minutes' , strtotime($dtime));
        						$beforeday = date("H:i", $beforedays);
									//print_r($beforeday);
        					}

        					if($date_range == "days"){
        						$beforedays = strtotime(date("Y-m-d", strtotime($ddate)) ."-". $date_val. " day");
        						$beforeday = date("Y-m-d", $beforedays);
									//print_r($beforeday);
        					}

        					if($date_range == "week"){
        						$beforedays = strtotime(date("Y-m-d", strtotime($ddate)) ."-".$date_val. " week");
        						$beforeday = date("Y-m-d", $beforedays);
									//print_r($beforeday);
        					}

        					if($date_range == "month"){
        						$beforedays = strtotime(date("Y-m-d", strtotime($ddate)) ."-". $date_val. " month");
        						$beforeday = date("Y-m-d", $beforedays);
									//print_r($beforeday);
        					}

        					if($beforeday == $today){
        						$arra = array("/{{Name}}/", "/{{task name}}/", "/{{date}}/");
        						$arra2 = array($user_name, $task_name, $task_date_time);                                
        						$mail = preg_replace($arra, $arra2, $reminder_mailcontent);

        						$subject = 'Task Reminders mails';
        						$body = $mail;
        						$headers = array('Content-Type: text/html; charset=UTF-8'); 
        						if(!empty($to)){
        							wp_mail( $to, $subject, $body, $headers );
        						}
        					}
        				}
        			}

        		}
        	} 
        }
        /**End send reminder mail */
    }

    /**
    * The schedule filter hook
    * @since    1.0.0
     * @access   public
    **/
    public function isa_add_every_three_minutes( $schedules ) {
    	$schedules['every_three_minutes'] = array(
    		'interval'  => 180,
    		'display'   => __( 'Every 3 Minutes', 'PTO_NB_MYPLTEXT' )
    	);
    	return $schedules;
    }
    /**
    * The deactivation hook
     * @since    1.0.0
     * @access   public
    **/
    function isa_deactivation(){
    	if( wp_next_scheduled( 'isa_add_every_three_minutes_event' ) ){
    		wp_clear_scheduled_hook( 'isa_add_every_three_minutes_event' );
    	}
    }
}
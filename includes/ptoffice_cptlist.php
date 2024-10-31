<?php

/**
 * PTO class for initiating necessary actions and core functions.
 */

/*
 * Defining Namespace
*/

namespace ptoffice\classes;

class WPNB_PtoCptList
{
    /**
     * Constructor for intiation.
     * @since    1.0.0
     * @access   public
    */
    public function __construct($cpt_get_arr){
        $this->init($cpt_get_arr);
    }

    
    /**
     * Initiating necessary functions
     * @since    1.0.0
     * @access   public
    **/
    public function init($cpt_get_arr_list){
        $this->wpnb_pto_cpt_list_data_get($cpt_get_arr_list);
    }

    /**
    * Project cpt pto cpt get data list 
    * Cpt date get 
    * @since    1.0.0
    * @access   public
    **/
    public function wpnb_pto_cpt_list_data_get($cpt_get_arr_list_detail){
        $args = array('post_type' => $cpt_get_arr_list_detail['post_type'], 'posts_per_page' => -1);
        $the_query = new \WP_Query($args);
        $cpt_header = "";
        $project_id = $cpt_get_arr_list_detail['filter']['metadata']['pto_associated_project_id'];
        $cnt = $trushcnt = $publishcnt = $archive = $draft = 0;
        $the_posts = get_posts(array('post_type' => $cpt_get_arr_list_detail['post_type']));
        $project_id = $cpt_get_arr_list_detail['filter']['metadata']['pto_associated_project_id'];
        $all_post_data = get_post_meta($project_id, "pto_sub_menu_cpt_add", true);
        $get_progect_listing_cpt = json_decode($all_post_data);
        $get_progect_listing_cpt = (array) $get_progect_listing_cpt;
        foreach ($get_progect_listing_cpt as $key => $cpt_single_datas) {
            $id = $cpt_single_datas;
            $post_type = get_post_type($id);
            if ($cpt_get_arr_list_detail['post_type'] ==  $post_type) {
                $cnt++;
                $status = get_post_status($id);
                if ($status == "trash") {
                    $trushcnt++;
                }
                if ($status == "publish") {
                    $publishcnt++;
                }
                if ($status == "archive") {
                    $archive++;
                }
                if ($status == "draft") {
                    $draft++;
                }
            }
        }
        $cpt_header .= $this->wpnb_getcpt_listing_header($cpt_get_arr_list_detail['fields'], $cpt_get_arr_list_detail['post_type'], $project_id, $cnt, $trushcnt, $publishcnt, $cpt_get_arr_list_detail['rules']['status'], $archive, $draft, $get_progect_listing_cpt, $cpt_get_arr_list_detail['rules']['publish_date']);
        foreach ($get_progect_listing_cpt as $key => $cpt_single_datas) {
            $id = $cpt_single_datas;
            $post_type = get_post_type($id);
            $cpt_single_data = get_post($id);
            if ($cpt_get_arr_list_detail['post_type'] ==  $post_type) {
                if (array_key_exists("rules", $cpt_get_arr_list_detail)) {
                    $post_status = get_post_status($cpt_single_datas);
                    if ($cpt_get_arr_list_detail['rules']['status'] == "") {
                        $cpt_header .= $this->wpnb_pto_cpt_list_header_get($id, $cpt_get_arr_list_detail, $cpt_get_arr_list_detail['post_type'], $cpt_single_data->post_content);
                    } else {
                        if ($cpt_get_arr_list_detail['rules']['publish_date'] == "") {
                            if ($cpt_get_arr_list_detail['rules']['status'] == "all") {
                                $cpt_header .= $this->wpnb_pto_cpt_list_header_get($id, $cpt_get_arr_list_detail, $cpt_get_arr_list_detail['post_type'], $cpt_single_data->post_content);
                            } else if ($cpt_get_arr_list_detail['rules']['status'] == "publish") {
                                if ($post_status == $cpt_get_arr_list_detail['rules']['status']) {
                                    $cpt_header .= $this->wpnb_pto_cpt_list_header_get($id, $cpt_get_arr_list_detail, $cpt_get_arr_list_detail['post_type'], $cpt_single_data->post_content);
                                }
                            } else if ($cpt_get_arr_list_detail['rules']['status'] == "trash") {

                                if ($post_status == $cpt_get_arr_list_detail['rules']['status']) {
                                    $cpt_header .= $this->wpnb_pto_cpt_list_header_get($id, $cpt_get_arr_list_detail, $cpt_get_arr_list_detail['post_type'], $cpt_single_data->post_content);
                                }
                            } else if ($cpt_get_arr_list_detail['rules']['status'] == "archive") {

                                if ($post_status == $cpt_get_arr_list_detail['rules']['status']) {
                                    $cpt_header .= $this->wpnb_pto_cpt_list_header_get($id, $cpt_get_arr_list_detail, $cpt_get_arr_list_detail['post_type'], $cpt_single_data->post_content);
                                }
                            } else if ($cpt_get_arr_list_detail['rules']['status'] == "draft") {

                                if ($post_status == $cpt_get_arr_list_detail['rules']['status']) {
                                    $cpt_header .= $this->wpnb_pto_cpt_list_header_get($id, $cpt_get_arr_list_detail, $cpt_get_arr_list_detail['post_type'], $cpt_single_data->post_content);
                                }
                            }
                        }
                    }
                    if ($cpt_get_arr_list_detail['rules']['publish_date'] != "") {
                        $get_date = $cpt_get_arr_list_detail['rules']['publish_date'];
                        $date = get_the_date('M Y', $id);
                        if ($get_date == $date) {
                            $cpt_header .= $this->wpnb_pto_cpt_list_header_get($id, $cpt_get_arr_list_detail, $cpt_get_arr_list_detail['post_type'], $cpt_single_data->post_content);
                        }
                    }
                }
            }
        }

        $cpt_header .= "</tbody></table>";
        print_r($cpt_header);
        if ($cpt_get_arr_list_detail['post_type'] == "pto-budget-items") {
        ?>  
            <div class="pto_budget_total"><b>Current Budget Value:</b>
                <span class="total_cnt"></span>
                <input type="hidden" name="total_cnt_budget" id="total_cnt_budget">
            </div>
        <?php
        }
        if ($cpt_get_arr_list_detail['post_type'] == "pto-tasks") {
            $chk_completed = get_post_meta($project_id, 'show_completed_chk', true);

            $completed = '';
            if ($chk_completed  == 1) {
                $completed = 'checked';
            }
        ?>
            <input type="checkbox" class="check_completed_post" id="show_completed_chk" value="1" <?php echo esc_html($completed); ?> name="show_completed_chk" value="">
            <label for="show_completed_chk"> View Completed Tasks</label><br>
        <?php

        }
        $cpt_name = "";
        if ($cpt_get_arr_list_detail['post_type'] == "pto-tasks") {
            $cpt_name = "task management";
        } else if ($cpt_get_arr_list_detail['post_type'] == "pto-note") {
            $cpt_name = "notes";
        } else if ($cpt_get_arr_list_detail['post_type'] == "pto-budget-items") {
            $cpt_name = "budget";
        } else if ($cpt_get_arr_list_detail['post_type'] == "pto-kanban") {
            $cpt_name = "kanban";
        } else if ($cpt_get_arr_list_detail['post_type'] == "pto-meeting") {
            $cpt_name = "meeting minutes";
        }
        $get_pulish_meta = $cpt_get_arr_list_detail['post_type'] . '-view';
        $publish_check =  get_post_meta($project_id, $get_pulish_meta, true);
        ?>
        <div class="pto-publish-tab-frontend">
            <input type="checkbox" name="<?php echo esc_html($cpt_get_arr_list_detail['post_type'] . '-view'); ?>" <?php if ($publish_check == "on") {
            echo esc_html_e("checked");
        } ?>>
        <label>Show <?php echo esc_html($cpt_name); ?> tab on public view &nbsp;<i class="fa fa-info-circle fas-tooltip" title="Checking this option will show this section's details on the front-end view of this project notebook. If you do not wish for this section to be visible on the front-end, leave this option unchecked."></i></label>
        </div>
<?php
    }

    /**
    * @since    1.0.0
     * @access   public
    **/
    public function wpnb_getcpt_listing_header($cpt_header_meta, $post_type, $project_id, $cnt, $trushcnt, $publishcnt, $status, $archive, $draft, $get_progect_listing_cpt, $publish_dates){

        include "structure/pto_listing_project_cpt/pto_listing_cpt_project_header.php";
        return $cpt_html_header;
    }
    /**
    * @since    1.0.0
    * @access   public
    **/
    public function wpnb_pto_cpt_list_header_get($id, $cpt_header_meta, $post_type, $post_content){
        include "structure/pto_listing_project_cpt/pto_listing_cpt_project_details.php";
        return $cpt_html_header;
    }
}
?>
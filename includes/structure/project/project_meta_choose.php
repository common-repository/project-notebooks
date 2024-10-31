<?php
global $post;
delete_post_meta($post->ID, 'cpt_selected_data', " ", true);
$selected_cpt = get_post_meta($post->ID, "cpt_selected_data", true);
if ($selected_cpt == "") {
    $selected_cpt = array();
}
?>
<div class="pto-project-cpt-data">
    <div class="pto-project-cpt-data-checkbox">
     <div class="pto-project-cpt-data-checkbox_input">
        <input type="checkbox" name="chosse_project[]" class="cpt_selected" value="Key" ids="custom_meta_box-key_view" <?php if (array_key_exists("Key", $selected_cpt)) {
            echo "checked";
        } ?>>
        <span class="pto-project-cpt-data-checkbox_title">
            <?php esc_html_e('Key Information', PTO_NB_MYPLTEXT); ?>
        </span>
    </div>
    <div class="pto-project-cpt-data-checkbox_input">
        <input type="checkbox" name="chosse_project[]" class="cpt_selected" value="Notes" ids="custom_meta_box-notes" <?php if (array_key_exists("Notes", $selected_cpt)) {
            echo "checked";
        } ?>>
        <span class="pto-project-cpt-data-checkbox_title">
            <?php esc_html_e('Notes', PTO_NB_MYPLTEXT); ?>
        </span>
    </div>
    <div class="pto-project-cpt-data-checkbox_input">
        <input type="checkbox" name="chosse_project[]" class="cpt_selected" value="pto-tasks" ids="custom_meta_box-tasks" <?php if (array_key_exists("pto-tasks", $selected_cpt)) {
            echo "checked";
        } ?>>
        <span class="pto-project-cpt-data-checkbox_title">
            <?php esc_html_e('Task Management', PTO_NB_MYPLTEXT); ?>
        </span>
    </div>
    <div class="pto-project-cpt-data-checkbox_input">
        <input type="checkbox" name="chosse_project[]" class="cpt_selected" value="pto-meeting" ids="custom_meta_box-metting" <?php if (array_key_exists("pto-meeting", $selected_cpt)) {
            echo "checked";
        } ?>>
        <span class="pto-project-cpt-data-checkbox_title">
            <?php esc_html_e('Meeting Minutes', PTO_NB_MYPLTEXT); ?>
        </span>
    </div>
    

    <div class="pto-project-cpt-data-checkbox_input">
        <input type="checkbox" name="chosse_project[]" class="cpt_selected" value="Budget" ids="custom_meta_box-buget-items" <?php if (array_key_exists("Budget", $selected_cpt)) {
            echo "checked";
        } ?>>
        <span class="pto-project-cpt-data-checkbox_title">
            <?php esc_html_e('Budget', PTO_NB_MYPLTEXT); ?>
        </span>
    </div>
    <div class="pto-project-cpt-data-checkbox_input">
        <input type="checkbox" name="chosse_project[]" class="cpt_selected" value="pto-kanban" ids="custom_meta_box-notes-kanban" <?php if (array_key_exists("pto-kanban", $selected_cpt)) {
            echo "checked";
        } ?>>
        <span class="pto-project-cpt-data-checkbox_title">
            <?php esc_html_e('Kanban', PTO_NB_MYPLTEXT); ?>
        </span>
    </div>
    
    
    
</div>
</div>
<script type="text/javascript">
    if (pagenow == "pto-project") {
        function reload_cpt(type, post_id, project_id, status, publish_date) {
            let ajax_fun = "";
            if (type == "pto-meeting") {
                ajax_fun = "wpnb_render_meta_box_content_metting"
            }
            if (type == "pto-note") {
                ajax_fun = "wpnb_render_meta_box_content_notes"
            }
            if (type == "pto-tasks") {
                ajax_fun = "wpnb_render_meta_box_content_tasks";
            }
            if (type == "pto-budget-items") {
                ajax_fun = "wpnb_render_meta_box_content_budgets";
            }
            let id = jQuery("#post_ID").val();
            jQuery.ajax({
                method: "POST",
                url: custom.ajax_url,
                data: {
                    action: ajax_fun,
                    id: id,
                    post_id: post_id,
                    project_id: project_id,
                    status: status,
                    publish_date: publish_date
                },
                success: function(response) {
                    var newStr = response.substring(0, response.length - 1);
                    jQuery("#" + type).html(response);
                    if (ajax_fun == "wpnb_render_meta_box_content_tasks") {
                        check_completed();
                    }
                }
            });
        }

        function kanbanview_load(project_id) {
            setTimeout(function() {
                jQuery.ajax({
                    method: "POST",
                    url: custom.ajax_url,
                    data: {
                        action: "wpnb_task_kanban_view",
                        project_id: project_id
                    },
                    success: function(response) {
                        var newStr = response.substring(0, response.length - 1);
                        jQuery("#custom_meta_box-notes-kanban .inside").html(newStr);
                    }
                });
            }, 1000);
        }
    }
</script>
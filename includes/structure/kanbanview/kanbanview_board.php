<?php
$no_exists_value = get_post_meta($post_id, 'pto_kanban_status', true);
$kan_check = get_post_meta($post_id, "pto-kanban-view", true);
if ($no_exists_value == "") {
  $pto_kanban_status = array();
  $pto_kanban_status[0]['not-started'] = "Not Started";
  $pto_kanban_status[1]['in-progress'] = "In Progress";
  $pto_kanban_status[2]['on-hold'] = "On Hold";
  $pto_kanban_status[3]['overdue'] = "Overdue";
  $pto_kanban_status[4]['completed'] = "Completed";
  add_post_meta($post_id, 'pto_kanban_status',  $pto_kanban_status, '', 'yes');
}
$all_post_data = get_post_meta($post_id, "pto_sub_menu_cpt_add", true); // get sub post data

$get_assign3 = json_decode($all_post_data);
$get_assign3 = (array) $get_assign3;  //convert json to array cpt ids
$the_posts = get_posts(array('post_type' => 'pto-kanban', 'posts_per_page' => '-1')); //get task cpt data
$url_add = esc_url(site_url()) . "/wp-admin/post-new.php?post_type=pto-kanban&proj_id=$post_id"; //create url fronm post add
$post_ids = get_post_meta($post_id, "pto_sub_menu_cpt_add", true);
$ids = explode(",", $post_ids);
$ids_arr = array();
foreach ($ids as $ids_task) {
  $ids_arr[$ids_task] = $ids_task;
}
$no_exists_value =  get_post_meta($post_id, 'pto_kanban_status', true); // get all options from task


?>
<div class="manage-status"><a href="javascript:void(0)" class="add_new outline_btn button-primary"><?php esc_html_e('Manage Stages', PTO_NB_MYPLTEXT); ?></a></div>
<div class="task-kan-ban kanban-status-list">
  <?php
  $i = 0;
  foreach ($no_exists_value as $key => $pto_task_status) {
    foreach ($pto_task_status as $key2 => $data) {
      ?>
      <div class="sortable-data connectedSortables" data-id="<?php echo esc_html($key2); ?>" data-ids-index="<?php echo esc_html($key); ?>">
        <div class="title"> <?php echo esc_html_e($data); ?><span class="plus-icon"><a href="<?php echo esc_html_e("javascript:void(window.open('" . $url_add . "&status=$key'))"); ?>">+</a></span></div>
        <ul id="<?php echo esc_html($key2); ?>" val="<?php echo esc_html($data); ?>" class="connectedSortable">
          <?php
          $i++;
          foreach ($the_posts as $single_post) {
            if (array_key_exists($single_post->ID, $get_assign3)) {
              $ass_id2 = get_post_meta($single_post->ID, 'pto_kanban_status', true);
              if ($key == $ass_id2) {
                $url = esc_url(site_url()) ."/wp-admin/post.php?post=". $single_post->ID ."&action=edit";
                echo "<li class='ui-state-default open_kanban' data-id='" . intval($single_post->ID) . "' link='". esc_url($url) ."'><p>" . sanitize_text_field($single_post->post_title) . "</p></li>";
              }
            }
          }
        }
        ?>
      </ul>
    </div>
    <?php
  }
  ?>
</div>
<div class="pto-publish-tab-frontend">
  <input type="checkbox" name="pto-kanban-view" <?php if ($kan_check == "on") {
    echo "checked";
  } ?>>
  <label>Show kanban tab on public view &nbsp;<i class="fa fa-info-circle fas-tooltip" title="Checking this option will show this section's details on the front-end view of this project notebook. If you do not wish for this section to be visible on the front-end, leave this option unchecked."></i></label>
</div>
<?php
wp_reset_postdata();
?>
<script>
  jQuery(".sortable-data ul").each(function() {
    let ids = jQuery(this).attr("id");
    jQuery(function() {
      jQuery("#" + ids).sortable({
        connectWith: ".connectedSortable",
        receive: function(event, ui) {
          let copy = ui.item.clone(true);
          let div_drag_id = event.target.id;
          let status = jQuery("#" + div_drag_id).parents(".sortable-data").attr("data-ids-index");
          let post_drag_id = jQuery(ui.item).data('id');
          jQuery.ajax({
            method: "POST",
            url: custom.ajax_url,
            data: {
              action: 'wpnb_pto_single_post_task_status',
              status: status,
              post_id: post_drag_id,
              nonce:custom.nonce
            },
            success: function(response) {}
          });
        }
      }).disableSelection();
    });
  })
  jQuery(function() {
    let temp_arr = {};
    let i = 0;
    jQuery('.task-kan-ban').sortable({
      update: function(event, ui) {
        let post_id = jQuery('#post_ID').val();
        let ids = [];
        let vals = [];
        jQuery(".sortable-data ul").each(function(i) {
          ids.push(jQuery(this).attr("id"));
          vals.push(jQuery(this).attr("val"));
        })
        jQuery.ajax({
          method: "POST",
          url: custom.ajax_url,
          data: {
            action: 'wpnb_pto_drag_single_post_task_status',
            post_id: post_id,
            id: ids,
            vals: vals,
          },
          success: function(response) {
            //reload_cpt("pto-tasks");
          }
        });
      }
    }).disableSelection();
  })
</script>
<?php
if ("pto-meeting" === get_post_type() || "pto-note"  === get_post_type() ||  "pto-tasks" === get_post_type() ||  "pto-kanban" === get_post_type() ||  "pto-project" === get_post_type() || isset($_GET['post_type']) == "pto-project") { ?>
  <!-- pto project planner all popup for backend -->
  <div id="id01" class="pto-modal" style='display:none'>
    <div class="pto-modal-content">
      <div class="pto-modal-container-header">
        <span><?php esc_html_e('Post Status Manage:', PTO_NB_MYPLTEXT); ?></span>
        <span onclick="jQuery('#id01').removeClass('pto-modal-open');" class="w3-button w3-display-topright">×</span>
      </div>
      <div class="pto-modal-container">
        <div class="pto-update-status">
          <?php
          $post_id = get_the_ID();
          $no_exists_value = get_post_meta($post_id, 'pto_kanban_status', true);
          foreach ($no_exists_value as $key => $status2) {
            foreach ($status2 as $key2 => $status) {
              ?>
              <div class="pto-update-status-detail">
                <label><?php esc_html_e('Status', PTO_NB_MYPLTEXT); ?></label>
                <div class="pto_data">
                  <input type="text" name="<?php echo esc_html($key); ?>" value="<?php echo esc_html($status); ?>" class="pto-status-checked">
                  <button class="add_new outline_btn del-status delete-btn" del-id="<?php echo esc_html($key2); ?>"><?php esc_html_e('delete', PTO_NB_MYPLTEXT); ?></button>
                </div>
              </div>
              <?php
            }
          }
          ?>
        </div>
        <div class="pto-add-new-status">
          <label><?php esc_html_e('Add New:', PTO_NB_MYPLTEXT); ?></label>
          <input type="text" name="project_kanban_status" id="project_kanban_status">
          <span class="err-resposnse" style="display:none;">Please enter value</span>
        </div>
      </div>
      <div class="pto-modal-footer">
        <input type="button" name="ok" value="submit" class="add_new outline_btn button-primary" id="add-new-status" data-id="<?php echo esc_html($post_id); ?>" onclick="pto_project_kanban_status(this);">
        <input type="button" name="cancel" value="Cancel" class="add_new outline_btn delete-btn" onclick="jQuery('#id01').removeClass('pto-modal-open');">
      </div>
    </div>
  </div>

  <!--  project manager popup -->
  <div id="pm_adddata" class="pto-modal" style='display:none'>
    <div class="pto-modal-content">
      <div class="pto-modal-container-header">
        <span><?php esc_html_e('Add Project Manager', PTO_NB_MYPLTEXT); ?> </span>
        <span onclick="jQuery('#pm_adddata').removeClass('pto-modal-open');" class="w3-button w3-display-topright">×</span>
      </div>
      <div class="pto-modal-container">
        <div class="pto-update-pmuser-manager">
          <input type="hidden" value="2" id="pm_type" name="user_type">
        </div>
        <div class="pto-add-new-pm">
          <input type="search" name="pmsearch" id="pmsearch">
          <div class="allpmdetails">
            <div class="pto-project-manager-section">
              <div class="pto-project-manager-section-desc-details">
                <ul class="pto-project-manager-section-desc-details-ul"></ul>
              </div>
            </div>
          </div>
          <div id="selected-check">
            <div class="seleted_pm" id="selected"><input type="hidden" name="pm_selected" class="ajax_pm_pass_id" value=""></div>
          </div>
        </div>
      </div>
      <div class="pto-modal-footer">
        <input type="button" name="ok" value="Save" data-id="<?php echo get_the_ID(); ?>" class="add_new_pm outline_btn button-primary">
        <input type="button" name="cancel" id="close-pm-popup" value="Cancel" class="add_new outline_btn modal-ancle-btn delete-btn" onclick="jQuery('#pm_adddata').removeClass('pto-modal-open');">
      </div>
    </div>
  </div>
  <?php } 
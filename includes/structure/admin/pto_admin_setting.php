<div class="wp-admin pto-custom-style">
  <div class="wrap">
    <div class="row">
      <div class="pto-project-setting-header">
        <!-- /* project seeting header*/ -->
        <h1 class="pto-project-setting-header"><?php esc_html_e('Project Notebooks Settings', PTO_NB_MYPLTEXT); ?> </h1>
      </div>

      <div class="tab pto-planner-setting-tabs">
        <button class="tablinks" onclick="openCity(event, 'admin')" id="defaultOpen">ADMINISTRATORS</button>
        <button class="tablinks" onclick="openCity(event, 'sysy_email')">SYSTEM EMAILS</button>
        <button class="tablinks" onclick="openCity(event, 'shortcode')">SHORT CODES</button>
        <button class="tablinks" onclick="openCity(event, 'displayoptoion')"> DISPLAY OPTIONS</button>

      </div>
      <?php 
        $request_allow_or_not = get_option("request_allow_or_not");
      ?>
      <div class="pto-project-setting-content">
        <div id="admin" class="tabcontent">
          <div class="pto-project-setting-plugin-admin">
            <!-- project admin role add -->
            <?php include "pto_admin_setting_add_project_admin.php"; ?>
          </div>
          <div class="pto-project-setting-plugin-project-own-user">
            <!-- project own sign up role add -->
            <?php include "pto_admin_setting_add_own_admin.php"; ?>
          </div>
          <?php 
            $get_request  = get_option("request_access");
            $checked = "";
            if( $get_request == "on"  ){
              $checked = "checked";
            }
           ?>
          <input type="checkbox" name="request_allow_or_not" id="request_allow_or_not" <?php echo esc_html_e($checked); ?>> Turn on the 'Request Access'.
        </div>

        <div id="sysy_email" class="tabcontent">
          <div class="pto-project-setting-plugin-project-email-system">
            <!-- project email system setup -->
            <?php include "pto_admin_setting_email_system.php"; ?>
          </div>
        </div>

        <div id="shortcode" class="tabcontent">
          <div class="pto-admin-setting-email-system-shotcode">
            <div class="pto-admin-setting-email-system-shotcode">
              <p class="pto-admin-setting-email-system-shotcode-title">
                <h2><?php esc_html_e('Short code to display "All Projects"', PTO_NB_MYPLTEXT); ?> <i class="fa fa-info-circle fas-tooltip" title="Add this shotcode to any page to list all published Notebooks on the front-end of your site. Only those sections marked as 'Show' will display for that respective project."></i></h2>
              </p>
            </div>
            <div class="pto-admin-setting-email-system-shotcode-button">
              <!-- show shortcode in -->
              <p id="copy_short_code">[project-all-listing]</p>
              <input type="hidden" name="copy_short_code" value="[project-all-listing]">
              <input type="button" name="copy" value="COPY" id="copy_button" class="button button-primary" onclick="copy_function(this)">
            </div>
          </div>
        </div>
        <div id="displayoptoion" class="tabcontent">
          <div class="pto-admin-setting-user-details">
            <!-- project admin role add -->
            <?php include "pto_admin_font_set.php"; ?>
          </div>
        </div>
      </div>
      <div class="pto-project-setting-content-save">
        <!-- save from all data  -->
        <input type="button" name="save" value="Save" class="button button-primary" id="all-satting-save">
      </div>
    </div>
  </div>
  <!-- user data add popup -->
  <div id="user_adddata" class="pto-modal">
    <div class="pto-modal-content">
      <div class="pto-modal-container-header">
        <span class='user-title'><?php esc_html_e("Create Your Own Project Users", PTO_NB_MYPLTEXT); ?> </span>
        <span onclick="jQuery('#user_adddata').removeClass('pto-modal-open');" class="w3-button w3-display-topright">×</span>
      </div>
      <div class="pto-modal-container">
        <div class="pto-update-status">
          <input type="hidden" value="" id="user_type" name="user_type">
        </div>
        <div class="pto-add-new-status">
          <input type="search" name="usersearch" id="usersearch_admin" placeholder="search">
          <div class="alluserdetails">
          </div>
          <div id="selected-check">
          </div>
        </div>
      </div>
      <div class="pto-modal-footer">
        <!--  <input type="button" name="ok" value="submit" class="add_new_users add_new_pm outline_btn button-primary" > -->
        <input type="button" name="ok" value="Save" class="add_new_users  outline_btn button-primary">
        <input type="button" name="cancel" value="Cancel" class="add_new outline_btn delete-btn" onclick="jQuery('#user_adddata').removeClass('pto-modal-open');">
      </div>
    </div>
  </div>
</div>
<script>
  function openCity(evt, cityName) {
    var i, tabcontent, tablinks;
    tabcontent = document.getElementsByClassName("tabcontent");
    for (i = 0; i < tabcontent.length; i++) {
      tabcontent[i].style.display = "none";
    }
    tablinks = document.getElementsByClassName("tablinks");
    for (i = 0; i < tablinks.length; i++) {
      tablinks[i].className = tablinks[i].className.replace(" active", "");
    }
    document.getElementById(cityName).style.display = "block";
    evt.currentTarget.className += " active";
  }

  // Get the element with id="defaultOpen" and click on it
  document.getElementById("defaultOpen").click();
</script>
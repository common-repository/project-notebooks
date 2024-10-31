<?php
$header_setting = get_option("header_setting");
$text_setting = get_option("text_setting");
$button_setting = get_option("button_setting");
if (empty($header_setting))
	$header_setting = array();
if (empty($text_setting))
	$text_setting = array();
if (empty($button_setting))
	$button_setting = array();
?>
<div class='pto-project-font-setting-tab'>
	<h1>Project Display Scheme <i class="fa fa-info-circle fas-tooltip" title="Customize the way your 'All Projects' displays on the front-end for your users."></i></h1>
	<?php esc_html_e("Here you can set the colors and seen on your project pages to match your overall brand. Setting colors here will overide any theme settings you may currently be using.", PTO_NB_MYPLTEXT); ?>
	<div class="pto-heading-setting">
		<div class='clear-all-button'>
			<input type='button' value='CLEAR ALL' id='clear_all_setting' class="button button-primary">
		</div>
		<div class="pto-setting-heading-one">
			<label>Heading 1 Size : </label>
			<input type="number" name="heading_one" id="heading_one" min="0" max="40" value="<?php if (array_key_exists("h1", $header_setting)) {
				echo esc_html($header_setting['h1']);
				} else {
					echo esc_html("32");
				} ?>">
		</div>
		<div class="pto-setting-heading-two">
			<label>Heading 2 Size : </label>
			<input type="number" name="heading_two" id="heading_two" min="0" max="40" value="<?php if (array_key_exists("h2", $header_setting)) {
				echo esc_html($header_setting['h2']);
				} else {
					echo esc_html("24");
				} ?>">
		</div>
		<div class="pto-setting-heading-three">
			<label>Heading 3 Size : </label>
			<input type="number" name="heading_three" id="heading_three" min="0" max="40" value="<?php if (array_key_exists("h3", $header_setting)) {
				echo esc_html($header_setting['h3']);
				} else {
					echo esc_html("18");
				} ?>">
		</div>
		<div class="pto-setting-heading-four">
			<label>Heading 4 Size : </label>
			<input type="number" name="heading_four" id="heading_four" min="0" max="40" value="<?php if (array_key_exists("h4", $header_setting)) {
				echo esc_html($header_setting['h4']);
				} else {
					echo esc_html("16");
				} ?>">
		</div>
		<div class="pto-setting-heading-five">
			<label>Heading 5 Size : </label>
			<input type="number" name="heading_five" id="heading_five" min="0" max="40" value="<?php if (array_key_exists("h5", $header_setting)) {
				echo esc_html($header_setting['h5']);
				} else {
					echo esc_html("13");
				} ?>">
		</div>
		<div class="pto-setting-heading-six">
			<label>Heading 6 Size : </label>
			<input type="number" name="heading_six" id="heading_six" min="0" max="40" value="<?php if (array_key_exists("h6", $header_setting)) {
				echo esc_html($header_setting['h6']);
				} else {
					echo esc_html("10");
				} ?>">
		</div>
	</div>
	<div class="pto-heading-setting">
		<div class="pto-setting-text-size">
			<div class="pto-setting-text-input">
				<label>Regular Text Size</label>
				<input type="number" name="text_size" id="text_size" min="0" max="40" value="<?php if (array_key_exists("text_size", $text_setting)) {
					echo esc_html($text_setting['text_size']);
					} else {
						echo esc_html("16");
					} ?>">
			</div>
			<div class="pto-setting-text-input">
				<label>Regular Text Color</label>
				<input type="color" name="text_color" id="text_color" min="0" max="40" value="<?php if (array_key_exists("text_color", $text_setting)) {
						echo esc_html($text_setting['text_color']);
					} ?>">
			</div>
		</div>
	</div>
	<div class="pto-heading-button">
		<div class="pto-button-text-size">
			<div class="pto-button-text-input">
				<label>Button Background Color</label>
				<input type="color" name="button_color" id="button_color" min="0" max="40" value="<?php if (array_key_exists("button_color", $button_setting)) {
					echo esc_html($button_setting['button_color']);
					} else {
						echo esc_html('#2271b1');
					} ?>">
			</div>
			<div class="pto-button-text-input">
				<label>Button Text Color</label>
				<input type="color" name="button_text_color" id="button_text_color" min="0" max="40" value="<?php if (array_key_exists("button_text_color", $button_setting)) {
					echo esc_html($button_setting['button_text_color']);
					} else {
						echo esc_html("#ffffff");
					} ?>">
			</div>
		</div>
	</div>
</div>
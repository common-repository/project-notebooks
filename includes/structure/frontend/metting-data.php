<?php
$post_id = intval($_POST['post_id']);
$all_post_data = get_post_meta(  $post_id , "pto_sub_menu_cpt_add" , true );
$all_post_data = json_decode( $all_post_data );
$all_post_data = (array) $all_post_data; 
$filter_name = sanitize_text_field($_POST['filter_name']);
$pto_notes = array();

if( !empty( $all_post_data ) ){
	foreach( $all_post_data as $cpt_id ){
		$post_type = get_post_type( $cpt_id );

		if( $post_type == $type ){
			$pto_notes[$cpt_id] = $cpt_id;
		}
	}	
}
$ids = implode(",",$pto_notes);
if($filter_name == "name"){
	$args = array(
		'post_type'=> $type,
		'orderby'    => 'ID',
		'post_status' => 'publish',
		'order'    => 'ASC',
		'orderby' => 'title',
        'posts_per_page' => -1, // this will retrive all the post that is published 
        'post__in' => $pto_notes,
    );
}else{
	$args = array(
		'post_type'=>  $type,
		'orderby'    => 'ID',
		'post_status' => 'publish',
		'orderby' => 'date',
		'order'    => 'ASC',
        'posts_per_page' => -1, // this will retrive all the post that is published 
        'post__in' => $pto_notes,
    );
	
}

$result = new WP_Query( $args );

if ( $result-> have_posts() ) {
	while ( $result->have_posts() ) { $result->the_post();
		$meeting_id = get_the_ID();
		?>
		<li class='single-project-list'>
		<div class='small-priject-banner-img'><img src='<?php echo esc_html_e(PTO_NB_PLUGIN_PATH)?>assets/images/file_icons.png' height='150' width='150'></div>
		<div class='single-project-info'><h4 class='pto-project-metting-list-single_title pto-header-four' data-id='<?php echo intval($meeting_id)?>'><?php esc_html_e(get_the_title($meeting_id)); ?></h4>
		<p class='pto-project-metting-list-single_publish_date'> <?php echo esc_html(get_the_date('m-d-Y',$meeting_id)) ?></p></div>
		</li>
		<?php
		echo "</li>";
	}
}

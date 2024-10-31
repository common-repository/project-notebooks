<?php
$cpt_html_header = "";
$cpt = "'" . $post_type . "'";
$tbl_tr_class = "";
if ( $post_type == "pto-tasks" ){
    $tbl_tr_class = "tr-task-status-details-" . $id;
}
$cpt_html_header .= '<tr class="' . $tbl_tr_class . '"><td><div class="cpt-single-record-details"><div class="pto_cpt_get_details_checkbox-cpt">
<input type="checkbox" name="checkall" class="checkall_'.$post_type.'" post-id="'.$id.'">
</div></td>';

if ( array_key_exists( "main_fields" , $cpt_header_meta['fields'] ) ){
    $filed_name = $cpt_header_meta['fields']['main_fields'];
}
if ( array_key_exists( "meta_key" , $cpt_header_meta['fields'] ) ){
    $filed_name2 = $cpt_header_meta['fields']['meta_key'];
}
$proj_id = $cpt_header_meta['filter']['metadata']['pto_associated_project_id'];
if ( array_key_exists( "action" , $cpt_header_meta ) ){
    $filed_name3 = $cpt_header_meta['action'];
}
if ( $cpt_header_meta != "" ){
    if ( !empty( $filed_name ) ){
        foreach ( $filed_name as $key => $value ){
            if ( $key == "title" ){
                $post_status = get_post_status( $id );
                $cpt_html_header .= '<td><div class="action-checker"><div class="pto_cpt_get_details_header_title_checkbox-cpt-title">';
                // $url = get_delete_post_link( $id, '', true );
                $edit_url = "'" . site_url() . "/wp-admin/post.php?post=$id&action=edit'";
                $cpt_html_header .= '<div class="pto_cpt_get_details_header_title_checkbox-cpt">';
                $cpt_html_header .= get_the_title( $id );

                $cpt_html_header .= '<div class="action-cpt-data row-actions">';
                if($cpt_header_meta['rules']['status'] != "trash"){				
                 if($post_status != "trash")
                    $cpt_html_header .= '<span class="edit-action"><a  href="javascript:void(window.open(' . $edit_url . '))" data-title="Edit">Edit</a> <span class="hor-line">|</span> </span>';
            }
            if($cpt_header_meta['rules']['status'] == "trash" || $post_status == "trash"){
                $cpt_html_header .= '<span class="restore-action"  cpt-id="' . $id . '" type=' . $cpt . '><a href="javascript:void(0)" data-title="Restore">Restore</a> <span class="hor-line">|</span> </span>';
                $cpt_html_header .= '<span class="delete-action-cpt"  cpt-id="' . $id . '" type=' . $cpt . '><a href="javascript:void(0)"  style="color:#b32d2e" data-title="Delete Permanently ">Delete Permanently</a> <span class="hor-line">|</span> </span>';
            }else{
                $cpt_html_header .= '<span class="trush-action"><a  href="javascript:void(0)" onclick="cpt_trash_data(' . $cpt . ',' . $id . ',' . $proj_id . ')" data-title="Trash">Trash</a> <span class="hor-line">|</span> </span>';    
            }
            if (!empty($filed_name3)){

                $url = wp_nonce_url(add_query_arg(array(
                    'action' => 'wpnb_rd_duplicate_post_as_draft_project' ,
                    'post' => $id,
                ) , 'admin.php' ) , basename(__FILE__) , 'duplicate_nonce' );

                if( $cpt_header_meta['rules']['status'] != "trash" || $post_status != "trash" ) 
                    $duplicate_url = '<a href="javascript:void(window.open(\'' . $url . '&cpt_type=' . $post_type . '&project_id=' . $proj_id . '\'))"  rel="permalink" data-title="Duplicate">Duplicate</a>';

                foreach ( $filed_name3 as $key => $fileds_action ){
                    if ($key == "duplicate" ){
                        if( $post_status != "trash" )
                            $cpt_html_header .= "<span class='" . $key . "' >" . $duplicate_url . " <span class='hor-line'>|</span> </span>";
                    }
                    else if( $key == "archive" ){
                        $post_status = get_post_status( $id );
                        $url = wp_nonce_url(add_query_arg(array(
                            'action' => 'wpnb_pto_archive_project',
                            'post' => $id,
                        ) , 'admin.php') , basename(__FILE__) , 'duplicate_nonce');

                        if( $post_status == "archive" ){
                         if($cpt_header_meta['rules']['status'] != "trash"){ 
                            if($post_status != "trash")
                                $cpt_html_header .= '<span class="' . $key . '"><a href="javascript:void(window.open(\'' .$url . '&st=1&projetct=0' . '\'))"  rel="permalink" data-title="Unarchive">Unarchive</a> <span class="hor-line">|</span> </span>';
                        }
                    }
                    else{
                     if( $cpt_header_meta['rules']['status'] != "trash" ) {
                        if( $post_status != "trash" )
                            $cpt_html_header .= '<span class="' . $key . '"><a href="javascript:void(window.open(\'' . $url . '&projetct=0&pid=' . $proj_id . '\'))"  rel="permalink" data-title="Archive">Archive</a> <span class="hor-line">|</span> </span>';
                    }
                }
            }
            else if ( $key == "taskstatus" ){
                $post_meta = get_post_meta( $id , "pto_task_status" , true );
                if ($post_meta != "completed"){
                    $cpt_html_header .= "<span class='" . $key . "' cpt-id='" . $id . "' type=" . $cpt . "><a href='javascript:void(0)' data-title='Mark as Complete'>Mark as Complete</a> <span class='hor-line'>|</span> </span>";
                }
            }
            else{
                $cpt_html_header .= "<span class='" . $key . "' ><a href='' >" . $fileds_action . "</a> <span class='hor-line'>|</span> </span>";
            }
        }
    }
    $cpt_html_header .= '</div></div></div></div></td>';
}
else if ( $key == "date" ){
    $cpt_html_header .= '<td><div class="pto_cpt_get_details_header_title_checkbox-cpt">';
    $date = get_the_date( 'm-d-Y' , $id );
    $cpt_html_header .= $date;
    $cpt_html_header .= '</div></td>';
}else if ( $key == "description" ){
    $cpt_html_header .= '<td><div class="pto_cpt_get_details_header_title_checkbox-cpt">';
    $date = mb_strimwidth( $post_content , 0 , 100 , "..." );
    $cpt_html_header .= $date;
    $cpt_html_header .= '</div></td>';

}else if ( $key == "categories" ){
    $category = get_the_terms( $id , 'NoteCategories' );
    $cpt_html_header .= '<td><div class="pto_cpt_get_details_header_title_checkbox-cpt">';
    if ( $category != "" ){
        foreach ( $category as $cat ){
            $cpt_html_header .= "<span>" . $cat->name . "</span> ";
        }
    }else{
        $cpt_html_header .= "Uncategorized";
    }
    $cpt_html_header .= '</div></td>';
}
}
}
if ( !empty( $filed_name2 ) ){
    foreach ( $filed_name2 as $key => $value ){
        $cpt_html_header .= "<td>";
        $cpt_html_header .= '<div class="pto_cpt_get_details_header_title_checkbox-cpt">';
        $post_meta = get_post_meta($id, $key, true);
        if ( $key == "pto_user_assign_key" ){
            $all_username = "";
            if ( $post_meta != "" ){
                foreach ( $post_meta as $userid ){
                    $user = get_user_by( 'id' , $userid );
                    $first_name = get_user_meta( $userid , 'first_name' , true );
                    $last_name = get_user_meta( $userid , 'last_name' , true );
                    $full_name = $first_name . " " . $last_name;
                    if ($full_name != " "){
                        $all_username .= $full_name . " - ";
                    }else{
                        $all_username .= $user->data->display_name . " - ";
                    }
                }
                $remove_last_char = substr( $all_username , 0 , -2);
                $cpt_html_header .= "<span class='all_user-list'>" . $remove_last_char . "</span>";
            }
        }
        else{
            if ( !empty( $post_meta ) ){
                
                if ( $key == "pto_task_due_date" ){
                    $cpt_html_header .= "<span class='all_user-list'>" . $post_meta['due_date'] . "</span>";
                }else if ($key == "pto_task_status"){
                    $cpt_html_header .= "<span data-pid='" . $tbl_tr_class . "' class='task-status-view " . $post_meta . "'>" . $post_meta . "</span>";
                }
                else if ( $key == "budget_items_type_value" ){
                    $nombre_format_francais = number_format( $post_meta , 2 , '.' , ',' );
                    $post_meta_type = get_post_meta( $id , "budget_items_type" , true );
                    $cpt_html_header .= "<span data-pid='" . $tbl_tr_class . "' type='" . $post_meta_type . "' class='budget_items_value' val='" . $post_meta . "'> <b>$</b>" . $nombre_format_francais . "</span>&nbsp;&nbsp;&nbsp;";
                }else{
                    $post_meta_type = get_post_meta( $id , "budget_items_type" , true );
                    $cpt_html_header .= "<span data-pid='" . $tbl_tr_class . "' type='" . $post_meta_type . "' class='budget_items_value' val='" . $post_meta . "'> <b>$</b>" . $post_meta . "</span>&nbsp;&nbsp;&nbsp;";
                }
            }
        }
        $cpt_html_header .= '</div></td>';
    }   
}
$cpt_html_header .= '</div></tr>';
}


/*
* Get themes and plugin list and then this data will be displayed in the dropdown. 
*/
// jQuery("#set-post-thumbnail").html("Set banner image");
// jQuery("#remove-post-thumbnail").html("Remove banner image");
// jQuery( document ).ajaxComplete(function() {
//  	jQuery("#set-post-thumbnail").html("Set banner image");
// 	jQuery("#remove-post-thumbnail").html("Remove banner image");
// });
jQuery(document).ready(function () {
	jQuery("div#project-categoriesdiv .ui-sortable-handle").html("Project Categories");
});
jQuery(document).on("dblclick",".open_kanban",function(){
	let url = jQuery(this).attr("link");
	window.open(url);
})
/* user search in metting cpt */
jQuery( document ).on( 'keyup', '#usersearch', function(){
	var myarray = [];
	jQuery("#selected-attende .single-user").each(function(){
		myarray.push(jQuery(this).val());
	})
	let name = jQuery(this).val();
	jQuery.ajax({
		method:"POST",
		url:custom.ajax_url,
		data:{action:'wpnb_pto_get_users_data_in_metting',name:name,ids:myarray,nonce:custom.nonce},
		success:function( response ){var tab_id = jQuery(this).attr('data-tab');
			jQuery(".alluserdetails").html(response);
		}
	});
});
jQuery(document).on("click","#clear_all_setting",function(){
	jQuery("#heading_one").val("32");
	jQuery("#heading_two").val("24");
	jQuery("#heading_three").val("18");
	jQuery("#heading_four").val("16");
	jQuery("#heading_five").val("13");
	jQuery("#heading_six").val("10");

	jQuery("#text_size").val("16");
	jQuery("#text_color").val("#000");
	jQuery("#button_color").val("#2271b1");
	jQuery("#button_text_color").val("#ffffff");
})
jQuery(document).on("change",".pto_admin_user",function(){
	let id = jQuery(this).attr("id");
	let name = jQuery(this).attr("name");
	if(jQuery(this).prop("checked")){
		jQuery("#selected-check").append("<div class='seleted_user' id='selected_"+ id +"'>"+name+"<i class='fa fa-times' aria-hidden='true' onclick='remove_user(\"selected_"+ id +"\");'></i><input type='hidden' name='user_selected' class='ajax_user_pass_id' value='"+ id +"'></div>");
	}else{
		jQuery("#selected_"+id ).remove();      
	}
})

/* user search in metting cpt */
jQuery( document ).on( 'keyup', '#usersearch_admin', function(){
	
	let user_type = jQuery("#user_type").val();
	let name = jQuery(this).val();
	jQuery.ajax({
		method:"POST",
		url:custom.ajax_url,
		data:{action:'wpnb_pto_get_users_data_in_admin',name:name,user_type:user_type,nonce:custom.nonce},
		success:function( response ){
			jQuery(".alluserdetails").html(response);
		}
	});
	
});

/* meting cpt in all check user */
jQuery(document).on("change","#checkall",function(){
	if(jQuery(this).prop("checked")){
		jQuery(".single-user").prop("checked",true);
	}else{
		jQuery(".single-user").prop("checked",false);
	}
});

/* add hideen user data add in metting cpt */    
jQuery(document).on("change",".single-users",function(){
	let ids= jQuery(this).attr("ids");
	let ids_val= jQuery(this).val();
	if(jQuery(this).prop("checked")){
		jQuery("#selected-check ul").append("<li><input type='hidden' class='single-user' name='user[]' value='"+ ids_val +"' id='"+ ids +"'></li>");
	}else{
		jQuery("#"+ids).parent().remove();
	}
}); 
// hide show project in cpts 


// hide show project in cpts 
jQuery(".cpt_selected").each(function(){
	let ids = jQuery(this).attr("ids");
	if(jQuery("#"+ids).hasClass("hide-if-js")){
		jQuery("#"+ids).removeClass("hide-if-js")
	}
	if(!jQuery(this).prop("checked")){
		jQuery("#"+ids).hide();
	}
});

// checkbox checked cpt show
jQuery(document).on("click",".cpt_selected",function(){
	let ids= jQuery(this).attr("ids");
	if(jQuery(this).prop("checked")){
		jQuery("#"+ids).show();
	}else{
		jQuery("#"+ids).hide();
	}
})
/* delete use fro m metting attendes */
function delete_user(ids,id,post_id)
{
	swal({
		title: "Are you sure?",
		text: "Once deleted, you will not be able to recover this user!",
		icon: "warning",
		buttons: true,
		dangerMode: true,
	})
	.then((willDelete) => {
		if (willDelete) {
			jQuery.ajax({
				method:"POST",
				url:custom.ajax_url,
				data:{action:'wpnb_pto_single_user_delete_in_metting',id:id,post_id:post_id,nonce:custom.nonce},
				success:function( response ){
					jQuery("#"+ids).remove();
					swal("Poof! Your user has been deleted!", {
						icon: "success",
					});
				}
			});
		} 
	});
}


/* due date select popup */   
jQuery(document).on("change","#select-reminder",function(){
	let due_date = jQuery(".duedate_filed").val();
	let due_time = jQuery("#duetime_filed").val();
	if(due_date == "")
	{
		swal({
			title : "Please select the due date and time.",
			button: "OK",
		})
		jQuery(this).prop("checked",false);
		return false;
	}
	if(jQuery(this).prop("checked"))
	{
		jQuery("#list-of-reminder").show();
	}else{
		jQuery(".reminder-checked").prop("checked",false);
		jQuery("#list-of-reminder").hide();
	}
})


/* due date option hide show */
jQuery(document).on("change","#due-custom",function(){
	
	if(jQuery(this).prop("checked"))
	{
		jQuery(".due-date-custom").show();
	}else{
		
		jQuery(".due-date-custom").hide();
	}
})


/* kan ban view statsus filed get after update */
function pto_project_kanban_status()
{
	let post_id = jQuery('#add-new-status').data('id');
	let status_val = jQuery("#project_kanban_status").val();
	let status_name_old = [];
	let status_vals_old = [];
	let i = 0;
	jQuery(".pto-status-checked").each(function(){
		let n = jQuery(this).attr("name");
		let v = jQuery(this).val();
		status_name_old[i] = n;
		i++;
		status_name_old[i] = v;
		i++;
	})
	
	const orderInputObjects = [];

	status_name_old.forEach(function(v, i, a) {
		if(i % 2) orderInputObjects.push({ [a[i - 1]]: v });
	});
	

	jQuery.ajax({
		method:"POST",
		url:custom.ajax_url,
		data:{post_id:post_id,action:'wpnb_task_kanban_view_status',status_val:status_val,pto_old_status:orderInputObjects,nonce:custom.nonce},
		success:function( response ){
			const obj = JSON.parse(response);
			let html = "";
			jQuery.each( obj, function( key, value ) {
				jQuery.each( value, function( key2, value2 ) {
					html += '<div class="pto-update-status-detail"><label>Status</label>  <div class="pto_data"> <input type="text" name="'+key2+'" value="'+value2+'" class="pto-status-checked">  <button class="add_new outline_btn " del-id="'+key2+'">delete</button></div></div>';
				});
			});
			jQuery(".pto-update-status").html(html);
			kanbanview_load(jQuery("#post_ID").val());
			reload_cpt("pto-tasks");
			jQuery("#id01").removeClass("pto-modal-open");
		}
	});

	
}

/* project in cpt data remove */
function cpt_trash_data(cpt_type,cpt_post_id,project_id)
{
	let msg = "";
	if( cpt_type == "pto-budget-items" ){
		msg = "Are you you sure you want to remove this budget item?";
	}else if(cpt_type == "pto-note"){
		msg = "Are you you sure you want to remove this note?";
	}else if(cpt_type == "pto-meeting"){
		msg = "Are you you sure you want to remove these minutes?";
	}else if(cpt_type == "pto-tasks"){
		msg = "Are you you sure you want to remove this task?";
	}else{
		msg = "Are you sure you want to delete?";
	}
	let status = jQuery("#"+cpt_type + " .pto-cpt-status.active").attr("data-type");
	swal({
		text: msg,
		icon: "warning",
		buttons: true,
		dangerMode: true,
	})
	.then((willDelete) => {
		if (willDelete) {
			jQuery.ajax({
				method:"POST",
				url:custom.ajax_url,
				data:{action:'wpnb_pto_trash_cpt',type:cpt_type,post_id:cpt_post_id,project_id:project_id,nonce:custom.nonce},
				success:function( response ){

					reload_cpt(cpt_type,"","",status);
					if(cpt_type == "tasks")
					{

						let po_id = jQuery("#post_ID").val();
						setTimeout(function(){ 

							jQuery.ajax({
								method:"POST",
								url:custom.ajax_url,
								data:{action:"wpnb_task_kanban_view",project_id:po_id,nonce:custom.nonce},
								success:function( response ){
									var newStr = response.substring(0, response.length - 1);
									jQuery("#custom_meta_box-notes-kanban .inside").html(newStr);
								}
							});
						}, 1000);
					}
				}
			});
		} 
	});
}



/* task status chnage */
jQuery(document).on("click",".taskstatus",function(){
	let post_id = jQuery(this).attr("cpt-id");
	let type = jQuery(this).attr("type");
	swal({
		
		text: "Are you sure you want to change the status?",
		icon: "warning",
		buttons: true,
		dangerMode: true,
	})
	.then((willDelete) => {
		if (willDelete) {
			jQuery.ajax({
				method:"POST",
				url:custom.ajax_url,
				data:{action:'wpnb_pto_single_post_task_status',post_id:post_id,nonce:custom.nonce},
				success:function( response ){
					reload_cpt(type);
				}
			});
		} 
	});
})

// hide show project in cpts 
jQuery(".cpt_selected").each(function(){
	let ids = jQuery(this).attr("ids");
	if(!jQuery(this).prop("checked")){
		jQuery("#"+ids).hide();
	}
});

// checkbox checked cpt show
jQuery(document).on("click",".cpt_selected",function(){
	let ids= jQuery(this).attr("ids");
	if(jQuery(this).prop("checked")){
		jQuery("#"+ids).show();
	}else{
		jQuery("#"+ids).hide();
	}
})

// task status change 
jQuery(document).on("click",".manage-status a",function(){
	jQuery(".err-resposnse").hide();
	jQuery("#project_kanban_status").val("");
	jQuery("#id01").addClass("pto-modal-open");

});


// remove status in task
jQuery(document).on("click",".del-status",function(){
	let po_id = jQuery("#post_ID").val();
	swal({
		
		text: "Are you sure you want to delete the stages?",
		icon: "warning",
		buttons: true,
		dangerMode: true,
	})
	.then((willDelete) => {
		if (willDelete) {
			let status_key = jQuery(this).attr("del-id");
			jQuery.ajax({
				method:"POST",
				url:custom.ajax_url,
				data:{po_id:po_id,action:'wpnb_task_kanban_view_status_delete',status_key:status_key,nonce:custom.nonce},
				success:function( response ){
					const obj = JSON.parse(response);
					let html = "";
					jQuery.each( obj, function( key, value ) {
						jQuery.each( value, function( key2, value2 ) {
							html += '<div class="pto-update-status-detail"><label>Status</label>  <div class="pto_data"> <input type="text" name="'+key2+'" value="'+value2+'" class="pto-status-checked">  <button class="add_new outline_btn del-status" del-id="'+key2+'">delete</button></div></div>';
						});
					});
					jQuery(".pto-update-status").html(html);
					reload_cpt("tasks");
					setTimeout(function(){ 

						jQuery.ajax({
							method:"POST",
							url:custom.ajax_url,
							data:{action:"wpnb_task_kanban_view",project_id:po_id},
							success:function( response ){
								var newStr = response.substring(0, response.length - 1);
								jQuery("#custom_meta_box-notes-kanban .inside").html(newStr);
							}
						});
					}, 1000);
				}
			});
		}
	});
	return false;
});   


// upload attechment in project cpt
jQuery(document).on("click",".remove_image",function(){

	jQuery(this).parent().addClass("remove-attech");
	
	let imag_name = jQuery(this).attr('img');
	let post_id = jQuery("#post_ID").val();

	jQuery.ajax({
		method:"POST",
		url:custom.ajax_url,
		data:{action:"wpnb_cpt_project_attechment",imag_name:imag_name,post_id:post_id,nonce:custom.nonce},
		success:function( response ){
			jQuery(".remove-attech").remove();
		}
	});
})




/* project setting tab in remove user */
jQuery(document).on("click",".delete_user_project",function(){
	let ids_type = jQuery(this).attr("type");
	swal({
		
		text: "Are you sure you want to delete user?",
		icon: "warning",
		buttons: true,
		dangerMode: true,
	})
	.then((willDelete) => {
		if (willDelete) {
			let user_id = jQuery(this).attr("id");
			jQuery.ajax({
				method:"POST",
				url:custom.ajax_url,
				data:{action:"wpnb_pto_users_deletd",user_id:user_id,ids_type:ids_type,nonce:custom.nonce},
				success:function( response ){
					window.location.reload(true);
				}
			});
		} 
	});
});
/* project setting tab in add user */
jQuery(document).on("click",".add_new_users",function(){
	let ids = "";
	jQuery(".ajax_user_pass_id").each(function(){
		ids += jQuery(this).val() + "," ;
	})
	let user_type = jQuery("#user_type").val();
	var newStr = ids.substring(0, ids.length - 1);
	if(newStr != "")
	{
		jQuery.ajax({
			method:"POST",	
			url:custom.ajax_url,
			data:{action:"wpnb_pto_new_users_add",ids:newStr,user_type:user_type,nonce:custom.nonce},
			success:function( resu ){
				jQuery('input[name="cancel"]').trigger('click');
				jQuery("#all-satting-save").trigger("click");
				// location.reload();
			}
		});
	}
})

/**
 * Resend Invitation
 * */

 /* project setting tab in add user */
 jQuery(document).on("click",".resend-invite",function(){
 	
 	let user_id = jQuery( this ).attr( 'id' );
 	let user_type = jQuery( this ).data( 'user-type' );
 	
 	jQuery.ajax({
 		method:"POST",	
 		url:custom.ajax_url,
 		data:{ 
 			action:"wpnb_pto_resend_invitation",
 			user_id:user_id,
 			user_type:user_type,
 			nonce:custom.nonce
 		},
 		success:function( resu ){
 			
 			swal("Poof! Your user Mail Sent", {
 				icon: "success",
 			});
 			
 		}
 	});
 	
 })

 /* project setting tab in data save */
 jQuery(document).on("click","#all-satting-save",function(){
    jQuery(".switch-tmce").each(function(){
        jQuery(this).trigger("click");
    })
 	/* setting get */
 	let h1 = jQuery("#heading_one").val();
 	let h2 = jQuery("#heading_two").val();
 	let h3 = jQuery("#heading_three").val();
 	let h4 = jQuery("#heading_four").val();
 	let h5 = jQuery("#heading_five").val();
 	let h6 = jQuery("#heading_six").val();
 	let text_size = jQuery("#text_size").val();
 	let text_color = jQuery("#text_color").val();
 	let button_color = jQuery("#button_color").val();
 	let button_text_color = jQuery("#button_text_color").val();
 	/* end */
 	let user_per ="";
 	jQuery(".allow-user-permision").each(function(){
 		if(jQuery(this).prop("checked")){
 			user_per =  jQuery(this).val();
 		}
 	})
 	let request_access = "";
 	if( jQuery("#request_allow_or_not").prop("checked") ){
 		request_access = "on";
 	}else{
 		request_access = "off";
 	}
 	let email_system = tinymce.get("email_system").getContent();
 	let task_email_system = tinymce.get("task_email_system").getContent();
 	jQuery.ajax({
 		method:"POST",
 		url:custom.ajax_url,
 		data:{action:"wpnb_pto_new_email_system_save",task_email_system:task_email_system,email_system:email_system,user_per:user_per,h1:h1,h2:h2,h3:h3,h4:h4,h5:h5,h6:h6,text_size:text_size,text_color:text_color,button_color:button_color,button_text_color:button_text_color,nonce:custom.nonce,request_access:request_access},
 		success:function( resu ){
 			window.location.reload(true);
 		}
 	});
 })

 /* project setting tab in copy shortcode */
 function copy_function(buttons) {
 	var $temp = jQuery( "<input>" );
 	jQuery( "body" ).append( $temp );
 	$temp.val( jQuery("#copy_short_code").html() ).select();
 	document.execCommand( "copy" );
 	$temp.remove();
 	jQuery("#copy_button").val("COPIED!");
 	setTimeout(function(){ 
 		jQuery("#copy_button").val("COPY");
 	}, 3000);
 }
 function remove_user(user_id){
 	let explod_id = user_id.split("_");
 	let ids = "checked_" + explod_id[1];
 	jQuery("."+ids).prop("checked",false);
 	jQuery("#"+user_id).remove();
 }

 jQuery(document).on("click" , "#publish",function(){
 	let title = jQuery("#title").val();
 	if( title == "")
 	{
 		swal({
		  text: "Please enter title.",
		  icon: "warning",
		  
		});
 		return false;
 	}
 })
 jQuery(document).on("click",".post-type-pto-budget-items #publish",function(){

 	let total_budgets = jQuery("#total_budgets").val();

 	let final_range =0;
 	jQuery(".items-type-input").each(function(){
 		if(jQuery(this).prop("checked"))
 		{
 			let val = jQuery(this).val();
 			let expence = jQuery("#total_budgets_assign_expence").val();
 			let revenue = jQuery("#total_budgets_assign_revenue").val();
 			let old_val = jQuery("#budget_item_value_old").val();
 			let item_new_value = jQuery("#budget_item_value").val();
 			if(val == "expense")
 			{
 				final_range = parseInt(total_budgets) - parseInt(expence);
 				final_range = parseInt(final_range) + parseInt(revenue);
 				final_range = parseInt(final_range) - parseInt(old_val);
 				final_range = parseInt(final_range) - parseInt(item_new_value);

 			}else if(val == "revenue")
 			{
 				final_range = parseInt(total_budgets) - parseInt(expence);
 				final_range = parseInt(final_range) + parseInt(revenue);
 				final_range = parseInt(final_range) + parseInt(item_new_value);
 				final_range = parseInt(final_range) - parseInt(old_val);

 			}
 		}

 	})
 	
	/*if(final_range < 0)
	{
		swal({
		  text: "Your budget has not been more than the starting amount you have.",
		  icon: "warning",
		  
		});
		return false;
	}*/
	
})

 jQuery(document).on("click",".button_check",function(){

 	let total = jQuery("#total-budgets").val();

 	if(total == ""){
		/*swal({
		  text: "Starting Budget must not be blanked.",
		  icon: "warning",
		  
		});*/
		return false;
	}
	let total_cnt_budgets = jQuery("#budget-count").val();
	if(total_cnt_budgets <= 0 || total_cnt_budgets == ""){
    	/*swal({
		  text: "Your budget has not been more than the starting amount you have.",
		  icon: "warning",
		  
		});*/
		return false;
	} 
});
 
 jQuery(document).on("blur","#total-budgets",function(){
 	let post_id = jQuery("#post_ID").val(); 
 	let val = jQuery(this).val();
 	jQuery.ajax({
 		method:"POST",	
 		url:custom.ajax_url,
 		dataType : 'json',
 		data:{action:"wpnb_pto_budget_add_value",post_id:post_id,val:val,nonce:custom.nonce},
 		success:function( resu ){
 			if( resu.price ){
 				// let dollarUSLocale = Intl.NumberFormat('en-US');
 				jQuery(".total_cnt").html("$"+ parseFloat(resu.price) );	
 			}
 		}
 	});
 })


 let total = jQuery("#total-budgets").val();
 jQuery(".budget_items_value").each(function(){
 	let type = jQuery(this).attr("type");
 	let val = jQuery(this).attr("val");
 	
 	if(type == "expense"){
 		total = parseFloat(total) - parseFloat(val);
 	}else{
 		total = parseFloat(total) + parseFloat(val);
 	}
 	
 })
 let dollarUSLocale = Intl.NumberFormat('en-US');
 jQuery(".total_cnt").html("$"+dollarUSLocale.format(total));

 jQuery( document ).ajaxComplete(function( event, request, settings ) {
 	if(jQuery('#total-budgets').length != 0){
 		let total = jQuery("#total-budgets").val();
 		if( total == "" )
 		{
 			total = 0;
 		}
 		jQuery(".budget_items_value").each(function(){
 			let type = jQuery(this).attr("type");
 			let val = jQuery(this).attr("val");
 			if(type == "expense"){

 				total = parseFloat(total) - parseFloat(val);
 				
 			}else{

 				total = parseFloat(total) + parseFloat(val);
 				
 			}
 		})

 		if ( total < 0 ) {
 			jQuery(".total_cnt").css( { 'color': 'red' } );
 		}else{
 			jQuery(".total_cnt").css( { 'color': 'black' } );
 		}

 		if( jQuery( '#total-budgets' ).val() == '' ){
 			jQuery( '#total-budgets' ).val(0.00);
 		}
 		let dollarUSLocale = Intl.NumberFormat('en-US');
 		jQuery(".total_cnt").html("$"+dollarUSLocale.format(total));

 	}	
 });

 if ( total < 0 ) {
 	jQuery(".total_cnt").css( { 'color': 'red' } );
 }else{
 	jQuery(".total_cnt").css( { 'color': 'black' } );
 }

 if( jQuery( '#total-budgets' ).val() == '' ){
 	jQuery( '#total-budgets' ).val(0.00);
 }

 jQuery('#pmsearch').donetyping(function(){
 	var pid = jQuery('.add_new_pm').data('id');
 	var search_user = jQuery(this).val();
 	jQuery.ajax({
 		method:"POST",	
 		url:custom.ajax_url,
 		data:{
 			pid : pid,
 			search_user:search_user,
 			action:"wpnb_get_all_pm_users",
 			nonce:custom.nonce
 		},
 		success:function( data){
 			
 			jQuery(".pto-project-manager-section-desc-details-ul").html(data);
 		}
 	});
 });

 jQuery(document).on("change",".post-type-project .pto_pm_user",function(){
 	let id = jQuery(this).attr("id");
 	let name = jQuery(this).attr("name");
 	if(jQuery(this).prop("checked")){
 		jQuery("#selected-check").append("<div class='seleted_pm' id='selected_"+ id +"'>"+name+"<i class='fa fa-times' aria-hidden='true' onclick='remove_user(\"selected_"+ id +"\");'></i><input type='hidden' name='user_selected' class='ajax_user_pass_id' value='"+ id +"'></div>");
 	}else{
 		jQuery("#selected_"+id ).remove();      
 	}
 });

 jQuery(document).on('click' , '.add_new_pm' , function(){
 	var post_id = jQuery('.add_new_pm').data('id');
 	var selected_user_id = [];
 	jQuery('.pto_pm_user_checkbox input:checked').each(function() {
 		selected_user_id.push(jQuery(this).attr('id'));
 	});
 	if(selected_user_id == ""){
 		swal({
 			title : "Please select the user.",
 			button: "OK",
 		})
 	}else{
 		jQuery.ajax({
 			method:"POST",	
 			url:custom.ajax_url,
 			data:{
 				post_id : post_id,
 				selected_user_id : selected_user_id,
 				action:"wpnb_get_all_project_manager",
 				nonce:custom.nonce
 			},
 			success:function( data){
 				console.log(data);
 				jQuery('.project-pm-list-tbl #the-list').html(data);
 				jQuery('#close-pm-popup').trigger('click');
 			}
 		});
 	}
 	
 });

 jQuery(document).on('click' , '.pm_delete' , function(){
 	var pid = jQuery(this).data('pid');
 	var uid = jQuery(this).data('userid');
 	
 	swal({
 		title: "Are you sure?",
 		text: "Once deleted, you will not be able to recover this user!",
 		icon: "warning",
 		buttons: true,
 		dangerMode: true,
 	})
 	.then((willDelete) => {
 		if (willDelete) {
 			jQuery.ajax({
 				method:"POST",	
 				url:custom.ajax_url,
 				data:{
 					pid : pid,
 					uid : uid,
 					action:"wpnb_delete_pmuser_from_post",
 					nonce:custom.nonce
 				},
 				success:function( data){
 					jQuery('#user-'+uid).remove();
 					swal("Poof! Your user has been deleted!", {
 						icon: "success",
 					});
 				}
 			});
 		} 
 	});
 	
 });

 jQuery(document).on('change' , '#show_completed_chk' , function(){
 	let post_id = jQuery("#post_ID").val();
 	if (jQuery('input.check_completed_post').is(':checked')) {
 		if(jQuery('.task-status-details tr span.completed').length){
 			console.log(jQuery('.task-status-details tr span.completed').length);
 			jQuery('.task-status-details tr span.completed').closest('tr').show();
 		}
 		jQuery.ajax({
 			method:"POST",	
 			url:custom.ajax_url,
 			data:{
 				action:"wpnb_update_task_show_data",
 				task_show : 1,
 				post_id: post_id,
 				nonce:custom.nonce
 			},
 			success:function( data){

 			}
 		});
 	}else{
 		jQuery('.task-status-details tr span.completed').closest('tr').hide();
 		jQuery.ajax({
 			method:"POST",	
 			url:custom.ajax_url,
 			data:{
 				action:"wpnb_update_task_show_data",
 				task_show : "",
 				post_id: post_id,
 				nonce:custom.nonce
 			},
 			success:function( data){

 			}
 		});
 	}
 });
 function check_completed(){
 	if(!jQuery("#show_completed_chk").prop("checked")){
 		jQuery("span.completed").each(function(){
 			let idsremove = jQuery(this).attr("data-pid");			
 			jQuery("tr."+idsremove).hide();
 		})	
 	}
 }

 check_completed();
 jQuery( document ).ajaxComplete(function( event, request, settings ) {
 	check_completed();
 });

 jQuery(document).on('click' , '.add-pm-user' , function(){
 	jQuery('#pmsearch').val('');
 	jQuery('.pto-project-manager-section-desc-details-ul').html('');
 	jQuery('#selected-check').html('');
 });

 jQuery(document).on('click', '#select-reminder' , function(){
 	restrict_user_toadd_days_hours();
 });

 jQuery(document).on('change', '.duedate_filed' , function(){
 	restrict_user_toadd_days_hours();
 });

 jQuery(document).on('keyup' , 'input[name="custom_range"]' , function(){
 	var cr = jQuery("input[name='custom_range']").val();
 	var select_val = jQuery( ".due-date-custom select option:selected" ).text();
 	console.log(select_val);
 	/**due date and time */
 	var due_date = jQuery('.duedate_filed').val();
 	var due_time = jQuery('#duetime_filed').val();
 	var due_dateTime = due_date+' '+due_time;
 	datetime1 = new Date(due_dateTime);

 	/**current date and time */
 	var today = new Date();
 	var currentdate = today.getFullYear()+'-'+(today.getMonth()+1)+'-'+today.getDate();
 	var time = today.getHours() + ":" + today.getMinutes() + ":" + today.getSeconds();
 	var current_dateTime = currentdate+' '+time;
 	datetime2 = new Date(current_dateTime);


 	var res = Math.abs(datetime1 - datetime2) / 1000;
 	
		// get total days between two dates
		var days = Math.floor(res / 86400);

		// get hours 
		var diff =(datetime2.getTime() - datetime1.getTime()) / 1000;
		diff /= (60 * 60);
		var diff_hours = Math.abs(Math.round(diff));

		if(select_val == 'Days'){
			console.log('days');
			if(cr > days){
				swal({
					title : "Value of Days limit exceed.",
					button: "OK",
				})
			}
		}
		
		if(select_val == 'Hours'){
			console.log(diff_hours);
			console.log(cr);
			if(cr > diff_hours){
				swal({
					title : "Value of Hours limit exceed.",
					button: "OK",
				})
			}
		}
		
	});

 function restrict_user_toadd_days_hours(){
 	if (jQuery('input#select-reminder').is(':checked')) {
 		/**due date and time */
 		var due_date = jQuery('.duedate_filed').val();
 		var due_time = jQuery('#duetime_filed').val();
 		var due_dateTime = due_date+' '+due_time;
 		datetime1 = new Date(due_dateTime);

 		/**current date and time */
 		var today = new Date();
 		var currentdate = today.getFullYear()+'-'+(today.getMonth()+1)+'-'+today.getDate();
 		var time = today.getHours() + ":" + today.getMinutes() + ":" + today.getSeconds();
 		var current_dateTime = currentdate+' '+time;
 		datetime2 = new Date(current_dateTime);


 		var res = Math.abs(datetime1 - datetime2) / 1000;
 		
		// get total days between two dates
		var days = Math.floor(res / 86400);
		if(days < 1)   {
			jQuery('input[value="1day"]').prop( "disabled", true );
			
		}else{
			jQuery('input[value="1day"]').prop( "disabled", false );
		}
		if(days < 7){
			jQuery('input[value="1week"]').prop( "disabled", true );
		}else{
			jQuery('input[value="1week"]').prop( "disabled", false );
		}
		if(days < 14){
			jQuery('input[value="2week"]').prop( "disabled", true );
		}else{
			jQuery('input[value="2week"]').prop( "disabled", false );
		}

		
		// get hours 
		var diff =(datetime2.getTime() - datetime1.getTime()) / 1000;
		diff /= (60 * 60);
		var diff_hours = Math.abs(Math.round(diff));
		
		if(days < 7){
			jQuery('option[value="week"]').prop( "disabled", true );
		}else{
			jQuery('option[value="week"]').prop( "disabled", false );
		}

		if(days > 7){
			jQuery('option[value="days"]').prop( "disabled", true );
		}else{
			jQuery('option[value="days"]').prop( "disabled", false );
		}

		if(days < 30){
			jQuery('option[value="month"]').prop( "disabled", true );
		}else{
			jQuery('option[value="month"]').prop( "disabled", false );
		}

		if(days < 1){
			console.log(days);
			jQuery('option[value="days"]').prop( "disabled", true );
		}else{
			jQuery('option[value="days"]').prop( "disabled", false );
		}
	}
}
jQuery( function() {
	jQuery( "#datepicker" ).datepicker({
		minDate: new Date(),
	});
	jQuery( ".datepicker" ).datepicker({
		// minDate: new Date(),
	});
} );


jQuery(document).on("click",".header-all-cpt-data,.header-publish-cpt-data,.header-trush-cpt-data",function(){
	jQuery(".header-all-cpt-data").removeClass("active");
	jQuery(this).addClass("active");
	reload_cpts(jQuery(this).attr("post-type"),"",jQuery("#post_ID").val(),jQuery(this).attr("data-type"));

})

function reload_cpts(type,post_id,project_id,status)
{   
	let ajax_fun ="";
	if(type == "pto-meeting")
	{
		ajax_fun = "wpnb_render_meta_box_content_metting"
	}
	if(type == "pto-note")
	{
		ajax_fun = "wpnb_render_meta_box_content_notes"
	}
	if(type == "pto-tasks")
	{
		ajax_fun = "wpnb_render_meta_box_content_tasks";
	}
	if(type == "pto-budget-items")
	{
		ajax_fun = "wpnb_render_meta_box_content_budgets";
	}


	let id = jQuery("#post_ID").val();
	jQuery.ajax({
		method:"POST",
		url:custom.ajax_url,
		data:{action:ajax_fun,id:id,post_id:post_id,project_id:project_id,status:status},
		success:function( response ){
			var newStr = response.substring(0, response.length - 1);
			jQuery("#" + type).html(response);
			if( ajax_fun == "wpnb_render_meta_box_content_tasks"){
				check_completed();
			}
		}
	});
}

jQuery(document).on("click",".restore-action",function(){
	let cpt_type = jQuery(this).attr("type");
	let status = jQuery("#"+cpt_type + " .pto-cpt-status.active").attr("data-type");
	let restore_id = jQuery(this).attr("cpt-id");
	jQuery.ajax({
		method:"POST",
		url:custom.ajax_url,
		data:{action:"wpnb_pto_restore_cpt_project",restore_id:restore_id,nonce:custom.nonce},
		success:function( response ){
			reload_cpt(cpt_type,"","",status);      
		}
	});
})

/* delete cpt from project cpt inside cpts */
jQuery(document).on("click",".delete-action-cpt",function(){
	swal({
		title: "Are you sure?",
		text: "Once deleted, you will not be able to recover this!",
		icon: "warning",
		buttons: true,
		dangerMode: true,
	})
	.then((willDelete) => {
		if (willDelete) {
			let cpt_type = jQuery(this).attr("type");
			let status = jQuery("#"+cpt_type + " .pto-cpt-status.active").attr("data-type");
			let delet_id = jQuery(this).attr("cpt-id");
			let project_id = jQuery("#post_ID").val();
			/* delete ajax call */
			jQuery.ajax({
				method:"POST",
				url:custom.ajax_url,
				data:{action:"wpnb_pto_restore_cpt_project",delet_id:delet_id,project_id:project_id,nonce:custom.nonce},
				success:function( response ){
					reload_cpt(cpt_type,"","",status);      
				}
			});
		} 
	});
});

/* cpt wise check box all check */
jQuery(document).on("change",".checkbox_all_check",function(){
	let ids = jQuery(this).attr("id");
	if(jQuery(this).prop("checked")){
		jQuery("."+ ids).prop("checked",true);
	}else{
		jQuery("."+ ids).prop("checked",false);
	}
});

/* all cpt filter ajax */
jQuery(document).on("click","#pto_button_fileter_apply",function(){
	let filter_ids = jQuery(this).attr("select");
	if(jQuery("#"+ filter_ids).val() != ""){
		let type = jQuery(this).attr("type-cpt");
		let action_name = jQuery("#"+filter_ids).val();
		let ids = "";
		let status = jQuery("#"+type + " .pto-cpt-status.active").attr("data-type");
		if(status == ""){
			status = "all";
		}
		jQuery(".checkall_"+ type).each(function(){
			if(jQuery(this).prop("checked")){
				ids += jQuery(this).attr("post-id") + ",";
			}
		});
		var newids = ids.substring(0, ids.length - 1);
		if(newids != ""){
			jQuery.ajax({
				method:"POST",
				url:custom.ajax_url,
				data:{action:"wpnb_pto_cpt_filter_action",newids:newids,status:status,action_name:action_name,nonce:custom.nonce},
				success:function( response ){
					reload_cpt(type,"","",status);      
				}
			}); 
		}	
	}
})
jQuery(document).on("click","#pto_button_fileter_month_apply",function(){
	let filter_ids = jQuery(this).attr("select");
	if(jQuery("#"+ filter_ids).val() != ""){
		let type = jQuery(this).attr("type-cpt");
		let publish_date = jQuery("#"+filter_ids).val();
		let status = jQuery("#"+type + " .pto-cpt-status.active").attr("data-type");
		reload_cpt(type,"","",status,publish_date); 
	}
})

function sortTable(n,type) {
	
	var table, rows, switching, i, x, y, shouldSwitch, dir, switchcount = 0;
	type= type + "_table";
	table = document.getElementById(type);
	switching = true;
  //Set the sorting direction to ascending:
  dir = "asc"; 
  /*Make a loop that will continue until
  no switching has been done:*/
  while (switching) {
    //start by saying: no switching is done:
    switching = false;
    rows = table.rows;
    /*Loop through all table rows (except the
    first, which contains table headers):*/
    for (i = 1; i < (rows.length - 1); i++) {
      //start by saying there should be no switching:
      shouldSwitch = false;
      /*Get the two elements you want to compare,
      one from current row and one from the next:*/
      x = rows[i].getElementsByTagName("TD")[n];
      y = rows[i + 1].getElementsByTagName("TD")[n];
      /*check if the two rows should switch place,
      based on the direction, asc or desc:*/
      if (dir == "asc") {
      	if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
          //if so, mark as a switch and break the loop:
          shouldSwitch= true;
          break;
      }
  } else if (dir == "desc") {
  	if (x.innerHTML.toLowerCase() < y.innerHTML.toLowerCase()) {
          //if so, mark as a switch and break the loop:
          shouldSwitch = true;
          break;
      }
  }
}
if (shouldSwitch) {
      /*If a switch has been marked, make the switch
      and mark that a switch has been done:*/
      rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
      switching = true;
      //Each time a switch is done, increase this count by 1:
      switchcount ++;      
  } else {
      /*If no switching has been done AND the direction is "asc",
      set the direction to "desc" and run the while loop again.*/
      if (switchcount == 0 && dir == "asc") {
      	dir = "desc";
      	switching = true;
      }
  }
}
}
jQuery( document ).on( "click" , "span.order-higher-indicator , span.order-lower-indicator" , function(){
     tinyMCE.remove();
     tinymce.init( window.tinyMCEPreInit.mceInit[ "keyinfo" ] );
} )

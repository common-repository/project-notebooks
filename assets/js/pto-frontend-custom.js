jQuery(document).ready(function(){
	
	jQuery('ul.pto-project-tabs li').click(function(){
		var tab_id = jQuery(this).attr('data-tab');

		jQuery('ul.pto-project-tabs li').removeClass('current');
		jQuery('.tab-content').removeClass('current');

		jQuery(this).addClass('current');
		jQuery("#"+tab_id).addClass('current');
	});
	if( jQuery("#my-projects ul li").length == 0){
		jQuery(".my-projects-tab").hide()
	}
});

jQuery(document).on('click' , '.search-project-btn' , function(){
	var searchproject = jQuery('.s-project').val();
	jQuery.ajax({
		method:"POST",	
		url:custom.ajax_url,
		dataType: "json",
		data:{
			searchproject:searchproject,
			action:"wpnb_get_all_pto_projects",
			nonce:custom.nonce
		},
		success:function(data){
			jQuery('#all-projects').html(data.all_project_data);
			jQuery('#my-projects').html(data.my_project_data);
		}
	});

}); 

jQuery(document).on('click' , '.project-access-btn' , function(){
    jQuery(this).removeClass("project-access-btn");
	var proj_id = jQuery(this).data('id');
	jQuery(this).val("Requested");
	jQuery.ajax({
		method:"POST",	
		url:custom.ajax_url,
		data:{
			proj_id:proj_id,
			action:"wpnb_get_all_pto_request_projects",
			nonce:custom.nonce
		},
		success:function(data){
			swal({
				title : "Thank you, Will notify you once administrator will approve the request.",
				button: "OK",
			})
            
		}
	});
});

jQuery(document).on('click' , '.accepts-req' , function(){
	var req_project_id = jQuery(this).data('id');
	var req_user_id = jQuery(this).data('userid');

	jQuery.ajax({
		method:"POST",	
		url:custom.ajax_url,
		data:{
			req_project_id:req_project_id,
			req_user_id:req_user_id,
			action:"wpnb_get_pto_request_projects_accept",
			nonce:custom.nonce
		},
		success:function(data){            
			swal({
				title : "Notification sent to user.",
				button: "OK",
			})
			jQuery('.ac_req_'+req_user_id+'_'+req_project_id).remove();
		}
	});
});

jQuery(document).on('click', '.decline-req', function(){
	var req_project_id = jQuery(this).data('id');
	var req_user_id = jQuery(this).data('userid');
	
	jQuery.ajax({
		method:"POST",	
		url:custom.ajax_url,
		data:{
			req_project_id:req_project_id,
			req_user_id:req_user_id,
			action:"wpnb_get_pto_request_projects_decline",
			nonce:custom.nonce
		},
		success:function(data){
			swal({
				title : "Notification sent to user.",
				button: "OK",
			})
			jQuery('.ac_req_'+req_user_id+'_'+req_project_id).remove();
		}
	});
});
jQuery("ul.pto-front-tabs li:first-child button").addClass("active");

function openTabProject(evt, cityName) {
	var i, tabcontent, tablinks;
	tabcontent = document.getElementsByClassName("pto-front-tabcontent");
	for (i = 0; i < tabcontent.length; i++) {
		tabcontent[i].style.display = "none";
	}
	tablinks = document.getElementsByClassName(".pto-front-tablinks");
	for (i = 0; i < tablinks.length; i++) {
		tablinks[i].className = tablinks[i].className.replace(" active", "");
	}
	document.getElementById(cityName).style.display = "block";
//   evt.currentTarget.className += " active";
}
if(jQuery("#defaultOpen").length != 0){
	document.getElementById("defaultOpen").click();
}
jQuery(document).on("click",".pto-front-tablinks",function(){
	jQuery(".pto-front-tablinks").removeClass("active");
	jQuery(this).addClass("active");
})

jQuery(document).on("change","#metting-filter",function(){
	let filter_name =  jQuery(this).val();
	let post_id =  jQuery(this).attr("post-id");
	if(filter_name != ""){
		jQuery.ajax({
			method:"POST",	
			url:custom.ajax_url,
			data:{
				post_id:post_id,
				filter_name:filter_name,
				action:"wpnb_get_metting_filter",
				nonce:custom.nonce
			},
			success:function(data){
				jQuery("#Meeting_Minutes .projects-list-block").html(data);
			}
		});
	}
})

jQuery(document).on("change","#notes-filter",function(){
	let filter_name =  jQuery(this).val();
	let post_id =  jQuery(this).attr("post-id");
	if(filter_name != ""){
		jQuery.ajax({
			method:"POST",	
			url:custom.ajax_url,
			data:{
				post_id:post_id,
				filter_name:filter_name,
				action:"wpnb_get_notes_filter",
				nonce:custom.nonce
			},
			success:function(data){
				jQuery("#Notes .projects-list-block").html(data);
			}
		});
	}
})
jQuery( document ).on('click' , '.pto-project-metting-list-single_title', function(){
	var m_id = jQuery( this ).data('id');
	if( m_id != '' ){
		jQuery.ajax({
			method:"POST",	
			url:custom.ajax_url,
			dataType : 'json',
			data:{
				m_id : m_id,
				action:"wpnb_pto_get_mettingpost_content",
				nonce:custom.nonce
			},
			success:function(data){
				if( data.return_html ){
					jQuery(".custom-popup-meetinginfo").html(data.return_html);
				}
			}
		});
	}
	
});

jQuery( document ).on('click' , '.pto-project-notes-list-single_title', function(){
	var n_id = jQuery( this ).data('id');
	let title =jQuery( this ).html();

	if( n_id != '' ){
		jQuery.ajax({
			method:"POST",	
			dataType : 'json',
			url:custom.ajax_url,
						data:{
				n_id : n_id,
				action:"wpnb_pto_get_notepost_content",
				nonce:custom.nonce
			},
			success:function(data){
				
				if( data.return_html ){
					jQuery(".custom-popup-noteinfo").html(data.return_html);
				}	
			}
		});
	}
	
});

jQuery( document ).on('click' , '.pto-project-metting-list-single_title' , function(){
	jQuery('.custom-popup-meeting').addClass('custom-popup-show');
});

jQuery( document ).on('click' , '.pto-project-notes-list-single_title' , function(){
	jQuery('.custom-popup-note').addClass('custom-popup-show');
});

jQuery( document ).on('click' , '.custom-popup-close' , function(){
	jQuery(this).parents().removeClass('custom-popup-show');
});

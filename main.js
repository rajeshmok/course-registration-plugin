// JavaScript Document


  jQuery(document).ready(function(e) {
    
	jQuery('.selector_btn a').click(function(e){
		e.preventDefault();
		jQuery('.selector_btn .active').removeClass('active');
		jQuery(this).parent().addClass('active');
		var tab = jQuery(this).attr('href');
		console.log(tab);
		jQuery('.courses_tabs .tab').hide();
		jQuery(tab).show();
		jQuery(".courses").select2({
    placeholder: "Selecteer een cursus"
});

		})
		
		jQuery('.headform').submit(function(e){
			
			var validation = true;
			jQuery(this).find('.select_required').each(function(index, element) {
		console.log('comming here');
		console.log(jQuery(this).val())
		var a = jQuery(this).val();
		
           if(jQuery(this).val()=="" || jQuery(this).val()==null ){
			   console.log('comming here with val');
			   console.log(jQuery(this).attr('name')+'='+jQuery(this).val())
			jQuery(this).next('.select2-container').addClass('invalid');
			validation = false;
		}else{
			jQuery(this).next('.select2-container').removeClass('invalid');
		}
    });
	
	return validation;
			})
		
		
jQuery("select").select2({
  tags: true
});
jQuery('#id_label_multiple').on('select2:select', function (e) {
if(jQuery(this).val()=="all"){
 jQuery("#id_label_multiple > option").prop("selected","selected");// Select All Options
 jQuery("#id_label_multiple > option[value='all']").prop("selected","");
   jQuery("#id_label_multiple").trigger("change");
}
		
		});
		
		jQuery('#id_label_multiple1').on('select2:select', function (e) {
if(jQuery(this).val()=="all"){
 jQuery("#id_label_multiple1 > option").prop("selected","selected");// Select All Options
 jQuery("#id_label_multiple1 > option[value='all']").prop("selected","");
   jQuery("#id_label_multiple1").trigger("change");
}
		
		});
		
jQuery(".location_row").select2();
jQuery('.location_row').on('select2:select', function (e) {
  // Do something


	console.log('get date list');
	jQuery('.bookingoverlay').show();
	var location = jQuery(this).val();
	var course = jQuery(this).parent().parent().parent().find('input[name="selectedcourse"]').val();
	 jQuery.ajax({
            url: ajaxUrl,
            method: 'POST',
            data: {
                action: 'get_dates_ajax',
               
				location : location,
				course : course
            },
            success: function(response) {
                // Handle the AJAX response
             jQuery('.course_date_available').html(response);
			  jQuery('.course_date_available').select2();
			  jQuery('.bookingoverlay').hide();
            },
            error: function(xhr, status, error) {
                // Handle AJAX error
                console.log(error);
            }
        });
	
	
});


jQuery(".courses").select2({
    placeholder: "Selecteer een cursus"
});



jQuery(document).on('click','#previous_step',function(e){
	if(jQuery('.book_step_three').hasClass('active_step')){
		jQuery('.book_step_three').removeClass('active_step')
		jQuery('.book_step_two').addClass('active_step')
	}
	if(jQuery('.book_step_four').hasClass('active_step')){
		jQuery('.book_step_four').removeClass('active_step')
		jQuery('.book_step_three').addClass('active_step')
	}
	if(jQuery('.book_step_two').hasClass('active_step')){
		jQuery('.completed_steps_fill').width('25%')
		}
	if(jQuery('.book_step_three').hasClass('active_step')){
		jQuery('.completed_steps_fill').width('50%')
		}
		if(jQuery('.book_step_four').hasClass('active_step')){
		jQuery('.completed_steps_fill').width('100%')
		}
});

jQuery(document).on('click','#next_step',function(e){
	e.preventDefault();
	console.log('next step');
	
	
	if(jQuery('.book_step_four').hasClass('active_step')){
	jQuery('.bookingoverlay').show();		
			 var form=jQuery(".bookingform");
			
			 jQuery.ajax({
            type:"POST",
             url: ajaxUrl,
			 data:form.serialize(),
            success: function(response){
               var retuls = response.split('#');
			   if(retuls[0]=="sucess"){
				   window.location.href =  retuls[1]
			   }else{
				jQuery('.book_step_four').append('<span class="error">Error'+ retuls[1]+'</span>');  
				jQuery('.bookingoverlay').hide();	
			   }
            }
        });
			
			return false;
			
		}
	
	
	
	
	var validation = true;
	if(jQuery('.book_step_one').hasClass('active_step')){
	  var course  = jQuery('.book_step_one').find('#courses_code').val().length;
	  console.log(course);
	  	if(course==0){
			jQuery('.book_step_one').find('#courses_code').next('.select2-container').addClass('invalid'); 
			validation = false;
		}
	}
	
	

	
	jQuery('.active_step').find('.required').each(function(index, element) {
		console.log(jQuery(this).val())
		var a = jQuery(this).val();
        if(jQuery(this).val()=="" || jQuery(this).val()==null ){
			console.log('comming here with val');
			jQuery(this).addClass('invalid');
			validation = false;
		}else{
			jQuery(this).removeClass('invalid');
		}
    });
	
	jQuery('.active_step').find('.select_required').each(function(index, element) {
		console.log('comming here');
		console.log(jQuery(this).val())
		var a = jQuery(this).val();
		
           if(jQuery(this).val()=="" || jQuery(this).val()==null ){
			   console.log('comming here with val');
			   console.log(jQuery(this).attr('name')+'='+jQuery(this).val())
			jQuery(this).next('.select2-container').addClass('invalid');
			validation = false;
		}else{
			jQuery(this).next('.select2-container').removeClass('invalid');
		}
    });
	
	console.log(validation)
	
	if(validation==true){
		var celement = jQuery('.active_step');
	 	jQuery('.active_step').next('.book_step').addClass('active_step')
		celement.removeClass('active_step');
		if(jQuery('.book_step_two').hasClass('active_step')){
		jQuery('.completed_steps_fill').width('33%')
		
		
		
		
		}
		if(jQuery('.book_step_three').hasClass('active_step')){
		jQuery('.completed_steps_fill').width('66%')
		}
		if(jQuery('.book_step_four').hasClass('active_step')){
		jQuery('.completed_steps_fill').width('100%')
		}
		
		
		
	}
		if(jQuery('.book_step_three').hasClass('active_step')){
			var totalprice = 0;
			jQuery('.booking_item_body').html('');
	var locations = jQuery('.coursem').each(function(index, element) {
           jQuery(this).find('.location_row').val();
		 var locationname = jQuery(this).find('.location_row').val();
		 var coursedate = jQuery(this).find('.course_date_available').val();
		 var course =  jQuery(this).find('.selectedcourse').val();
		  var courseprice =  parseInt(jQuery(this).find('.courseprice').val());
		   var examen =  jQuery(this).find('.examen').val();
		   var examentext = "";
		   if(examen){
			   examentext = examen+',- Eur';
		   }
		 totalprice = parseInt(totalprice) + courseprice;
		 jQuery('.booking_item_body').append('<div class="booking_item"><div class="booking_item_col_one"><span class="label">Cursus</span><span class="col_info" id="coursename">'+course+'</span></div><div class="booking_item_col_two"><span class="label">Locatie</span><span class="col_info" id="course_location">'+locationname+'</span></div><div class="booking_item_col_three"><span class="label">Cursus Datum</span><span class="col_info" id="course_date">'+coursedate+'</span></div><div class="booking_item_col_four"><span class="label">Tarief</span><span class="col_info" id="course_price">'+courseprice+',- Eur</span></div><div class="booking_item_col_five"><span class="label">Examen</span><span class="col_info">'+examentext+' </span></div></div>');
		     
        });
		jQuery('.grand_total').attr('data-booking-value',totalprice);
		jQuery('input[name="grand_total"]').attr('data-booking-value',totalprice);
		jQuery('.grand_total').html(totalprice+',- Eur');
		
		jQuery('#all_course_price_total').html(totalprice);
		}
		
		
		
	})
});
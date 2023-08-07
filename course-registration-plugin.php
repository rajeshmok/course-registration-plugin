<?php
/*
Plugin Name: Course Registration Plugin
*/

// Include Mollie API library


// Register shortcode for the course registration form


function my_course_enqueue_scripts() {

   wp_enqueue_style('select-course-style', plugin_dir_url(__FILE__) . 'select2.min.css');
   wp_enqueue_style('my-course-style', plugin_dir_url(__FILE__) . '/style.css');


}


function add_my_custom_page() {
	
	if(FALSE === is_page('Course detail')){
    // Create post object
        $my_post = array(
          'post_title'    => wp_strip_all_tags( 'Course Details' ),
          'post_content'  => '[]',
          'post_status'   => 'publish',
          'post_author'   => 1,
          'post_type'     => 'page',
      );

    // Insert the post into the database
        $pageid =  wp_insert_post( $my_post );


        
    }
}
register_activation_hook(__FILE__, 'add_my_custom_page');


add_action('wp_enqueue_scripts', 'my_course_enqueue_scripts');
function custom_post_types_course() {
    // Custom Post Type 1
    $args1 = array(
        'public' => true,
        'label'  => 'Courses',
        'show_in_menu' => true, 
        'publicly_queryable'  => false,
        'rewrite' => array(
            'slug'       => '',
            'with_front' => true,
        ),
        'supports'           => array( 'title'),
        // Add more arguments as needed
    );
    register_post_type('courses', $args1);

    // Custom Post Type 2
    $args2 = array(
        'public' => true,
        'label'  => 'Location',
        'show_in_menu' => true, 
        'publicly_queryable'  => false,
        'rewrite' => array(
            'slug'       => '',
            'with_front' => true,
        ),
        'supports'           => array( 'title'),
        // Add more arguments as needed
    );
    register_post_type('location', $args2);

    $args2 = array(
        'public' => true,
        'label'  => 'Registrations',
        'show_in_menu' => true, 
        'publicly_queryable'  => false,
        'rewrite' => array(
            'slug'       => '',
            'with_front' => true,
        ),
        'supports'           => array( 'title'),
        // Add more arguments as needed
    );
    register_post_type('registration', $args2);
}
add_action('init', 'custom_post_types_course');


// Register Custom Taxonomy: Course Type
function your_plugin_register_taxonomy_course_type() {
    $labels = array(
        'name' => 'Course Types',
        'singular_name' => 'Course Type',
        'menu_name' => 'Course Types',
        'all_items' => 'All Course Types',
        'edit_item' => 'Edit Course Type',
        'view_item' => 'View Course Type',
        'update_item' => 'Update Course Type',
        'add_new_item' => 'Add New Course Type',
        'new_item_name' => 'New Course Type',
        'parent_item' => 'Parent Course Type',
        'parent_item_colon' => 'Parent Course Type:',
        'search_items' => 'Search Course Types',
        'popular_items' => 'Popular Course Types',
        'separate_items_with_commas' => 'Separate course types with commas',
        'add_or_remove_items' => 'Add or remove course types',
        'choose_from_most_used' => 'Choose from the most used course types',
        'not_found' => 'No course types found',
    );

    $args = array(
        'labels' => $labels,
        'hierarchical' => true,
        'public' => true,
        'show_ui' => true,
        'publicly_queryable'  => false,
        'show_admin_column' => true,
        'query_var' => true,
        'rewrite' => array('slug' => 'course-type'),
    );

    register_taxonomy('course_type', 'courses', $args);
}
add_action('init', 'your_plugin_register_taxonomy_course_type');


// Add menu in the admin area
// Add meta boxes for the Courses post type
function your_plugin_add_course_meta_boxes() {
    add_meta_box(
        'course_price',
        'Course Price',
        'your_plugin_course_price_meta_box_callback',
        'courses',
        'normal',
        'default'
    );
    add_meta_box(
        'examen',
        'Examen Price',
        'your_plugin_examen_price_meta_box_callback',
        'courses',
        'normal',
        'default'
    );

    add_meta_box(
        'course_locations',
        'Course Locations',
        'your_plugin_course_locations_meta_box_callback',
        'courses',
        'normal',
        'default'
    );
    add_meta_box(
        'course_details',
        'Course details',
        'your_plugin_additional_fields_meta_box_callback',
        'courses',
        'normal',
        'default'
    );

    add_meta_box(
        'registraion_details',
        'Registraion details',
        'your_plugin_registration_fields_meta_box_callback',
        'registration',
        'normal',
        'default'
    );


}
add_action('add_meta_boxes', 'your_plugin_add_course_meta_boxes');


function your_plugin_registration_fields_meta_box_callback($post){
	
	
   $courses = get_post_meta($post->ID, 'courses', true);
   $post_id = $post->ID;
   $firstname =  get_post_meta($post_id,'firstname',true);
   $middle_name =  get_post_meta($post_id,'middle_name',true);
   $last_name =  get_post_meta($post_id,'last_name',true);
   $phone =  get_post_meta($post_id,'phone',true);
   $email =  get_post_meta($post_id,'email',true);
   $address =  get_post_meta($post_id,'address',true);
   $postcode=  get_post_meta($post_id,'postcode',true);
   $residence=  get_post_meta($post_id,'residence',true);
   $date_of_birth =  get_post_meta($post_id,'date_of_birth',true);
   $referral =  get_post_meta($post_id,'referral',true);
   $payment_method =  get_post_meta($post_id,'payment_method',true);
   $payment_status =  get_post_meta($post_id,'payment_status',true);
   $paymentid =  get_post_meta($post_id,'paymentid',true);

    // Output the field HTML
   ?>
   <div class="wrap">
    <h1>Course Details</h1>
    <?php if($courses){ $total = 0;?>
        <table cellpadding="10" cellspacing="10" border="0">
            <tr><th><strong>Cursus</strong></th><th><strong>Locatie</strong></th><th><strong>Cursus Datum</strong></th><th><strong>Tarief</strong></th><th><strong>Examen</strong></th></tr>
            <?php foreach($courses as $course){

             $total = $total +  floatval($course['price']) + floatval($course['examen']);
             ?>
             <tr>
                <td><?php echo $course['course'];?></td>
                <td><?php echo $course['location'];?></td>
                <td><?php echo $course['date'];?></td>
                <td><?php echo $course['price'];?>,- Eur</td>
                <td><?php echo $course['examen'];?>,-  Eur</td>
            </tr>

        <?php }?>
        <tr><td><strong>Totals</strong></td><td colspan="4" align="right"><strong><?php echo $total;?>,- Eur</strong></td></tr>
    </table>

<?php }?>

<h1>Personal Details</h1>
<div class="pdetail" style="display:flex;flex-direction:column;gap:20px;font-size:16px; line-height:20px;">
   <div class="row" style="display:flex;align-items:center"><strong>Full Name :</strong> <?php echo $firstname.' '.$middle_name.' '.$last_name;?></div>
   <div class="row"><strong>Phone :</strong> <?php echo $phone;?></div>
   <div class="row"><strong>Email :</strong> <?php echo $phone;?></div>
   <div class="row"><strong>Address :</strong> <?php echo $address;?></div>
   <div class="row"><strong>Residence :</strong> <?php echo $residence;?></div>
   <div class="row"><strong>Date of birth :</strong> <?php echo $date_of_birth;?></div>
   <div class="row"><strong>Referral :</strong> <?php echo $referral;?></div>
   <div class="row"><strong>Payment method :</strong> <?php echo $payment_method;?></div>
   <div class="row"><strong>Payment status :</strong> <?php echo $payment_status;?></div>
</div>

</div>
<?php 


}

// Callback function for the Course Price meta box
function your_plugin_course_price_meta_box_callback($post) {
    // Get the current price value
    $price = get_post_meta($post->ID, 'course_price', true);

    // Output the field HTML
    ?>
    <label for="course_price">Price:</label>
    <input type="text" name="course_price" id="course_price" value="<?php echo esc_attr($price); ?>">
    <?php
}

function your_plugin_examen_price_meta_box_callback($post) {
    // Get the current price value
    $price = get_post_meta($post->ID, 'examen_price', true);

    // Output the field HTML
    ?>
    <label for="course_price">Price:</label>
    <input type="text" name="examen_price" id="examen_price" value="<?php echo esc_attr($price); ?>">
    <?php
}


// Callback function for the Course Locations meta box
// Callback function for the Course Locations meta box
// Callback function for the Course Locations meta box
function your_plugin_course_locations_meta_box_callback($post) {
    // Get all the location posts
    $locations = get_posts(array(
        'post_type' => 'location',
        'posts_per_page' => -1,
    ));

    // Get the selected locations for the current course
    $selected_locations = get_post_meta($post->ID, 'course_locations', true);
    if( $selected_locations==""){
       $selected_locations = array();	
   }
    // Output the field HTML
   ?>
   <label for="course_locations">Locations:</label>
   <select name="course_locations[]" id="course_locations" multiple>
    <option value="">All</option>
    <?php foreach ($locations as $location) : ?>
        <option value="<?php echo esc_attr($location->ID); ?>" <?php selected(in_array($location->ID, $selected_locations), true); ?>>
            <?php echo esc_html($location->post_title); ?>
        </option>
    <?php endforeach; ?>
</select>
<?php
}


// Callback function for the Additional Fields meta box
function your_plugin_additional_fields_meta_box_callback($post) {
    // Get the saved additional fields
    $saved_fields = get_post_meta($post->ID, 'additional_fields', true);
    $fields = !empty($saved_fields) ? $saved_fields : array(array('date' => '', 'location' => '')); // Initialize with an empty field
    $total = count($fields);
    // Output the field HTML
    ?>
    <div id="additional-fields-container">
        <div class="instruction"><strong>Date will be comma seprated for locaion like  25-10-2024,20-11-2024 (mm-mm-YY)</strong><br /></div>
        <?php foreach ($fields as $index => $field) : ?>
            <div class="additional-field-row">

                <textarea  name="additional_fields[<?php echo $index; ?>][date]" placeholder="Date"><?php echo esc_attr($field['date']); ?></textarea>
                <?php
                // Location dropdown
                $location_value = isset($field['location']) ? $field['location'] : '';
                $locations = get_posts(array(
                    'post_type' => 'location',
                    'posts_per_page' => -1,
                ));
                ?>
                <select name="additional_fields[<?php echo $index; ?>][location]">
                    <option value="">Select Location</option>
                    <?php foreach ($locations as $location) : ?>
                        <option value="<?php echo $location->post_title; ?>" <?php selected($location_value, $location->post_title); ?>><?php echo $location->post_title; ?></option>
                    <?php endforeach; ?>
                </select>
                
                <?php if ($index === count($fields) - 1) : ?>
                    <button class="add-field-row">Add More</button>
                <?php else : ?>
                    <button class="remove-field-row">Remove</button>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>

    <script>
        jQuery(document).ready(function($) {
            // Add more field rows
         var rows = $('.additional-field-row').length;
         $('.add-field-row').on('click', function(e) {
            e.preventDefault();

            var newRow = $('.additional-field-row:last').clone();
            newRow.find('textarea').attr('name','additional_fields['+rows+'][date]');
            newRow.find('select').attr('name','additional_fields['+rows+'][location]');
            newRow.find('.add-field-row').removeClass('add-field-row').addClass('remove-field-row').text('Remove');
            newRow.insertAfter('.additional-field-row:last');
            rows++;
        });

            // Remove field row
         $(document).on('click', '.remove-field-row', function(e) {
            e.preventDefault();
            $(this).closest('.additional-field-row').remove();
        });
     });
 </script>
 <?php
}


// Save meta box data
function your_plugin_save_course_meta($post_id) {
    if (isset($_POST['course_price'])) {
        $price = sanitize_text_field($_POST['course_price']);
        update_post_meta($post_id, 'course_price', $price);
    }
    if (isset($_POST['examen_price'])) {
        $price = sanitize_text_field($_POST['examen_price']);
        update_post_meta($post_id, 'examen_price', $price);
    }


    if (isset($_POST['course_locations'])) {
        $locations = array_map('intval', $_POST['course_locations']);
        update_post_meta($post_id, 'course_locations', $locations);
    }

    if (isset($_POST['additional_fields'])) {
        $fields = $_POST['additional_fields'];
        $sanitized_fields = array();

        foreach ($fields as $field) {
            $sanitized_field = array(
                'date' => sanitize_text_field($field['date']),
                'location' => sanitize_text_field($field['location']),
            );
            $sanitized_fields[] = $sanitized_field;
        }

        update_post_meta($post_id, 'additional_fields', $sanitized_fields);
    }

}
add_action('save_post_courses', 'your_plugin_save_course_meta');




function course_registration_form_shortcode() {

    course_registration_form();
    
}
add_shortcode('course_registration_form', 'course_registration_form_shortcode');
function course_registration_details_form_shortcode() {
   ?>  
   <?php if(isset($_REQUEST['courses'])){?>
       <div class="booking_wrapper" id="booking_wrapper">
           <!-- booking header start -->
           <form class="bookingform">
               <div class="bookingoverlay">
                <div class="booking_loader"></div>
            </div>

            <div class="progresswrapper"><div class="steps_label">

                <span>
                    <lottie-player src="<?php echo  plugin_dir_url(__FILE__);?>wired-outline-28-calendar.json" background="transparent"  speed="1"  style="width: 100px; height:100px;margin:0 auto" loop  autoplay></lottie-player>

                    <div class="header_title">Selecteer een cursus</div>
                    <div class="steps_text">Selecteer de cursus die jij nodig hebt voor het behalen van jouw theorie.</div>
                </span>

                <span>
                   <lottie-player src="<?php echo  plugin_dir_url(__FILE__);?>wired-outline-1103-confetti.json" background="transparent"  speed="1"  style="width: 100px; height:100px;margin:0 auto" loop  autoplay></lottie-player>
                   <div class="header_title">Dag van de cursus</div>
                   <div class="steps_text">Onze instructeurs doen bereiden je voor op het behalen van jouw examen!</div>
               </span>

               <span>
                  <lottie-player src="<?php echo  plugin_dir_url(__FILE__);?>wired-outline-1660-noticeboard.json" background="transparent"  speed="1"  style="width: 100px; height:100px;margin:0 auto" loop  autoplay></lottie-player>
                  <div class="header_title">Gefeliciteerd!</div>
                  <div class="steps_text">Gefeliciteerd met het behalen van jouw theorie en je loopbaan als vrachtwagenchauffer!</div>
              </span>

       <!-- <span>
        <img width="41" height="40" decoding="async" class="form_header_icon entered lazyloaded" alt="Examen" src="<?php echo  plugin_dir_url(__FILE__);?>images/hat.svg" title="Aanmelden 5" data-lazy-src="<?php echo  plugin_dir_url(__FILE__);?>images/hat.svg" data-ll-status="loaded"><noscript><img width="41" height="40" decoding="async" class="form_header_icon" alt="Examen" src="<?php echo  plugin_dir_url(__FILE__);?>images/hat.svg" title="Aanmelden 5"></noscript>
        <div class="header_title">Het examen</div>
        <div class="steps_text">Cursus en examen op dezelfde dag met de hoogste slagingskans!</div>
    </span>-->
</div><div class="steps_progress_bar">
    <div class="pending_steps_fill"></div>
    <div class="completed_steps_fill"></div>
</div></div>					

<div id="booking_steps">

 <!-- step one start -->
 <div class="book_step book_step_one <?php if(!isset($_REQUEST['courses']) ){?> active_step<?php }?>" tabindex="1">

   <div class="book_step_one_inner">
      <div class="book_col_select">
        <?php if($_REQUEST['course_type']=="niwo"){?>
            <select name="courses_code[]" id="courses_code" class="select_required  courses " multiple="" data-select2-id="select2-data-courses_code" tabindex="-1" aria-hidden="true">  
               <option value="alle_curs">Alle cursussen</option>
               <?php
               $locations = get_posts(array(
                'post_type' => 'courses',
                'posts_per_page' => -1,
                'tax_query' => array(

                    'taxonomy' => 'course_type',
                    'field' => 'name',
                    'terms' => 'Niwo'


                )

            ));
            ?>


            <?php foreach ($locations as $location) : ?>
                <option value="<?php echo $location->post_title; ?>" >><?php echo $location->post_title; ?></option>
            <?php endforeach; ?>

        </select>
    <?php }else{?>
     <select name="courses_code[]" id="courses_code" class="select_required  courses " multiple="" data-select2-id="select2-data-courses_code" tabindex="-1" aria-hidden="true">  


        <?php
        $locations = get_posts(array(
            'post_type' => 'courses',
            'posts_per_page' => -1,
            'tax_query' => array(

                'taxonomy' => 'course_type',
                'field' => 'name',
                'terms' => 'Vrachtwagen theorie'


            )

        ));
        $selectedcourse = array();
        if(isset($_REQUEST['courses']) ){
           $selectedcourse = $_REQUEST['courses'];
       }
       ?>


       <?php foreach ($locations as $location) : ?>
        <option value="<?php echo $location->post_title; ?>" <?php selected(in_array($location->post_title, $selectedcourse), true); ?>><?php echo $location->post_title; ?></option>
    <?php endforeach; ?>


</select>
<?php }?>
</div>
<div class="book_col_btn">
 <button id="stepone_action" class="button main_form_btn">Volgende</button>
</div>
</div>
</div>							
<!-- end -->




<div class="book_step book_step_two <?php if(isset($_REQUEST['courses']) ){?> active_step<?php }?> " tabindex="2">
	<div class="date_finder_wrapper" id="date_finder_wrapper"><div class="date_form_row">


     <?php if(isset($_REQUEST['courses']) ){
       foreach($_REQUEST['courses'] as $course){
        $posttitle = $course;
        global $wpdb;
        $courseid = $wpdb->get_var( "SELECT ID FROM $wpdb->posts WHERE post_title = '" . $posttitle . "' and post_type = 'courses'" );
        $price = get_post_meta($courseid,'course_price',true);  
        $eprice = get_post_meta($courseid,'examen_price',true);
        ?> 
        <div class="coursem">
            <div class="course_code"><span><?php echo $course;?><input type="hidden" name="selectedcourse[]" class="selectedcourse" value="<?php echo $course;?>" />
                <input type="hidden" name="courseprice[]" value="<?php echo $price;?>" class="courseprice" />
                <input type="hidden" name="examen[]" value="<?php echo $eprice;?>" class="examen" />
            </span></div>
            <div class="courseitems">
                <div class="location_code">
                    <?php
                    $locations = array('Amsterdam','Den Haag','Eindhoven','Utrecht','Leeuwarden','Breda','Goes','Zwolle','Enschede','Maastricht','Venlo');
                    if($_REQUEST['location']!=""){
                      $location = $_REQUEST['location'];
                  }?>
                  <select  class="location_row select_required " data-course='<?php echo $courseid;?>' name="location[]"  ><option value="" >Kies uw locatie</option>

                    <?php foreach($locations as $loc){
                       if($loc==$location){
                           $selected = "selected='selected'";	
                       }else{
                          $selected="";
                      }
                      ?>
                      <option value="<?php echo $loc;?>" <?php echo $selected;?>><?php echo $loc;?></option>
                  <?php }?>

              </select></div>
              <div class="course_action mobileonly"><span class="remove"> <i class="fa fa-trashs" aria-hidden="true"></i></span></div>
              <div class="course_date"><i class="fa fa-cal" aria-hidden="true"></i>
                <?php $dates = get_post_meta($courseid,'additional_fields',true);


                ?>
                <select class="course_date_available select_required select2-hidden-accessible" name="date[]" ><?php if($_REQUEST['courses'] && $_REQUEST['location']){?>
                    <option value="">Kies uw datum</option>
                    <?php foreach($dates as $date){
                       if($date['location']==$location){
                          $dates = explode(',',$date['date']);
                          if($dates){
                              foreach($dates as $date){
                                  echo '<option value="'.$date.'">'.$date.'</option>';
                              }
                          }

                      }
                  }?>

              <?php }?>
          </option>

      </select></div>
      <div class="course_action desktoponly"><span class="remove">Verwijder <i class="fa fa-trashs" aria-hidden="true"></i></span></div>
  </div>
</div>
<?php   
}
?>

<?php }else{?>
 <div class="courseitems">
    <div class="location_code">
        <?php
        $locations = array('Amsterdam','Den Haag','Eindhoven','Utrecht','Leeuwarden','Breda','Goes','Zwolle','Enschede','Maastricht','Venlo');
        if($_REQUEST['location']!=""){
          $location = $_REQUEST['location'];
      }?>
      <select class="location_row select_required " name="location" id="location" ><option value="" data-select2-id="select2-data-18-33ih">Kies uw locatie</option>

        <?php foreach($locations as $loc){
           if($loc==$location){
               $selected = "selected='selected'";	
           }else{
              $selected="";
          }
          ?>
          <option value="<?php echo $loc;?>" <?php echo $selected;?>><?php echo $loc;?></option>
      <?php }?>

  </select></div>

  <div class="course_date"><i class="fa fa-cal" aria-hidden="true"></i><select class="course_date_available select_required select2-hidden-accessible" name="date" id="date"><?php if($_REQUEST['courses'] && $_REQUEST['location']){?>
    <option value="">Kies uw datum</option>

    <option value="3951">donderdag, 6 juli 2023</option>
    <option value="3969">woensdag, 12 juli 2023</option>
    <option value="3989">dinsdag, 18 juli 2023</option>
    <option value="4014">dinsdag, 25 juli 2023</option>
    <option value="4060">donderdag, 3 aug 2023</option>
    <option value="4079">donderdag, 10 aug 2023</option>
    <option value="4095">dinsdag, 15 aug 2023</option>
    <option value="4113">maandag, 21 aug 2023</option>
    <option value="4140">dinsdag, 29 aug 2023</option>
<?php }?>
</option>

</select></div>
<div class="course_action desktoponly"><span class="remove">Verwijder <i class="fa fa-trashs" aria-hidden="true"></i></span></div>
</div>
<div class="bookingoverlay"></div>
<?php }?>


</div></div>

</div>


<div class="book_step book_step_three " tabindex="3">
    <div class="booking_items">
        <div class="booking_grid_heading">
            <div class="booking_title_col_one">Cursus</div>
            <div class="booking_title_col_two">Locatie</div>
            <div class="booking_title_col_three">Cursus Datum</div>
            <div class="booking_title_col_four">Tarief</div>
            <div class="booking_title_col_five">Examen</div>
            <!-- <div class='booking_title_col_one'>Cursus</div> -->
            <!-- <div class='booking_title_col_two'>Time</div> -->
            <!-- <div class='booking_title_col_three'>Locatie</div> -->
            <!-- <div class='booking_title_col_four'>Start Date</div> -->
            <!-- <div class='booking_title_col_five'>Tarief</div> -->
            <!-- <div class='booking_title_col_six'>Storting</div> -->
        </div>
        <div class="booking_item_body">


        </div>
        <div class="booking_footer_totals">
            <div class="total_label">Totaal</div>
            <div class="grand_total" data-booking-value="250.0" id="grand_total">250.0 Eur
                <input type="hidden" name="grand_total" value="" />
            </div>
            <!-- <div class='partial_total' data-booking-value="0" id='partial_total'></div> -->
        </div>

    </div>
    <div class="customer_info">
        <div class="form_control_row flexcol_two">
            <div class="form_col_one">
                <label for="first_name">Voornaam</label>
                <input type="text" name="first_name" id="first_name" class="required" value="" placeholder="">
            </div>
            <div class="midcol">
                <label for="middle_name">Tussenvoegsel</label>
                <input type="text" name="middle_name" id="middle_name" value="" placeholder="">
            </div>
            <div class="form_col_two">
                <label for="last_name">Achternaam</label>
                <input type="text" name="last_name" id="last_name" class="required" value="" placeholder="">
            </div>
        </div>

        <div class="form_control_row flexcol_two">
            <div class="form_col_one">
                <label for="phone">Telefoon </label>
                <input type="text" name="phone" id="phone" class="required" value="" placeholder="">
            </div>
            <div class="form_col_two">
                <label for="email">E-mail</label>
                <input type="email" name="email" id="email" class="required" value="" placeholder="">
            </div>
        </div>

        <div class="form_control_row">
            <label for="address">Straat en huisnummer</label>
            <input type="text" name="address" id="address" class="required" value="" placeholder="">
        </div>

        <div class="form_control_row flexcol_two">
            <div class="form_col_one">
                <label for="postcode">Postcode</label>
                <input type="text" name="postcode" maxlength="8" id="postcode" class="required" value="" placeholder="">
            </div>
            <div class="form_col_two">
                <label for="residence">Woonplaats</label>
                <input type="text" name="residence" id="residence" class="required" value="" placeholder="">
            </div>
        </div>

        <div class="form_control_row flexcol_two">
            <div class="form_col_one calendar_icon">
                <label for="date_of_birth">Geboortedatum </label>
                <input type="text" name="date_of_birth" placeholder="dd-mm-yyyy" maxlength="10" id="date_of_birth" class="required" value="">
                <span id="date_format_valid_msg" class="invalid_input">Voer uw geboortedatum in het formaat in (dd-mm-yyyy).</span>
            </div>
            <div class="form_col_two">
                <label for="free_course">Wilt u gebruik maken van een (praktijk) proefles in uw regio?

                </label>
                <div name="free_course" id="free_course" class="form-check form-check-inline" style="display:flex;">
                    <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio1" value="yes" style="margin-bottom:10px">
                    <label class="form-check-label" for="inlineRadio1">Ja</label>
                    <input class="form-check-input" type="radio" name="inlineRadioOptions" value="no" id="inlineRadio2" style="margin-left:20px; margin-bottom:10px" checked="">
                    <label class="form-check-label" for="inlineRadio2">Nee</label>
                </div>
            </div>
        </div>
        <div class="form_control_row flexcol_two">
            <div class="form_col_one">
                <label for="referral">Hoe heeft u ons gevonden?</label>
                <select name="referral" id="referral" class="select_required select2-hidden-accessible" data-select2-id="select2-data-referral" tabindex="-1" aria-hidden="true">
                    <option value="" data-select2-id="select2-data-3-8iey">Kies uw referral</option>

                    <option value="Google">Google</option>

                    <option value="Radio">Radio</option>

                    <option value="Social media">Social media</option>

                    <option value="Familie/vrienden">Familie/vrienden</option>

                    <option value="Anders">Anders</option>

                </select>
            </div>
        </div>

    </div>

    <div class="clearfix form_control_row" style="text-align: center">
        <label id="terms_required"><input type="checkbox" required="" name="terms"> Ik ga akkoord met de algemene voorwaarden</label>
    </div>
</div>


<div class="book_step book_step_four " tabindex="4">

	<span class="betaling">Betaling door middel van IDeal (Mollie)</span>
  <div class="payment_options">
      <ul>
         <li><input type="radio" name="payment_method" id="payment_method_skip" value="skip"><label for="payment_method_skip">Ik betaal op locatie</label></li>
         <li><input type="radio" name="payment_method" id="payment_method_partial" value="partial"><label for="payment_method_partial">Ik doe een aanbetaling van <span id="per_course_price">50</span> euro - iDeal(Mollie)</label></li>
         <li><input type="radio" name="payment_method" id="payment_method_full" value="full"><label for="payment_method_full">Ik betaal <span id="all_course_price_total">250.0</span> euro vooraf - iDeal(Mollie)</label></li>







     </ul>
 </div>

</div>
</div>

<div class="booking_action clearfix">
  <div class="book_col_6">
     <span id="previous_step" class="previous backbtn">Vorige</span>
 </div>
 <div class="book_col_6">
     <span id="next_step" class="button main_form_btn">Volgende</span>
     <span id="pay_now" class="button main_form_btn">Volgende</span>
 </div>
</div>
<div id="booking_completed">
    <p>Uw aanmelding is in goede orde ontvangen Nuvrachtwagen.nl</p></div>
    <input type="hidden" id="action" value="get_postorder" name="action" />
</form>
</div>
<script>
	var ajaxUrl = '<?php echo admin_url( 'admin-ajax.php' );?>';
</script>
<?php 
}

if(isset($_REQUEST['orderid'])){
    ?>
    <div class="thankyou">
        <h2>Thank you For Registration on our course</h2>
        <p>We are committed to providing you with an exceptional learning experience. Our team of experienced instructors and staff are dedicated to ensuring that you have all the tools and resources needed to succeed in this course.</p>
        <p>
        Once again, thank you for choosing our course. We look forward to meeting you and embarking on this educational journey together. Get ready to expand your knowledge, acquire new skills, and unlock exciting opportunities!</p>
    </div>
    <?php 	

// Set up the payment ID
    $paymentId = get_post_meta($_REQUEST['orderid'],'paymentid',true);
    $payment_status = get_post_meta($_REQUEST['orderid'],'payment_status',true); 
    $payment_method = get_post_meta($_REQUEST['orderid'],'payment_method',true); 
    if($paymentId && $payment_status!="paid" && ($payment_method=="full" || $payment_method=="partial")){
// Set up the API endpoint URL
        $apiEndpoint = 'https://api.mollie.com/v2/payments/' . $paymentId;

// Set up the API key


        $mollie_api_key = 'test_Ajg54dn8kjnvBEHAusHbWkqSB5ee9D';
        $test_api = get_option('test-api-key');
        if($test_api==""){
            $test_api = 'test_Ajg54dn8kjnvBEHAusHbWkqSB5ee9D';	
        }



// Set up the request headers
        $headers = array(
            'Authorization: Bearer ' . $mollie_api_key,
            'Content-Type: application/json'
        );

// Set up the cURL options
        $curlOptions = array(
            CURLOPT_URL => $apiEndpoint,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => $headers
        );


// Initialize cURL
        $curl = curl_init();

// Set the cURL options
        curl_setopt_array($curl, $curlOptions);

// Execute the cURL request
        $response = curl_exec($curl);

// Check for cURL errors
        if (curl_errno($curl)) {
    // Handle the error
            $error = curl_error($curl);
    // ...
        } else {
    // Parse the JSON response
            $payment = json_decode($response, true);

    // Retrieve the payment status
            $status = $payment['status'];
            update_post_meta($_REQUEST['orderid'],'payment_status',$status);
    // Process the payment status as needed
    // ...
        }

// Close the cURL connection
        curl_close($curl);
    }

}

}


add_shortcode('course_registration_details_form', 'course_registration_details_form_shortcode');



// Display the course registration form
function course_registration_form() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        handle_registration_submission();
    }

    $courses = array(
        'Course 1' => array('Sub Course A', 'Sub Course B'),
        'Course 2' => array('Sub Course C', 'Sub Course D')
    );

    $locations = array('Location 1', 'Location 2', 'Location 3');

    ?>
    <div class="header_form">
      <div class="couse_selector">
        <div class="selector_btn">
            <div class="vrach_btn active"><a href="#vrach_tab">Vrachtwagen theorie</a></div>
            <div class="niwo_btn"><a href="#niwo_tab">NIWO</a></div>
        </div>
    </div>
    <div class="courses_tabs">
     <div id="vrach_tab" class="tab">
      <div class="vrach_tab">
         <form class="headform" action="<?php echo get_bloginfo('url');?>/course-detail/" method="get">
            <div class="course_select">
              <input type="hidden" name="course_type"  value="Vrachtwagen theorie"/>
              <select class="select2-selection select2-selection--multiple courses select_required" name="courses[]" id="id_label_multiple" multiple="multiple">

                <?php
                $courses = get_posts(array(
                    'post_type' => 'courses',
                    'posts_per_page' => -1,
                    'tax_query' => array(
                     array(
                        'taxonomy' => 'course_type',
                        'field' => 'name',
                        'terms' => 'Vrachtwagen theorie'
                    )

                 )

                ));

                ?>
                

                <?php foreach ($courses as $course) :  ?>
                    <option value="<?php echo $course->post_title; ?>" ><?php echo $course->post_title; ?></option>
                <?php endforeach; ?>
                <option value="all">Alle 3 €750</option></select>
            </div>
            <div class="location_select">
                <select name="location" id="courses_location_head" class="select2-selection locations select_required" >
                    <option value="" data-select2-id="select2-data-4-ucq3">Kies uw locatie</option>
                    <?php
                    $locations = get_posts(array(
                        'post_type' => 'location',
                        'posts_per_page' => -1,

                    ));

                    ?>


                    <?php foreach ($locations as $location) :  ?>
                        <option value="<?php echo $location->post_title; ?>" ><?php echo $location->post_title; ?></option>
                    <?php endforeach; ?>

                </select>
            </div>
            <button id="header_step_action" class="form_btn head_btn">Inschrijven</button>
        </form>
    </div> 

</div>
<div id="niwo_tab" class="tab" style="display:none">
  <div class="niwo_tab">
      <form class="headform" action="<?php echo get_bloginfo('url');?>/course-detail/" method="get">
       <div class="course_select">
           <input type="hidden" name="course_type"  value="niwo"/>
           <select name="courses[]" id="id_label_multiple1"  class="select2-selection select2-selection--multiple courses select_required" multiple="" ><option value="all">Alle cursussen</option>
               <?php
               $locations = get_posts(array(
                'post_type' => 'courses',
                'posts_per_page' => -1,
                'tax_query' => array(
                 array(
                    'taxonomy' => 'course_type',
                    'field' => 'name',
                    'terms' => 'Niwo'
                )

             )

            ));

            ?>


            <?php foreach ($locations as $location) :  ?>
                <option value="<?php echo $location->post_title; ?>" ><?php echo $location->post_title; ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="location_select">
        <select name="location" id="courses_location_head_niwo" class="select2-selection locations select_required" >

           <?php
           $locations = get_posts(array(
            'post_type' => 'location',
            'posts_per_page' => -1,

        ));

        ?>


        <?php foreach ($locations as $location) :  ?>
            <option value="<?php echo $location->post_title; ?>" ><?php echo $location->post_title; ?></option>
        <?php endforeach; ?>

    </select>
</div>
<button id="header_step_action_niwo" class="form_btn head_btn">Inschrijven</button>

</form>
</div>
</div>
</div>
</div>


<?php
}

// Handle the form submission
function handle_registration_submission() {
    $course = sanitize_text_field($_POST['course']);
    $subCourse = sanitize_text_field($_POST['subCourse']);
    $location = sanitize_text_field($_POST['location']);
    $name = sanitize_text_field($_POST['name']);
    $email = sanitize_text_field($_POST['email']);

    // Save registration data in user meta fields
    $user_id = get_current_user_id();
    update_user_meta($user_id, 'course', $course);
    update_user_meta($user_id, 'sub_course', $subCourse);
    update_user_meta($user_id, 'location', $location);
    update_user_meta($user_id, 'name', $name);
    update_user_meta($user_id, 'email', $email);

    // Create payment in Mollie
    /*$mollie = new \Mollie\Api\MollieApiClient();
    $mollie->setApiKey('YOUR_MOLLIE_API_KEY');

    $payment = $mollie->payments->create([
        'amount' => [
            'currency' => 'EUR',
            'value' => '10.00' // Replace with the actual amount
        ],
        'description' => 'Course Registration Payment',
        'redirectUrl' => get_site_url() . '/payment-confirmation' // Replace with the actual URL of the payment confirmation page
    ]);
*/
    // Store payment ID in user meta field
    update_user_meta($user_id, 'payment_id', $payment->id);

    // Send thank you email to the user
    $user_email_subject = 'Thank you for registering';
    $user_email_body = 'Dear ' . $name . ',<br><br>Thank you for registering for the course. Your registration details are as follows:<br><br>'
    . 'Course: ' . $course . '<br>'
    . 'Sub-course: ' . $subCourse . '<br>'
    . 'Location: ' . $location . '<br><br>'
    . 'We will contact you with further information.';

    wp_mail($email, $user_email_subject, $user_email_body);

    // Send notification email to the admin
    $admin_email_subject = 'New course registration';
    $admin_email_body = 'A new course registration has been received. The details are as follows:<br><br>'
    . 'Course: ' . $course . '<br>'
    . 'Sub-course: ' . $subCourse . '<br>'
    . 'Location: ' . $location . '<br>'
    . 'Name: ' . $name . '<br>'
    . 'Email: ' . $email;

    $admin_email = get_option('admin_email');
    wp_mail($admin_email, $admin_email_subject, $admin_email_body);

    // Redirect the user to Mollie for payment
    wp_redirect($payment->getCheckoutUrl());
    exit;
}

function enqueue_cours_scripts() {
    wp_enqueue_script( 'cours-script',  plugin_dir_url(__FILE__) . 'main.js', array( 'jquery' ), '1.0', true );
    wp_register_script('ajaxHandle',plugin_dir_url(__FILE__) . 'main.js',array(), false, true);
    wp_enqueue_script('select-course-js', 'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js');
    wp_enqueue_script( 'booking-script',  plugin_dir_url(__FILE__) . 'booking.js', array( 'jquery' ), '1.0', true );
    wp_enqueue_script( 'lottie-script', 'https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js', array( 'jquery' ), '1.0', true );

}
add_action( 'wp_enqueue_scripts', 'enqueue_cours_scripts' );


function get_dates_ajax(){
  $posttitle = $_REQUEST['course'];
  $location = $_REQUEST['location'];
  global $wpdb;
  $courseid = $wpdb->get_var( "SELECT ID FROM $wpdb->posts WHERE post_title = '" . $posttitle . "' and post_type = 'courses'" );
  $dates = get_post_meta($courseid,'additional_fields',true);
  ?>
  <option value="">Kies uw datum</option>
  <?php foreach($dates as $date){
   if($date['location']==$location){
      $dates = explode(',',$date['date']);
      if($dates){
          foreach($dates as $date){
              echo '<option value="'.$date.'">'.$date.'</option>';
          }
      }
  }
}?>



<?php
die();
}
add_action('wp_ajax_nopriv_get_dates_ajax', 'get_dates_ajax');
add_action('wp_ajax_get_dates_ajax', 'get_dates_ajax');

function get_postorder(){

    $firstname = $_POST['first_name'];
    $middle_name = $_POST['middle_name'];
    $last_name = $_POST['last_name'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $postcode = $_POST['postcode'];
    $residence = $_POST['residence'];
    $dob = $_POST['date_of_birth'];
    $referral = $_POST['referral'];
    $payment_method =  $_POST['payment_method'];
    $payment_status = 'pending';
    $coursename = "";
    $tital = $firstname." ".$middle_name." ".$last_name.' Registed for ';
    $courses = $_POST['courses_code'];
    $location = $_POST['location'];
    $dates = $_POST['date'];
    $prices = $_POST['courseprice'];
    $examens = $_POST['examen'];
    $total = $_POST['grand_total'];
    $total = 0;
    $sanitized_fields = array();
    if($courses){
     $tital.= implode("-",$courses);

     $c=0;
     foreach ($courses as $field) {
        $sanitized_field = array(
            'course' => sanitize_text_field($field),
            'location' => sanitize_text_field($location[$c]),
            'date' => sanitize_text_field($dates[$c]),
            'price' => sanitize_text_field($prices[$c]),
            'examen' => sanitize_text_field($examens[$c]),
        );
        $total = $total + $prices[$c] ;

        $sanitized_fields[] = $sanitized_field;
        $c++;
    }

}
$total = number_format($total,2);
if($courses){
 $post_id = wp_insert_post(array (
     'post_type' => 'registration',
     'post_title' => wp_strip_all_tags($tital),
     'post_content' => '',
     'post_status' => 'publish',
   'comment_status' => 'closed',   // if you prefer
   'ping_status' => 'closed',      // if you prefer
));

 if($post_id){

    update_post_meta($post_id,'courses',$sanitized_fields);
    update_post_meta($post_id,'firstname',$firstname);
    update_post_meta($post_id,'middle_name',$middle_name);
    update_post_meta($post_id,'last_name',$last_name);
    update_post_meta($post_id,'phone',$phone);
    update_post_meta($post_id,'email',$email);
    update_post_meta($post_id,'address',$address);
    update_post_meta($post_id,'postcode',$postcode);
    update_post_meta($post_id,'residence',$residence);
    update_post_meta($post_id,'date_of_birth',$dob);
    update_post_meta($post_id,'referral',$referral);
    update_post_meta($post_id,'payment_method',$payment_method);
    update_post_meta($post_id,'payment_status',$payment_status);
    update_post_meta($post_id,'total',$total);

    if($payment_method=="partial" || $payment_method=='full'){
       $total = $total;

       if($payment_method=="partial"){
         $total = '50.00';
     }

     $mollie_api_key = 'test_Ajg54dn8kjnvBEHAusHbWkqSB5ee9D';
     $test_api = get_option('test-api-key');
     if($test_api==""){
        $test_api = 'test_Ajg54dn8kjnvBEHAusHbWkqSB5ee9D';	
    }
    $mollie_api_key = get_option('api-key');


    if($mollie_api_key==""){
        $mollie_api_key =  $test_api;	
    }
    $redirecturl =  get_bloginfo('url').'/course-details/';

// Set the API endpoint URL for testing
    $api_url = 'https://api.mollie.com/v2/payments';

// Set the payment data
    $payment_data = array(
        'amount' => array(
            'currency' => 'EUR',
            'value' => $total,
        ),
        'description' => 'Course Registration',
        'redirectUrl' => $redirecturl."?orderid=".$post_id,

    );

// Prepare the HTTP headers
    $headers = array(
        'Authorization: Bearer ' . $mollie_api_key,
        'Content-Type: application/json'
    );

// Send the POST request to create the payment
    $ch = curl_init($api_url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payment_data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    $response = curl_exec($ch);
    curl_close($ch);

// Process the API response
    if ($response === false) {
    // Error handling
        die('Error: ' . curl_error($ch));
    }

// Decode the API response JSON
    $payment = json_decode($response, true);


    update_post_meta($post_id,'paymentid',$payment['id']);
// Redirect the user to the payment URL
    if (!empty($payment['_links']['checkout']['href'])) {
   // header('Location: ' . $payment['links']['checkout']['href']);
     echo 'sucess#'.$payment['_links']['checkout']['href'];

 } else {
    // Error handling
    echo 'error#Error creating payment.';
}



}



}


}



die();	
}

add_action('wp_ajax_nopriv_get_postorder', 'get_postorder');
add_action('wp_ajax_get_postorder', 'get_postorder');




/* ==============================*/

function my_plugin_register_menu_page() {
    add_menu_page(
        'Mollie Setting Page', // Page title
        'Mollie Setting Page', // Menu title
        'manage_options', // Capability required to access the page
        'my-plugin-menu', // Menu slug
        'my_plugin_render_menu_page', // Callback function to render the menu page
        'dashicons-admin-plugins', // Icon for the menu
        10 // Position of the menu item
    );
    
    add_action('admin_init', 'my_plugin_register_settings');
}
add_action('admin_menu', 'my_plugin_register_menu_page');

function my_plugin_register_settings() {
    // Register the settings
    register_setting(
        'my-plugin-settings', // Option group
        'api-key', // Option name
        'sanitize_text_field' // Sanitization callback function
    );

    register_setting(
        'my-plugin-settings', // Option group
        'test-api-key', // Option name
        'sanitize_text_field' // Sanitization callback function
    );

    register_setting(
        'my-plugin-settings', // Option group
        'redirecturl', // Option name
        'sanitize_text_field' // Sanitization callback function
    );


    // Add a section for the settings
    add_settings_section(
        'my-plugin-settings-section', // Section ID
        'Custom Settings', // Section title
        'my_plugin_render_settings_section', // Callback function to render the section description
        'my-plugin-menu' // Menu slug of the menu page
    );

    // Add the settings fields
    add_settings_field(
        'api-key', // Field ID
        'API Key', // Field title
        'my_plugin_render_setting_field', // Callback function to render the field
        'my-plugin-menu', // Menu slug of the menu page
        'my-plugin-settings-section', // Section ID
        [
            'id' => 'api-key', // Setting ID
            'label' => 'API Key' // Setting label
        ]
    );

    add_settings_field(
        'test-api-key', // Field ID
        'Test Api KEY', // Field title
        'my_plugin_render_setting_field', // Callback function to render the field
        'my-plugin-menu', // Menu slug of the menu page
        'my-plugin-settings-section', // Section ID
        [
            'id' => 'test-api-key', // Setting ID
            'label' => 'Test Api Key' // Setting label
        ]
    );

    add_settings_field(
        'redirecturl', // Field ID
        'Redirect Url', // Field title
        'my_plugin_render_setting_field', // Callback function to render the field
        'my-plugin-menu', // Menu slug of the menu page
        'my-plugin-settings-section', // Section ID
        [
            'id' => 'redirecturl', // Setting ID
            'label' => 'Redirect URL' // Setting label
        ]
    );
}

function my_plugin_render_settings_section() {
    echo 'API Configure settings.';
}

function my_plugin_render_setting_field($args) {
    $setting_id = $args['id'];
    $setting_label = $args['label'];
    $setting_value = get_option($setting_id);

    ?>
    <input type="text" name="<?php echo esc_attr($setting_id); ?>" value="<?php echo esc_attr($setting_value); ?>" class="regular-text" />
    
    <?php
}

function my_plugin_render_menu_page() {
    ?>
    <div class="wrap">
        <h1>My Plugin Menu Page</h1>
        <form method="post" action="options.php">
            <?php
            settings_fields('my-plugin-settings');
            do_settings_sections('my-plugin-menu');
            submit_button('Save Settings');
            ?>
        </form>
    </div>
    <?php
}

// Register the menu page and settings
add_action('admin_menu', 'my_plugin_register_menu_page');
add_action('admin_init', 'my_plugin_register_settings');
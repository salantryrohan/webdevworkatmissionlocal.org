<?php 
/**
 * Template Name: npcreateevents
 * author: Rohan Salantry
 * Copyright (c) 2012 The Regents of the University of California
 * Released under the GPL Version 2 license
 * http://www.opensource.org/licenses/gpl-2.0.php
 * This program is distributed in the hope that it will be useful, but
 * WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

?>

<?php

  //response generation function

  $response = "";
  $skills = array('Admin/clerical', 'Animal care', 'Farming/gardening', 'Music Arts', 'Performing Arts', 'Dance', 'Visual Arts', 'Photography', 'Children/Youth services', 'Tutoring/mentoring', 'Financial Literacy', 'Sport Coaching', 'Food Service and Events', 'IT Help', 'Web Design', 'Sales', 'Writing');

  //function to generate response
  function my_contact_form_generate_response($type, $message)
  {

    global $response;

    if($type == "success") $response = "<div class='success'>{$message}</div>";
    else $response = "<div class='error'>{$message}</div>";

  }

  //response messages
  $not_human       = "Human verification incorrect.";
  $missing_content = "Please supply all information.";
  $email_invalid   = "Email Address Invalid.";
  $message_unsent  = "Message was not sent. Try Again.";
  $message_sent    = "Your event has been created. Volunteers will contact you with questions.";

  //user posted variables
  
  $email = $_POST['message_email'];
  $code = $_POST['message_code'];
  $cause = $_POST['message_cause'];
  
  $mission = $_POST['message_mission'];
  $start = $_POST['message_start'];
  $end = $_POST['message_end'];
  
  $human = $_POST['message_human'];
  

  if(!$human == 0)
  {
    if($human != 2) my_contact_form_generate_response("error", $not_human); //not human!
    else 
  {
      if(empty($email) || empty($code) || empty($mission) || empty($start) || empty($end))
      {
          my_contact_form_generate_response("error", $missing_content);
      }
      else // good to go !
      {
        $catid = get_cat_ID("nonprofitevents");
        global $wpdb; // used for custom sql queries

        $querystr = "select * from wp_postmeta where meta_key = 'accesscode' and meta_value = '".$code."'";

        $result = $wpdb->get_row($querystr); //result is one row

        if(is_null($result))
        {
            my_contact_form_generate_response("error", "no match for accesscode");
        }
        else 
        { 
              $parpostid = $result->post_id;
        echo "parent".$parpostid;
        $name = get_the_title($parpostid);

        $post_title = $cause;
        $post_content = $mission;



        $my_post = array(
          'post_title'    => $post_content,
          'post_content'  => $post_content,
          'post_status'   => 'publish',
          'post_author'   => 1,
          'post_category' => array($catid),
          'post_parent' => $parpostid
          
         // 'post_type' => 'nonprofitevents'
        );

        // Insert the post into the database
        $post_id = wp_insert_post( $my_post );
        echo $post_id;

        // create a tag with the organization name
        wp_set_post_tags($post_id, $name, true);
        wp_set_post_tags($post_id, $cause, true);

        
        $timestampstart = strtotime($start);
        $timestampend = strtotime($end);
        // 
        add_post_meta($post_id, 'mission', $mission, true );
        add_post_meta($post_id, 'start', $timestampstart, true );
        add_post_meta($post_id, 'end', $timestampend, true );


        // confirmation
        my_contact_form_generate_response("success", $message_sent);
        //now you can use $post_id withing add_post_meta or update_post_meta

        }
        
      }      
      
    }

    
        
      
      
    }
  else if ($_POST['submitted']) my_contact_form_generate_response("error", $missing_content);

?>

<?php get_header(); ?>

  <div id="primary" class="site-content">
    <div id="content" role="main" style="margin-left:50px;">

      <?php while ( have_posts() ) : the_post(); ?>

          <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

            <header class="entry-header">
              <h1 class="entry-title"><?php the_title(); ?></h1>
            </header>

            <div class="entry-content">
              <?php the_content(); ?>
              <?php echo $post_id;?>

              <style type="text/css">
                .error{
                  padding: 5px 9px;
                  border: 1px solid red;
                  color: red;
                  border-radius: 3px;
                }

                .success{
                  padding: 5px 9px;
                  border: 1px solid green;
                  color: green;
                  border-radius: 3px;
                }

                form span{
                  color: red;
                }
              </style>

              <div id="respond">
                <?php echo $response; ?>
                <form action="<?php the_permalink(); ?>" method="post">
                 
                  
                  <p><label for="message_email">Email:
                  <span>*</span> <br> <input type="text" name="message_email" value="<?php echo esc_attr($_POST['message_email']); ?>"></label></p>
                                    
                  <p><label for="message_code">Access Code:
                  <span>*</span> <br> <input type="text" name="message_code" value="<?php echo esc_attr($_POST['message_code']); ?>"></label></p>
                  


                  <p><label for="message_cause">Skills you are looking for:
                  <span>*</span> <br>
                  
                  <select name="message_cause">
    
            <option value=""></option>
<?php foreach ($skills as $val) 
{
echo "<option value=\"".$val."\">".$val."</option>";    
}
?>


</select> </label> <br>

                  <p><label for="message_mission">Description of what volunteers would be doing:<span>*</span> <br><textarea type="text" name="message_mission" value="<?php echo esc_attr($_POST['message_mission']); ?>"></textarea></label></p>
                  
                 <p><label for="message_start">Start:
                  <span>*</span> <br> <input type="text" name="message_start" value="<?php echo esc_attr($_POST['message_start']); ?>"></label></p>
                 
                  <p><label for="message_end">End:
                  <span>*</span> <br> <input type="text" name="message_end" value="<?php echo esc_attr($_POST['message_end']); ?>"></label></p>
                 
                    

              
                  <p><label for="message_human">Human Verification: <span>*</span> <br><input type="text" style="width: 60px;" name="message_human"> + 3 = 5</label></p>
                  <input type="hidden" name="submitted" value="1">
                  <p><input type="submit"></p>
                </form>
              </div>


            </div><!-- .entry-content -->

          </article><!-- #post -->

      <?php endwhile; // end of the loop. ?>

    </div><!-- #content -->
  </div><!-- #primary -->


<?php get_footer(); ?>


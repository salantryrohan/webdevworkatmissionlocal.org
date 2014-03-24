<?php 
/**
 * Template Name: volunteer
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
  $message_sent    = "Thanks for registering with us. We will email you an access code to create volunteer events.";

  //user posted variables
  $name = $_POST['message_name'];
  $addr = $_POST['message_addr'];
  $email = $_POST['message_email'];
  $cause = $_POST['message_cause'];
  $cause1 = $_POST['message_cause1'];
  $cause2 = $_POST['message_cause2'];
  $mission = $_POST['message_mission'];
  $contact = $_POST['message_contact'];
  $notice = $_POST['message_notice'];
  $human = $_POST['message_human'];
  $requirements = $_POST['message_requirements'];

  if(!$human == 0)
  {
    if($human != 2) my_contact_form_generate_response("error", $not_human); //not human!
    else 
  {
      if(empty($name) || empty($addr) || empty($email))
      {
          my_contact_form_generate_response("error", $missing_content);
      }
      else // good to go !
      {
        $catid = get_cat_ID("nonprofit");

      $post_title = $name;
      $post_content = "Name: ".$name."<br/>Address:".$addr."<br/>Email: ".$email."<br/>Cause 1:".$cause."<br/>Cause 2:".$cause1."<br/>Cause 3:".$cause2;
      $post_content = $post_content."<br/>mission statement:".$mission."<br/>notice:".$notice."<br/>requirements:".$requirements;


        $my_post = array(
          'post_title'    => $post_title,
          'post_content'  => $post_content,
          'post_status'   => 'draft',
          'post_author'   => 1,
          'post_category' => array($catid)
        );

        // Insert the post into the database
        $post_id = wp_insert_post( $my_post );

        // create a tag with the organization name
        wp_set_post_tags($post_id, $name, true);

        $rand = rand();
        // 
        add_post_meta($post_id, 'primaryemail', $email, true );
        add_post_meta($post_id, 'accesscode', $rand, true );

        // confirmation
        my_contact_form_generate_response("success", $message_sent);
        //now you can use $post_id withing add_post_meta or update_post_meta
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
                  <p><label for="message_name">Name: <span>*</span> <br><input type="text" name="message_name" value="<?php echo esc_attr($_POST['message_name']); ?>"></label></p>
                  
                  <p><label for="message_email">primary email of company
                  <span>*</span> <br> <input type="text" name="message_email" value="<?php echo esc_attr($_POST['message_email']); ?>"></label></p>
                                    
                  <p><label for="message_addr">Address: <span>*</span> <br><textarea type="text" name="message_addr"><?php echo esc_textarea($_POST['message_addr']); ?></textarea></label></p>
                  
                  Enter atleast one and upto three areas in which you are seeking volunteers. 


                  <p><label for="message_cause">Area 1
                  <span>*</span> <br>
                  
                  <select name="message_cause">
    <option value="Advocacy and Human Rights">Advocacy and Human Rights</option>
    <option value="Animals">Animals</option>
    <option value="Arts and Culture">Arts and Culture</option>
    <option value="Children and Youth ">Children and Youth </option>

    <option value="Community">Community</option>
    <option value="Computers and Technology">Computers and Technology</option>
    <option value="Crisis Support">Crisis Support</option>
    <option value="Disabled">Disabled</option>
    <option value="Disaster Relief">Disaster Relief</option>

    <option value="Education and Literacy">Education and Literacy</option>
    <option value="Emergency and Safety">Emergency and Safety</option>
    <option value="Employment">Employment</option>

    <option value="Faith-Based">Faith-Based</option>
    <option value="Health and Medicine">Health and Medicine</option>
    <option value="Homeless and Housing">Homeless and Housing</option>
    <option value="Hunger">Hunger</option>

    <option value="Immigrants">Immigrants</option>
    <option value="Justice and Legal">Justice and Legal</option>
    <option value="LGBT">LGBT</option>
    <option value="Media and Broadcasting">Media and Broadcasting</option>

    <option value="Politics">Politics</option>
    <option value="Seniors">Seniors</option>
    <option value="Sports and Recreation">Sports and Recreation</option>
    <option value="Veterans">Veterans</option>
    <option value="Women">Women</option>



</select> </label> <br>

<label for="message_cause1">Area 2
<span></span> <br>
              <select name="message_cause">
    <option value="Advocacy and Human Rights">Advocacy and Human Rights</option>
    <option value="Animals">Animals</option>
    <option value="Arts and Culture">Arts and Culture</option>
    <option value="Children and Youth ">Children and Youth </option>

    <option value="Community">Community</option>
    <option value="Computers and Technology">Computers and Technology</option>
    <option value="Crisis Support">Crisis Support</option>
    <option value="Disabled">Disabled</option>
    <option value="Disaster Relief">Disaster Relief</option>
    
    <option value="Education and Literacy">Education and Literacy</option>
    <option value="Emergency and Safety">Emergency and Safety</option>
    <option value="Employment">Employment</option>

    <option value="Faith-Based">Faith-Based</option>
    <option value="Health and Medicine">Health and Medicine</option>
    <option value="Homeless and Housing">Homeless and Housing</option>
    <option value="Hunger">Hunger</option>

    <option value="Immigrants">Immigrants</option>
    <option value="Justice and Legal">Justice and Legal</option>
    <option value="LGBT">LGBT</option>
    <option value="Media and Broadcasting">Media and Broadcasting</option>

    <option value="Politics">Politics</option>
    <option value="Seniors">Seniors</option>
    <option value="Sports and Recreation">Sports and Recreation</option>
    <option value="Veterans">Veterans</option>
    <option value="Women">Women</option>



</select> </label>
<br>
<label for="message_cause2">Area 3
<span></span> <br>

              <select name="message_cause">
    <option value="Advocacy and Human Rights">Advocacy and Human Rights</option>
    <option value="Animals">Animals</option>
    <option value="Arts and Culture">Arts and Culture</option>
    <option value="Children and Youth ">Children and Youth </option>

    <option value="Community">Community</option>
    <option value="Computers and Technology">Computers and Technology</option>
    <option value="Crisis Support">Crisis Support</option>
    <option value="Disabled">Disabled</option>
    <option value="Disaster Relief">Disaster Relief</option>
    
    <option value="Education and Literacy">Education and Literacy</option>
    <option value="Emergency and Safety">Emergency and Safety</option>
    <option value="Employment">Employment</option>

    <option value="Faith-Based">Faith-Based</option>
    <option value="Health and Medicine">Health and Medicine</option>
    <option value="Homeless and Housing">Homeless and Housing</option>
    <option value="Hunger">Hunger</option>

    <option value="Immigrants">Immigrants</option>
    <option value="Justice and Legal">Justice and Legal</option>
    <option value="LGBT">LGBT</option>
    <option value="Media and Broadcasting">Media and Broadcasting</option>

    <option value="Politics">Politics</option>
    <option value="Seniors">Seniors</option>
    <option value="Sports and Recreation">Sports and Recreation</option>
    <option value="Veterans">Veterans</option>
    <option value="Women">Women</option>



</select> </label>
                  </p>


                  <p><label for="message_mission">Basic Mission: <span>*</span> <br><input type="text" name="message_mission" value="<?php echo esc_attr($_POST['message_mission']); ?>"></label></p>
                  
                  <p><label for="message_contact">Who do I contact to come over and volunteer now? <span>*</span> <br><textarea type="text" name="message_contact"><?php echo esc_textarea($_POST['message_contact']); ?></textarea></label></p>

                  <p><label for="message_notice">How much notice do you need? <span>*</span> <br><textarea type="text" name="message_notice"><?php echo esc_textarea($_POST['message_notice']); ?></textarea></label></p>

                  <p><label for="message_requirements">Requirements to Volunteer (Do they need to be fingerprinted. Age Requirement. If fingerprints are required does the school/organization provide services)
<span>*</span> <br><textarea type="text" name="message_requirements"><?php echo esc_textarea($_POST['message_requirements']); ?></textarea></label></p>

              
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


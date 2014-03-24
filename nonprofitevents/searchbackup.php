<?php 
/**
 * Template Name: npsearchevents
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
/**
 * Template Name: Snaps
 */
get_header();
wp_enqueue_style('snapcss', '/wp-content/themes/calpress2/volunteer/volunteer.css');
wp_enqueue_script('jquery-ui-datepicker');
wp_enqueue_style('jquery-style', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css');
?>



<?php

$submitted = $_POST['submitted'];
$area = $_POST['message_area'];
$tag = $_POST['message_company'];
$start = $_POST['message_from'];


$today = date('m/d/Y');
$today = strtotime($today); // time stamp

$nonprofits = array();
query_posts('category_name=nonprofit');
	

	if (have_posts()) : while (have_posts()) : the_post();
        $title = get_the_title();
		array_push($nonprofits,$title);
		
		
	endwhile; endif; 

if($submitted != "")

{

echo "non blank query, reset pagination";

$tagid1 = "";$tagid2 = "";
$args = "";




	//echo "all the nonprofits: ";
	//var_dump($nonprofits);





// http://designshack.net/articles/css/5-simple-and-practical-css-list-styles-you-can-copy-and-paste/
// set the "paged" parameter (use 'page' if the query is on a static front page)
$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;

// set arguments here
// pull posts with category snap


if($area != null or $tag!= null or $start!= null)
{

	if($start == null and ($area != null or $tag != null)) 
	{
		echo "start is null and tag/area is not null";

		if($tag!= null)
		{
			echo "looking up organizations";
			$tagrecord = get_term_by('name', $tag, 'post_tag');
			
			if($tagrecord)
			{
				$tagid1 = $tagrecord->term_id;
			}
		}

		if($area!= null)
		{
			echo "looking up areas";

			$tagrecord1 = get_term_by('name', $area, 'post_tag');
			
			if($tagrecord1)
			{
				$tagid2 = $tagrecord1->term_id;
			}
		}

		$args = array(
		'posts_per_page' => 3,
		
		'tag__in' => array($tagid1,$tagid2),
		
		'paged' => $paged,
		'category_name' => 'nonprofitevents',

		);

	//	var_dump($args);
	} elseif($start != null and $area == null and $tag == null)
	{
		$today = strtotime(date($start));

		echo "start not null and area and tag are null";
		echo "<br>";

		$args = array(
		'posts_per_page' => 3,
		
		'paged' => $paged,
		'category_name' => 'nonprofitevents',

		'meta_key' => 'start',
		'orderby' => 'meta_value',
		'order' => 'ASC',
		'meta_query' => array(
	          
	       		  array(
	                'key' => 'end',
	                 
	             	'value' => $today,
	                'compare' => '>=',
	                'type' => 'NUMERIC'


	                
	            )
	        )
		);
		//var_dump($args);
	} 
	elseif($start != null and ($area!= null or $tag!= null)) 
	{
		$today = strtotime(date($start));

		if($tag!= null)
		{
			echo "looking up organizations";
			$tagrecord = get_term_by('name', $tag, 'post_tag');
			
			if($tagrecord)
			{
				$tagid1 = $tagrecord->term_id;
			}
		}

		if($area!= null)
		{
			echo "looking up areas";

			$tagrecord1 = get_term_by('name', $area, 'post_tag');
			
			if($tagrecord1)
			{
				$tagid2 = $tagrecord1->term_id;
			}
		}
	


		$args = array(
		'posts_per_page' => 3,
		
		'tag__in' => array($tagid1,$tagid2),
		
		'paged' => $paged,
		'category_name' => 'nonprofitevents',

		'meta_key' => 'start',
		'orderby' => 'meta_value',
		'order' => 'ASC',
		'meta_query' => array(
	          
	       		  array(
	                'key' => 'end',
	                 
	             	'value' => $today,
	                'compare' => '>=',
	                'type' => 'NUMERIC'	                
	            )
	        )
		);
		var_dump($args);
	}

} // only worth bothering for if you receive a post request
}
else
{
	echo "blank query with pagination";
	$args = array(
		'posts_per_page' => 5,
		'paged' => $paged,
		'category_name' => 'nonprofitevents',

		'meta_key' => 'start',
		'orderby' => 'meta_value',
		'order' => 'ASC',
		'meta_query' => array(
	          
	       		  array(
	                'key' => 'end',
	                 
	             	'value' => $today,
	                'compare' => '>=',
	                'type' => 'NUMERIC'	                
	            )
	        )
		);

}



// the query
//$the_query = new WP_Query( 'category_name=snaps&paged=' . $paged ); 
$the_query = new WP_Query($args); 
?>

<hr>
<center><p> Find events to volunteer for non profits
<a href = ""><h1>resources for non profits </h1> </a>

<hr>

<div class = "metatags">


<form action="http://staging.missionlocal.org/npevents/" method =post>	
<label for="message_area">Cause Interest

<select name="message_area">
	<option value=""></option>

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

<label for="message_company"> Nonprofit Name 
<select name="message_company">
<option value=""></option>

<?php 
foreach ($nonprofits as $val) 
{
echo "<option value=\"".$val."\">".$val."</option>";    
}
?> 
</select> </label>
&nbsp;&nbsp;

<label for="message_from"> Date you are available <input type="text" name="message_from" id= "message_from" value="<?php echo esc_attr($_POST['message_from']); ?>"></label></p>
<br>
<input type="hidden" value="1" name="submitted">
<input type="submit" value="SEARCH" class = "myButton">
</form>
</div>

<?php if ( $the_query->have_posts() ) : ?>





<?php
function string_limit_words($string, $word_limit)
{
  $words = explode(' ', $string, ($word_limit + 1));
  if(count($words) > $word_limit)
  array_pop($words);
  return implode(' ', $words);
}
?>






<div class = "eventsearchdiv">
<ul>
<?php
// the loop
while ( $the_query->have_posts() ) : $the_query->the_post(); 
?>



<?php if(has_post_thumbnail()) 
$imageurl = wp_get_attachment_url( get_post_thumbnail_id($post->ID) ); ?>
	<li>
	<?php 
	$start = get_post_meta( get_the_ID(), 'start', true );
	$end = get_post_meta( get_the_ID(), 'end', true );
	$description = get_the_content();
	$parent_title = get_the_title($post->post_parent);
	$permalink = get_permalink($post->post_parent);
	?>
	
	

<span style= "display: block;/*float:left;*/ padding: 7px 10px 7px 20px;
background-color: #749796;
color: #fff;
font-weight: bold;
font-size: 14px;"> Nonprofit Name: <a href = <?php echo $permalink;?> > <?php echo $parent_title;?> </a>
</span>
<br>
	
	<span style="display: block;
font-weight: normal;
font-size: 15px;
text-align: left;
float:left;">
	From: <?php echo date('m/d/Y', $start);?>&nbsp;-
	To: <?php echo date('m/d/Y', $end);?>
	</span>
	<br>
	<?php $desc = string_limit_words($description,20); ?>
	<span style="display: block;
font-weight: normal;
font-size: 15px;
text-align: left;"> Event Description: <?php echo $desc; ?> </span> 
	
	


	
	
	</li> <hr>

		

<?php endwhile; ?>
<center>
<?php
// next_posts_link() usage with max_num_pages
previous_posts_link( 'PREVIOUS' );
echo '&nbsp';echo '&nbsp';echo '&nbsp';
echo '&nbsp';echo '&nbsp';echo '&nbsp';
next_posts_link( 'NEXT', $the_query->max_num_pages );
?>
</center>

  

<?php echo "</ul></div>"?>

<script>
  	jQuery(function(){
  		jQuery("#message_from").datepicker();
  	});

  	
</script>




<?php 
// clean up after our query
wp_reset_postdata(); 
?>

<?php else:  ?>
<p><?php _e( 'Sorry, no posts matched your criteria.' ); ?></p>
<?php endif; ?>

<?php get_footer(); ?>

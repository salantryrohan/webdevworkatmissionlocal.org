<?php 
session_start();
/**
 * Template Name: Snaps
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
//wp_register_script('customcsstag','/wp-content/themes/calpress2/volunteer/volunteer.css');

wp_enqueue_style('customcsstag','/wp-content/themes/calpress2/volunteer/volunteer_final.css');
wp_enqueue_script('jquery-ui-datepicker');
wp_enqueue_script('cjs','http://code.jquery.com/ui/1.10.4/jquery-ui.js');

wp_enqueue_style('jquery-style', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css');

get_header();
//wp_register_script('jquery-style','http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css');

?>


<?php

$submitted = $_POST['submitted'];

// set the "paged" parameter (use 'page' if the query is on a static front page)
$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;

$today = date('m/d/Y');
$today = strtotime($today); // time stamp



echo "before:";


if(!isset($_SESSION['start'])){
$_SESSION['start'] = $today;  	
}
if(!isset($_SESSION['query'])){
$_SESSION['query'] = "blank";	
}


$skills = array('Admin/clerical', 'Animal care', 'Farming/gardening', 'Music Arts', 'Performing Arts', 'Dance', 'Visual Arts', 'Photography', 'Children/Youth services', 'Tutoring/mentoring', 'Financial Literacy', 'Sport Coaching', 'Food Service and Events', 'IT Help', 'Web Design', 'Sales', 'Writing');
//echo 'rohan'.$_SESSION['query'];

$nonprofits = array();

$category_id = get_cat_ID('nonprofit');

echo "look here".$catid;

$argsnonprofits = array('post_type' => 'post','category_name' => 'nonprofit'); 
$the_query_nonprofits = new WP_Query($argsnonprofits); 

if ( $the_query_nonprofits->have_posts() ) {
        while ( $the_query_nonprofits->have_posts() ) {
		$the_query_nonprofits->the_post();
		array_push($nonprofits,get_the_title());
		}
}	

var_dump($nonprofits);

// conditions for setting session parameterse

if($submitted != "")
{
	// skill
	$area = $_POST['message_area'];

	// non profit name
	$tag = $_POST['message_company'];
	
	$start = $_POST['message_from'];	

	if($area == null and $tag == null and $start == null)
	{
		$_SESSION['query'] = "blank";
		$_SESSION['start'] = $today; //todays date
		
		if(isset($_SESSION['tagid1'])){
		$_SESSION['tagid1'] = null;  	
		}
		if(isset($_SESSION['tagid2'])){
		$_SESSION['tagid2'] = null;  	
		}
	}

	if($start == null and ($area != null or $tag != null)) 
	{
		echo "session set to st null";
		$_SESSION['query'] = "stnull";
		
		if($tag != null) 
		{
			$tagrecord = get_term_by('name', $tag, 'post_tag');
			
			if($tagrecord)
			{
				$tagid1 = $tagrecord->term_id;
				$_SESSION['tagid1'] = $tagid1;
			}	
		

		if(isset($_SESSION['tagid2'])){
		$_SESSION['tagid2'] = null;  	
		}

		}	

		if($area != null) 
		{
			echo "looking up".$area;

			$tagrecord1 = get_term_by('name', $area, 'post_tag');
			
			if($tagrecord1)
			{
				$tagid2 = $tagrecord1->term_id;
				$_SESSION['tagid2'] = $tagid2;
			}
		
		
			if($tag == null and isset($_SESSION['tagid1']))
			{
				$_SESSION['tagid1'] = null;  	

			}
		
		}
	}

	if($start != null and $area == null and $tag == null) 
	{
		$_SESSION['query'] = "stnotnull";
		
		$today = strtotime(date($start));
		$_SESSION['start'] = $today;	
		if(isset($_SESSION['tagid1'])){
		$_SESSION['tagid1'] = null;  	
		}
		if(isset($_SESSION['tagid2'])){
		$_SESSION['tagid2'] = null;  	
		}
		
		
	}

	if($start != null and ($area!= null or $tag!= null))
	{
		$_SESSION['query'] = "stnotnullareatag";		
		
		if($tag != null) 
		{
			$tagrecord = get_term_by('name', $tag, 'post_tag');
			
			if($tagrecord)
			{
				$tagid1 = $tagrecord->term_id;
			}	
		$_SESSION['tagid1'] = $tagid1;

		if(isset($_SESSION['tagid2'])){
		$_SESSION['tagid2'] = null;  	
		}

		}	

		if($area != null) 
		{
			$tagrecord1 = get_term_by('name', $area, 'post_tag');
			
			if($tagrecord1)
			{
				$tagid2 = $tagrecord1->term_id;
			}
		$_SESSION['tagid2'] = $tagid2;
		
			if($tag == null and isset($_SESSION['tagid2']))
			{
				$_SESSION['tagid2'] = null;  	

			}
		
		}
	}

}

var_dump($_SESSION);	

// QUERY REGION
$args = "";
$tagid1 = "";
$tagid2 = "";

if($_SESSION['query'] == "blank")
{
	echo "blank :@";

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
	                 
	             	'vfprintf(handle, format, args)alue' => $_SESSION['start'],
	                'compare' => '>=',
	                'type' => 'NUMERIC'	                
	            )
	        )
);
}
elseif($_SESSION['query'] == "stnull")
{
		echo "start is null and tag/area is not null";
		echo $_SESSION['query'];

		$args = array(
		'posts_per_page' => 3,
		
		'tag__in' => array($_SESSION['tagid1'],$_SESSION['tagid2']),
		
		'paged' => $paged,
		'category_name' => 'nonprofitevents',

		);


}
elseif($_SESSION["query"] == "stnotnull")
{	
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
	                 
	             	'value' => $_SESSION['start'],
	                'compare' => '>=',
	                'type' => 'NUMERIC'


	                
	            )
	        )
		);

}
elseif($_SESSION["query"] == "stnotnullareatag")
{

	$args = array(
		'posts_per_page' => 3,
		
		'tag__in' => array($_SESSION['tagid1'],$_SESSION['tagid2']),
		
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
<label for="message_area">Search by your Skills:

<select name="message_area">
<option value=""></option>
<?php foreach ($skills as $val) 
{
echo "<option value=\"".$val."\">".$val."</option>";    
}
?>


</select> </label>
<br>
<br>
<label for="message_company"> Search by Nonprofit Name 
<select name="message_company">
<option value=""></option>

<?php 
foreach ($nonprofits as $val) 
{

echo "<option value=\"".$val."\">".$val."</option>";    
}
?> 
</select> </label>
<br><br>
<b>OR</b> <br>
<label for="message_from"> <u>Tell us when you are available</u> <input type="text" name="message_from" id= "message_from" value="<?php echo esc_attr($_POST['message_from']); ?>"></label></p>
<br><br>
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
	
	

<span style= "display: block;padding: 7px 10px 7px 20px;
background-color: #749796;
color: #fff;
font-weight: bold;
font-size: 14px;margin-right: 12px;"> Nonprofit Name: <a href = <?php echo $permalink;?> > <?php echo $parent_title;?> </a>
</span>
<br>
	
	<span style="float:left;">
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

<?php 
// clean up after our query
wp_reset_postdata(); 
?>

<script>
  	jQuery(function(){
  		jQuery("#message_from").datepicker();
  	});

 </script>



<?php else:  ?>
<p><?php _e( 'Sorry, no posts matched your criteria.' ); ?></p>
<?php endif; ?>


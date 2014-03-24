<?php 
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

get_header();
echo "<link href=/wp-content/themes/calpress2/volunteer/volunteer.css type=text/css rel=stylesheet/>";
wp_enqueue_style('snapcss', '/wp-content/themes/calpress2/snaps/snaps.css');
?>


<?php
// set the "paged" parameter (use 'page' if the query is on a static front page)
$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;

// set arguments here
// pull posts with category snap

$args = array(
	'posts_per_page' => 12,
	'paged' => $paged,
	'category_name' => 'snaps',
	'orderby' => 'date',
	'order' => 'DESC'
);

// the query
//$the_query = new WP_Query( 'category_name=snaps&paged=' . $paged ); 
$the_query = new WP_Query($args); 
?>

<?php if ( $the_query->have_posts() ) : ?>
<hr>
<center><p> SNAPP, a project by citizen journalists.
<a href = "http://missionlocal.org/uploadsnaps/"> <img src="http://missionlocal.org/wp-content/uploads/2014/03/upload.png"> </a></p></center>

<hr>

<?php
function string_limit_words($string, $word_limit)
{
  $words = explode(' ', $string, ($word_limit + 1));
  if(count($words) > $word_limit)
  array_pop($words);
  return implode(' ', $words);
}
?>

<?php echo "<ul class=\"rig columns-4\">"?>
<?php
// the loop
while ( $the_query->have_posts() ) : $the_query->the_post(); 
?>



<?php if(has_post_thumbnail()) 
$imageurl = wp_get_attachment_url( get_post_thumbnail_id($post->ID) ); ?>
	<li>
	<a href = "<?php echo get_permalink(); ?>"><?php echo "<img src=\"".$imageurl."\">"; ?></a>

	<a href = "<?php echo get_permalink(); ?>"><p>
	<?php $title_short = get_the_title(); ?>

	<?php echo $title_short;?> 
	
	</a>
	
	</p>
	<p><?php echo get_the_date();?> </p>
	
	<?php $content = get_the_content(); ?>
	<?php if($content != "") 
		{
			echo "<a href = \"".get_permalink()."\"><p>".string_limit_words($content,5)."...</p></a>";
		}
		
		?>
	
	
	</li>	

		

<?php endwhile; ?>


<?php echo "</ul>"?>
	

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

<?php else:  ?>
<p><?php _e( 'Sorry, no posts matched your criteria.' ); ?></p>
<?php endif; ?>

<?php get_footer(); ?>

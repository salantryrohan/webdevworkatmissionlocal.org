<?php
/*
Plugin Name: Upcoming Events Widget Custom
Author: Rohan Salantry
*/


class RandomPostWidget extends WP_Widget
{
  function RandomPostWidget()
  {
    $widget_ops = array('classname' => 'tribe-events-adv-list-widget', 'description' => 'Displays a random post with thumbnail' );
    $this->WP_Widget('RandomPostWidget', 'Random Post and Thumbnail', $widget_ops);
  }
 
  function form($instance)
  {
    $instance = wp_parse_args( (array) $instance, array( 'title' => '' ) );
    $title = $instance['title'];
?>
  <p><label for="<?php echo $this->get_field_id('title'); ?>">Title: <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo attribute_escape($title); ?>" /></label></p>
<?php
  }
 
  function update($new_instance, $old_instance)
  {
    $instance = $old_instance;
    $instance['title'] = $new_instance['title'];
    return $instance;
  }
 
  function widget($args, $instance)
  {
    extract($args, EXTR_SKIP);
 
    echo $before_widget;
    $title = empty($instance['title']) ? ' ' : apply_filters('widget_title', $instance['title']);
 
    if (!empty($title))
      echo $before_title . $title . $after_title;;
 
    
  //    print("start");

    // WIDGET CODE GOES HERE
    global $wpdb; // used for custom sql queries
    
//    if(date('Y-m-d')>'2011-10-10 10:10:10')

//    print(date('Y-m-d'));

$sql="select *
from wp_posts post,wp_postmeta meta
where post.post_type = 'tribe_events' and
post.id = meta.post_id and meta.meta_key = '_EventEndDate'
and meta.meta_value >=".date('Y-m-d')." and meta.meta_value is not null 
order by meta.meta_value asc";
    
     $posts = $wpdb->get_results($sql);


     $count = 0;
                    // print events that are not recurring
                    print("<ol class = \"hfeed vcalendar\">");
                    
                    foreach ($posts as $post)
                    {   
                       // print("check");

                        //print($post->meta_value);
                      /*  if('2014-2-5 17:00:00'>date('Y-m-d'))
                            {print("greater");
                            }
                        else{
                            print("lesser");
                            }*/

                        print("<li>");
                        if($count >= 5)
                        {
                            //print("reached 5");
                            break;
                        }

                        
                        
                        $sql1 = "select * from wp_postmeta where post_id ='".$post->post_id."' and meta_key = '_EventRecurrence'";
                        $postrecur = $wpdb->get_row($sql1); //result is one row
                        
                        $str = $postrecur->meta_value;
                        //print($sql1);print($str);
                           
                        

                        $re = '/every/i'; // look for the word every
                        
                        // regex for end date information
                        $regexdates = '/Eventstartdate";[\w]:[\d]+:"(?P<year>\d+)-(?P<month>\d+)-(?P<day>\d+) (?P<hour>\d+):(?P<min>\d+)/i';
                        $regexdatesend = '/Eventenddate";[\w]:[\d]+:"(?P<year>\d+)-(?P<month>\d+)-(?P<day>\d+) (?P<hour>\d+):(?P<min>\d+)/i';
                        
                        preg_match($regexdates, $str, $datearr);
                        preg_match($regexdatesend, $str, $datearrend);

                        
                   //   var_dump($datearr);
                        $monthvar = $datearr["month"];
                        $monthvarend = $datearrend["month"];

                        $stringmonth = '';
                        $stringmonthend = '';

                        switch($monthvar) 
                    { 
                        case "01" : $stringmonth = "Jan"; case "02" : $stringmonth = "Feb"; case "03" :    $stringmonth = "Mar"; case "04" : $stringmonth = "Apr"; case "05" :  $stringmonth = "May"; case "06" : $stringmonth = "June"; 
                        case "07" :     $stringmonth = "July"; case "08" : $stringmonth = "Aug"; 
                        case "09" :     $stringmonth = "Sep"; case "10" : $stringmonth = "Oct"; case "11" : $stringmonth = "Nov"; 
                        case "12" : $stringmonth = "Dec"; 
                    }


                    switch($monthvarend) 
                    { 
                        case "01" : $stringmonthend = "Jan"; case "02" : $stringmonthend = "Feb"; case "03" :    $stringmonthend = "Mar"; case "04" : $stringmonthend = "Apr"; case "05" :  $stringmonthend = "May"; case "06" : $stringmonthend = "June"; 
                        case "07" :     $stringmonthend = "July"; case "08" : $stringmonthend = "Aug"; 
                        case "09" :     $stringmonthend = "Sep"; case "10" : $stringmonthend = "Oct"; case "11" : $stringmonthend = "Nov"; 
                        case "12" : $stringmonthend = "Dec"; 
                    }

                        if(preg_match($re, $str, $matches) == 0)
                            {
                                //print("not recurring");
                            
                                print("<h4 class=title_summary><a href=\"".get_permalink($post->id)."\">".$post->post_title."</a></h4>");    
                                print("<div class=\"duration\"><span class=\"date-start dtstart\">");
                                print($stringmonth." ".$datearr["day"]." @ ".$datearr["hour"].":".$datearr["min"]);
                                print("</span>");
                                


                                print(" - ");
                                print("<span class=\"date-end dtend\">");

                                print($stringmonthend." ".$datearrend["day"]." @ ".$datearrend["hour"].":".$datearrend["min"]);
                                print("</span></div>");

                                //print('<hr>');

                            //  print("<div class=\"vcard adr location\"><span class=\"fn org tribe-venue\">");

                                $sql2 = "select * from wp_postmeta where post_id ='".$post->post_id."' and meta_key = '_EventCost'";
                                $postcost = $wpdb->get_row($sql2); //result is one row
                                $strcost = $postcost->meta_value;
                                print("<div class =\"tribe-events-event-cost\">");
                                if(!$strcost){$strcost = 'FREE';}
                                else{$strcost ='$ '.$strcost; }
                                print(" | ".$strcost."</div>"); 
                                print("\n");


                                $sql3 = "select * from wp_postmeta where post_id ='".$post->post_id."' and meta_key = '_EventVenueID'";
                                $postvenueid = $wpdb->get_row($sql3); //result is one row
                                $strvenueid = $postvenueid->meta_value;
                                
                                $sql4 = "select * from wp_postmeta where post_id ='".$strvenueid."' and meta_key = '_VenueVenue'";
                                $postvenuename = $wpdb->get_row($sql4); //result is one row
                                $strvenuename = $postvenuename->meta_value;

                                $sql5 = "select * from wp_postmeta where post_id ='".$strvenueid."' and meta_key = '_VenueAddress'";
                                $postvenueaddr = $wpdb->get_row($sql5); //result is one row
                                $strvenueaddr = $postvenueaddr->meta_value;
                             
                                $url = get_permalink($strvenueid);

                                $sql6 = "select guid from wp_posts where id = '".$strvenueid."'";
                                $postvenueurl = $wpdb->get_row($sql6); //result is one row
                                $strvenueurl = $postvenueurl->guid;

                                //print($sql6);
                                //print($strvenueurl);

                                print("<div class = \"vcard adr location\">");
                                
                               
                                print("<a href = \"".$url."\">".$strvenuename."</a>");
                                
                                
                                print("  ".$strvenueaddr);
                                print("</div>");

                                print("</li>");
                               // print("<hr>");
                       
                            $count++;

                            }

                    } // foreach ends
                    

                    if($count < 5)
                    {

                     print("<li>");
                        print("recurring event");
                     print("</li>");


                    }



                    print("</ol>");
 
    
    echo $after_widget;
  }
 
}
add_action( 'widgets_init', create_function('', 'return register_widget("RandomPostWidget");') );?>
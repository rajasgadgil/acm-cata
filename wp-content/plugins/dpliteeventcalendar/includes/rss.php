<?php

//Include Configuration
require_once (dirname (__FILE__) . '/../../../../wp-load.php');
require_once(dirname (__FILE__) . '/../classes/base.class.php');

global $dpProEventCalendar, $wpdb, $table_prefix;

if(!is_numeric($_GET['calendar_id']) || $_GET['calendar_id'] <= 0) { 
	die(); 
}

$calendar_id = $_GET['calendar_id'];

$dpProEventCalendar_class = new DpProEventCalendar( false, $calendar_id );

if(!$dpProEventCalendar_class->calendar_obj->rss_active) 
	die();
	
$limit = $dpProEventCalendar_class->calendar_obj->rss_limit;
if( !is_numeric($limit) || $limit <= 0 ) {
	$limit = 99;	
}
$cal_events = $dpProEventCalendar_class->upcomingCalendarLayout( true, $limit, '', null, null, true, false, true, false, false, '', false );
$blog_desc = ent2ncr(convert_chars(strip_tags(get_bloginfo()))) . " - " . __('Calendar','dpProEventCalendar');

$rssfeed = '<?xml version="1.0" encoding="UTF-8"?>
<rss version="2.0" xmlns:georss="http://www.georss.org/georss" xmlns:atom="http://www.w3.org/2005/Atom">
<channel>
<title>'.$blog_desc.'</title>
<link>'.home_url().'</link>
<atom:link type="application/rss+xml" href="http://'.$_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"].'" rel="self"/>
<description>'.$blog_desc.'</description>
<language>en-us</language>
<ttl>40</ttl>';

if(is_array($cal_events)) {
	foreach ( $cal_events as $event ) {
		if($event->id == "") 
			$event->id = $event->ID;
		
		$event = (object)array_merge((array)$dpProEventCalendar_class->getEventData($event->id), (array)$event);
		
		
								
		if ( get_option('permalink_structure') ) {
			$link = rtrim(get_permalink($event->id), '/').'/'.strtotime($event->date);
		} else {
			$link = get_permalink($event->id).(strpos(get_permalink($event->id), "?") === false ? "?" : "&").'event_date='.strtotime($event->date);
		}

		if(get_post_meta($event->id, 'pec_use_link', true) && get_post_meta($event->id, 'pec_link', true) != "") {
			$link = get_post_meta($event->id, 'pec_link', true);
		}

		$rssfeed .= '
		<item>
		<title>' . $event->title . '</title>
		<description><![CDATA[' . $event->description . ']]></description>
		<link>'.$link .'</link>
		<pubDate>' . date("D, d M Y H:i:s O", strtotime($event->date)) . '</pubDate>
		</item>';
	}
}
$rssfeed .= '
</channel>
</rss>';

//date_default_timezone_set($tz); // set the PHP timezone back the way it was
header("Content-Type: application/rss+xml; charset=UTF-8");
header("Expires: Tue, 03 Jul 2001 06:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

echo $rssfeed;
<?php
/*
Plugin Name: KXAN Headlines
Description: Display KXAN headlines on your WordPress site.
Version: 1.0
Author: Chris Stelly
Author URI: https://stel.ly/
*/

// Register shortcode to display headlines
add_shortcode('kxan_headlines', 'kxan_headlines_shortcode');

// Enqueue style guide CSS
add_action('wp_enqueue_scripts', 'kxan_headlines_enqueue_styles');

// Shortcode callback function
function kxan_headlines_shortcode($atts) {
    // Extract shortcode attributes with default value
    $atts = shortcode_atts(array(
        'count' => 5 // Default value is 5 headlines
    ), $atts);

    // Parse the RSS feed
    $rss = simplexml_load_file('https://www.kxan.com/feed/');

    // Check if the feed was successfully loaded
    if ($rss) {
        $output = '<h3>Headlines from KXAN Austin</h3><ul class="kxan-headlines">';
        
        // Loop through each item in the feed and display the headline
        $counter = 0;
        foreach ($rss->channel->item as $item) {
            if ($counter >= $atts['count']) {
                break;
            }
            $output .= '<li><a href="' . $item->link . '" target="_blank">' . $item->title . '</a></li>';
            $counter++;
        }
        
        $output .= '</ul>';
        
        return $output;
    } else {
        return 'Unable to retrieve headlines.';
    }
}

// Enqueue style guide CSS
function kxan_headlines_enqueue_styles() {
    wp_enqueue_style('kxan-headlines-style', plugin_dir_url(__FILE__) . 'kxan-headlines-style.css');
}
?>

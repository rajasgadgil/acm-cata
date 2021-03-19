<?php
/*
Plugin Name: WP CSS Dropdown Menu
Plugin URI: http://wp.zackdesign.biz/css-dropdown-menu/
Description: The ultimate wordpress dropdown menu builder. <a href="http://www.zackdesign.biz">Donate</a> | <a href="http://www.cssplay.co.uk/menus/">Other Menu Styles</a>
Version: 4.0.2
Author: Isaac Rowntree
Author URI: http://www.zackdesign.biz
*/

if (!class_exists("CSSDropDownMenu")) {
    class CSSDropDownMenu {
    
        var $orientation;
        var $name;
        var $container_class;
        var $theme_location;
        var $menu_class;
        
        function CSSDropDownMenu() { //constructor
            $this->orientation = 'top';
            $this->name = null;
            $this->container_class = null;
            $this->theme_location = null;
            $this->menu_class = null;
        }
    
        function show()
        {
            $options = array();
            
            switch ($this->orientation)
            {
                case 'right' :
                    $options['container_class'] = $this->container_class;
                    $options['menu'] = $this->name;
                    $options['menu_class'] = $this->orientation.'-menu';
                break;
                case 'left' :
                    $options['menu'] = $this->name;
                    $options['container_class'] = $this->container_class;
                    $options['menu_class'] = $this->orientation.'-menu';
                break;
                default :
                    $options['theme_location'] = $this->theme_location;
                    $options['menu'] = $this->name;
                    $options['container_class'] = $this->container_class;
                    if (!empty($this->menu_class)) : $options['menu_class'] = $this->menu_class; endif;
                    if (get_option('wp_css_menu_jclass')) : $options['menu_class'] = get_option('wp_css_menu_jclass'); endif;
                break;
            }
            //print_r($options);
            
            if (function_exists('wp_nav_menu')) : wp_nav_menu($options); endif;// Now using the Wordpress nav menu builder
        }   
    }
} //End Class CSSDropDownMenu

// Widget Class 
class CSS_DDMenu extends WP_Widget 
{	    
    function CSS_DDMenu()
    {
        parent::WP_Widget(false, $name = 'Dropdown Menu');
    }
    
    function widget($args, $instance) {
       extract($args);
       
       echo $before_widget;
       
       $title = apply_filters('widget_title', $instance['title']);
       
       if ( $title )
           echo $before_title . $title . $after_title; 
      
      if (class_exists("CSSDropDownMenu")) {
          $cssMenu = new CSSDropDownMenu();     
          if (isset($instance['orientation'])) : $cssMenu->orientation = esc_attr($instance['orientation']); endif;
          if (isset($instance['name'])) : $cssMenu->name = esc_attr($instance['name']); endif;
          $cssMenu->show();
      }

       echo $after_widget;
    }
    
    function update($new_instance, $old_instance)
    {
        return $new_instance;
    }
    
    function form($instance)
    {
        if (isset($instance['title'])) : $title = esc_attr($instance['title']); else : $title = ''; endif;        
        if (isset($instance['name'])) : $name = esc_attr($instance['name']); else : $name = ''; endif;     
        if (isset($instance['orientation'])) : $orientation = esc_attr($instance['orientation']); else : $orientation = ''; endif; 
        
        $right = '';
        $left = '';
        $top = '';
        
        if ($orientation == 'right')
            $right = 'selected="selected"';
        else if ($orientation == 'left')
            $left = 'selected="selected"';
        else if ($orientation == 'top')
            $top = 'selected="selected"';
            
        
        ?>
            <p><label for="<?php echo $this->get_field_id('title'); ?>"> <?php _e('Title:'); ?> <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $title ?>" /></label></p>
            
            <p><label for="<?php echo $this->get_field_id('name'); ?>"> <?php _e('Menu Name (id, slug, or name):'); ?> <input class="widefat" id="<?php echo $this->get_field_id('name'); ?>" name="<?php echo $this->get_field_name('name'); ?>" value="<?php echo $name ?>" /></label></p>
            
            <p><label for="<?php echo $this->get_field_id('orientation'); ?>"> <?php _e('Orientation:'); ?> <select class="widefat" id="<?php echo $this->get_field_id('orientation'); ?>" name="<?php echo $this->get_field_name('orientation'); ?>"><option <?php echo $right; ?>>right</option><option  <?php echo $left; ?>>left</option><option  <?php echo $top; ?>>top</option></select></label></p>
            
        <?php
    }	    
}

add_action('widgets_init', create_function('','return register_widget("CSS_DDMenu");'));


function css_dropdownmenu_css() {

    $dynamic = get_option('wp_css_menu_dynamic');
    $start_parent = get_option('wp_css_start_page');
    if (empty($start_parent))
        $start_parent = 0;
    $extra_pages = get_option('wp_css_extra');
    if (empty($extra_pages))
        $extra_pages = 0;
        
    if (get_option('wp_css_menu_home'))
        $extra_pages++;
        
    if ($dynamic)
    {
        $pages = get_option('wp_css_menu_page_num') + $extra_pages;
        $width = get_option('wp_css_menu_width');
        $class = get_option('wp_css_menu_class');
        if (!$class || ($class == ''))                         
            $class = 'menu';
        
        // Li is the full width divided by the number of pages - the a width is li less a seemingly arbitrary number??
        if ($pages < 1)
            $li = 1; 
        else
            $li = ($width - 1) / $pages ;
        $a  = $li - 10;
        
        $lili = 128;
        // Now the second level widths...
        $lili = 128;
        if ($li > $lili)
            $lili = $li - 21;
        $aa = $lili + 22;
            
        
        echo '
        <!-- wp_css_menu_dropdown dynamic menu widths -->
        <style type=\'text/css\' media=\'screen\'>
            .'.$class.' {width: '.$width.'px}
            * html .'.$class.' {width:'.$width.'px; w\idth:'.$width.'px;}
            .'.$class.' li {width:'.$li.'px; }
            .'.$class.' a, .'.$class.' a:visited {width:'.$a.'px; }
            * html .'.$class.' a, * html .'.$class.' a:visited {width:'.$a.'px; w\idth:'.($a-2).'px;}
            .'.$class.' ul ul a, .'.$class.' ul ul a:visited {width:'.$lili.'px;}
            * html .'.$class.' ul ul a, * html .'.$class.' ul ul a:visited {width:'.$aa.'px;w\idth:'.$lili.'px;}       
            .'.$class.' ul ul ul {width:'.$li.'px; left:'.$li.'px}
            .'.$class.' ul ul {width:'.$li.'px;}                     
        </style>
        <!-- /dynamic menu widths -->
        
        ';
    }
}
        
function include_CSSMenu_file()
{	    	    
   if (!is_admin()) :
    
    // Show menu.css depending on what's available
    if (file_exists(TEMPLATEPATH.'/menu.css'))
        wp_enqueue_style('wp-css-dropdown-menu-style', get_bloginfo('template_directory').'/menu.css');
    else
        wp_enqueue_style('wp-css-dropdown-menu-style', plugins_url('/theme_css/menu.css', __FILE__));
	
   endif;
}

add_action('wp_head', 'css_dropdownmenu_css');
        
add_action('init', 'include_CSSMenu_file');

// Deprecated function, reintroduced as people seem to be breaking their sites left right and centre.
function wp_css_dropdownmenu()
{
    echo 'Please visit <a href="http://wordpress.org/extend/plugins/wordpress-css-drop-down-menu/installation/">the Installation guide</a> to set up your menu properly as it has been changed (remember you can easily edit header.php).
    If you can\'t do it yourself visit <a href="http://zackdesign.biz">Zack Design</a> and contact us for help. Or, <a href="http://downloads.wordpress.org/plugin/wordpress-css-drop-down-menu.2.3.7.zip">reinstall version 2.3.7</a>';
}

?>

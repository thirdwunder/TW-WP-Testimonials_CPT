<?php
class twTestimonialsSliderWidget extends WP_Widget{
  private $enable_cat;
  private $enable_tag;

  function twTestimonialsSliderWidget(){
    parent::WP_Widget(false, 'TW Testimonials Slider', array('description'=>''));
    $this->enable_cat = get_option('wpt_tw_testimonial_category')=='on' ? true : false;
    $this->enable_tag = get_option('wpt_tw_testimonial_tag')=='on' ? true : false;
  }

  function update($new_instance, $old_instance){
    $instance = $old_instance;

    $instance['number']    = $new_instance['number'];
    $instance['order']     = $new_instance['order'];
    if($this->enable_cat){
      $instance['category'] = $new_instance['category'];
    }
    if($this->enable_tag){
      $instance['tag']      = $new_instance['tag'];
    }

    return $instance;
  }

  function widget($args, $instance) {
    extract($args, EXTR_SKIP);
    // outputs the content of the widget
    $args['number']  = empty($instance['number']) ? '' : $instance['number'];
    $args['order']   = empty($instance['order'])  ? '' : $instance['order'];
    if($this->enable_cat){
      $args['category'] = empty($instance['category']) ? '' : $instance['category'];
    }

    if($this->enable_tag){
      $args['tag']     = empty($instance['tag'])    ? '' : $instance['tag'];
    }

    $args['enable_cat'] = $this->enable_cat;
    $args['enable_tag'] = $this->enable_tag;
    tw_testimonials_sliders_widget($args);
  }

  function form($instance){
    $instance = wp_parse_args( (array) $instance, array( 'number' => '', 'order'=>'', 'tag' => '' ) );

		if($instance['number']){
  		$number = esc_attr($instance['number']);
		}else{$number = '';}

		if($instance['order']){
  		$order = esc_attr($instance['order']);
		}else{$order = '';}

    if($this->enable_cat){
      if($instance['category']){
    		$category = esc_attr($instance['category']);
  		}else{$category = '';}

      $slide_cats = get_terms('tw_testimonial_category',
                               array(
                               	'orderby'    => 'count',
                               	'hide_empty' => 0,
                               )
                              );
    }

    if($this->enable_tag){
      if($instance['tag']){
    		$tag = esc_attr($instance['tag']);
  		}else{$tag = '';}
    }
?>
		<p><label for="<?php echo $this->get_field_id('number'); ?>"><?php _e('Number of Slides','tw'); ?> </label>
		    <input class="widefat" id="<?php echo $this->get_field_id('number'); ?>" name="<?php echo $this->get_field_name('number'); ?>" type="number" value="<?php echo $number; ?>" />
    </p>

    <p><label for="<?php echo $this->get_field_id('order'); ?>"><?php _e('Order by','tw'); ?> </label>
      <select id="<?php echo $this->get_field_id( 'order' ); ?>" name="<?php echo $this->get_field_name( 'order' ); ?>">
           <option value="date"       <?php selected( $order, 'date' ); ?>><?php echo __('Date','tw'); ?></option>
           <option value="name"       <?php selected( $order, 'name' ); ?>><?php echo __('Name','tw'); ?></option>
           <option value="menu_order" <?php selected( $order, 'menu_order' ); ?>><?php echo __('Assigned Order','tw'); ?></option>
      </select>
    </p>

    <?php if($this->enable_cat): ?>
    <p><label for="<?php echo $this->get_field_id('category'); ?>"><?php _e('Category','tw'); ?> </label>
      <select id="<?php echo $this->get_field_id( 'category' ); ?>" name="<?php echo $this->get_field_name( 'category' ); ?>">
        <option value=""  <?php selected( $category, '' ); ?>><?php echo __('','tw'); ?></option>

        <?php foreach($slide_cats as $scat): ?>
          <option value="<?php echo $scat->slug; ?>"  <?php selected( $category, $scat->slug ); ?>><?php echo $scat->name; ?></option>
        <?php endforeach; ?>

      </select>
    </p>
    <?php endif;?>

    <?php if($this->enable_tag): ?>
		<p><label for="<?php echo $this->get_field_id('tag'); ?>"><?php _e('Tags to feature','tw'); ?> </label>
		    <input class="widefat" id="<?php echo $this->get_field_id('tag'); ?>" name="<?php echo $this->get_field_name('tag'); ?>" type="text" value="<?php echo $tag; ?>" />
    </p>
    <p class="description"><?php _e('Enter a comma separated list of tags to include','tw');?></p>
    <?php endif; ?>
<?php
  }

}
add_action( 'widgets_init', create_function('', 'return register_widget("twTestimonialsSliderWidget");') );


function tw_testimonials_sliders_widget($args){
  echo $args['before_widget'];
  do_action( 'tw_testimonials_sliders_widget_hook', $args);
  echo $args['after_widget'];
}
add_action( 'tw_testimonials_sliders_widget_hook', 'tw_testimonials_sliders_widget_action', 10, 1 );
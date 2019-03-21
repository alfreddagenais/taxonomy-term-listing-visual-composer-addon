<?php

if ( !class_exists( 'Taxonomy_Term_Addon' ) ) {
  class Taxonomy_Term_Addon {

    // Constructor for the taxonomy term listing. Loads options and hooks in the init method.
    public function __construct() { 
      add_action( 'vc_before_init', array( $this, 'termlisting' ) );
      add_action( 'plugins_loaded', array( $this,'taxonomy_listing_addon_load_textdomain' ) );
    }

    // Constructor for the taxonomy term listing. Loads options and hooks in the init method.
    public function checkBoolean( $string ) { 
      if( is_string($string) && $string == '1' ||
          is_string($string) && strtolower($string) == 'true' ||
          is_bool($string) && $string === TRUE ){
        return TRUE;
      }

      return FALSE;

    }

    // Include or mapping
    function termlisting() {
      vc_map( array(
        'name' => __('Taxonomy Term Listing','taxonomy-term-listing-visual-composer-addon'),
        'base' => 'taxonomy_term',
        'icon' => TAXONOMY_LISTING_ADDON_PLUGIN_URL . '/images/icon-taxonomy-listing.png',
        'class' => '',
        'category' => 'Content',
        'params' => array(
          array(
            'type' => 'Taxonomy_Names',
            'holder' => 'div',
            'class' => '',
            'heading' => __( '', 'taxonomy-term-listing-visual-composer-addon' ),
            'param_name' => 'taxonomy_names',
            'value' => '',
            'description' => __('Select desired taxonomy name','taxonomy-term-listing-visual-composer-addon'),
          ),
          array( 
            'type' => 'dropdown',
            'class' => '',
            'heading' => __( '', 'taxonomy-term-listing-visual-composer-addon' ),
            'param_name' => 'order',
            'value' => array(
              'Ascending' => 'ASC',
              'Descending' => 'DESC'
            ),
            'description' => '',
          ),
          array(
            'type' => 'include_child_category',
            'class' => '',
            'heading' => __( '', 'taxonomy-term-listing-visual-composer-addon' ),
            'param_name' => 'include_subcategory',
            'value' => '',
            'description' => '',
            'admin_label' => 'false',
          ),
          array(
            'type' => 'count_display',
            'class' => '',
            'heading' => __( '', 'taxonomy-term-listing-visual-composer-addon' ),
            'param_name' => 'count',
            'value' => '',
            'description' => '',
            'admin_label' => 'false',
          ),
          array(
            'type' => 'Hide_empty',
            'class' => '',
            'heading' => __( '', 'taxonomy-term-listing-visual-composer-addon' ),
            'param_name' => 'hide_empty',
            'value' => '',
            'description' => '',
            'admin_label' => 'false',
          ),
          array(
            'type' => 'specific_subcategory',
            'class' => '',
            'heading' => __('Enter Parent term Id','taxonomy-term-listing-visual-composer-addon'),
            'param_name' => 'specific_subcategory',
            'value' => '',
            'description' => __('include any specific subcategory','taxonomy-term-listing-visual-composer-addon'),
            'admin_label' => 'false',
          ),
          array(
            'type' => 'textfield',
            'class' => '',
            'heading' => __('Extra Class Name','taxonomy-term-listing-visual-composer-addon'),
            'param_name' => 'extra_class_name',
            'value' => '',
            'description' => __('For styling any particular element','taxonomy-term-listing-visual-composer-addon'),
            'admin_label' => 'false',
          ),
          array(
            'type' => 'article_taxonomy_display',
            'class' => '',
            'heading' => __( '', 'taxonomy-term-listing-visual-composer-addon' ),
            'param_name' => 'article_taxonomy_display',
            'value' => 'false',
            'description' => '',
            'admin_label' => 'false',
          ),
        )
      ) );
    }

    function taxonomy_name_settings_field( $settings, $value ) {
      $data = '<div class="taxonomy_name_list">' . '<select name="' . esc_attr( $settings['param_name'] ) . '" class="wpb_vc_param_value wpb-input wpb-select">';
      $data .= '<option value="">'.__('Select Taxonomy','taxonomy-term-listing-visual-composer-addon').'</option>';
      $post_types = get_post_types( array( 'public' => true ) );
      foreach ( $post_types as $key => $post_type_name ) {
        $taxonomy_names = get_object_taxonomies( $post_type_name );
        foreach( $taxonomy_names as $taxonomy_name ) {
          $data .= '<option value="' . $taxonomy_name . '"' . ( ( $taxonomy_name == $value ) ? 'selected' : '' ) . '>' . $taxonomy_name . '</option>';   
        }
      }
      $data .= '</select>' . '</div>'; ?>
      <script>
      (function( $ ) {
        jQuery('.taxonomy_name_list select').change(function(){
          var taxonomyValue = {
            action: "get_taxonomy_term_id",
            postdata: jQuery('.taxonomy_name_list select').val()
          }
          jQuery.post("<?php echo admin_url('admin-ajax.php'); ?>", taxonomyValue, function( response ) {
            jQuery('.parent_id_list select').empty().append(response);        
          } ); 
        });
        if ( jQuery('.taxonomy_name_list select').val() != "" ) {
          var taxonomyValue1 = {
            action: "get_taxonomy_term_id",
            postdata_selected: jQuery('.taxonomy_name_list select').val(),
            postdata_termselected: jQuery('.parent_id_list select').val()
          }
          jQuery.post("<?php echo admin_url('admin-ajax.php'); ?>", taxonomyValue1, function( response ) {
            jQuery('.parent_id_list select').empty().append(response);        
          } ); 
        }
      })( jQuery );
      </script>
      <?php return $data;
    }
   
    function include_child_settings_field( $settings, $value ) {
      $include_child_categories = '<div class="include-child"><input name="' . esc_attr( $settings['param_name'] ) . '" class="wpb_vc_param_value checkbox" type="checkbox" value="' . ( ( $value != "" ) ? $value : 1 ) . '" ' . ( $value == 1 ? checked : '' ) . '' . ( ( $value == "" ) ? checked : '' ) . ' >'.__('include Subcategory','taxonomy-term-listing-visual-composer-addon'). '</div>'; ?>
      <script>
      (function( $ ) {
        jQuery( 'input[name="include_subcategory"]' ).on( 'change', function() {
          this.value = this.checked ? 1 : 0 ;
        });
      })( jQuery );
      </script>
      <?php return $include_child_categories;
    }

    function count_display_settings_field( $settings, $value ) {
      $include_count_display = '<div class="include-count"><input name="'. esc_attr( $settings['param_name'] ) .'" class="wpb_vc_param_value checkbox" type="checkbox" value="' . ( ( $value != "" ) ? $value : 1 ) . '" ' . ( $value == 1 ? checked : '' ) . '' . ( ( $value == "" ) ? checked : '' ) . ' >'.__('show count','taxonomy-term-listing-visual-composer-addon') . '</div>'; ?>
      <script>
      (function( $ ) {
        jQuery( 'input[name="count"]' ).on( 'change', function() {
          this.value = this.checked ? 1 : 0 ;
        });
      })( jQuery );
      </script>
      <?php return $include_count_display;
    }

    function article_taxonomy_display_settings_field( $settings, $value ) {
      $include_count_display = '<div class="include-count"><input name="'. esc_attr( $settings['param_name'] ) .'" class="wpb_vc_param_value checkbox" type="checkbox" value="' . ( ( $value != "" ) ? $value : 1 ) . '" ' . ( $value == 1 ? checked : '' ) . '' . ( ( $value == "" ) ? checked : '' ) . ' >'.__('show taxonomies from this article','taxonomy-term-listing-visual-composer-addon') . '</div>'; ?>
      <script>
      (function( $ ) {
        jQuery( 'input[name="article_taxonomy_display"]' ).on( 'change', function() {
          this.value = this.checked ? 1 : 0 ;
        });
      })( jQuery );
      </script>
      <?php return $include_count_display;
    }

    function hide_empty_settings_field( $settings, $value ){
      $hide_empty_cat = '<div class="hide_empty_main"><input name="'. esc_attr( $settings['param_name'] ) .'" class="wpb_vc_param_value checkbox" type="checkbox" value="' . ( ( $value != "" ) ? $value : 1 ) . '" ' . ( $value == 1 ? checked : '' ) . '' . ( ( $value == "" ) ? checked : '' ) . ' >'.__('Hide Empty Category','taxonomy-term-listing-visual-composer-addon') . '</div>'; ?>
      <script>
      (function( $ ) {
        jQuery( 'input[name="hide_empty"]' ).on( 'change', function() {
          this.value = this.checked ? 1 : 0 ;
        });
      })( jQuery );
      </script>
      <?php return $hide_empty_cat;
    }

    function specific_subcategory_settings_field( $settings, $value ) {
      $specific_cat = '<div class="parent_id_list">' . '<select name="'. esc_attr( $settings['param_name'] ) . '" class="wpb_vc_param_value wpb-input wpb-select">';
      $specific_cat .= '<option value="' . $value . '">'.__('Select Taxonomy first','taxonomy-term-listing-visual-composer-addon').'</option>';  
      $specific_cat .= '</select>' . '</div>';
      return $specific_cat;
    }

      // Load plugin textdomain.
    function taxonomy_listing_addon_load_textdomain() {
      load_plugin_textdomain( 'taxonomy-term-listing-visual-composer-addon', false, basename( dirname( __FILE__ ) ) . '/languages' );
    }

    // frontend view 
    function term_listing_func( $atts ) {
      global $post;

      $specific_subcategory = isset( $atts['specific_subcategory'] ) ? $atts['specific_subcategory'] : 0 ;
      $order_attr = ( isset( $atts['order'] ) ? $atts['order'] : 'ASC' );
      $taxonomy_names_attr = ( isset( $atts['taxonomy_names'] ) ? $atts['taxonomy_names'] : NULL ); 
      $class = ( isset( $atts['extra_class_name']) ? "class = ".$atts['extra_class_name'] : "");
      $arguments = array( 'hide_empty' => $atts['hide_empty'], 'order' => $order_attr, 'parent'=> 0 );

      $article_taxonomy_display = $this->checkBoolean( isset( $atts['article_taxonomy_display'] ) ? $atts['article_taxonomy_display'] : FALSE );

      $post_id = NULL;
      if( !is_null($post) && isset($post->ID) ){
        $post_id = $post->ID;
      }

      if( !is_numeric($post_id) ){
        $article_taxonomy_display = FALSE;
      }

      if ( isset( $atts['specific_subcategory'] ) || $atts['include_subcategory'] == 1 ) {
        if ( isset( $atts['specific_subcategory'] ) ) {
          $arguments = array( 'hide_empty' => $atts['hide_empty'], 'order' => $order_attr, 'parent' => $specific_subcategory ); 
        }
        
        if( $article_taxonomy_display === TRUE ){
          $term = wp_get_post_terms( $post_id, $taxonomy_names_attr, $arguments );
        }else{
          $term = get_terms( $taxonomy_names_attr,$arguments );
        }
        
        $response = '';
        $response = '<div class="vc_taxonomy_listing">';
        $response .= '<ul ' . $class . '>';
        foreach( $term as $terms ) {

          if( ( is_array($terms) && isset($terms['invalid_taxonomy']) ) || (is_object($terms) && isset($terms->invalid_taxonomy) ) || !isset($terms->term_id) || empty($terms->term_id) ){
            continue;
          }

          $response .= '<li><a href="' . get_term_link( $terms->term_id ) . '">' . $terms->name .( $atts['count'] == 1 ? '(' . $terms->count . ')' : '') . '</a>';
          if ( isset( $atts['specific_subcategory'] ) ? ( $terms->parent != 0 ) : ( $terms->parent == 0 ) ) {
            $response .= '<ul>';
            $arg_inner = array( 'hide_empty' => $atts['hide_empty'], 'order' => $order_attr, 'parent' => $terms->term_id );
            $child_terms = get_terms( $taxonomy_names_attr, $arg_inner ); 
            foreach( $child_terms as $child_term ) {
              $response .= '<li><a href="' . get_term_link( $child_term->term_id ) . '">' . $child_term->name.( $atts['count'] == 1 ? '(' . $child_term->count . ')' : '') . '</a>';
              if ( $child_term->parent != 0 ) {
                $response .= '<ul>';
                $arg_inner_child = array( 'hide_empty' => $atts['hide_empty'], 'order' => $order_attr, 'parent' => $child_term->term_id );
                $inner_child_terms = get_terms( $taxonomy_names_attr, $arg_inner_child ); 
                foreach( $inner_child_terms as $inner_child_term ) {
                  $response .= '<li><a href="' . get_term_link( $inner_child_term->term_id ) . '">' . $inner_child_term->name.( $atts['count'] == 1 ? '(' . $inner_child_term->count . ')' : '') . '</a></li>';
                }
                $response .= '</ul>';
              }
            }
            $response .= '</ul></li>';
          }
        }
        $response .= '</ul>';
        $response .= '</div>';
        return $response;
      } else {
        if( $article_taxonomy_display === TRUE ){
          $term = wp_get_post_terms( $post_id, $taxonomy_names_attr, $arguments );
        }else{
          $term = get_terms( $taxonomy_names_attr,$arguments );
        }

        $response = '';
        $response = '<ul>';
        $response = '<div class="vc_taxonomy_listing">';
        foreach ($term as $terms ){
          $response .= '<li><a href="' . get_term_link( $terms->term_id ) . '">' . $terms->name . ( $atts['count'] == 1 ? '(' . $terms->count . ')' : '') . '</a></li>';
        }
        $response .= '</ul>';
        $response .= '</div>';
        return $response;
      }
    }
  }
}
// Instantiate our class
$taxonomy_listing_obj = new Taxonomy_Term_Addon();  
vc_add_shortcode_param( 'Taxonomy_Names',  array($taxonomy_listing_obj, 'taxonomy_name_settings_field' ) );
vc_add_shortcode_param( 'include_child_category', array( $taxonomy_listing_obj, 'include_child_settings_field' ) );
vc_add_shortcode_param( 'count_display', array( $taxonomy_listing_obj, 'count_display_settings_field' ) );
vc_add_shortcode_param( 'Hide_empty', array( $taxonomy_listing_obj, 'hide_empty_settings_field' ) );
vc_add_shortcode_param( 'article_taxonomy_display', array( $taxonomy_listing_obj, 'article_taxonomy_display_settings_field' ) );
vc_add_shortcode_param( 'count_display', array( $taxonomy_listing_obj, 'count_display_settings_field' ) );
add_shortcode( 'taxonomy_term', array( $taxonomy_listing_obj, 'term_listing_func' ) );

// Ajax call for selection of parent term id. 
add_action( 'wp_ajax_get_taxonomy_term_id', 'get_taxonomy_term_id' );
add_action( 'wp_ajax_nopriv_get_taxonomy_term_id', 'get_taxonomy_term_id' );
function get_taxonomy_term_id() {
  global $wpdb;
  if ( isset( $_POST['postdata'] ) ) {
    $tax_name = sanitize_text_field( $_POST['postdata'] );
  }
  elseif ( isset( $_POST['postdata_selected'] ) ) {
    $tax_name = sanitize_text_field( $_POST['postdata_selected'] );
    $term_val = sanitize_text_field( $_POST['postdata_termselected'] );
  }
  $str="";
  if( ! empty( $tax_name ) ) {
    $arg = array( 'taxonomy' => $tax_name );
    $terms = get_categories( $arg );
    if ( isset( $_POST['postdata'] ) || isset( $_POST['postdata_termselected'] ) ) {
      $str .= '<option value="">Select Term</option>';
    }
    foreach( $terms as $term ) {
      if ( $term->parent == 0 ) {
        $str .= '<option value="' . $term->term_id . '" ' . ( $term->term_id == $term_val ? selected : '' ) . '>' . $term->name . '</option>';
      }
    }
  }
  echo $str;
  exit();
}
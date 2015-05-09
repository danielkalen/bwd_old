<?php
/*
Intense Template Name: Boxed
*/

global $intense_testimony;
$random_ID = rand();
?>

<div class="testimonial_boxed_container">
  <input type="radio" name="nav" />
  <div>
    <blockquote id="testimonial_<?php echo $random_ID ?>">
      <span class="leftq quotes">&ldquo;</span> <?php echo $intense_testimony[ 'content' ]; ?> <span class="rightq quotes">&bdquo; </span>
    </blockquote>
    
    <?php 
      if ( isset( $intense_testimony['background'] ) || isset( $intense_testimony['font_color'] ) ) {
        echo "<style>";

       if ( isset( $intense_testimony['background'] ) ) {
          echo "#testimonial_" . $random_ID . " { background-color:" . $intense_testimony['background'] . " !important; } #testimonial_" . $random_ID . ":after { border-top-color: " . $intense_testimony['background'] . " !important; border-left-color: " . $intense_testimony['background'] . " !important;}";
        }

        if ( isset( $intense_testimony['font_color'] ) ) {
          echo "#testimonial_" . $random_ID . " { color:" . $intense_testimony['font_color']  . " !important; } #testimonial_" . $random_ID . " .quotes { color:" . intense_get_rgb_color( $intense_testimony['font_color'] , 50) . " !important; }";
        }

        echo "</style>";
      }

      if ( !empty( $intense_testimony['image'] ) ) {        
        echo intense_run_shortcode( 'intense_image', array( 
          'image' => $intense_testimony['image'],
          'size' => 'square150',
          'alt' => ( !empty( $intense_testimony['company'] ) ? esc_attr( $intense_testimony['company'] ) : '' )
        ) );
      }
    ?>
    <h2><?php echo $intense_testimony['author']; ?></h2>
    <?php
    if ( !empty( $intense_testimony['company'] ) ) {
      if ( !empty( $intense_testimony['link'] ) ) {
        echo "<h6><a href='" . $intense_testimony['link'] . "' target='" . $intense_testimony['link_target'] . "'>" . $intense_testimony['company'] . "</a></h6>";
      } else {
        echo "<h6>" . $intense_testimony['company'] . "</h6>";
      }
    }
    ?>
    
  </div>
</div>
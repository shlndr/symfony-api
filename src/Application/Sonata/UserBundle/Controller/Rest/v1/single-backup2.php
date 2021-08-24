<?php /*

@package prisma

*/

get_header(); ?>
<?php global $prisma_admin_Panel;
$prisma_single_post_type = $prisma_admin_Panel['single_Post_type_selection_button'];?>

<div class="container">
         <div class="row">
		<div class="col-md-8">
	<div id="primary" class="content-area">
           
		<main id="main" class="site-main" role="main">
                   
                    <div class="container" style="width: 100%;">
				
						<?php
							if( have_posts() ):

								while( have_posts() ): the_post();
									prisma_save_post_views( get_the_ID() );

                                                                
                                                                            get_template_part( 'template-parts/single', get_post_format() );
                                                                                      echo prisma_post_navigation();?>
                          
                                          
                    

                                    <?php if ( comments_open() ):
                                                                                            comments_template();
                                                                                    endif;

                                                                            endwhile;

                                                                    endif;

                                                            ?>	

                      <!--Custom-Owl-->

<style>
    #owl-cust .item{
        padding: 30px 0px;
        margin: 10px;
        -webkit-border-radius: 3px;
        -moz-border-radius: 3px;
        border-radius: 3px;
        text-align: left;
    }
    .cor-img{
      display: block;
      height: 180px;
      line-height: 0;
      margin: 0 auto;
      overflow: hidden;
      text-align: center;
      width: 100%;
      background-color: #f1f1f1;
      object-fit: cover;
      background-position: center center;
    }

    .carouslide {
        width: 100%!important;
    }

    #owl-cust .item { padding: 5px; }

    
    .owl-item a:hover .item {
    	background: #fcfcf4;
    	transition: all 0.4s ease-in-out;
    	-webkit-transition: all 0.4s ease-in-out;
    	-moz-transition: all 0.4s ease-in-out;
    	-o-transition: all 0.4s ease-in-out;
    }

    .owl-item a:hover h5 { 
    	color: #f56b08; 
    	transition: all 0.4s ease-in-out;
    	-webkit-transition: all 0.4s ease-in-out;
    	-moz-transition: all 0.4s ease-in-out;
    	-o-transition: all 0.4s ease-in-out
    }


}
</style>

    <?php
      global $post;
      $postcat = get_the_category( $post->ID );

      if(isset($postcat) && !empty($postcat)){
      $catId = $postcat[0]->term_id;
      $showposts = 10;  
      $args = array('cat' => $catId, 'orderby' => 'post_date', 'order' => 'DESC', 'posts_per_page' => $showposts,'post_status' => 'publish','post__not_in' => array($post->ID));

      $posts = query_posts($args);
        }else{
      $posts = carousel_slider_posts( $id );
        }

    ?>
      <div id="cust" style="display:none">
        <div class="carouslide">
          <div class="row">
            <div class="span12">

              <div id="owl-cust" class="owl-carousel">
                <?php 
                foreach ( $posts as $post ){
                  echo '<a href="'.get_permalink( $post->ID ).'">';
                echo '<div class="item">';
                if (has_post_thumbnail( $post->ID ) ):
                  $image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'single-post-thumbnail' );
                echo '<img src="'.$image[0].'" class="cor-img" alt="'.$post->post_title.'"><h5>'.$post->post_title.'</h5>';
                endif;
                echo '</div></a>';
                }

               ?>
              </div>

            </div>
          </div>
        </div>
      </div>

<!--Custom-Owl-->       

				
                                    </div><!-- .container -->

        
		</main>
            </div><!-- #primary -->
              </div><!-- .col-xs-8 -->
            <?php
            global $prisma_admin_panel;
            if($prisma_admin_panel['layout_siderbar_switch']==true): ?> 
          <div class=" col-lg-4 col-md-4 col-sm-12 col-xs-12 sidebar_div" style=" <?php echo esc_attr($prisma_sidebar_position); ?>">
         <?php get_sidebar();  ?>
        </div><!--sidebar-->
       <?php endif; ?>
    </div><!-- .row -->
 
    </div>

<script>
$(document).ready(function() {
console.log('');
$('.saboxplugin-wrap').after($('#cust'));
  });
</script>

<?php get_footer(); 

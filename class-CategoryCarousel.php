<?php
if (!class_exists('CategoryCarousel')) {
	class CategoryCarousel {
        private $postsNumber;
        
		function __construct() {
			add_action( 'wp_enqueue_scripts', array($this, 'CategoryCarousel_scripts') );
			add_action( 'wp_enqueue_scripts', array($this, 'CategoryCarousel_styles') );
			add_shortcode( 'category-carousel', array($this, 'category_carousel_func') );
		}
		
		function CategoryCarousel_scripts() {
			wp_register_script( 'category_carousel', plugin_dir_url( __FILE__ ) . '/includes/js/category-carousel.js', array('jquery'), false, false );
			wp_enqueue_script( 'category_carousel' );
		}
		
		function CategoryCarousel_styles() {
			wp_register_style( 'category_carousel_css',  plugin_dir_url( __FILE__ ) . '/includes/css/category-carousel.css' );
			wp_enqueue_style( 'category_carousel_css' );
		}
		
		
		
		function category_carousel_func( $atts ) {
			$a = shortcode_atts( array(
				'inverted' => false,
				'category_name' => ''
			), $atts );
			
			$output = '';
			$firstSlide = true;
			$queryArgs = 'category_name=' . $a['category_name'];
			$carousel_query = new WP_Query( $queryArgs );
            $this->postsNumber = count($carousel_query->posts);
            
			ob_start();
			?>
						<div class="wrapper">
							<div class="category-carousel category-carousel-<?php echo $a['category_name'] ?>">
								<div class="inner">
						
			<?php if ( $carousel_query->have_posts() ) : ?>
				
				<?php while ( $carousel_query->have_posts() ) : $carousel_query->the_post(); ?>
				
			
						<div class="slide <?php echo $firstSlide ? 'active' : ''; ?> ">
							<?php $firstSlide = false; ?>
							<div class="row">
								<div class="large-4 columns text-container <?php echo $a['inverted'] ? 'large-push-8 inverted' : ''; ?> ">
									<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
									<?php /*the_content();*/ the_excerpt(); ?>
									<br><a class="read-more-link" href="<?php the_permalink(); ?>"><?php _e('Learn more >', 'category-carousel' ); ?></a>
								</div>
								<div class="large-8 columns image-container <?php echo $a['inverted'] ? 'large-pull-4 inverted' : ''; ?> "> 
								<?php if ( has_post_thumbnail() ) {
										the_post_thumbnail();
										} ?>
			
						</div>
							</div>
						</div>
						
						
						<?php endwhile; ?>
						
					<?php endif; ?>
					<?php
					/*Queries done*/ 
						/* Restore original Post Data 
						 * NB: Because we are using new WP_Query we aren't stomping on the 
						 * original $wp_query and it does not need to be reset with 
						 * wp_reset_query(). We just need to set the post data back up with
						 * wp_reset_postdata().
						 */
						wp_reset_postdata(); ?>	
					</div>
					<?php if ($this->postsNumber > 1) { ?>
					<div class="arrow arrow-left arrow-left-<?php echo $a['category_name'] ?> <?php echo $a['inverted'] ? 'inverted' : '' ?>" data-activecategory="<?php echo $a['category_name'] ?>"></div>
					<div class="arrow arrow-right arrow-right-<?php echo $a['category_name']?> <?php echo $a['inverted'] ? 'inverted' : '' ?>" data-activecategory="<?php echo $a['category_name'] ?>"></div>
				    <?php } ?>
				</div>
			</div>
			<script>
				// Custom options for the carousel
				var args = {
					arrowRight : '.arrow-right', //A jQuery reference to the right arrow
					arrowLeft : '.arrow-left', //A jQuery reference to the left arrow
					speed : 1000, //The speed of the animation (milliseconds)
					slideDuration : 4000, //The amount of time between animations (milliseconds)
					activeCategory: '<?php echo $a['category_name'] ?>' //The category for the current carousel
				};
				
				jQuery(document).ready(function() {
                    /*Sizing*/
					jQuery('.category-carousel-<?php echo $a['category_name'] ?>').css( 'padding-top', jQuery('.category-carousel-<?php echo $a['category_name'] ?> .row').height() );
					
                    <?php if ($this->postsNumber > 1) { ?>
                    jQuery('.category-carousel-<?php echo $a['category_name'] ?>').CategoryCarousel( args );
					
					jQuery('body').on('click', '.arrow-right-<?php echo $a['category_name'] ?>', {direction: 'right'}, document.querySelector('.category-carousel-<?php echo $a['category_name'] ?>').CategoryCarousel.changeSlide);
					jQuery('body').on('click', '.arrow-left-<?php echo $a['category_name'] ?>', {direction: 'left'}, document.querySelector('.category-carousel-<?php echo $a['category_name'] ?>').CategoryCarousel.changeSlide);
				    <?php } ?>
                });
				
				jQuery(window).resize(function() {
					jQuery('.category-carousel-<?php echo $a['category_name'] ?>').css( 'padding-top', jQuery('.category-carousel-<?php echo $a['category_name'] ?> .row').height() );
				});
			</script>
			<?php
			
				$output = ob_get_clean();
				return $output;
			}
	}	
}


?>

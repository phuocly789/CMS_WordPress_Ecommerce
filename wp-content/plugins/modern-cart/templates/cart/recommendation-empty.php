<?php
/**
 * Modern Cart Woo slide-out Recommendations
 *
 * @package modern-cart
 * @since 0.0.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$allow_wp_kses = [
	'span' => [
		'class' => [],
	],
	'p'    => [
		'class' => [],
	],
	'bdi'  => [
		'class' => [],
	],
	'del'  => [
		'aria-hidden' => [],
	],
	'div'  => [
		'class' => [],
	],
	'a'    => [
		'href'  => [],
		'title' => [],
		'class' => [],
	],
];

$img_wp_kses = [
	'img' => [
		'title'   => [],
		'src'     => [],
		'class'   => [],
		'alt'     => [],
		'width'   => [],
		'height'  => [],
		'loading' => [],
	],
];

?>

<div class="<?php echo esc_attr( implode( ' ', array_filter( $classes ) ) ); ?>">

	<div class="moderncart-slide-out-empty-cart-recommendations-inner">
		<h4 class="moderncart-slide-out-empty-cart-recommendations-title"><?php echo esc_html( $title ); ?></h4>

		<div class="splide moderncart-empty-cart-recommendation-slider moderncart-slider">
			<div class="splide__track">
				<div class="splide__list moderncart-empty-cart-recommendation-slider-list">
					<?php foreach ( $recommended_products as $key => $recommended_product ) : ?>
						<?php
						$product_permalink = $recommended_product->is_visible() ? $recommended_product->get_permalink() : '';
						?>
						<div class="splide__slide moderncart-empty-cart-recommendation-slider-item">
							<div class="moderncart-cart-item-recommended-image">
								<?php if ( $product_permalink ) : ?>
									<a href="<?php echo esc_url( $product_permalink ); ?>">
								<?php endif; ?>
									<?php if ( wp_get_attachment_image( $recommended_product->get_image_id(), 'thumbnail' ) ) : ?>
										<?php
										echo wp_kses_post(
											wp_get_attachment_image(
												$recommended_product->get_image_id(),
												'full',
												false,
												array(
													'aria-hidden' => 'true',
												) 
											) 
										);
										?>
									<?php else : ?>
										<?php
										echo wp_kses(
											wc_placeholder_img(
												'thumbnail',
												array(
													'aria-hidden' => 'true',
												) 
											),
											$img_wp_kses 
										);
										?>
									<?php endif; ?>	
								<?php if ( $product_permalink ) : ?>
									</a>
								<?php endif; ?>
							</div>
							<div class="moderncart-cart-item-product-link" title="<?php echo wp_kses( $recommended_product->get_name(), $allow_wp_kses ); ?>">
								<?php if ( $product_permalink ) : ?>
									<a href="<?php echo esc_url( $product_permalink ); ?>">
								<?php endif; ?>

								<?php echo wp_kses( $recommended_product->get_name(), $allow_wp_kses ); ?></a>

								<?php if ( $product_permalink ) : ?>
								</a>
								<?php endif; ?>
							</div>
							<?php 
								$short_description = $recommended_product->get_short_description();
							if ( ! empty( $short_description ) ) :
								?>
								<p class="moderncart-cart-item-product-description"><?php echo esc_html( wp_trim_words( $short_description, 15 ) ); ?></p>
							<?php endif; ?>
							<p class="moderncart-cart-item-product-price" title="<?php echo esc_attr( get_woocommerce_currency_symbol() . $recommended_product->get_price() ); ?>"><?php echo wp_kses( $recommended_product->get_price_html(), $allow_wp_kses ); ?></p>
							<div class="moderncart-cart-recommended-item-actions">
								<?php
								$classes                   = array( 'moderncart-btn-upsell ', 'moderncart-button' );
								$simple_product_classes    = array( 'moderncart_add_to_cart_button' );
								$simple_product_attributes = array();
								$upsell_button_text        = __( 'Select Options', 'modern-cart' );
								$upsell_button_type        = 'text';

								if ( $recommended_product->is_type( 'simple' ) ) {
									$classes = array_merge( $classes, $simple_product_classes );
								}

								$attributes = array(
									'type'  => 'button',
									'class' => esc_html( implode( ' ', array_filter( $classes ) ) ),
									'href'  => esc_url( $product_permalink ),
								);

								if ( $recommended_product->is_type( 'simple' ) ) {
									$simple_product_attributes = array(
										'value'           => $recommended_product->get_id(),
										'data-moderncart-cart-open' => 'false',
										'data-moderncart-type' => 'recommendation',
										'data-quantity'   => '1',
										'data-product_id' => $recommended_product->get_id(),
										'href'            => '#',
									);
									$attributes                = array_merge( $attributes, $simple_product_attributes );
									$upsell_button_text        = $button_text;
									$upsell_button_type        = $button_type;
								}

								if ( $recommended_product->is_type( array( 'subscription', 'variable-subscription', 'subscription_variation' ) ) ) {
									$upsell_button_text = __( 'Sign Up Now', 'modern-cart' );
								}
								?>
									<div class="moderncart-add-to-cart">
										<a <?php echo wp_kses( moderncart_implode_html_attributes( $attributes ), $allow_wp_kses ); ?>>
											<?php if ( 'text' === $upsell_button_type || 'text_icon' === $upsell_button_type ) : ?>
												<span><?php echo esc_html( $upsell_button_text ); ?></span>
											<?php endif; ?>
											<?php if ( 'icon' === $upsell_button_type || 'text_icon' === $upsell_button_type ) : ?>
												<span><?php esc_html_e( 'Add to Cart', 'modern-cart' ); ?></span>
											<?php endif; ?>
										</a>
									</div>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
			</div>
		</div>
	</div>

</div>

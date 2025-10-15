<?php
/**
 * Modern Cart Woo Cart Item
 *
 * @package modern-cart
 * @version 0.0.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$args          = array(
	'cart_item_key' => $cart_item_key,
	'cart_item'     => $cart_item,
	'product'       => $product,
);
$allow_wp_kses = [
	'span' => [
		'class'                  => [],
		'data-moderncart-toggle' => [],
		'data-moderncart-target' => [],
		'role'                   => [],
		'tabindex'               => [],
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
		'title'       => [],
		'src'         => [],
		'data-src'    => [],
		'data-srcset' => [],
		'data-sizes'  => [],
		'decoding'    => [],
		'class'       => [],
		'alt'         => [],
		'width'       => [],
		'height'      => [],
		'loading'     => [],
	],
];

$allow_quantity_kses = [
	'span'   => [
		'class'       => [],
		'aria-hidden' => [],
		'id'          => [],
	],
	'svg'    => [
		'aria-hidden' => [],
		'focusable'   => [],
		'role'        => [],
		'class'       => [],
		'viewBox'     => [],
	],
	'path'   => [
		'fill' => [],
		'd'    => [],
	],
	'input'  => [
		'class'         => [],
		'type'          => [],
		'aria-label'    => [],
		'step'          => [],
		'min'           => [],
		'max'           => [],
		'value'         => [],
		'placeholder'   => [],
		'inputmode'     => [],
		'data-key'      => [],
		'id'            => [],
		'data-action'   => [],
		'pattern'       => [],
		'tabindex'      => [],
		'aria-valuemin' => [],
		'aria-valuemax' => [],
		'aria-valuenow' => [],
		'aria-live'     => [],
		'aria-atomic'   => [],
	],
	'div'    => [
		'class'           => [],
		'role'            => [],
		'aria-labelledby' => [],
	],
	'button' => [
		'class'       => [],
		'data-key'    => [],
		'data-action' => [],
		'aria-label'  => [],
		'tabindex'    => [],
		
	],
];
?>

<div class="<?php echo esc_attr( implode( ' ', array_filter( $classes ) ) ); ?> moderncart-cart-item-<?php echo esc_attr( $cart_item_key ); ?>" data-key="<?php echo esc_attr( $cart_item_key ); ?>" role="row">

	<div class="moderncart-cart-item-container" role="cell">

			<div class="moderncart-cart-item-image">
				<?php if ( $product_permalink ) : ?>
					<a href="<?php echo esc_url( $product_permalink ); ?>" aria-label="
										<?php
										/* translators: %s: product name */
										echo esc_attr( sprintf( __( 'View %s product page', 'modern-cart' ), wp_strip_all_tags( $product_name ) ) );
										?>
						">
				<?php endif; ?>

				<?php if ( $thumbnail ) : ?>
					<?php echo wp_kses( $thumbnail, $img_wp_kses ); ?>
				<?php endif; ?>
					<?php if ( $product->is_on_sale() ) : ?>
						<span class="moderncart-cart-item-onsale">Sale!</span>
						<?php endif; ?>
				<?php if ( $product_permalink ) : ?>
					</a>
				<?php endif; ?>
			</div>

		<div class="moderncart-cart-item-product">
			<div class="moderncart-cart-item__details">
				<div class="moderncart-cart-item-product-name" role="heading" aria-level="3"><?php echo wp_kses( $product_name, $allow_wp_kses ); ?></div>
			</div>

			<?php if ( $product_subtotal ) : ?>
				<?php moderncart_get_template_part( 'cart/price', '', $args ); ?>
			<?php endif; ?>

			<?php if ( $delete ) : ?>
				<button
					aria-label="<?php esc_attr_e( 'Remove Item From Cart', 'modern-cart' ); ?>" 
					class="moderncart-cart-item-actions-remove" 
					data-key="<?php echo esc_attr( $cart_item_key ); ?>">
					<?php esc_html_e( 'Remove', 'modern-cart' ); ?>
				</button>
			<?php endif; ?>
		</div>
	</div>

	<div class="moderncart-cart-item-actions" role="cell">
		<?php if ( $quantity ) : ?>
			<?php echo wp_kses( $quantity, $allow_quantity_kses ); ?>
		<?php endif; ?>
	</div>
</div>

<?php

class DT_Product_Data_Store_CPT extends WC_Product_Data_Store_CPT {

	/**
	 * Method to read a product from the database.
	 * @param WC_Product
	 */

	public function read( &$product ) {

		$woo_purchase_cpt = apply_filters( 'dtlms_woo_purchase_cpt', array () );

		$product->set_defaults();

		if ( ! $product->get_id() || ! ( $post_object = get_post( $product->get_id() ) ) || ! ( ('product' === $post_object->post_type) || in_array($post_object->post_type, $woo_purchase_cpt) ) ) {
			throw new Exception( esc_html__( 'Invalid product.', 'dtlms-lite' ) );
		}

		$id = $product->get_id();

		$product->set_props( array(
			'name'              => $post_object->post_title,
			'slug'              => $post_object->post_name,
			'date_created'      => 0 < $post_object->post_date_gmt ? wc_string_to_timestamp( $post_object->post_date_gmt ) : null,
			'date_modified'     => 0 < $post_object->post_modified_gmt ? wc_string_to_timestamp( $post_object->post_modified_gmt ) : null,
			'status'            => $post_object->post_status,
			'description'       => $post_object->post_content,
			'short_description' => $post_object->post_excerpt,
			'parent_id'         => $post_object->post_parent,
			'menu_order'        => $post_object->menu_order,
			'reviews_allowed'   => 'open' === $post_object->comment_status,
		) );

		$this->read_attributes( $product );
		$this->read_downloads( $product );
		$this->read_visibility( $product );
		$this->read_product_data( $product );
		$this->read_extra_data( $product );
		$product->set_object_read( true );
	}

	/**
	 * Get the product type based on product ID.
	 *
	 * @since 3.0.0
	 * @param int $product_id
	 * @return bool|string
	 */
	public function get_product_type( $product_id ) {

		$woo_purchase_cpt = apply_filters( 'dtlms_woo_purchase_cpt', array () );

		$post_type = get_post_type( $product_id );
		if ( 'product_variation' === $post_type ) {
			return 'variation';
		} elseif ( ( $post_type === 'product' ) || in_array($post_type, $woo_purchase_cpt) ) {
			$terms = get_the_terms( $product_id, 'product_type' );
			return ! empty( $terms ) ? sanitize_title( current( $terms )->name ) : 'simple';
		} else {
			return false;
		}

	}
}
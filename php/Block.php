<?php
/**
 * Block class.
 *
 * @package SiteCounts
 */

namespace XWP\SiteCounts;

use WP_Block;
use WP_Query;

/**
 * The Site Counts dynamic block.
 *
 * Registers and renders the dynamic block.
 */
class Block {

	/**
	 * The Plugin instance.
	 *
	 * @var Plugin
	 */
	protected $plugin;

	/**
	 * Instantiates the class.
	 *
	 * @param Plugin $plugin The plugin object.
	 */
	public function __construct( $plugin ) {
		$this->plugin = $plugin;
	}

	/**
	 * Adds the action to register the block.
	 *
	 * @return void
	 */
	public function init() {
		add_action( 'init', [ $this, 'register_block' ] );
	}

	/**
	 * Registers the block.
	 */
	public function register_block() {
		register_block_type_from_metadata(
			$this->plugin->dir(),
			[
				'render_callback' => [ $this, 'render_callback' ],
			]
		);
	}

	/**
	 * Renders the block.
	 *
	 * @param array    $attributes The attributes for the block.
	 * @param string   $content    The block content, if any.
	 * @param WP_Block $block      The instance of this block.
	 * @return string The markup of the block.
	 */
	public function render_callback( $attributes, $content, $block ) {
		$types_array = array( 'page', 'attachment' , 'elementor_library', 'e-landing-page' );
		$post_types = get_post_types(  [ 'public' => true ], 'names', 'and' );
		$class_name = $attributes['className'];
		$id_name = $attributes['idName'];
	    	printf( '<div class="%1$s" id="%2$s">', esc_html($class_name), esc_html($id_name));
	    	printf( '<h2>%1$s</h2>', __('Post Counts','SiteCounts'));
		if($content != ''):
	    	printf( '<p>%1$s</p>', esc_html($content));
		endif;
		print('<ul>');
		foreach ( $post_types as $post_type ) :
			if(!in_array($post_type, $types_array)):
				$post_count = wp_count_posts($post_type)->publish;
				printf( '<li>There are %1$s %2$s.</li>', $post_count, $post_type);
			endif;
		endforeach;
		print('</ul>');
		print('</div>');
	}
}

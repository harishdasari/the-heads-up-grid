<?php
/*
Plugin Name: The Heads Up Grid
Plugin URI: http://harishdasari.in/
Description: The Heads Up Grid for WordPress Themes Development
Version: 1.0
Author: Harish Dasari
Author URI: http://harishdasari.in/
*/

/**
 * Copyright (c) Aug 5 2013 Harish Dasari. All rights reserved.
 *
 * Released under the GPL license
 * http://www.opensource.org/licenses/gpl-license.php
 *
 * This is an add-on for WordPress
 * http://wordpress.org/
 *
 * **********************************************************************
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 * **********************************************************************
 */

/**
 * The Heads Up Grid for WordPress Theme Development
 *
 * @version 1.0
 * @link http://bohemianalps.com/tools/grid/
 * @link http://harishdasari.in/
 */
class The_Heads_Up_Grid {

	/**
	 * The Constructor
	 *
	 * @since 1.0
	 */
	function __construct() {

		register_activation_hook( __FILE__, array( $this, 'activation' ) );

		add_action( 'admin_menu', array( $this, 'register_settings_page' ) );
		add_action( 'admin_init', array( $this, 'register_settings' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_heads_up_grid' ) );
		add_action( 'wp_footer', array( $this, 'print_heads_up_gird' ) );

	}

	/**
	 * Activation Hook
	 * Adds default options required for plugin
	 *
	 * @since 1.0
	 *
	 * @return null
	 */
	function activation() {

		$defaults = array(
			'pageUnits'     => 'px',
			'colUnits'      => 'px',
			'pagewidth'     => 960,
			'columns'       => 12,
			'columnwidth'   => 60,
			'gutterwidth'   => 20,
			'pagetopmargin' => 20,
			'rowheight'     => 24,
			'gridonload'    => 'on',
		);

		add_option( 'the_heads_up_grid', $defaults );
		add_option( 'the_heads_up_grid_enable', 1 );

	}

	/**
	 * Register a Options Page for Heads Up Grid
	 *
	 * @since 1.0
	 *
	 * @return null
	 */
	function register_settings_page() {

		add_options_page( __( 'The Heads Up Grid', 'harish' ), __( 'The Heads Up Grid Settings', 'harish' ), 'install_plugins', 'the-heads-up-grid', array( $this, 'the_heads_up_grid_options' ) );

	}

	/**
	 * Register the Setting
	 *
	 * This setting is used to store all options of heads up grid
	 *
	 * @since 1.0
	 *
	 * @return null
	 */
	function register_settings() {

		register_setting( 'the_heads_up_grid', 'the_heads_up_grid', array( $this, 'sanitize_settings' ) );
		register_setting( 'the_heads_up_grid', 'the_heads_up_grid_enable', array( $this, 'sanitize_settings' ) );

	}

	/**
	 * Sanitize the settings value
	 *
	 * @since 1.0
	 *
	 * @param  mixed $value Submitted option value
	 * @return mixed        Sanitized option value
	 */
	function sanitize_settings( $value ) {

		if ( current_filter() == 'sanitize_option_the_heads_up_grid_enable' )
			return absint( $value );

		if ( is_array( $value ) ) {
			if ( array_key_exists( 'pageUnits', $value ) )
				$value['pageUnits'] = $value['pageUnits'] == 'px' ? 'px' : '%';
			if ( array_key_exists( 'colUnits', $value ) )
				$value['colUnits'] = $value['colUnits'] == 'px' ? 'px' : '%';
			foreach ( array( 'pagewidth','columns','columnwidth','gutterwidth','pagetopmargin','rowheight' ) as $opt ) {
				if ( array_key_exists( $opt, $value ) )
					$value[ $opt ] = absint( $value[ $opt ] );
			}
			if ( array_key_exists( 'gridonload', $value ) )
				$value['gridonload'] = $value['gridonload'] == 'on' ? 'on' : 'off';
		}

		return $value;

	}

	/**
	 * The Heads Up Grid Options Page
	 *
	 * @since 1.0
	 *
	 * @return null
	 */
	function the_heads_up_grid_options() {

		?>
		<div class="wrap">
			<?php screen_icon( 'options-general' ); ?>
			<h2><?php _e( 'The Heads Up Grid Options', 'harish' ); ?></h2>
			<?php
				$defaults = array(
					'pageUnits'     => '',
					'colUnits'      => '',
					'pagewidth'     => '',
					'columns'       => '',
					'columnwidth'   => '',
					'gutterwidth'   => '',
					'pagetopmargin' => '',
					'rowheight'     => '',
					'gridonload'    => '',
				);
				extract( wp_parse_args( get_option( 'the_heads_up_grid' ), $defaults ) );
			?>
			<form action="options.php" method="post">
				<?php settings_fields( 'the_heads_up_grid' ); ?>

				<table class="form-table">
					<tr valign="top">
						<th scope="row"><label for=""><?php _e( 'Enable The Heads Up Grid?', 'harish' ) ?></label></th>
						<td>
							<select name="the_heads_up_grid_enable" id="">
								<option value="1"<?php selected( '1', get_option( 'the_heads_up_grid_enable' ) ) ?>>Yes</option>
								<option value="0"<?php selected( '0', get_option( 'the_heads_up_grid_enable' ) ) ?>>No</option>
							</select>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><label for=""><?php _e( 'Page Units', 'harish' ) ?></label></th>
						<td>
							<select name="the_heads_up_grid[pageUnits]" id="">
								<option value="px"<?php selected( 'px', $pageUnits ) ?>>px</option>
								<option value="%"<?php selected( '%', $pageUnits ) ?>>%</option>
							</select>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><label for=""><?php _e( 'Col Units', 'harish' ) ?></label></th>
						<td>
							<select name="the_heads_up_grid[colUnits]" id="">
								<option value="px"<?php selected( 'px', $colUnits ) ?>>px</option>
								<option value="%"<?php selected( '%', $colUnits ) ?>>%</option>
							</select>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><label for=""><?php _e( 'Page Width', 'harish' ) ?></label></th>
						<td><input type="text" name="the_heads_up_grid[pagewidth]" id="" size="4" value="<?php echo esc_attr( $pagewidth ); ?>"/></td>
					</tr>
					<tr valign="top">
						<th scope="row"><label for=""><?php _e( 'Number of Columns', 'harish' ) ?></label></th>
						<td><input type="text" name="the_heads_up_grid[columns]" id="" size="4" value="<?php echo esc_attr( $columns ); ?>"/></td>
					</tr>
					<tr valign="top">
						<th scope="row"><label for=""><?php _e( 'Column Width', 'harish' ) ?></label></th>
						<td><input type="text" name="the_heads_up_grid[columnwidth]" id="" size="4" value="<?php echo esc_attr( $columnwidth ); ?>"/></td>
					</tr>
					<tr valign="top">
						<th scope="row"><label for=""><?php _e( 'Gutter Width', 'harish' ) ?></label></th>
						<td><input type="text" name="the_heads_up_grid[gutterwidth]" id="" size="4" value="<?php echo esc_attr( $gutterwidth ); ?>"/></td>
					</tr>
					<tr valign="top">
						<th scope="row"><label for=""><?php _e( 'Page Top Margin', 'harish' ) ?></label></th>
						<td><input type="text" name="the_heads_up_grid[pagetopmargin]" id="" size="4" value="<?php echo esc_attr( $pagetopmargin ); ?>"/></td>
					</tr>
					<tr valign="top">
						<th scope="row"><label for=""><?php _e( 'Row Height', 'harish' ) ?></label></th>
						<td><input type="text" name="the_heads_up_grid[rowheight]" id="" size="4" value="<?php echo esc_attr( $rowheight ); ?>"/></td>
					</tr>
					<tr valign="top">
						<th scope="row"><label for=""><?php _e( 'Grid Onload', 'harish' ) ?></label></th>
						<td>
							<select name="the_heads_up_grid[gridonload]" id="">
								<option value="off"<?php selected( 'off', $gridonload ) ?>>off</option>
								<option value="on"<?php selected( 'on', $gridonload ) ?>>on</option>
							</select>
						</td>
					</tr>
				</table>

				<?php submit_button(); ?>
			</form>

		</div>
		<?php


	}

	/**
	 * Enqueue Scripts and Styles required for The Heads Up Grid
	 *
	 * @since 1.0
	 *
	 * @return null
	 */
	function enqueue_heads_up_grid() {

		if ( get_option( 'the_heads_up_grid_enable' ) ) {
			wp_enqueue_style( 'the-heads-up-grid', plugins_url( '/css/hugrid.css', __FILE__ ), '', null );
			wp_enqueue_script( 'the-heads-up-grid', plugins_url( '/js/hugrid.js', __FILE__ ), array( 'jquery' ), null, true );
		}

	}

	/**
	 * Print Javascript calll for The Heads Up Grid
	 *
	 * @since 1.0
	 *
	 * @return null
	 */
	function print_heads_up_gird() {

		if ( ! get_option( 'the_heads_up_grid_enable' ) )
			return;

		?>
		<script type="text/javascript">
			jQuery(document).ready(function($) {
				<?php
				$options = get_option( 'the_heads_up_grid' );
				foreach ( $options as $opt_key => $opt_val ) {
					if ( is_numeric( $opt_val ) )
						printf( "\t\t" . '%s = %d;' . "\n", $opt_key, $opt_val );
					else
						printf( "\t\t" . '%s = "%s";' . "\n", $opt_key, esc_attr( $opt_val ) );
				}
				?>
				makehugrid();
				setgridonload();
			});
		</script>
		<?php

	}

}

register_uninstall_hook( __FILE__, 'uninstall_the_heads_up_grid' );
/**
 * Uninstall Hook
 *
 * Removes the options on plugin removal.
 *
 * @since 1.0
 *
 * @return null
 */
function uninstall_the_heads_up_grid() {

	if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) )
		exit();

	delete_option( 'the_heads_up_grid' );

}

add_action( 'plugins_loaded', 'the_heads_up_grid_init' );
/**
 * Instantiate the The Heads Up Grid
 *
 * @since 1.0
 *
 * @return null
 */
function the_heads_up_grid_init() {

	new The_Heads_Up_Grid();

}

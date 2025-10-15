<?php
/**
 * Astra Sites Importer
 *
 * @since  1.0.0
 * @package Astra Sites
 */

use STImporter\Importer\ST_Importer_Helper;
use STImporter\Importer\WXR_Importer\ST_WXR_Importer;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( 'Astra_Sites_Importer' ) ) {

	/**
	 * Astra Sites Importer
	 */
	class Astra_Sites_Importer {

		/**
		 * Instance
		 *
		 * @since  1.0.0
		 * @var self Class object
		 */
		public static $instance = null;

		/**
		 * Set Instance
		 *
		 * @since  1.0.0
		 *
		 * @return self Class object.
		 */
		public static function get_instance() {
			if ( ! isset( self::$instance ) ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		/**
		 * Constructor.
		 *
		 * @since  1.0.0
		 */
		public function __construct() {

			require_once ASTRA_SITES_DIR . 'inc/classes/class-astra-sites-importer-log.php';
			require_once ASTRA_SITES_DIR . 'inc/importers/class-astra-sites-helper.php';
			require_once ASTRA_SITES_DIR . 'inc/importers/class-astra-widget-importer.php';
			require_once ASTRA_SITES_DIR . 'inc/importers/class-astra-customizer-import.php';
			require_once ASTRA_SITES_DIR . 'inc/importers/class-astra-site-options-import.php';

			// Hooks in AJAX.
			add_action( 'wp_ajax_astra-sites-import-wpforms', array( $this, 'import_wpforms' ) );
			add_action( 'wp_ajax_astra-sites-import-cartflows', array( $this, 'import_cartflows' ) );
			add_action( 'wp_ajax_astra-sites-import-cart-abandonment-recovery', array( $this, 'import_cart_abandonment_recovery' ) );
			add_action( 'wp_ajax_astra-sites-import-latepoint', array( $this, 'import_latepoint' ) );
			add_action( 'astra_sites_import_complete', array( $this, 'clear_related_cache' ) );

			require_once ASTRA_SITES_DIR . 'inc/importers/batch-processing/class-astra-sites-batch-processing.php';

			if ( version_compare( get_bloginfo( 'version' ), '5.1.0', '>=' ) ) {
				add_filter( 'http_request_timeout', array( $this, 'set_timeout_for_images' ), 10, 2 ); //phpcs:ignore WordPressVIPMinimum.Hooks.RestrictedHooks.http_request_timeout -- We need this to avoid timeout on slow servers while installing theme, plugin etc.
			}

			add_action( 'init', array( $this, 'disable_default_woo_pages_creation' ), 2 );
			add_filter( 'upgrader_package_options', array( $this, 'plugin_install_clear_directory' ) );
		}

		/**
		 * Delete imported posts
		 *
		 * @since 1.3.0
		 * @since 1.4.0 The `$post_id` was added.
		 * Note: This function can be deleted after a few releases since we are performing the delete operation in chunks.
		 *
		 * @param  integer $post_id Post ID.
		 * @return void
		 */
		public function delete_imported_posts( $post_id = 0 ) {

			if ( wp_doing_ajax() ) {
				// Verify Nonce.
				check_ajax_referer( 'astra-sites', '_ajax_nonce' );

				if ( ! current_user_can( 'customize' ) ) {
					wp_send_json_error( __( "Permission denied: You don't have the required capability to delete imported posts. Please contact your site administrator.", 'astra-sites' ) );
				}
			}

			$post_id = isset( $_REQUEST['post_id'] ) ? absint( $_REQUEST['post_id'] ) : $post_id;

			$message = 'Deleted - Post ID ' . $post_id . ' - ' . get_post_type( $post_id ) . ' - ' . get_the_title( $post_id );

			$message = '';
			if ( $post_id ) {

				$post_type = get_post_type( $post_id );
				$message   = 'Deleted - Post ID ' . $post_id . ' - ' . $post_type . ' - ' . get_the_title( $post_id );

				do_action( 'astra_sites_before_delete_imported_posts', $post_id, $post_type );

				Astra_Sites_Importer_Log::add( $message );
				wp_delete_post( $post_id, true );
			}

			if ( defined( 'WP_CLI' ) ) {
				WP_CLI::line( $message );
			} elseif ( wp_doing_ajax() ) {
				wp_send_json_success( $message );
			}
		}

		/**
		 * Delete imported WP forms
		 *
		 * @since 1.3.0
		 * @since 1.4.0 The `$post_id` was added.
		 * Note: This function can be deleted after a few releases since we are performing the delete operation in chunks.
		 *
		 * @param  integer $post_id Post ID.
		 * @return void
		 */
		public function delete_imported_wp_forms( $post_id = 0 ) {

			if ( ! defined( 'WP_CLI' ) && wp_doing_ajax() ) {
				// Verify Nonce.
				check_ajax_referer( 'astra-sites', '_ajax_nonce' );

				if ( ! current_user_can( 'customize' ) ) {
					wp_send_json_error( __( "Permission denied: You don't have the required capability to delete imported forms. Please contact your site administrator.", 'astra-sites' ) );
				}
			}

			$post_id = isset( $_REQUEST['post_id'] ) ? absint( $_REQUEST['post_id'] ) : $post_id;

			$message = '';
			if ( $post_id ) {

				do_action( 'astra_sites_before_delete_imported_wp_forms', $post_id );

				$message = 'Deleted - Form ID ' . $post_id . ' - ' . get_post_type( $post_id ) . ' - ' . get_the_title( $post_id );
				Astra_Sites_Importer_Log::add( $message );
				wp_delete_post( $post_id, true );
			}

			if ( defined( 'WP_CLI' ) ) {
				WP_CLI::line( $message );
			} elseif ( wp_doing_ajax() ) {
				wp_send_json_success( $message );
			}
		}

		/**
		 * Delete imported terms
		 *
		 * @since 1.3.0
		 * @since 1.4.0 The `$post_id` was added.
		 * Note: This function can be deleted after a few releases since we are performing the delete operation in chunks.
		 *
		 * @param  integer $term_id Term ID.
		 * @return void
		 */
		public function delete_imported_terms( $term_id = 0 ) {
			if ( ! defined( 'WP_CLI' ) && wp_doing_ajax() ) {
				// Verify Nonce.
				check_ajax_referer( 'astra-sites', '_ajax_nonce' );

				if ( ! current_user_can( 'customize' ) ) {
					wp_send_json_error( __( "Permission denied: You don't have the required capability to delete imported terms. Please contact your site administrator.", 'astra-sites' ) );
				}
			}

			$term_id = isset( $_REQUEST['term_id'] ) ? absint( $_REQUEST['term_id'] ) : $term_id;

			$message = '';
			if ( $term_id ) {
				$term = get_term( $term_id );
				if ( ! is_wp_error( $term ) && ! empty( $term ) && is_object( $term ) ) {

					do_action( 'astra_sites_before_delete_imported_terms', $term_id, $term );

					$message = 'Deleted - Term ' . $term_id . ' - ' . $term->name . ' ' . $term->taxonomy;
					Astra_Sites_Importer_Log::add( $message );
					wp_delete_term( $term_id, $term->taxonomy );
				}
			}

			if ( defined( 'WP_CLI' ) ) {
				WP_CLI::line( $message );
			} elseif ( wp_doing_ajax() ) {
				wp_send_json_success( $message );
			}
		}

		/**
		 * Delete related transients
		 *
		 * @since 3.1.3
		 */
		public function delete_related_transient() {
			delete_option( 'astra_sites_batch_process_started' );
			Astra_Sites_File_System::get_instance()->delete_demo_content();
			delete_option( 'ast_ai_import_current_url' );
			delete_option( 'astra_sites_ai_import_started' );
		}

		/**
		 * Delete directory when installing plugin.
		 *
		 * Set by enabling `clear_destination` option in the upgrader.
		 *
		 * @since 3.0.10
		 * @param array $options Options for the upgrader.
		 * @return array $options The options.
		 */
		public function plugin_install_clear_directory( $options ) {


			if ( true !== astra_sites_has_import_started() ) {
				return $options;
			}

			$is_ast_request = isset( $_REQUEST['is_ast_request'] ) && 'true' === $_REQUEST['is_ast_request']; //phpcs:ignore 


			if ( ! $is_ast_request ) {
				return $options;
			}

			// Verify Nonce.
			check_ajax_referer( 'astra-sites', 'ajax_nonce' );

			if ( isset( $_REQUEST['clear_destination'] ) && 'true' === $_REQUEST['clear_destination'] ) {
				$options['clear_destination'] = true;
			}

			return $options;
		}

		/**
		 * Restrict WooCommerce Pages Creation process
		 *
		 * Why? WooCommerce creates set of pages on it's activation
		 * These pages are re created via our XML import step.
		 * In order to avoid the duplicacy we restrict these page creation process.
		 *
		 * @since 3.0.0
		 */
		public function disable_default_woo_pages_creation() {
			if ( astra_sites_has_import_started() ) {
				add_filter( 'woocommerce_create_pages', '__return_empty_array' );
			}
		}

		/**
		 * Set the timeout for the HTTP request by request URL.
		 *
		 * E.g. If URL is images (jpg|png|gif|jpeg) are from the domain `https://websitedemos.net` then we have set the timeout by 30 seconds. Default 5 seconds.
		 *
		 * @since 1.3.8
		 *
		 * @param int    $timeout_value Time in seconds until a request times out. Default 5.
		 * @param string $url           The request URL.
		 */
		public function set_timeout_for_images( $timeout_value, $url ) {

			// URL not contain `https://websitedemos.net` then return $timeout_value.
			if ( strpos( $url, 'https://websitedemos.net' ) === false ) {
				return $timeout_value;
			}

			// Check is image URL of type jpg|png|gif|jpeg.
			if ( astra_sites_is_valid_image( $url ) ) {
				$timeout_value = 300;
			}

			return $timeout_value;
		}

		/**
		 * Change flow status
		 *
		 * @since 2.0.0
		 *
		 * @param  array $args Flow query args.
		 * @return array Flow query args.
		 */
		public function change_flow_status( $args ) {
			$args['post_status'] = 'publish';
			return $args;
		}

		/**
		 * Track Flow
		 *
		 * @since 2.0.0
		 *
		 * @param  integer $flow_id Flow ID.
		 * @return void
		 */
		public function track_flows( $flow_id ) {
			Astra_Sites_Importer_Log::add( 'Flow ID ' . $flow_id );
			ST_Importer_Helper::track_post( $flow_id );
		}

		/**
		 * Common function for downloading and validating import data files.
		 *
		 * @since 4.4.39
		 *
		 * @param string $url URL of the JSON data file to download and validate.
		 * @param bool   $decode Whether to decode the JSON data. Default true.
		 * @return string|array|WP_Error Returns the file contents as a string if $decode is false, or an associative array if $decode is true. Returns WP_Error on failure.
		 */
		private function download_and_validate_import_data( $url = '', $decode = true ) {
			// Skip import gracefully if no URL (normal condition).
			if ( empty( $url ) ) {
				// Success response - no data to import is normal.
				if ( defined( 'WP_CLI' ) ) {
					WP_CLI::line( 'No data to import - ' . esc_url( $url ) );
				}

				return array();
			}

			// Validate URL format.
			if ( ! filter_var( $url, FILTER_VALIDATE_URL ) ) {
				return new WP_Error(
					'invalid_url_format',
					sprintf(
						/* translators: %s: URL */
						__( 'Invalid URL format - %s', 'astra-sites' ),
						$url
					)
				);
			}

			// Validate URL security.
			if ( ! astra_sites_is_valid_url( $url ) ) {
				return new WP_Error(
					'invalid_url',
					sprintf(
						/* translators: %s: URL */
						__( 'Invalid data file URL - %s', 'astra-sites' ),
						esc_url_raw( $url )
					)
				);
			}

			// Download JSON file.
			$file_path = ST_WXR_Importer::download_file( $url );

			if ( empty( $file_path['success'] ) ) {
				$error_message = ! empty( $file_path['data'] )
					? $file_path['data']
					: __( 'Could not download data file. Please check your internet connection and try again.', 'astra-sites' );
				
				return new WP_Error(
					'download_failed',
					sprintf(
						/* translators: %s: Error message */
						__( 'File download failed - %s', 'astra-sites' ),
						$error_message
					)
				);
			}

			if ( empty( $file_path['data']['file'] ) ) {
				return new WP_Error(
					'missing_file_path',
					__( 'Downloaded file path is missing in the response.', 'astra-sites' )
				);
			}

			$downloaded_file_path = $file_path['data']['file'];

			// Verify file exists.
			if ( ! file_exists( $downloaded_file_path ) ) {
				return new WP_Error(
					'file_not_found',
					__( 'Downloaded file is missing. Please retry the import.', 'astra-sites' )
				);
			}

			// Verify file is readable.
			if ( ! is_readable( $downloaded_file_path ) ) {
				return new WP_Error(
					'file_not_readable',
					__( 'Downloaded file is not readable. Please check server file permissions.', 'astra-sites' )
				);
			}

			// Validate file extension.
			$ext = strtolower( pathinfo( $downloaded_file_path, PATHINFO_EXTENSION ) );
			if ( 'json' !== $ext ) {
				return new WP_Error(
					'invalid_file_type',
					__( 'The file must be in JSON format.', 'astra-sites' )
				);
			}

			// Read file contents.
			$contents = Astra_Sites::get_instance()->get_filesystem()->get_contents( $downloaded_file_path );

			// If decoding not required, return raw contents.
			if ( ! $decode ) {
				return $contents;
			}
			
			if ( false === $contents ) {
				return new WP_Error(
					'file_read_failed',
					__( 'Could not read the file from the server.', 'astra-sites' )
				);
			}

			if ( empty( $contents ) ) {
				return new WP_Error(
					'empty_file',
					__( 'The file is empty.', 'astra-sites' )
				);
			}

			// Parse JSON.
			$data       = json_decode( $contents, true );
			$json_error = json_last_error();

			if ( JSON_ERROR_NONE !== $json_error ) {
				return new WP_Error(
					'invalid_json',
					sprintf(
						/* translators: %s: JSON error */
						__( 'Invalid JSON format - %s', 'astra-sites' ),
						json_last_error_msg()
					)
				);
			}

			return $data;
		}

		/**
		 * Import WP Forms
		 *
		 * @since 1.2.14
		 * @since 1.4.0 The `$wpforms_url` was added.
		 *
		 * @param  string $wpforms_url WP Forms JSON file URL.
		 * @return void
		 */
		public function import_wpforms( $wpforms_url = '' ) {

			if ( ! defined( 'WP_CLI' ) && wp_doing_ajax() ) {
				// Verify Nonce.
				check_ajax_referer( 'astra-sites', '_ajax_nonce' );

				if ( ! current_user_can( 'customize' ) ) {
					wp_send_json_error( __( "Permission denied: You don't have the required capability to import forms. Please contact your site administrator.", 'astra-sites' ) );
				}
			}

			try {
				$screen = ( isset( $_REQUEST['screen'] ) ) ? sanitize_text_field( $_REQUEST['screen'] ) : '';
				$id = ( isset( $_REQUEST['id'] ) ) ? absint( $_REQUEST['id'] ) : '';

				// Get WPForms URL with enhanced error handling.
				if ( 'elementor' === $screen ) {
					if ( empty( $id ) ) {
						wp_send_json_error(
							sprintf(
								// translators: %s is Elementor plugin name.
								__( 'WPForms import failed: Template ID is missing. Unable to proceed with %s import.', 'astra-sites' ),
								'Elementor'
							)
						);
					}
					$wpforms_url = astra_sites_get_wp_forms_url( $id );
				} else {
					$wpforms_url = astra_get_site_data( 'astra-site-wpforms-path' );
				}

				$ids_mapping = array();

				$forms = $this->download_and_validate_import_data( $wpforms_url );
				if ( is_wp_error( $forms ) ) {
					wp_send_json_error(
						sprintf(
							// translators: %s is the error message.
							__( 'WPForms import failed: %s', 'astra-sites' ),
							$forms->get_error_message()
						)
					);
				}

				if ( empty( $forms ) ) {
					wp_send_json_success( $wpforms_url );
				}

				// Check WPForms plugin availability.
				if ( ! function_exists( 'wpforms_encode' ) ) {
					wp_send_json_error( __( 'WPForms import failed: WPForms plugin is not installed or not active. Please install/activate WPForms to continue.', 'astra-sites' ) );
				}

				// Process forms with error handling.
				foreach ( $forms as $form ) {
					if ( ! is_array( $form ) ) {
						continue; // Skip invalid form data.
					}

					$title = ! empty( $form['settings']['form_title'] ) ? sanitize_text_field( $form['settings']['form_title'] ) : '';
					$desc  = ! empty( $form['settings']['form_desc'] ) ? sanitize_textarea_field( $form['settings']['form_desc'] ) : '';

					if ( empty( $title ) ) {
						continue; // Skip forms without titles.
					}

					$new_id = post_exists( $title );

					if ( ! $new_id ) {
						try {
							$new_id = wp_insert_post(
								array(
									'post_title'   => $title,
									'post_status'  => 'publish',
									'post_type'    => 'wpforms',
									'post_excerpt' => $desc,
								)
							);

							if ( is_wp_error( $new_id ) ) {
								astra_sites_error_log( 'WPForms import error: ' . $new_id->get_error_message() );
								continue; // Skip this form and continue with others.
							}

							if ( defined( 'WP_CLI' ) ) {
								WP_CLI::line( 'Imported Form ' . $title );
							}

							// Set meta for tracking the post..
							update_post_meta( $new_id, '_astra_sites_imported_wp_forms', true );
							Astra_Sites_Importer_Log::add( 'Inserted WP Form ' . $new_id );

						} catch ( Exception $e ) {
							astra_sites_error_log( 'WPForms post creation error: ' . $e->getMessage() );
							continue; // Skip this form and continue with others.
						}
					}

					if ( $new_id && ! empty( $form['id'] ) ) {
						// ID mapping..
						$ids_mapping[ $form['id'] ] = $new_id;

						$form['id'] = $new_id;
						
						try {
							$encoded_form = wpforms_encode( $form );
							wp_update_post(
								array(
									'ID' => $new_id,
									'post_content' => $encoded_form,
								)
							);
						} catch ( Exception $e ) {
							astra_sites_error_log( 'WPForms content update error: ' . $e->getMessage() );
							// Continue even if content update fails.
						}
					}
				}

				// Save ID mapping.
				update_option( 'astra_sites_wpforms_ids_mapping', $ids_mapping, 'no' );

				// Success response.
				if ( defined( 'WP_CLI' ) ) {
					WP_CLI::line( 'WP Forms Imported.' );
				} elseif ( wp_doing_ajax() ) {
					wp_send_json_success( $ids_mapping );
				}           
			} catch ( \Exception $e ) {
				// Catch any unexpected errors.
				astra_sites_error_log( 'WPForms import error: ' . $e->getMessage() );
				wp_send_json_error(
					sprintf(
						// translators: %s is exception error message.
						__( 'WPForms import failed: Unexpected error - %s', 'astra-sites' ),
						$e->getMessage()
					)
				);
			} catch ( \Error $e ) {
				// translators: %s: Fatal error message.
				wp_send_json_error( sprintf( __( 'WPForms import failed: Fatal Error: %s', 'astra-sites' ), $e->getMessage() ) );
			}
		}

		/**
		 * Import CartFlows
		 *
		 * @since 2.0.0
		 *
		 * @param  string $url Cartflows JSON file URL.
		 * @return void
		 */
		public function import_cartflows( $url = '' ) {

			if ( ! defined( 'WP_CLI' ) && wp_doing_ajax() ) {
				// Verify Nonce.
				check_ajax_referer( 'astra-sites', '_ajax_nonce' );

				if ( ! current_user_can( 'edit_posts' ) ) {
					wp_send_json_error( __( "Permission Denied: You don't have permission to import CartFlows flows. Please contact your site administrator.", 'astra-sites' ) );
				}
			}

			// Disable CartFlows import logging.
			add_filter( 'cartflows_enable_log', '__return_false' );

			// Make the flow publish.
			add_filter( 'cartflows_flow_importer_args', array( $this, 'change_flow_status' ) );
			add_action( 'cartflows_flow_imported', array( $this, 'track_flows' ) );
			add_action( 'cartflows_step_imported', array( $this, 'track_flows' ) );
			add_filter( 'cartflows_enable_imported_content_processing', '__return_false' );

			$url = astra_get_site_data( 'astra-site-cartflows-path' );

			try {
				$flows = $this->download_and_validate_import_data( $url );
				if ( is_wp_error( $flows ) ) {
					wp_send_json_error(
						sprintf(
							// translators: %s is the error message.
							__( 'WPForms import failed: %s', 'astra-sites' ),
							$flows->get_error_message()
						)
					);
				}

				if ( empty( $flows ) ) {
					wp_send_json_success( $url );
				}

				if ( ! is_callable( 'CartFlows_Importer::get_instance' ) ) {
					wp_send_json_error( __( 'CartFlows import failed: Importer not found. Please ensure the CartFlows plugin is active and try again.', 'astra-sites' ) );
				}

				// Import CartFlows flows.
				$import_result = CartFlows_Importer::get_instance()->import_from_json_data( $flows );

				if ( is_wp_error( $import_result ) ) {
					wp_send_json_error(
						sprintf(
							// translators: Sending cartflows import failed.
							__( 'CartFlows import failed: %s', 'astra-sites' ), 
							$import_result->get_error_message() 
						)
					);
				}
			} catch ( \Exception $e ) {
				astra_sites_error_log( 'Astra Sites CartFlows Import Exception: ' . $e->getMessage() );
				// translators: %s: Exception error message.
				wp_send_json_error( sprintf( __( 'CartFlows import failed: Unexpected error - %s', 'astra-sites' ), $e->getMessage() ) );
			} catch ( \Error $e ) {
				astra_sites_error_log( 'Astra Sites CartFlows Import Fatal Error: ' . $e->getMessage() );
				// translators: %s: Fatal error message.
				wp_send_json_error( sprintf( __( 'CartFlows import failed: Fatal Error - %s', 'astra-sites' ), $e->getMessage() ) );
			}

			if ( defined( 'WP_CLI' ) ) {
				WP_CLI::line( 'Imported from ' . $url );
			} elseif ( wp_doing_ajax() ) {
				wp_send_json_success( $url );
			}
		}

		/**
		 * Import Cart Abandonment Recovery data.
		 *
		 * @since 4.4.27
		 *
		 * @param  string $url JSON file URL.
		 * @return void
		 */
		public function import_cart_abandonment_recovery( $url = '' ) {
			try {
				if ( ! defined( 'WP_CLI' ) && wp_doing_ajax() ) {
					// Verify Nonce.
					check_ajax_referer( 'astra-sites', '_ajax_nonce' );

					if ( ! current_user_can( 'edit_posts' ) ) {
						wp_send_json_error(
							__( "Permission denied: You don't have permission to import Cart Abandonment Recovery data. Please contact your site administrator.", 'astra-sites' )
						);
					}
				}

				$url = astra_get_site_data( 'astra-site-cart-abandonment-recovery-path' );

				$data = $this->download_and_validate_import_data( $url );
				if ( is_wp_error( $data ) ) {
					wp_send_json_error(
						sprintf(
							// translators: %s is the error message.
							__( 'WPForms import failed: %s', 'astra-sites' ),
							$data->get_error_message()
						)
					);
				}

				// Skip import gracefully if no URL (normal condition).
				if ( empty( $data ) ) {
					wp_send_json_success( $url );
				}

				if ( ! is_callable( 'Cartflows_CA_Email_Template_Importer_Exporter::get_instance' ) ) {
					wp_send_json_error(
						__( 'Cart Abandonment Recovery import failed: Importer not found. Please ensure the plugin is active.', 'astra-sites' )
					);
				}

				Cartflows_CA_Email_Template_Importer_Exporter::get_instance()->insert_templates( $data );

				if ( defined( 'WP_CLI' ) ) {
					WP_CLI::line( 'Imported Cart Abandonment Recovery data from ' . $url );
				} elseif ( wp_doing_ajax() ) {
					wp_send_json_success( $url );
				}
			} catch ( Exception $e ) {
				// translators: %s: Exception error message.
				wp_send_json_error( sprintf( __( 'Cart Abandonment Recovery import failed: Unexpected error - %s', 'astra-sites' ), $e->getMessage() ) );
			} catch ( \Error $e ) {
				// translators: %s: Fatal error message.
				wp_send_json_error( sprintf( __( 'Cart Abandonment Recovery import failed: Fatal Error - %s', 'astra-sites' ), $e->getMessage() ) );
			}
		}

		/**
		 * Import LatePoint
		 *
		 * @since 2.0.0
		 *
		 * @param  string $url LatePoint JSON file URL.
		 * @return void
		 */
		public function import_latepoint( $url = '' ) {

			if ( ! defined( 'WP_CLI' ) && wp_doing_ajax() ) {
				// Verify Nonce.
				check_ajax_referer( 'astra-sites', '_ajax_nonce' );

				if ( ! current_user_can( 'edit_posts' ) ) {
					wp_send_json_error(
						__( "Permission denied: You don't have permission to import LatePoint data. Please contact your site administrator.", 'astra-sites' )
					);
				}
			}

			$url = astra_get_site_data( 'astra-site-latepoint-path' );

			try {
				$content = $this->download_and_validate_import_data( $url, false );

				if ( is_wp_error( $content ) ) {
					wp_send_json_error(
						sprintf(
							// translators: %s is the error message.
							__( 'WPForms import failed: %s', 'astra-sites' ),
							$content->get_error_message()
						)
					);
				}

				try {
					OsSettingsHelper::import_data( $content );
				} catch ( \Exception $e ) {
					wp_send_json_error(
						// translators: %s is exception error message.
						sprintf( __( 'LatePoint import failed: %s', 'astra-sites' ), $e->getMessage() )
					);
				}
			} catch ( \Exception $e ) {
				wp_send_json_error(
					// translators: %s is exception error message.
					sprintf( __( 'LatePoint import failed: Unexpected error - %s', 'astra-sites' ), $e->getMessage() )
				);
			} catch ( \Error $e ) {
				wp_send_json_error(
					// translators: %s: Fatal error message.
					sprintf( __( 'LatePoint import failed: Fatal Error - %s', 'astra-sites' ), $e->getMessage() )
				);
			}

			if ( defined( 'WP_CLI' ) ) {
				WP_CLI::line( 'Imported from ' . $url );
			} elseif ( wp_doing_ajax() ) {
				wp_send_json_success( $url );
			}
		}

		/**
		 * Get single demo.
		 *
		 * @since  1.0.0
		 *
		 * @param  (String) $demo_api_uri API URL of a demo.
		 *
		 * @return (Array) $astra_demo_data demo data for the demo.
		 */
		public static function get_single_demo( $demo_api_uri ) {

			if ( is_int( $demo_api_uri ) ) {
				$demo_api_uri = Astra_Sites::get_instance()->get_api_url() . 'astra-sites/' . $demo_api_uri;
			}

			// default values.
			$remote_args = array();
			$defaults    = array(
				'id'                          => '',
				'astra-site-widgets-data'     => '',
				'astra-site-customizer-data'  => '',
				'astra-site-options-data'     => '',
				'astra-post-data-mapping'     => '',
				'astra-site-wxr-path'         => '',
				'astra-site-wpforms-path'     => '',
				'astra-enabled-extensions'    => '',
				'astra-custom-404'            => '',
				'required-plugins'            => '',
				'astra-site-taxonomy-mapping' => '',
				'license-status'              => '',
				'site-type'                   => '',
				'astra-site-url'              => '',
			);

			$api_args = apply_filters(
				'astra_sites_api_args',
				array(
					'timeout' => 15,
				)
			);

			// Use this for premium demos.
			$request_params = apply_filters(
				'astra_sites_api_params',
				array(
					'purchase_key' => '',
					'site_url'     => '',
				)
			);

			$demo_api_uri = add_query_arg( $request_params, trailingslashit( $demo_api_uri ) );

			// API Call.
			$response = wp_safe_remote_get( $demo_api_uri, $api_args );

			if ( is_wp_error( $response ) || ( isset( $response->status ) && 0 === $response->status ) ) {
				if ( isset( $response->status ) ) {
					$data = json_decode( $response, true );
				} else {
					return new WP_Error( 'api_invalid_response_code', $response->get_error_message() );
				}
			}

			if ( wp_remote_retrieve_response_code( $response ) !== 200 ) {
				return new WP_Error( 'api_invalid_response_code', wp_remote_retrieve_body( $response ) );
			} else {
				$data = json_decode( wp_remote_retrieve_body( $response ), true );
			}

			$data = json_decode( wp_remote_retrieve_body( $response ), true );

			if ( ! isset( $data['code'] ) ) {
				$remote_args['id']                          = $data['id'];
				$remote_args['astra-site-widgets-data']     = json_decode( $data['astra-site-widgets-data'] );
				$remote_args['astra-site-customizer-data']  = $data['astra-site-customizer-data'];
				$remote_args['astra-site-options-data']     = $data['astra-site-options-data'];
				$remote_args['astra-post-data-mapping']     = $data['astra-post-data-mapping'];
				$remote_args['astra-site-wxr-path']         = $data['astra-site-wxr-path'];
				$remote_args['astra-site-wpforms-path']     = $data['astra-site-wpforms-path'];
				$remote_args['astra-enabled-extensions']    = $data['astra-enabled-extensions'];
				$remote_args['astra-custom-404']            = $data['astra-custom-404'];
				$remote_args['required-plugins']            = $data['required-plugins'];
				$remote_args['astra-site-taxonomy-mapping'] = $data['astra-site-taxonomy-mapping'];
				$remote_args['license-status']              = $data['license-status'];
				$remote_args['site-type']                   = $data['astra-site-type'];
				$remote_args['astra-site-url']              = $data['astra-site-url'];
			}

			// Merge remote demo and defaults.
			return wp_parse_args( $remote_args, $defaults );
		}

		/**
		 * Clear Cache.
		 *
		 * @since  1.0.9
		 */
		public function clear_related_cache() {

			// Clear 'Builder Builder' cache.
			if ( is_callable( 'FLBuilderModel::delete_asset_cache_for_all_posts' ) ) {
				FLBuilderModel::delete_asset_cache_for_all_posts();
				Astra_Sites_Importer_Log::add( 'Cache for Beaver Builder cleared.' );
			}

			// Clear 'Astra Addon' cache.
			if ( is_callable( 'Astra_Minify::refresh_assets' ) ) {
				Astra_Minify::refresh_assets();
				Astra_Sites_Importer_Log::add( 'Cache for Astra Addon cleared.' );
			}

			Astra_Sites_Utils::third_party_cache_plugins_clear_cache();

			$this->update_latest_checksums();

			// Flush permalinks.
			flush_rewrite_rules(); //phpcs:ignore WordPressVIPMinimum.Functions.RestrictedFunctions.flush_rewrite_rules_flush_rewrite_rules -- This function is called only after import is completed
		}

		/**
		 * Update Latest Checksums
		 *
		 * Store latest checksum after batch complete.
		 *
		 * @since 2.0.0
		 * @return void
		 */
		public function update_latest_checksums() {
			$latest_checksums = get_site_option( 'astra-sites-last-export-checksums-latest', '' );
			update_site_option( 'astra-sites-last-export-checksums', $latest_checksums );
		}
	}

	/**
	 * Kicking this off by calling 'get_instance()' method
	 */
	Astra_Sites_Importer::get_instance();
}

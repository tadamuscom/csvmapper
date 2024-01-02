<?php

/**
 * Render a view
 *
 * @package csvmapper
 * @author Tadamus <hello@tadamus.com>
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'CSVM_View' ) ) {
	class CSVM_View {

		/**
		 * The view name
		 *
		 * @since 1.0
		 *
		 * @var string
		 */
		private string $view;

		/**
		 * The attached values
		 *
		 * @since 1.0
		 *
		 * @var mixed
		 */
		private mixed $with;

		/**
		 * Notifications toggle
		 *
		 * @since 1.0
		 *
		 * @var bool|mixed
		 */
		private bool $notifications;

		public function __construct( string $view, $with = false, $notifications = true ) {
			$this->view = $view;

			if ( $with ) {
				$this->with = $with;
			}

			$this->notifications = $notifications;

			$this->render();
		}

		/**
		 * Add the notifications partial
		 *
		 * @since 1.0
		 *
		 * @return void
		 */
		private function add_notifications(): void {
			if ( $this->notifications ) {
				if ( isset( $_COOKIE['csvm_redirect_type'] ) && isset( $_COOKIE['csvm_redirect_message'] ) ) {
					$type    = $this->generate_message_class( $_COOKIE['csvm_redirect_type'] );
					$message = $_COOKIE['csvm_redirect_message'];

					$this->show_redirect_message( $type, $message );
				}
			}
		}

		/**
		 * The notifications partial
		 *
		 * @since 1.0
		 *
		 * @param string $type
		 * @param string $message
		 *
		 * @return void
		 */
		private function show_redirect_message( string $type, string $message ): void {
			?>
			<div class="csvm-message-container">
				<div class="csvm-notice <?php echo $type; ?>">
					<p><?php echo $message; ?></p>
				</div>
			</div>

			<script>
				document.cookie = "csvm_redirect_type=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
				document.cookie = "csvm_redirect_message=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
			</script>
			<?php
		}

		/**
		 * Generate which HTML class to assign
		 *
		 * @since 1.0
		 *
		 * @param string $type
		 *
		 * @return string
		 */
		private function generate_message_class( string $type ): string {
			return match ( $type ) {
				'success' => 'csvm-success',
				'alert' => 'csvm-alert',
				'error' => 'csvm-error',
				default => '',
			};
		}

		/**
		 * Renders the page with the values passed
		 *
		 * @since 1.0
		 */
		private function render(): void {
			$this->add_notifications();

			if ( ! empty( $this->with ) ) {
				foreach ( $this->with as $key => $value ) {
					${$key} = $value;
				}
			}

			include_once CSVM_VIEW . '/' . $this->view . '.php';
		}
	}
}
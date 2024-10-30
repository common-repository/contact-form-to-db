<?php
/**
 * Displays the content on the plugin settings page
 */

if ( ! class_exists( 'Cntctfrmtdb_Settings_Tabs' ) ) {
	class Cntctfrmtdb_Settings_Tabs extends Bws_Settings_Tabs {
		private $periods;

		/**
		 * Constructor.
		 *
		 * @access public
		 *
		 * @see Bws_Settings_Tabs::__construct() for more information on default arguments.
		 *
		 * @param string $plugin_basename Plugin basename.
		 */
		public function __construct( $plugin_basename ) {
			global $cntctfrmtdb_options, $cntctfrmtdb_plugin_info;

			$tabs = array(
				'settings' => array( 'label' => __( 'Settings', 'contact-form-to-db' ) ),
				'misc'     => array( 'label' => __( 'Misc', 'contact-form-to-db' ) ),
				'license'  => array( 'label' => __( 'License Key', 'contact-form-to-db' ) ),
			);

			parent::__construct(
				array(
					'plugin_basename'    => $plugin_basename,
					'plugins_info'       => $cntctfrmtdb_plugin_info,
					'prefix'             => 'cntctfrmtdb',
					'default_options'    => cntctfrmtdb_get_options_default(),
					'options'            => $cntctfrmtdb_options,
					'is_network_options' => is_network_admin(),
					'tabs'               => $tabs,
					'wp_slug'            => 'contact-form-to-db',
					'link_key'           => '5906020043c50e2eab1528d63b126791',
					'link_pn'            => '91',
					'doc_link'           => 'https://bestwebsoft.com/documentation/contact-form-to-db/contact-form-to-db-user-guide/',
				)
			);

			$this->periods = array(
				'daily'            => __( 'every 24 hours', 'contact-form-to-db' ),
				'every_three_days' => __( 'every 3 days', 'contact-form-to-db' ),
				'weekly'           => __( 'every 1 week', 'contact-form-to-db' ),
				'every_two_weeks'  => __( 'every 2 weeks', 'contact-form-to-db' ),
				'monthly'          => __( 'every 1 month', 'contact-form-to-db' ),
				'every_six_months' => __( 'every 6 months', 'contact-form-to-db' ),
				'yearly'           => __( 'every 1 year', 'contact-form-to-db' ),
			);
		}

		/**
		 * Save options
		 */
		public function save_options() {

			$message = '';
			$notice  = '';
			$error   = '';

			if ( isset( $_POST['cntctfrmtdb_nonce_field'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['cntctfrmtdb_nonce_field'] ) ), 'cntctfrmtdb_action' ) ) {
				$this->options['save_messages_to_db']  = isset( $_POST['cntctfrmtdb_save_messages_to_db'] ) ? 1 : 0;
				$this->options['format_save_messages'] = isset( $_POST['cntctfrmtdb_format_save_messages'] ) && in_array( sanitize_text_field( wp_unslash( $_POST['cntctfrmtdb_format_save_messages'] ) ), array( 'xml', 'eml', 'csv' ) ) ? sanitize_text_field( wp_unslash( $_POST['cntctfrmtdb_format_save_messages'] ) ) : $this->options['format_save_messages'];
				$this->options['csv_separator']        = isset( $_POST['cntctfrmtdb_csv_separator'] ) && in_array( sanitize_text_field( wp_unslash( $_POST['cntctfrmtdb_csv_separator'] ) ), array( ',', ';', 't' ) ) ? sanitize_text_field( wp_unslash( $_POST['cntctfrmtdb_csv_separator'] ) ) : $this->options['csv_separator'];
				$this->options['csv_enclosure']        = isset( $_POST['cntctfrmtdb_csv_enclosure'] ) && in_array( sanitize_text_field( wp_unslash( $_POST['cntctfrmtdb_csv_enclosure'] ) ), array( '\"', "\'", '`' ) ) ? sanitize_text_field( wp_unslash( $_POST['cntctfrmtdb_csv_enclosure'] ) ) : $this->options['csv_enclosure'];

				update_option( 'cntctfrmtdb_options', $this->options );
				$message = __( 'Settings saved.', 'contact-form-to-db' );
			}

			return compact( 'message', 'notice', 'error' );
		}

		/**
		 * Display tab Settings
		 */
		public function tab_settings() { ?>
			<h3 class="bws_tab_label"><?php esc_html_e( 'Contact Form to DB Settings', 'contact-form-to-db' ); ?></h3>
			<?php $this->help_phrase(); ?>
			<hr>
			<table class="form-table">
				<tr>
					<th scope="row"><?php esc_html_e( 'Save Messages to Database', 'contact-form-to-db' ); ?></th>
					<td>
						<input type="checkbox" name="cntctfrmtdb_save_messages_to_db" class="bws_option_affect" data-affect-show="#cntctfrmtdb-options" value="1" <?php checked( $this->options['save_messages_to_db'] ); ?> />
					</td>
				</tr>
				<tr id="cntctfrmtdb-options">
					<th scope="row"><?php esc_html_e( 'Download Messages in', 'contact-form-to-db' ); ?></th>
					<td>
						<select<?php echo esc_html( $this->change_permission_attr ); ?> name="cntctfrmtdb_format_save_messages">
							<option value='xml' <?php selected( 'xml', $this->options['format_save_messages'] ); ?>>.xml</option>
							<option value='eml' <?php selected( 'eml', $this->options['format_save_messages'] ); ?>>.eml</option>
							<option class="bws_option_affect" data-affect-show="#cntctfrmtdb-csv-separators" value='csv' <?php selected( 'csv', $this->options['format_save_messages'] ); ?>>.csv</option>
						</select>
						<span><?php esc_html_e( 'format', 'contact-form-to-db' ); ?></span><br />
						<div id="cntctfrmtdb-csv-separators">
							<span><?php esc_html_e( 'Choose separator and enclosure symbols', 'contact-form-to-db' ); ?></span><br /><br />
							<select<?php echo esc_html( $this->change_permission_attr ); ?> name="cntctfrmtdb_csv_separator" id="cntctfrmtdb_csv_separator">
								<option value="," <?php selected( ',', $this->options['csv_separator'] ); ?>>,</option>
								<option value=";" <?php selected( ';', $this->options['csv_separator'] ); ?>>;</option>
								<option value="t" <?php selected( 't', $this->options['csv_separator'] ); ?>>\t</option>
							</select>
							<span><?php esc_html_e( 'separator', 'contact-form-to-db' ); ?></span><br /><br />
							<select<?php echo esc_html( $this->change_permission_attr ); ?> name="cntctfrmtdb_csv_enclosure" id="cntctfrmtdb_csv_enclosure">
								<option value='"' <?php selected( '\"', $this->options['csv_enclosure'] ); ?>>"</option>
								<option value="'" <?php selected( "\'", $this->options['csv_enclosure'] ); ?>>'</option>
								<option value="`" <?php selected( '`', $this->options['csv_enclosure'] ); ?>>`</option>
							</select>
							<span><?php esc_html_e( 'enclosure', 'contact-form-to-db' ); ?></span>
							<?php if ( ! $this->hide_pro_tabs ) { ?>
								<div class="bws_pro_version_bloc">
									<div class="bws_pro_version_table_bloc">
										<button type="submit" name="bws_hide_premium_options" class="notice-dismiss bws_hide_premium_options" title="<?php esc_html_e( 'Close', 'contact-form-to-db' ); ?>"></button>
										<div class="bws_table_bg"></div>
										<div class="bws_pro_version">
											<label><input disabled="disabled" type="checkbox" name="cntctfrmtdb_include_attachments" /><?php esc_html_e( 'Include content of attachments in to "csv"-file', 'contact-form-to-db' ); ?></label>
										</div>
									</div>
									<?php $this->bws_pro_block_links(); ?>
								</div>
							<?php } ?>
						</div>
					</td>
				</tr>
			</table>
			<?php if ( ! $this->hide_pro_tabs ) { ?>
				<div class="bws_pro_version_bloc">
					<div class="bws_pro_version_table_bloc">
						<button type="submit" name="bws_hide_premium_options" class="notice-dismiss bws_hide_premium_options" title="<?php esc_html_e( 'Close', 'contact-form-to-db' ); ?>"></button>
						<div class="bws_table_bg"></div>
						<table class="form-table bws_pro_version">
							<tr class="cntctfrmtdb_save_messages_to_db">
								<th scope="row"><?php esc_html_e( 'Save Attachments', 'contact-form-to-db' ); ?></th>
								<td>
									<input disabled="disabled" checked="checked" name="cntctfrmtdb_save_attachments" type="checkbox" value="1" /><br />
									<br />
									<fieldset>
										<label><input disabled="disabled" type="radio" name="cntctfrmtdb_save_attachments_to" value="database" /><?php esc_html_e( 'Save attachments to database', 'contact-form-to-db' ); ?></label><br />
										<label><input disabled="disabled" type="radio" name="cntctfrmtdb_save_attachments_to" value="uploads" /><?php esc_html_e( 'Save attachments to "Uploads"', 'contact-form-to-db' ); ?></label>
									</fieldset>
								</td>
							</tr>
							<tr>
								<th scope="row"><?php esc_html_e( 'Re-Send a Message to the Email Address Specified in Contact Form Settings', 'contact-form-to-db' ); ?></th>
								<td>
									<input disabled="disabled" type="checkbox" name="cntctfrmtdb_mail_address" /><br />
									<span class="bws_info"><?php esc_html_e( 'If the option is disabled, all messages will be sent to the email address which was valid at the time of the message receipt.', 'contact-form-to-db' ); ?></span>
								</td>
							</tr>
							<tr>
								<th scope="row"><?php esc_html_e( 'Periodically Delete Old Messages', 'contact-form-to-db' ); ?></th>
								<td>
									<input disabled="disabled" checked="checked" type="checkbox" name="cntctfrmtdb_delete_messages" /><br />
									<select disabled="disabled" name="cntctfrmtdb_delete_messages_after">
										<?php foreach ( $this->periods as $key => $period ) { ?>
											<option value='<?php echo esc_attr( $key ); ?>'><?php echo esc_html( $period ); ?></option>
										<?php } ?>
									</select><br />
									<span class="bws_info"><?php esc_html_e( 'All messages older than the specified period will be deleted at the end of the same period.', 'contact-form-to-db' ); ?></span>
								</td>
							</tr>
						</table>
					</div>
					<?php $this->bws_pro_block_links(); ?>
				</div>
				<?php
			}
			wp_nonce_field( 'cntctfrmtdb_action', 'cntctfrmtdb_nonce_field' );
		}
	}
}

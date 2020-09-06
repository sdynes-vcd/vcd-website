<?php
/**
 * Destination Tabs AJAX Controller
 *
 * @package BackupBuddy
 */

backupbuddy_core::verifyAjaxAccess();

require_once pb_backupbuddy::plugin_path() . '/destinations/bootstrap.php';
pb_backupbuddy::$ui->ajax_header( true, false, 'destination-tabs' );

pb_backupbuddy::load_style( 'destination_picker.css' );
pb_backupbuddy::load_script( 'jquery-ui-core' );
pb_backupbuddy::load_script( 'jquery-ui-widget' );
pb_backupbuddy::load_script( 'backupbuddy_global_admin_scripts', false );
pb_backupbuddy::load_script( 'backupbuddy.min.js' );
pb_backupbuddy::load_style( 'backupbuddy-core.css' );

pb_backupbuddy::load_style( 'backupProcess.css' );
pb_backupbuddy::load_style( 'backupProcess2.css' );

$active_tab = pb_backupbuddy::_GET( 'tab' );
$mode       = 'destination';

$picker_url = pb_backupbuddy::ajax_url( 'destinationTabs' );
if ( 'migration' === $mode ) {
	$picker_url = pb_backupbuddy::ajax_url( 'migration_picker' );
} elseif ( pb_backupbuddy::_GET( 'tab' ) ) {
	$picker_url .= '&tab=' . rawurlencode( pb_backupbuddy::_GET( 'tab' ) );
}
?>
<script type="text/javascript">
	jQuery(function( $ ) {

		$( '.tab.add-new a' ).on( 'click', function(){
			$( '.bb_destinations-adding' ).hide();
			$( '.bb_destinations' ).show();
		});

		$( '.bb_destination-new-item a' ).on( 'click', function(e){
			e.preventDefault();

			if ( $(this).parent('.bb_destination-item').hasClass('bb_destination-item-disabled') ) {
				alert( 'Error #848448: This destination is not available on your server.' );
				return false;
			}

			var archive_send_rel = $('#pb_backupbuddy_archive_send').attr('rel') ? $('#pb_backupbuddy_archive_send').attr('rel') : '',
				sendURL = '<?php echo pb_backupbuddy::ajax_url( 'destination_picker' ); ?>&add=' + $(this).attr('rel') + '&filter=' + $(this).attr('rel') + '&callback_data=' + archive_send_rel + '&sending=0';

			$.post( sendURL,
				function(data) {
					data = $.trim( data );
					$( '.bb_destinations' ).hide();
					$( '.bb_destinations-adding' ).html( data ).show();
				}
			);
		});

		// Save a remote destination settings.
		function backupbuddy_destinations_bind_save() {
			$( '.pb_backupbuddy_destpicker_save' ).off( 'click' ).on( 'click', function(e) {
				e.preventDefault();

				var pb_remote_id = $(this).closest('.backupbuddy-destination-wrap').attr('data-destination_id');
				var new_title = $(this).closest('form').find( '#pb_backupbuddy_title' ).val();
				var configToggler = $(this).closest('.backupbuddy-destination-wrap').find('.backupbuddy-destination-config');
				$(this).closest('form').find( '.pb_backupbuddy_destpicker_saveload' ).show();
				$.post( '<?php echo pb_backupbuddy::ajax_url( 'remote_save' ); ?>&pb_backupbuddy_destinationid=' + pb_remote_id, $(this).parent( 'form' ).serialize(),
					function(data) {
						//data = jQuery.trim( data );

						if ( data.success ) {
							if ( 'added' === data.status ) {
								<?php if ( pb_backupbuddy::_GET( 'quickstart' ) != '' ) { ?>
									var win = window.dialogArguments || opener || parent || top;
									win.pb_backupbuddy_quickstart_destinationselected();
									win.tb_remove();
									return false;
								<?php } ?>

								window.location.href = '<?php echo $picker_url; ?>&callback_data=<?php echo pb_backupbuddy::_GET( 'callback_data' ); ?>&sending=<?php echo pb_backupbuddy::_GET( 'sending' ); ?>&alert_notice=' + encodeURIComponent( 'New destination successfully added.' );
							} else if ( 'saved' === data.status ) {
								$( '.pb_backupbuddy_destpicker_saveload' ).hide();
								$( '.nav-tab-active' ).find( '.destination_title' ).text( new_title );
								configToggler.toggle();
								configToggler.closest('.backupbuddy-destination-wrap').find( 'iframe' ).attr( 'src', function ( i, val ) { return val; }); // Refresh iframe.
							}
						} else {
							if ( data.status ) {
								$( '.pb_backupbuddy_destpicker_saveload' ).hide();
								alert( "Error: \n\n" + data.status );
							} else if ( data.error ) {
								$( '.pb_backupbuddy_destpicker_saveload' ).hide();
								alert( "Error: \n\n" + data.error );
							} else {
								$( '.pb_backupbuddy_destpicker_saveload' ).hide();
								alert( 'An unknown error has occurred. Please contact support.' );
							}
						}

					}
				);

				return false;
			} );
		}

		// Test a remote destination.
		function backupbuddy_destinations_bind_test() {
			$( '.pb_backupbuddy_destpicker_test' ).off( 'click' ).on( 'click', function(e) {
				e.preventDefault();

				$(this).children( '.pb_backupbuddy_destpicker_testload' ).show();
				$.post( '<?php echo pb_backupbuddy::ajax_url( 'remote_test' ); ?>', $(this).parent( 'form' ).serialize(),
					function(data) {
						$( '.pb_backupbuddy_destpicker_testload' ).hide();
						data = $.trim( data );
						alert( data );
					}
				);

				return false;
			} );
		}

		// Delete a remote destination settings.
		function backupbuddy_destinations_bind_delete() {
			$( '.pb_backupbuddy_destpicker_delete' ).off( 'click' ).on( 'click', function(e) {
				e.preventDefault();

				if ( ! confirm( 'Are you sure you want to delete this destination?' ) ) {
					return false;
				}

				var pb_remote_id = $(this).closest('.backupbuddy-destination-wrap').attr('data-destination_id');
				$.post( '<?php echo pb_backupbuddy::ajax_url( 'remote_delete' ); ?>&pb_backupbuddy_destinationid=' + pb_remote_id, $(this).parent( 'form' ).serialize(),
					function( data ) {
						data = $.trim( data );

						if ( data == 'Destination deleted.' ) {
							window.location.href = '<?php echo $picker_url; ?>&callback_data=<?php echo pb_backupbuddy::_GET( 'callback_data' ); ?>&sending=<?php echo pb_backupbuddy::_GET( 'sending' ); ?>&alert_notice=' + encodeURIComponent( 'Destination deleted.' );
						} else { // Show message if not success.
							alert( 'Error #82724. Details: `' + data + '`.' );
						}

					}
				);

				return false;
			} );
		}

		$( '.bb_destination_config_icon' ).on( 'click', function(e){
			e.preventDefault();

			var destination_id = $(this).attr('data-id'),
				$config = $( '.backupbuddy-destination-wrap[data-destination_id="' + destination_id + '"]' ).find( '.backupbuddy-destination-config' );

			if ( '1' !== $config.attr( 'data-loaded' ) ) {
				$.ajax({
					url: '<?php echo pb_backupbuddy::ajax_url( 'destination-settings' ); ?>',
					data: { destination_id: destination_id, mode: '<?php echo $mode; ?>' },
					type: 'get',
					success: function( response ) {
						$config.html( response );
						backupbuddy_destinations_bind_save();
						backupbuddy_destinations_bind_test();
						backupbuddy_destinations_bind_delete();
						backupbuddy_destinations_advanced_toggle();

						$config.attr( 'data-loaded', '1' );
					}
				});
			}

			$config.toggle();
		});
	});
</script>
<div class="backupbuddy-tabs">

	<ul class="tab-controls">
		<?php
		$first = true;
		foreach ( pb_backupbuddy::$options['remote_destinations'] as $destination_id => $destination ) {
			if ( 'live' == $destination['type'] ) { // Hide Live from tab listing.
				continue;
			}

			$href             = '#destination-' . $destination['type'] . '-' . $destination_id;
			$title_style      = '';
			$hover_title_text = __( 'Destination type', 'it-l10n-backupbuddy' ) . ': ' . $destination['type'] . '. ID: ' . $destination_id;
			if ( isset( $destination['disabled'] ) && '1' == $destination['disabled'] ) {
				$title_style       = 'text-decoration: line-through';
				$hover_title_text .= ' [' . __( 'DISABLED', 'it-l10n-backupbuddy' ) . ']';
			}
			$label = '<span title="' . esc_attr( $hover_title_text ) . '" class="destination_title" style="' . esc_attr( $title_style ) . '">' . esc_html( $destination['title'] ) . '<span class="bb_destination_config_icon" data-id="' . esc_attr( $destination_id ) . '" title="Show configuration options"></span>';

			$class  = ' ' . sanitize_title( strip_tags( $label ) );
			$class .= ' type-' . esc_attr( $destination['type'] );
			if ( $active_tab ) {
				$class .= ( '#' . $active_tab === $href ) ? ' active' : '';
			} else {
				$class .= $first ? ' active' : '';
			}

			printf( '<li class="tab%s"><a href="%s">%s</a>', esc_attr( $class ), esc_attr( $href ), $label );
			$first = false;
		}

		if ( false === apply_filters( 'itbub_disable_add_destination_tab', false ) ) {
			$add_href  = '#add';
			$add_label = esc_html__( 'Add New', 'it-l10n-backupbuddy' );
			$add_class = ' add-new';
			if ( '#' . $active_tab === $add_href ) {
				$add_class .= ' active';
			} elseif ( $first ) {
				$add_class .= ' active';
			}
			printf( '<li class="tab%s"><a href="%s">%s</a>', esc_attr( $add_class ), esc_attr( $add_href ), $add_label );
		}
		?>
	</ul>

	<div class="tabs-container">
		<?php
		if ( '' != pb_backupbuddy::_GET( 'alert_notice' ) ) {
			pb_backupbuddy::alert( htmlentities( pb_backupbuddy::_GET( 'alert_notice' ) ) );
		}

		$first = true;
		foreach ( pb_backupbuddy::$options['remote_destinations'] as $destination_id => $destination ) {
			if ( 'live' == $destination['type'] ) { // Hide Live from tab listing.
				continue;
			}

			$tab_id = 'destination-' . $destination['type'] . '-' . $destination_id;
			$class  = ( ( ! $active_tab && $first ) || $tab_id === $active_tab ) ? ' active' : '';
			$src    = pb_backupbuddy::ajax_url( 'remoteClient' ) . '&destination_id=' . $destination_id;
			?>
			<div id="<?php echo esc_attr( $tab_id ); ?>" class="tab-contents<?php echo esc_attr( $class ); ?>">

				<div class="backupbuddy-destination-wrap" data-destination_id="<?php echo esc_attr( $destination_id ); ?>">

					<div class="backupbuddy-destination-config" style="display: none;">
						<span class="spinner" style="visibility: visible; float: left; margin-top: 0;"></span>
						<?php esc_html_e( 'Loading Destination Settings...', 'it-l10n-backupbuddy' ); ?>
					</div><!-- .backupbuddy-destination-config -->

					<iframe id="pb_backupbuddy_iframe-dest-<?php echo esc_attr( $destination_id ); ?>" class="backupbuddy-destination-iframe" src="<?php echo esc_attr( $src ); ?>" width="100%" frameBorder="0" onload="BackupBuddy.Destinations.resizeDestIframe();">Error #4584594579. Browser not compatible with iframes.</iframe>
				</div><!-- .backupbuddy-destination-wrap -->

			</div><!-- #<?php echo esc_html( $tab_id ); ?>-->
			<?php
			$first = false;
		}
		?>
		<div id="add" class="tab-contents<?php if ( 'add' === $active_tab || $first ) echo ' active'; ?>">
			<?php
			$destination_type = pb_backupbuddy::_GET( 'add' );

			require_once pb_backupbuddy::plugin_path() . '/destinations/bootstrap.php';
			?>
			<div class="bb_destinations" style="display: block; margin: 0;">
				<div class="bb_destinations-group bb_destinations-new" style="display: block;">
					Backups are by default stored in your local backup directory<?php pb_backupbuddy::tip( backupbuddy_core::getBackupDirectory() ); ?> configured on the Settings page.
					<br>
					You may also send backups to additional remote destinations or directories for safe keeping & redundancy.
					<br><br>

					<h2>What kind of destination do you want to add?</h2><br>
					<ul>
						<?php
						$best_count   = 0;
						$normal_count = 0;
						$legacy_count = 0;
						$best         = '';
						$normal       = '';
						$legacy       = '';

						foreach ( pb_backupbuddy_destinations::get_destinations_list( true ) as $destination_name => $destination ) {
							// Hide Live from Remote Destinations page.
							if ( 'live' == $destination['type'] ) {
								continue;
							}
							if ( empty( $destination['name'] ) ) {
								continue;
							}

							$disable_class = '';
							if ( true !== $destination['compatible'] ) {
								$disable_class = 'bb_destination-item-disabled';
							}

							$this_dest  = '';
							$this_dest .= '<li class="bb_destination-item bb_destination-' . $destination_name . ' bb_destination-new-item ' . $disable_class . '">';
							if ( 'stash3' == $destination_name ) {
								$this_dest .= '<div class="bb-ribbon"><span>New</span></div>';
							}
							$this_dest .= '<a href="javascript:void(0)" rel="' . $destination_name . '">';
							$this_dest .= $destination['name'];
							if ( true !== $destination['compatible'] ) {
								$this_dest .= ' [Unavailable; ' . $destination['compatibility'] . ']';
							}
							$this_dest .= '</a></li>';

							$category = isset( $destination['category'] ) ? $destination['category'] : false;

							if ( 'best' === $category ) {
								$best .= $this_dest;
								$best_count++;
								if ( $best_count > 4 ) {
									$best      .= '<span class="bb_destination-break"></span>';
									$best_count = 0;
								}
							} elseif ( 'legacy' === $category ) {
								$legacy .= $this_dest;
								$legacy_count++;
								if ( $legacy_count > 4 ) {
									$legacy      .= '<span class="bb_destination-break"></span>';
									$legacy_count = 0;
								}
							} elseif ( 'normal' === $category ) {
								$normal .= $this_dest;
								$normal_count++;
								if ( $normal_count > 4 ) {
									$normal      .= '<span class="bb_destination-break"></span>';
									$normal_count = 0;
								}
							}
						}

						echo '<h3>' . esc_html__( 'Preferred', 'it-l10n-backupbuddy' ) . '</h3>' . $best;
						echo '<br><br><hr style="max-width: 1200px;"><br>';
						echo '<h3>' . esc_html__( 'Normal', 'it-l10n-backupbuddy' ) . '</h3>' . $normal;
						echo '<br><br><hr style="max-width: 1200px;"><br>';
						echo '<h3>' . esc_html__( 'Legacy', 'it-l10n-backupbuddy' ) . '</h3>' . $legacy;

						echo '<br><br><hr style="max-width: 1200px;"><br>';
						?>
					</ul>

					<h3><?php esc_html_e( 'Discontinued', 'it-l10n-backupbuddy' ); ?></h3>
					<br>
					<span class="description"><?php esc_html_e( 'Stash (v1), Dropbox (v1), and AmazonS3 (v1) destinations have been discontinued as these legacy APIs have been discontinued by their providers. Please use their newer respective versions instead.', 'it-l10n-backupbuddy' ); ?></span>
				</div>
			</div>
			<div class="bb_destinations-adding"></div>
		</div><!-- #add.tab -->

	</div><!-- .tabs-container -->
</div><!-- .backupbuddy-tabs -->

<?php
pb_backupbuddy::$ui->ajax_footer();
die();

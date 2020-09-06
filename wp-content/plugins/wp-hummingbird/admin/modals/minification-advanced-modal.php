<?php
/**
 * Asset optimization: switch to advanced mode modal.
 *
 * @package Hummingbird
 */

use Hummingbird\Core\Utils;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>

<div class="sui-dialog sui-dialog-onboard sui-fade-in" aria-hidden="true" tabindex="-1" id="wphb-advanced-minification-modal">
	<div class="sui-dialog-overlay" data-a11y-dialog-hide="wphb-advanced-minification-modal"></div>
	<div class="sui-dialog-content sui-content-fade-in" aria-labelledby="switchAdvanced" aria-describedby="dialogDescription" role="dialog">
		<div class="sui-slider">
			<ul class="sui-slider-content" role="document">

				<li class="sui-current sui-loaded" data-slide="1">
					<div class="sui-box">
						<div class="sui-box-header sui-md sui-block-content-center" style="padding-top: 60px">
							<h2 id="switchAdvanced" class="sui-box-title">
								<?php esc_html_e( 'Just be careful!', 'wphb' ); ?>
							</h2>

							<span id="dialogDescription" class="sui-description">
								<?php esc_html_e( 'Advanced mode gives you full control over your files but can easily break your website if configured incorrectly.', 'wphb' ); ?>
								<br><br>
								<?php
								printf(
									/* translators: %1$s - <strong>, %2$s - </strong> */
									esc_html__( '%1$sWe recommend you make one tweak at a time%2$s and check the frontend of your website each change to avoid any mishaps. ', 'wphb' ),
									'<strong>',
									'</strong>'
								);
								?>
							</span>

							<button data-a11y-dialog-hide="wphb-advanced-minification-modal" class="sui-dialog-close" aria-label="<?php esc_attr_e( 'Close this dialog window', 'wphb' ); ?>"></button>
						</div>

						<div class="sui-box-body sui-md sui-block-content-center sui-margin-bottom">
							<a class="sui-button sui-button-ghost" data-a11y-dialog-tour-next="">
								<i class="sui-icon-web-globe-world" aria-hidden="true"></i>
								<?php esc_html_e( 'Take a Tour', 'wphb' ); ?>
							</a>
							<a onclick="WPHB_Admin.minification.switchView( 'advanced' )"  class="sui-button">
								<?php esc_html_e( 'Got It', 'wphb' ); ?>
							</a>
						</div>

						<?php if ( ! Utils::hide_wpmudev_branding() ) : ?>
							<img class="sui-image"
								src="<?php echo esc_url( WPHB_DIR_URL . 'admin/assets/image/graphic-minify-modal-warning@1x.png' ); ?>"
								srcset="<?php echo esc_url( WPHB_DIR_URL . 'admin/assets/image/graphic-minify-modal-warning@2x.png' ); ?> 2x"
								alt="<?php esc_attr_e( 'Hummingbird', 'wphb' ); ?>">
						<?php endif; ?>
					</div>
				</li>

				<li data-slide="2" class="">
					<div class="sui-box">
						<div class="sui-box-banner" role="banner" aria-hidden="true">
							<img src="<?php echo esc_url( WPHB_DIR_URL . 'admin/assets/image/tour/tour-compression.png' ); ?>"
								srcset="<?php echo esc_url( WPHB_DIR_URL . 'admin/assets/image/tour/tour-compression@2x.png' ); ?> 2x">
						</div>

						<div class="sui-box-header sui-lg sui-block-content-center">
							<span id="dialogDescription" class="sui-description">
								<?php esc_html_e( 'Here are the available options for advanced mode. Compression removes the clutter from CSS and Javascript files. Smaller files, in turn, help your site load faster, since your server doesn’t have to waste time reading unnecessary characters & spaces.', 'wphb' ); ?>
							</span>
							<button class="sui-dialog-back" aria-label="<?php esc_attr_e( 'Go to previous slide', 'wphb' ); ?>" data-a11y-dialog-tour-back=""></button>
							<button data-a11y-dialog-hide="wphb-minification-advanced-tour" class="sui-dialog-close" aria-label="<?php esc_attr_e( 'Close this dialog window', 'wphb' ); ?>"></button>
						</div>

						<div class="sui-box-body sui-md sui-block-content-center">
							<button class="sui-button" data-a11y-dialog-tour-next="">
								<?php esc_html_e( 'Next', 'wphb' ); ?>
							</button>
						</div>
					</div>
				</li>

				<li data-slide="3" class="">
					<div class="sui-box">
						<div class="sui-box-banner" role="banner" aria-hidden="true">
							<img src="<?php echo esc_url( WPHB_DIR_URL . 'admin/assets/image/tour/tour-combine.png' ); ?>"
								srcset="<?php echo esc_url( WPHB_DIR_URL . 'admin/assets/image/tour/tour-combine@2x.png' ); ?> 2x">
						</div>

						<div class="sui-box-header sui-lg sui-block-content-center">
							<span id="dialogDescription" class="sui-description">
								<?php esc_html_e( 'Hummingbird can combine smaller files together to reduce the number of requests made when a page is loaded. Less requests mean less waiting, and faster page speeds!', 'wphb' ); ?>
							</span>
							<button class="sui-dialog-back" aria-label="<?php esc_attr_e( 'Go to previous slide', 'wphb' ); ?>" data-a11y-dialog-tour-back=""></button>
							<button data-a11y-dialog-hide="wphb-minification-advanced-tour" class="sui-dialog-close" aria-label="<?php esc_attr_e( 'Close this dialog window', 'wphb' ); ?>"></button>
						</div>

						<div class="sui-box-body sui-md sui-block-content-center">
							<button class="sui-button" data-a11y-dialog-tour-next="">
								<?php esc_html_e( 'Next', 'wphb' ); ?>
							</button>
						</div>
					</div>
				</li>

				<li data-slide="4" class="">
					<div class="sui-box">
						<div class="sui-box-banner" role="banner" aria-hidden="true">
							<img src="<?php echo esc_url( WPHB_DIR_URL . 'admin/assets/image/tour/tour-move-footer.png' ); ?>"
								srcset="<?php echo esc_url( WPHB_DIR_URL . 'admin/assets/image/tour/tour-move-footer@2x.png' ); ?> 2x">
						</div>

						<div class="sui-box-header sui-lg sui-block-content-center">
							<span id="dialogDescription" class="sui-description">
								<?php esc_html_e( 'When it comes to rendering blocking issues and WordPress, the best practice is to load as many scripts as possible in the footer of your site, so slow-loading scripts won’t prevent vital parts of your site from loading quickly. You can choose whether to move the file to the footer or keep in original position.', 'wphb' ); ?>
							</span>
							<button class="sui-dialog-back" aria-label="<?php esc_attr_e( 'Go to previous slide', 'wphb' ); ?>" data-a11y-dialog-tour-back=""></button>
							<button data-a11y-dialog-hide="wphb-minification-advanced-tour" class="sui-dialog-close" aria-label="<?php esc_attr_e( 'Close this dialog window', 'wphb' ); ?>"></button>
						</div>

						<div class="sui-box-body sui-md sui-block-content-center">
							<button class="sui-button" data-a11y-dialog-tour-next="">
								<?php esc_html_e( 'Next', 'wphb' ); ?>
							</button>
						</div>
					</div>
				</li>

				<li data-slide="5" class="">
					<div class="sui-box">
						<div class="sui-box-banner" role="banner" aria-hidden="true">
							<img src="<?php echo esc_url( WPHB_DIR_URL . 'admin/assets/image/tour/tour-inline.png' ); ?>"
								srcset="<?php echo esc_url( WPHB_DIR_URL . 'admin/assets/image/tour/tour-inline@2x.png' ); ?> 2x">
						</div>

						<div class="sui-box-header sui-lg sui-block-content-center">
							<span id="dialogDescription" class="sui-description">
								<?php esc_html_e( 'To add CSS styles to your website, you can use three different ways to insert the CSS. You can Use an “External Stylesheet”, an “Internal Stylesheet”, or in “Inline Style”. The inline style uses the HTML “style” attribute. This allows CSS properties on a “per tag” basis.', 'wphb' ); ?>
							</span>
							<button class="sui-dialog-back" aria-label="<?php esc_attr_e( 'Go to previous slide', 'wphb' ); ?>" data-a11y-dialog-tour-back=""></button>
							<button data-a11y-dialog-hide="wphb-minification-advanced-tour" class="sui-dialog-close" aria-label="<?php esc_attr_e( 'Close this dialog window', 'wphb' ); ?>"></button>
						</div>

						<div class="sui-box-body sui-md sui-block-content-center">
							<button class="sui-button" data-a11y-dialog-tour-next="">
								<?php esc_html_e( 'Next', 'wphb' ); ?>
							</button>
						</div>
					</div>
				</li>

				<li data-slide="6" class="">
					<div class="sui-box">
						<div class="sui-box-banner" role="banner" aria-hidden="true">
							<img src="<?php echo esc_url( WPHB_DIR_URL . 'admin/assets/image/tour/tour-defer.png' ); ?>"
								srcset="<?php echo esc_url( WPHB_DIR_URL . 'admin/assets/image/tour/tour-defer@2x.png' ); ?> 2x">
						</div>

						<div class="sui-box-header sui-lg sui-block-content-center">
							<span id="dialogDescription" class="sui-description">
								<?php esc_html_e( 'For JavaScript (JS) files you will have the option to Defer it (force load it after the page had loaded). This means they will load only after everything else on your page has loaded, which allows you to load the most important files & content first.', 'wphb' ); ?>
							</span>
							<button class="sui-dialog-back" aria-label="<?php esc_attr_e( 'Go to previous slide', 'wphb' ); ?>" data-a11y-dialog-tour-back=""></button>
							<button data-a11y-dialog-hide="wphb-minification-advanced-tour" class="sui-dialog-close" aria-label="<?php esc_attr_e( 'Close this dialog window', 'wphb' ); ?>"></button>
						</div>

						<div class="sui-box-body sui-md sui-block-content-center">
							<button class="sui-button" data-a11y-dialog-tour-next="">
								<?php esc_html_e( 'Next', 'wphb' ); ?>
							</button>
						</div>
					</div>
				</li>

				<li data-slide="7" class="">
					<div class="sui-box">
						<div class="sui-box-banner" role="banner" aria-hidden="true">
							<img src="<?php echo esc_url( WPHB_DIR_URL . 'admin/assets/image/tour/tour-dont-load.png' ); ?>"
								srcset="<?php echo esc_url( WPHB_DIR_URL . 'admin/assets/image/tour/tour-dont-load@2x.png' ); ?> 2x">
						</div>

						<div class="sui-box-header sui-lg sui-block-content-center">
							<span id="dialogDescription" class="sui-description">
								<?php esc_html_e( 'If you click this, it will prevent the file while loading page.', 'wphb' ); ?>
							</span>
							<button class="sui-dialog-back" aria-label="<?php esc_attr_e( 'Go to previous slide', 'wphb' ); ?>" data-a11y-dialog-tour-back=""></button>
							<button data-a11y-dialog-hide="wphb-minification-advanced-tour" class="sui-dialog-close" aria-label="<?php esc_attr_e( 'Close this dialog window', 'wphb' ); ?>"></button>
						</div>

						<div class="sui-box-body sui-md sui-block-content-center">
							<button class="sui-button" data-a11y-dialog-tour-next="">
								<?php esc_html_e( 'Next', 'wphb' ); ?>
							</button>
						</div>
					</div>
				</li>

				<li data-slide="8" class="">
					<div class="sui-box">
						<div class="sui-box-banner" role="banner" aria-hidden="true">
							<img src="<?php echo esc_url( WPHB_DIR_URL . 'admin/assets/image/tour/tour-publish-advanced.png' ); ?>"
								srcset="<?php echo esc_url( WPHB_DIR_URL . 'admin/assets/image/tour/tour-publish-advanced@2x.png' ); ?> 2x">
						</div>

						<div class="sui-box-header sui-lg sui-block-content-center">
							<span id="dialogDescription" class="sui-description">
								<?php esc_html_e( 'After making changes, you need to click “Publish Changes” button or new settings to take affect.', 'wphb' ); ?>
							</span>
							<button class="sui-dialog-back" aria-label="<?php esc_attr_e( 'Go to previous slide', 'wphb' ); ?>" data-a11y-dialog-tour-back=""></button>
							<button data-a11y-dialog-hide="wphb-minification-advanced-tour" class="sui-dialog-close" aria-label="<?php esc_attr_e( 'Close this dialog window', 'wphb' ); ?>"></button>
						</div>

						<div class="sui-box-body sui-md sui-block-content-center">
							<button class="sui-button" data-a11y-dialog-tour-next="">
								<?php esc_html_e( 'Next', 'wphb' ); ?>
							</button>
						</div>
					</div>
				</li>

				<li data-slide="9" class="">
					<div class="sui-box">
						<div class="sui-box-banner" role="banner" aria-hidden="true">
							<img src="<?php echo esc_url( WPHB_DIR_URL . 'admin/assets/image/tour/tour-bulk.png' ); ?>"
								srcset="<?php echo esc_url( WPHB_DIR_URL . 'admin/assets/image/tour/tour-bulk@2x.png' ); ?> 2x">
						</div>

						<div class="sui-box-header sui-lg sui-block-content-center">
							<span id="dialogDescription" class="sui-description">
								<?php esc_html_e( 'If you know you have multiple files that need to have a single action applied to them, you can click the checkbox next to each file and then click on the “Bulk Update” button. A screen will then pop up that will let you choose which options to apply to all of the selected files. Note: it is not recommended to bulk action all the files, as it cause some things.', 'wphb' ); ?>
								<br>
								<?php esc_html_e( 'You can always re-take this tour with the button in the header after closing this modal.', 'wphb' ); ?>
							</span>
							<button class="sui-dialog-back" aria-label="<?php esc_attr_e( 'Go to previous slide', 'wphb' ); ?>" data-a11y-dialog-tour-back=""></button>
						</div>

						<div class="sui-box-body sui-md sui-block-content-center">
							<a onclick="WPHB_Admin.minification.switchView( 'advanced' )"  class="sui-button sui-button-blue" data-a11y-dialog-hide="wphb-minification-advanced-tour">
								<?php esc_html_e( 'Got it, thanks', 'wphb' ); ?>
							</a>
						</div>

						<img width="120" class="sui-image" src="<?php echo esc_url( WPHB_DIR_URL . 'admin/assets/image/graphic-caching-top.png' ); ?>"
							srcset="<?php echo esc_url( WPHB_DIR_URL . 'admin/assets/image/graphic-caching-top@2x.png' ); ?> 2x" style="margin: 30px auto 0;">
					</div>
				</li>

			</ul>
		</div>
	</div>
</div>
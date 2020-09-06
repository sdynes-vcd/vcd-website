<?php
/**
 * Asset optimization tour modal.
 *
 * @since 2.1.0
 * @package Hummingbird
 */

if ( ! defined( 'ABSPATH' ) ) {
	die;
}

use Hummingbird\Core\Utils;

?>

<div class="sui-dialog sui-dialog-onboard sui-fade-in" aria-hidden="true" tabindex="-1" id="wphb-minification-tour">
	<div class="sui-dialog-overlay" data-a11y-dialog-hide="wphb-minification-tour"></div>
	<div class="sui-dialog-content sui-content-fade-in" aria-labelledby="dialogTitle" aria-describedby="dialogDescription" role="dialog">
		<div class="sui-slider">
			<ul class="sui-slider-content" role="document">

				<li class="sui-current sui-loaded" data-slide="1">
					<div class="sui-box">
						<div class="sui-box-header sui-md sui-block-content-center" style="padding-top: 60px">
							<h2 id="dialogTitle" class="sui-box-title">
								<?php esc_html_e( 'Take a Quick Tour', 'wphb' ); ?>
							</h2>
							<span id="dialogDescription" class="sui-description">
								<?php esc_html_e( "Asset optimization is a complex feature for optimizing your files, if it's your first time we recommend you take a quick tour of the configuration options.", 'wphb' ); ?>
							</span>
							<button data-a11y-dialog-hide="wphb-minification-tour" class="sui-dialog-close" aria-label="Close this dialog window"></button>
						</div>

						<div class="sui-box-body sui-md sui-block-content-center">
							<button class="sui-button sui-button-blue" data-a11y-dialog-tour-next="">
								<?php esc_html_e( 'Take the tour', 'wphb' ); ?>
							</button>
						</div>

						<?php if ( ! Utils::hide_wpmudev_branding() ) : ?>
							<img class="sui-image sui-margin-top"
								src="<?php echo esc_url( WPHB_DIR_URL . 'admin/assets/image/graphic-minify-modal-warning@1x.png' ); ?>"
								srcset="<?php echo esc_url( WPHB_DIR_URL . 'admin/assets/image/graphic-minify-modal-warning@2x.png' ); ?> 2x"
								alt="<?php esc_attr_e( 'Hummingbird', 'wphb' ); ?>">
						<?php endif; ?>
					</div>
					<p class="sui-onboard-skip">
						<a href="#" data-a11y-dialog-hide="wphb-minification-tour" onclick="WPHB_Admin.minification.skipTour()">
							<?php esc_html_e( 'Skip this', 'wphb' ); ?>
						</a>
					</p>
				</li>

				<li data-slide="2" class="">
					<div class="sui-box">
						<div class="sui-box-banner" role="banner" aria-hidden="true">
							<img src="<?php echo esc_url( WPHB_DIR_URL . 'admin/assets/image/tour/tour-grey-compress.png' ); ?>"
								srcset="<?php echo esc_url( WPHB_DIR_URL . 'admin/assets/image/tour/tour-grey-compress@2x.png' ); ?> 2x">
						</div>

						<div class="sui-box-header sui-lg sui-block-content-center">
							<span id="dialogDescription" class="sui-description">
								<?php esc_html_e( "Greyed-out icons like this mean that the file has already been optimized (like *.min.js and *.min.css.), we can't compress these files any further so it's safe to ignore them.", 'wphb' ); ?>
							</span>
							<button class="sui-dialog-back" aria-label="<?php esc_attr_e( 'Go to previous slide', 'wphb' ); ?>" data-a11y-dialog-tour-back=""></button>
							<button data-a11y-dialog-hide="wphb-minification-tour" class="sui-dialog-close" aria-label="<?php esc_attr_e( 'Close this dialog window', 'wphb' ); ?>"></button>
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
							<img src="<?php echo esc_url( WPHB_DIR_URL . 'admin/assets/image/tour/tour-white-compress.png' ); ?>"
								srcset="<?php echo esc_url( WPHB_DIR_URL . 'admin/assets/image/tour/tour-white-compress@2x.png' ); ?> 2x">
						</div>

						<div class="sui-box-header sui-lg sui-block-content-center">
							<span id="dialogDescription" class="sui-description">
								<?php esc_html_e( "For files that can be compressed, click the 'Compress' icon and when you save your changes we'll do our best to optimize and reduce its file size.", 'wphb' ); ?>
							</span>
							<button class="sui-dialog-back" aria-label="<?php esc_attr_e( 'Go to previous slide', 'wphb' ); ?>" data-a11y-dialog-tour-back=""></button>
							<button data-a11y-dialog-hide="wphb-minification-tour" class="sui-dialog-close" aria-label="<?php esc_attr_e( 'Close this dialog window', 'wphb' ); ?>"></button>
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
							<img src="<?php echo esc_url( WPHB_DIR_URL . 'admin/assets/image/tour/tour-blue-compress.png' ); ?>"
								srcset="<?php echo esc_url( WPHB_DIR_URL . 'admin/assets/image/tour/tour-blue-compress@2x.png' ); ?> 2x">
						</div>

						<div class="sui-box-header sui-lg sui-block-content-center">
							<span id="dialogDescription" class="sui-description">
								<?php esc_html_e( "When an optimization option is active you'll see a blue icon (like the above). Note: compressing files can take a few minutes before they will be appear optimized on your web pages.", 'wphb' ); ?>
							</span>
							<button class="sui-dialog-back" aria-label="<?php esc_attr_e( 'Go to previous slide', 'wphb' ); ?>" data-a11y-dialog-tour-back=""></button>
							<button data-a11y-dialog-hide="wphb-minification-tour" class="sui-dialog-close" aria-label="<?php esc_attr_e( 'Close this dialog window', 'wphb' ); ?>"></button>
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
							<img src="<?php echo esc_url( WPHB_DIR_URL . 'admin/assets/image/tour/tour-advanced-mode.png' ); ?>"
								srcset="<?php echo esc_url( WPHB_DIR_URL . 'admin/assets/image/tour/tour-advanced-mode@2x.png' ); ?> 2x">
						</div>

						<div class="sui-box-header sui-lg sui-block-content-center">
							<span id="dialogDescription" class="sui-description">
								<?php esc_html_e( 'We also offer combining files, moving files to the footer, inlining CSS, deferring scripts and removing them completely. Switch to advanced mode to take full control of your assets.', 'wphb' ); ?>
							</span>
							<button class="sui-dialog-back" aria-label="<?php esc_attr_e( 'Go to previous slide', 'wphb' ); ?>" data-a11y-dialog-tour-back=""></button>
						</div>

						<div class="sui-box-body sui-md sui-block-content-center">
							<button class="sui-button sui-button-blue" data-a11y-dialog-hide="wphb-minification-tour" onclick="WPHB_Admin.minification.skipTour()">
								<?php esc_html_e( 'Got it, thanks', 'wphb' ); ?>
							</button>
						</div>

						<img width="120" class="sui-image" src="<?php echo esc_url( WPHB_DIR_URL . 'admin/assets/image/graphic-caching-top.png' ); ?>"
							srcset="<?php echo esc_url( WPHB_DIR_URL . 'admin/assets/image/graphic-caching-top@2x.png' ); ?> 2x" style="margin: 30px auto 0;">
					</div>
				</li>

			</ul>
		</div>
	</div>
</div>
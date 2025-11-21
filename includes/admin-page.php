<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Render main admin settings page.
 */
function holidify_render_admin_page() {

    if ( ! current_user_can( 'manage_options' ) ) {
        return;
    }

    $holidays        = holidify_get_holidays();
    $available_icons = holidify_get_available_icons();
    $is_paused       = get_option( 'holidify_paused', false );

    // Automatically detect active holiday (unless paused).
    $active_id = $is_paused ? null : holidify_get_active_holiday_based_on_date( $holidays );
    ?>
    <div class="wrap holidify-admin-wrap">

        <h1><?php esc_html_e( '🎉 Holidify My Website', 'holidify-my-website' ); ?></h1>
        <p class="description">
            <?php esc_html_e( 'Seasonal themes activate automatically based on dates. You can pause holiday mode any time.', 'holidify-my-website' ); ?>
        </p>

        <!-- Active holiday / paused / none -->
        <?php if ( $is_paused ) : ?>

            <div class="notice notice-warning is-dismissible holidify-active-notice">
                <p>
                    <strong><?php esc_html_e( 'Holiday Mode Paused', 'holidify-my-website' ); ?></strong>
                    <button type="button" class="button button-primary holidify-resume-holiday" style="margin-left:15px;">
                        <?php esc_html_e( 'Resume Holiday Mode', 'holidify-my-website' ); ?>
                    </button>
                </p>
            </div>

        <?php elseif ( $active_id && isset( $holidays[ $active_id ] ) ) : ?>

            <div class="notice notice-success is-dismissible holidify-active-notice">
                <p>
                    <strong><?php esc_html_e( 'Active Holiday:', 'holidify-my-website' ); ?></strong>
                    <?php echo esc_html( $holidays[ $active_id ]['name'] ); ?>
                    <button type="button" class="button button-secondary holidify-disable-all" style="margin-left:15px;">
                        <?php esc_html_e( 'Disable Holiday Mode', 'holidify-my-website' ); ?>
                    </button>
                </p>
            </div>

        <?php else : ?>

            <div class="notice notice-info is-dismissible holidify-active-notice">
                <p><?php esc_html_e( 'No holiday is active today.', 'holidify-my-website' ); ?></p>
            </div>

        <?php endif; ?>

        <h2 class="holidify-section-title"><?php esc_html_e( 'Holiday Themes', 'holidify-my-website' ); ?></h2>

        <table class="wp-list-table widefat fixed striped holidify-table">
            <thead>
                <tr>
                    <th><?php esc_html_e( 'Holiday Name', 'holidify-my-website' ); ?></th>
                    <th><?php esc_html_e( 'Greeting', 'holidify-my-website' ); ?></th>
                    <th><?php esc_html_e( 'Start Date', 'holidify-my-website' ); ?></th>
                    <th><?php esc_html_e( 'End Date', 'holidify-my-website' ); ?></th>
                    <th><?php esc_html_e( 'Icons', 'holidify-my-website' ); ?></th>
                    <th><?php esc_html_e( 'Animation', 'holidify-my-website' ); ?></th>
                    <th><?php esc_html_e( 'Actions', 'holidify-my-website' ); ?></th>
                </tr>
            </thead>

            <tbody id="holidify-table-body">
                <?php if ( ! empty( $holidays ) ) : ?>
                    <?php foreach ( $holidays as $id => $holiday ) : ?>
                        <tr data-holidify-id="<?php echo esc_attr( $id ); ?>">

                            <td>
                                <input
                                    type="text"
                                    class="regular-text holidify-name"
                                    value="<?php echo esc_attr( $holiday['name'] ?? '' ); ?>"
                                />
                            </td>

                            <td>
                                <input
                                    type="text"
                                    class="regular-text holidify-greeting"
                                    value="<?php echo esc_attr( $holiday['greeting'] ?? '' ); ?>"
                                    placeholder="<?php esc_attr_e( 'e.g. Merry Christmas 🎄', 'holidify-my-website' ); ?>"
                                />
                            </td>

                            <td>
                                <input
                                    type="date"
                                    class="small-text holidify-start-date"
                                    value="<?php echo esc_attr( $holiday['start_date'] ?? '' ); ?>"
                                />
                            </td>

                            <td>
                                <input
                                    type="date"
                                    class="small-text holidify-end-date"
                                    value="<?php echo esc_attr( $holiday['end_date'] ?? '' ); ?>"
                                />
                            </td>

                            <td class="holidify-icons-cell">
                                <?php
                                $icons = $holiday['icons'] ?? array( '', '', '' );
                                $icons = array_pad( $icons, 3, '' );
                                ?>
                                <div class="holidify-icon-inputs">
                                    <input type="text" class="small-text holidify-icon" maxlength="4" value="<?php echo esc_attr( $icons[0] ); ?>" />
                                    <input type="text" class="small-text holidify-icon" maxlength="4" value="<?php echo esc_attr( $icons[1] ); ?>" />
                                    <input type="text" class="small-text holidify-icon" maxlength="4" value="<?php echo esc_attr( $icons[2] ); ?>" />
                                    <button type="button" class="button button-secondary holidify-icon-picker-btn">
                                        <?php esc_html_e( 'Icon Picker', 'holidify-my-website' ); ?>
                                    </button>
                                </div>
                            </td>

                            <td>
                                <?php
                                $animation  = $holiday['animation'] ?? 'snowflakes';
                                $animations = array(
                                    'none'       => __( 'None', 'holidify-my-website' ),
                                    'snowflakes' => __( 'Snowflakes', 'holidify-my-website' ),
                                    'bats'       => __( 'Bats', 'holidify-my-website' ),
                                    'leaves'     => __( 'Falling Leaves', 'holidify-my-website' ),
                                    'confetti'   => __( 'Confetti', 'holidify-my-website' ),
                                    'hearts'     => __( 'Floating Hearts', 'holidify-my-website' ),
                                    'fireworks'  => __( 'Fireworks', 'holidify-my-website' ),
                                    'maple'      => __( 'Maple Leaves', 'holidify-my-website' ),
                                    'eggs'       => __( 'Bouncing Eggs', 'holidify-my-website' ),
                                );
                                ?>
                                <select class="holidify-animation-select">
                                    <?php foreach ( $animations as $key => $label ) : ?>
                                        <option value="<?php echo esc_attr( $key ); ?>" <?php selected( $animation, $key ); ?>>
                                            <?php echo esc_html( $label ); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </td>

                            <td>
                                <button type="button" class="button button-primary holidify-save-holiday">
                                    <?php esc_html_e( 'Save', 'holidify-my-website' ); ?>
                                </button>

                                <?php $defaults = holidify_get_default_holidays(); ?>
                                <?php if ( ! isset( $defaults[ $id ] ) ) : ?>
                                    <button type="button" class="button button-link-delete holidify-delete-holiday">
                                        <?php esc_html_e( 'Delete', 'holidify-my-website' ); ?>
                                    </button>
                                <?php endif; ?>
                            </td>

                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr class="no-items">
                        <td colspan="7"><?php esc_html_e( 'No holidays found.', 'holidify-my-website' ); ?></td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <p>
            <button type="button" class="button button-secondary" id="holidify-add-new-holiday">
                <?php esc_html_e( 'Add New Holiday', 'holidify-my-website' ); ?>
            </button>
        </p>

        <!-- Icon Picker Modal -->
        <div id="holidify-icon-modal" class="holidify-modal" style="display:none;">
            <div class="holidify-modal-backdrop"></div>
            <div class="holidify-modal-dialog" role="dialog" aria-modal="true" aria-labelledby="holidify-modal-title">
                <div class="holidify-modal-header">
                    <h2 id="holidify-modal-title"><?php esc_html_e( 'Choose an Icon', 'holidify-my-website' ); ?></h2>
                    <button type="button" class="holidify-modal-close">&times;</button>
                </div>
                <div class="holidify-modal-body">
                    <div class="holidify-icon-grid">
                        <?php foreach ( $available_icons as $icon ) : ?>
                            <button type="button" class="holidify-icon-option">
                                <?php echo esc_html( $icon ); ?>
                            </button>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="holidify-modal-footer">
                    <button type="button" class="button holidify-modal-cancel">
                        <?php esc_html_e( 'Cancel', 'holidify-my-website' ); ?>
                    </button>
                </div>
            </div>
        </div>

    </div>
    <?php
}

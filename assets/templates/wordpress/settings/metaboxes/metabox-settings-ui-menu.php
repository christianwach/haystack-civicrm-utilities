<?php
/**
 * Modify Shortcuts Menu settings template.
 *
 * Handles markup for the Active Custom Post Types meta box.
 *
 * @package Haystack_CU
 * @since 1.0.0
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

?>
<!-- <?php echo esc_html( $this->metabox_path ); ?>metabox-settings-ui-menu.php -->
<table class="form-table">
	<tr>
		<th scope="row"><?php esc_html_e( 'Modify Shortcuts Menu', 'haystack-civicrm-utilities' ); ?></th>
		<td>
			<select class="settings-select" name="<?php echo esc_attr( $this->key_menu_enabled ); ?>" id="<?php echo esc_attr( $this->key_menu_enabled ); ?>">
				<option value="no" <?php selected( $menu_enabled, 'no' ); ?>><?php esc_html_e( 'No', 'haystack-civicrm-utilities' ); ?></option>
				<option value="yes" <?php selected( $menu_enabled, 'yes' ); ?>><?php esc_html_e( 'Yes', 'haystack-civicrm-utilities' ); ?></option>
			</select>
			<p class="description"><?php esc_html_e( 'Replaces dashboard links with ones more useful for developers.', 'haystack-admin-utilities' ); ?></p>
		</td>
	</tr>
</table>
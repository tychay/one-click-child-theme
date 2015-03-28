<?php
/**
 * Page for showing the child theme creation form. (In the Theme > Child Theme submenu.)
 *
 * Local Variables
 * - $this/self: the OCCT object
 * - $parent_theme_name: the human-readable name of the parent theme
 * - $theme_name: form variable (if error)
 * - $description: form variable (if error)
 * - $author: form variable
 * @author terry chay <tychay@php.net>
 */
?>
<div class="wrap">

	<h2><?php esc_html_e('Create a Child Theme', self::_SLUG) ?></h2>

	<div class="copy"><?php printf( __( 'Fill out this form to create a child theme based on %s (your current theme).', self::_SLUG ), $parent_theme_name ); ?></div>

	<form action="admin-post.php" method="post" id="create_child_form">
		<input type="hidden" name="action" value="<?php echo $this->_createChildFormId; ?>" />
		<?php wp_nonce_field($this->_createChildFormId.'-verify'); ?>
		<table class="form-table">
			<tr>
				<th scope="row"><label for="create_child_theme_name"><?php esc_html_e( 'Theme Name', self::_SLUG ); ?></label></th>
				<td>
					<input type="text" name="theme_name" value="<?php esc_attr_e($theme_name); ?>" id="create_child_theme_name" />
					<p class="description"><?php esc_html_e( 'Give your theme a name.', self::_SLUG ); ?>
				</td>
			</tr>
			<tr>
				<th scope="row"><label for="create_child_description"><?php esc_html_e( 'Description', 'one-click-child-theme' ); ?></label></th>
				<td>
					<textarea name="description" value="<?php echo $description; ?>" rows="2" cols="40" id="create_child_description"><?php esc_html_e($description); ?></textarea>
					<p class="description"><?php esc_html_e( 'Describe your theme.', self::_SLUG ); ?>
				</td>
			</tr>
			<tr>
				<th scope="row"><label for="create_child_author_name"><?php esc_html_e( 'Author', self::_SLUG ); ?></label></th>
				<td>
					<input name="author_name" value="<?php esc_attr_e($author); ?>" id="create_child_author_name" />
					<p class="description"><?php esc_html_e( 'Your name.', self::_SLUG ); ?>
				</td>
			</tr>
		</table>
		<p class="submit">
			<input type="submit" class="button button-primary" value="<?php esc_attr_e( 'Create Child', self::_SLUG ); ?>" />
		</p>
	</form>
</div>

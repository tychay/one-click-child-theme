<?php
/**
 * Page for showing the child theme creation form. (In the Theme > Child Theme submenu.)
 *
 * @author terry chay <tychay@php.net>
 */

settings_errors();
?>
<div class="wrap">
	<h2><?php _e('Already a child theme','one-click-child-theme') ?></h2>
<?php
if ( $child_needs_repair ) :
?>
	<h3><?php _e('Child theme needs repair', 'one-click-child-theme') ?></h3>
	<div class="copy"><?php _e( 'Detected outdated child theme mechanism. Click the button below to attempt a one-click repair.', 'one-click-child-theme' ); ?></div>
	<form action="<?php echo admin_url( 'themes.php?page=one-click-child-theme-page' ); ?>" method="post" id="repair_child_form">
		<input type="hidden" name="cmd" value="repair_child_theme" />
		<p class="submit">
			<input type="submit" class="button button-primary" value="<?php _e( 'Repair Child Theme', 'one-click-child-theme' ); ?>" />
		</p>
	</form>
<?php
endif;
?>
	<h3><?php _e('Grandchild theme?','one-click-child-theme') ?></h3>
	<div class="copy"><?php _e( 'WordPress has no formal support for theme grandchildren. No other actions currently supported in One Click Child Theme.', 'one-click-child-theme' ); ?></div>

</div>
<?php
return;
?>

	<form action="<?php echo admin_url( 'themes.php?page=one-click-child-theme-page' ); ?>" method="post" id="create_child_form">
		<input type="hidden" name="cmd" value="create_child_theme" />
		<table class="form-table">
			<tr>
				<th scope="row"><label for="create_child_theme_name"><?php _e( 'Theme Name', 'one-click-child-theme' ); ?></label></th>
				<td>
					<input type="text" name="theme_name" value="<?php echo $theme_name; ?>" id="create_child_theme_name" />
					<p class="description"><?php _e( 'Give your theme a name.', 'one-click-child-theme' ); ?>
				</td>
			</tr>
			<tr>
				<th scope="row"><label for="create_child_description"><?php _e( 'Description', 'one-click-child-theme' ); ?></label></th>
				<td>
					<textarea name="description" value="<?php echo $description; ?>" rows="2" cols="40" id="create_child_description"></textarea>
					<p class="description"><?php _e( 'Describe your theme.', 'one-click-child-theme' ); ?>
				</td>
			</tr>
			<tr>
				<th scope="row"><label for="create_child_author_name"><?php _e( 'Author', 'one-click-child-theme' ); ?></label></th>
				<td>
					<input name="author_name" value="<?php echo $author; ?>" id="create_child_author_name" />
					<p class="description"><?php _e( 'Your name.', 'one-click-child-theme' ); ?>
				</td>
			</tr>
		</table>
		<p class="submit">
			<input type="submit" class="button button-primary" value="<?php _e( 'Create Child', 'one-click-child-theme' ); ?>" />
		</p>
	</form>


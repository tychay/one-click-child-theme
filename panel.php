<?php
/**
 * Page for showing the child theme creation form. (In the Theme > Child Theme submenu.)
 *
 * @author terry chay <tychay@php.net>
 */

settings_errors();
?>
<div class="wrap">

	<h2><?php _e('Create a Child Theme','one-click-child-theme') ?></h2>

	<div class="copy"><?php printf( __( 'Fill out this form to create a child theme based on %s (your current theme).', 'one-click-child-theme' ), $parent_theme_name ); ?></div>
<?php
if ( !empty( $error ) ) :
?>
	<div class="error"><?php echo $error; ?></div>
<?php
endif;
?>

	<form action="<?php echo admin_url( 'themes.php?page=one-click-child-theme-page' ); ?>" method="post" id="create_child_form">
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
</div>


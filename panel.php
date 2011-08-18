<?php
/**
 * Page for showing the child theme creation form. (In the Theme > Child Theme submenu.)
 *
 * @author terry chay <tychay@php.net>
 */
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
		<p>
			<label>
				<?php _e( 'Give your theme a name:', 'one-click-child-theme' ) ?>
				<input type="text" name="theme_name" value="<?php echo $theme_name; ?>" />
			</label>
		</p>
		<p>
			<label>
				<?php _e( 'Describe your theme:', 'one-click-child-theme' ) ?><br />
				<textarea name="description" value="<?php echo $description; ?>" rows="2" cols="40"/></textarea>
			</label>
		</p>
		<p>
			<label>
				<?php _e( 'Your Name:', 'one-click-child-theme' ) ?>
				<input name="author_name" value="<?php echo $author; ?>" />
			</label>
		</p>
		<p class="submit">
			<input type="submit" class="button-primary" value="<?php _e( 'Create Child', 'one-click-child-theme' ); ?>" />
		</p>
	</form>
</div>


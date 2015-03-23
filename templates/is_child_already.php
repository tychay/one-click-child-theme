<?php
/**
 * Page for showing the child theme creation form. (In the Theme > Child Theme submenu.)
 *
 * @author terry chay <tychay@php.net>
 */

settings_errors();
?>
<div class="wrap">
	<h2><?php printf( __('%s is already a child theme','one-click-child-theme'), $current_theme->Name ); ?></h2>
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
if ( !empty($template_files) ) :
?>
	<h3><?php _e('Child files','one-click-child-theme') ?></h3>
	<div class="copy"><?php _e( 'If you wish to modify the behavior of a template file, select it and click the "Copy Template" button below.', 'one-click-child-theme' ); ?></div>
	<form action="<?php echo admin_url( 'themes.php?page=one-click-child-theme-page' ); ?>" method="post" id="copy_template_file_form">
		<input type="hidden" name="cmd" value="copy_template_file" />
		<table class="form-table">
			<tr>
				<th scope="row"><label for="copy_template_file_name"><?php _e( 'Template File', 'one-click-child-theme' ); ?></label></th>
				<td><select name="filename" id="copy_template_file_name">
<?php
	foreach ($template_files as $filename) :
?>
					<option value="<?php esc_attr_e($filename); ?>"><?php esc_html_e($filename) ?></option>
<?php
	endforeach;
?>
				</select></td>
			</tr>
		</table>
		<p class="submit">
			<input type="submit" class="button button-primary" value="<?php _e( 'Copy Template', 'one-click-child-theme' ); ?>" />
		</p>
	</form>
<?php
endif;
?>
	<h3><?php _e('Grandchild theme?','one-click-child-theme') ?></h3>
	<div class="copy"><?php _e( 'WordPress has no formal support for theme grandchildren. No other actions currently supported in One Click Child Theme.', 'one-click-child-theme' ); ?></div>
</div>

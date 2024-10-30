<div class="wrap">
	<h2><?php _e('Inspirational Quote Rotator') ?></h2>
	<?php if(isset($_GET['action'])&&trim($_GET['action'])=='edit'): ?>
	<form action="" method="post">
		<h3 class="title"><?php _e('Add/Edit Quote') ?></h3>
		<?php settings_fields('iqr-settings') ?>
		<table class="form-table">
			<tbody>
				<tr>
					<th><label for="_iqr_title"><?php _e('Title') ?>:</label></th>
					<td>
						<input name="_iqr_title" id="_iqr_title" class="regular-text code" value="<?php echo $current_title ?>">
					</td>
				</tr>
				<tr>
					<th><label for="_iqr_title"><?php _e('Quote Contents') ?>:</label></th>
					<td>
						<?php wp_editor(htmlspecialchars_decode(stripslashes($current_contents),ENT_NOQUOTES),'_iqr_contents',array('teeny'=>true,'textarea_rows'=>15,'tabindex'=>1)); ?>
					</td>
				</tr>
			</tbody>
		</table>
		<p class="submit">
			<input type="submit" name="submit" id="submit" class="button button-primary" value="<?php _e('Save Quote') ?>">
			<a href="<?php echo admin_url('options-general.php?page=iqr-settings') ?>" class="button button-default"><?php _e('Cancel') ?></a>
		</p>
	</form>
	<?php else: ?>
		<style>
		.manage-column.column-title{
			width:25%;
		}
		</style>
		<?php echo $table->display() ?>
	<?php endif; ?>
</div>
<?php if(isset($this->_quote->contents)): ?>
	<?php add_thickbox(); ?>
	<a id="inspirational-quote-rotator-call" title="<?php echo $this->_quote->title ?>" href="#TB_inline?width=600&height=550&inlineId=inspirational-quote-rotator" class="thickbox">
		<div id="inspirational-quote-rotator" style="display:none;">
			<?php echo htmlspecialchars_decode(stripslashes($this->_quote->contents),ENT_NOQUOTES) ?>
		</div>
	</a>
<?php endif; ?>
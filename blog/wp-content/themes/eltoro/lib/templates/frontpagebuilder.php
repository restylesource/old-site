<div id="frontpage_builder">
	<div id="invisible-fixed-point"></div>
	<div id="the-closet" style="display:none">
		<?php
			$template = new FrontpageElement( array() );
			$template -> render_backend();
		?>
	</div>

	<div id="add_fp_element" class="add_fpb fpb button">
		<a href="javascript:void(0);" class="fpb_icon">&nbsp;</a>
		<a href="javascript:void(0);" class="fpb_label">
			<?php echo __( 'Add element' , 'cosmotheme' );?>
		</a>
	</div>

	<div id="elements-container">
		<?php
			foreach( $this -> elements as $element ){
				$element -> render_backend();
			}
		?>
	</div>

	<div id="element-builder-shadow"></div>
</div>
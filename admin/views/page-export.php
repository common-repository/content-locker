<div class="wrap mts-wrap">

	<h2 class="mb0"><?php esc_html_e( 'Exporting Leads', 'content-locker' ) ?></h2>
	<p class="mt0">
		<?php esc_html_e( 'Select leads you would like to export and click the button "Export Leads".', 'content-locker' ) ?>
	</p>

	<?php if( isset( $this->message ) ) {
		echo $this->message;
	} ?>

	<div style="width: 800px" class="mt40">
		<?php echo cmb2_get_metabox_form( $cmb, 'cl-export-plz', array( 'save_button' => __( 'Export Leads', 'content-locker' ) ) ) ?>
	</div>
</div>

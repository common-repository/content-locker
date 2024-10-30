<div class="mts-cl-terms">

	<div class="mts-cl-terms-wrapper-inner">
	<?php

		$links = array();
		if( $terms ) {
			$links[] = sprintf( '<a target="_blank" href="%2$s" class="mts-cl-link">%1$s</a>', $label_terms, $terms );
		}
		if( $policy ) {
			$links[] = sprintf( '<a target="_blank" href="%2$s" class="mts-cl-link">%1$s</a>', $label_policy, $policy );
		}

		echo str_replace( '{links}', join( ', ', $links ), $label_agree_with );
	?>
	</div>

</div>

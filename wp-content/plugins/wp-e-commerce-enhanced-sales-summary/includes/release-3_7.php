<?php
if( is_admin() ) {

	/* Start of: WordPress Administration */

	if( !function_exists( 'wpsc_currency_display' ) ) {

		function wpsc_currency_display( $price, $args = null ) {

			$output = nzshpcrt_currency_display( $price, true );
			return $output;

		}

	}

	/* End of: WordPress Administration */

}
?>
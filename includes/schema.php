
/**
 * Shell Attr
 * 
 * @since 0.2.0
 */
function shell_attr( $slug, $context = '', $attr = array() ){

	/* Get action/filter hook prefix. */
	$prefix = hybrid_get_prefix();

	/* Output */
	$out    = '';

	/* body */
	if ( 'body' == $slug ){
		$attr['class']     = join( ' ', hybrid_get_body_class() );
		$attr['dir']       = is_rtl() ? 'rtl' : 'ltr';
		$attr['itemscope'] = 'itemscope';
		$attr['itemtype']  = 'http://schema.org/WebPage';
	}
	
	if ( 'header' == $slug ){
		$attr['id']        = 'header';
		$attr['role']      = 'banner';
		$attr['itemscope'] = 'itemscope';
		$attr['itemtype']  = 'http://schema.org/WPHeader';
	}
	
	$attr  = apply_filters( "{$prefix}_attr_{$slug}", $attr, $context );

	foreach ( $attr as $name => $value ){
		$out .= !empty( $value ) ? sprintf( ' %s="%s"', esc_html( $name ), esc_attr( $value ) ) : esc_html( " {$name}" );
	}

	return trim( $out );
}

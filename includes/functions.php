<?php

function sln_filter($val, $filter = null){
    if(empty($filter)){
        return $val;
    }
    if($filter == 'int'){
        return intval($filter);
    }elseif($filter == 'float'){
        return floatval(str_replace(',','.',$val));
    }else{
        return $val;
    }
}

function sln_get_booking_price( $post_id = '' ) {
	if ( empty( $post_id ) )
		$post_id = get_the_ID();
	$price = apply_filters( 'sln_booking_price', get_post_meta( $post_id, '_sln_booking_price', true ) );
	$price = !empty( $price ) ? floatval( $price ) : '';
	return $price;
}

function sln_get_service_price( $post_id = '' ) {
	if ( empty( $post_id ) )
		$post_id = get_the_ID();
	$ret = apply_filters( 'sln_service_price', get_post_meta( $post_id, '_sln_service_price', true ) );
	$ret = !empty( $ret ) ? floatval( $ret) : '';
	return $ret;
}


function sln_get_service_unit( $post_id = '' ) {
	if ( empty( $post_id ) )
		$post_id = get_the_ID();
	$ret = apply_filters( 'sln_service_unit', get_post_meta( $post_id, '_sln_service_unit', true ) );
	$ret = !empty( $ret ) ? floatval( $ret ) : '';
	return $ret;
}

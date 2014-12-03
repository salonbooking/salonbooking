<?php

function sln_filter($val, $filter = null){
    if(empty($filter)){
        return $val;
    }
    if($filter == 'int'){
        return intval($filter);
    }elseif($filter == 'float'){
        return floatval(str_replace(',','.',$val));
    }elseif($filter == 'time'){
        if(empty($val)) return null;
        if(strpos($val,':') === false)
            $val .= ':00';
        return date('H:i', strtotime('1970-01-01 '.$val));
    }elseif($filter == 'date'){
        return date('Y-m-d', strtotime($val));
    }elseif($filter == 'bool'){
        return $val ? true : false;
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
function sln_get_service_secondary( $post_id = '' ) {
	if ( empty( $post_id ) )
		$post_id = get_the_ID();
	$ret = apply_filters( 'sln_service_secondary', get_post_meta( $post_id, '_sln_service_secondary', true ) );
	$ret = empty( $ret ) ? false : ($ret ? true : false);
	return $ret;
}
function sln_get_service_notav( $post_id = '', $key ) {
	if ( empty( $post_id ) )
		$post_id = get_the_ID();
	$ret = apply_filters( 'sln_service_notav_'.$key, get_post_meta( $post_id, '_sln_service_notav_'.$key, true ) );
	$ret = empty( $ret ) ? false : ($ret ? true : false);
	return $ret;
}

function sln_get_service_notav_time( $post_id = '', $key ) {
	if ( empty( $post_id ) )
		$post_id = get_the_ID();
	$ret = apply_filters( 'sln_service_notav_'.$key, get_post_meta( $post_id, '_sln_service_notav_'.$key, true ) );
	$ret = sln_filter($ret,'time');
	return $ret;
}

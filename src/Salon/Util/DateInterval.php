<?php

namespace Salon\Util;

class DateInterval {
	private $from;
	private $to;

	public function __construct( Date $from = null, Date $to = null ) {
		$this->from = $from;
		$this->to   = $to;
	}

	public function containsDate( Date $date ) {
		if ( $this->from && ( $this->from->isGt( $date ) ) ) {
			return false;
		}
		if ( $this->to && $this->to->isLt( $date ) ) {
			return false;
		}

		return true;
	}

	public function isAlways() {
		return empty( $this->from ) && empty( $this->to );
	}
}
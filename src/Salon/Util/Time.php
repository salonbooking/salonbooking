<?php

namespace Salon\Util;

use SLN_Func;
use SLN_Plugin;

class Time {
	private $time;

	public function __construct( $str ) {
		if ( is_int( $str ) ) {
			$h          = floor( $str / 60 );
			$this->time = SLN_Func::zerofill( $h ) . ':' . SLN_Func::zerofill( $str % 60 );
		} else {
			$this->time = $str;
			if ( empty( $this->time ) ) {
				$this->time = '00:00';
			}
		}
		if ( ! strpos( $this->time, ':' ) ) {
			throw new \Exception( 'bad time value' . $this->time );
		}
	}

	public function __toString() {
		return (string) $this->time;
	}

	/**
	 * @return \DateTime
	 */
	public function toDateTime() {
		return new \DateTime( '1970-01-01 ' . $this->toString() );
	}

	/**
	 * @return bool
	 */
	public function isMidnight() {
		return $this->toString() == '00:00';
	}

	/**
	 * @param Time $t
	 *
	 * @return bool
	 */
	public function isLt( Time $t ) {
		return $this->toInt() < $t->toInt();
	}

	/**
	 * @param Time $t
	 *
	 * @return bool
	 */
	public function isGt( Time $t ) {
		return $this->toInt() > $t->toInt();
	}

	/**
	 * @param Time $t
	 *
	 * @return bool
	 */
	public function isLte( Time $t ) {
		return $this->toInt() <= $t->toInt();
	}

	/**
	 * @param Time $t
	 *
	 * @return bool
	 */
	public function isGte( Time $t ) {
		return $this->toInt() >= $t->toInt();
	}

	/**
	 * @param Time $t
	 *
	 * @return bool
	 */
	public function isEq( Time $t ) {
		return $this->toInt() == $t->toInt();
	}

	/**
	 * @return int
	 */
	public function toInt() {
		return intval( str_replace( ':', '', $this->time ) );
	}

	/**
	 * @return int
	 */
	public function toMinutes() {
		$x = explode( ':', $this->time );

		return ( $x[0] * 60 ) + $x[1];
	}

	/**
	 * @return string
	 */
	public function toString() {
		return $this->__toString();
	}

	/**
	 * @param int|Time|null $interval
	 *
	 * @return Time
	 */
	public function add( $interval ) {
		return self::increment( $this, $interval, false );
	}

	/**
	 * @param int|Time|null $interval
	 *
	 * @return Time
	 */
	public function sub( $interval ) {
		return self::increment( $this, $interval, true );
	}


	/**
	 * @param Time          $time
	 * @param int|Time|null $interval
	 * @param bool          $negative
	 *
	 * @return Time
	 */
	public static function increment( Time $time, $interval = null, $negative = false ) {
		$interval = self::bindInterval( $interval );
		if ( $interval == 0 ) {
			return $time;
		}
		$m = $negative ? ( $time->toMinutes() - $interval ) : ( $time->toMinutes() + $interval );
		$h = floor( $m / 60 );

		return new Time( SLN_Func::zerofill( $h ) . ':' . SLN_Func::zerofill( $m % 60 ) );
	}

	/**
	 * @param int|Time|null $interval
	 *
	 * @return int
	 */
	private static function bindInterval( $interval = null ) {
		if ( $interval === null ) {
			$interval = SLN_Plugin::getInstance()->getSettings()->getInterval();
		} elseif ( $interval instanceof Time ) {
			$interval = $interval->toMinutes();
		} elseif ( $interval instanceof \DateTime ) {
			$interval = Time::create( $interval )->toMinutes();
		}

		return (int) $interval;
	}

	/**
	 * @param      $times
	 * @param Time $duration
	 *
	 * @return mixed
	 */
	public static function filterTimesArrayByDuration( $times, Time $duration ) {
		foreach ( $times as $k => $t ) {
			$t = $t instanceof Time ? $t : Time::create( $t );
			if ( ! self::checkTimeDuration( $times, $t, $duration ) ) {
				unset( $times[ $k ] );
			}
		}

		return $times;
	}

	/**
	 * @param      $times
	 * @param Time $time
	 * @param Time $duration
	 *
	 * @return bool
	 */
	public static function checkTimeDuration( $times, Time $time, Time $duration ) {
		$end = Time::increment( $time, $duration );
		do {
			if ( ! isset( $times[ (string) $time ] ) ) {
				return false;
			}
			$time = Time::increment( $time );
		} while ( $time->isLt( $end ) );

		return true;
	}

	/**
	 * @param $time
	 *
	 * @return Time
	 */
	public static function create( $time ) {
		if ( $time instanceof Time ) {
			$ret = $time;
		} elseif ( $time instanceof \DateTime ) {
			$ret = new Time( $time->format( 'H:i' ) );
		} else {
			$ret = new Time( $time );
		}

		return $ret;
	}
}

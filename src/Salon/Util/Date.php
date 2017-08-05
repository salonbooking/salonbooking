<?php

namespace Salon\Util;

class Date {
	/** @var  \DateTime */
	private $dateTime;

	public function __construct( $date ) {
		if ( $date instanceof \DateTime ) {
			$date = clone $date;
		} elseif(is_string($date)) {
			$date = new \DateTime( $date );
		} elseif($date instanceof self) {
			$date = $date->dateTime;
		}else{
			throw new \Exception('bad object for date '.get_class($date));
		}
		$this->dateTime = $date;
		$this->dateTime->setTime( 0, 0 );
	}

	public function getDateTime() {
		return clone $this->dateTime;
	}

	/**
	 * @return int
	 */
	public function getWeekday() {
		return intval($this->dateTime->format( "w" ));
	}

	public function isGt( Date $date ) {
		return $this->dateTime > $date->dateTime;
	}

	public function isLt( Date $date ) {
		return $this->dateTime < $date->dateTime;
	}

	public function isLte( Date $date ) {
		return $this->dateTime <= $date->dateTime;
	}

	public function isEq( Date $date ) {
		return $this->dateTime == $date->dateTime;
	}

	public function toString(){
		return $this->dateTime->format( 'Y-m-d' );
	}

	/**
	 * @return Date
	 */
	public function getPrevDate() {
		return new self( $this->getDateTime()->modify( '-1 day' ) );
	}

	/**
	 * @return Date
	 */
	public function getNextDate() {
		return new self( $this->getDateTime()->modify( '+1 day' ) );
	}

	/**
	 * @param $date
	 *
	 * @return Date
	 */
	public static function create( $date ) {
		return new Date( $date );
	}
}
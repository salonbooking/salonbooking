<?php

use Salon\Util\Date;
use Salon\Util\DateInterval;
use Salon\Util\Time;
use Salon\Util\TimeInterval;

class SLN_Helper_AvailabilityItem
{
    private $data;
    /** @var TimeInterval[] */
    private $times = array();
	/** @var DateInterval */
    private $period;


	function __construct( $data ) {
		$this->data = $data;
		if ( $data ) {
			for ( $i = 0; $i <= 1; $i ++ ) {
				if ( $data['from'][ $i ] != '00:00' ) {
//					if ( $data['to'][ $i ] == '24:00' ) {
//						$data['to'][ $i ] = '23:59';
//					}
					$this->times[] = new TimeInterval(
						new Time( $data['from'][ $i ] ),
						new Time( $data['to'][ $i ] )
					);
				}
			}
			$from         = isset( $data['from_date'] ) ? new Date( $data['from_date'] ) : null;
			$to           = isset( $data['to_date'] ) ? new Date( $data['to_date'] ) : null;
			$this->period = new DateInterval( $from, $to );
		}else{
			$this->period = new DateInterval();
		}
		if ( empty( $this->times ) ) {
			$this->times[] = new TimeInterval(
				new Time( '00:00' ),
				new Time( '24:00' )
			);
		}
	}

    /**
     * @param $date
     * @return bool
     */
    public function isValidDate(Date $date)
    {
        return $this->isValidDayOfPeriod($date) && $this->isValidDayOfWeek($date);
    }

    public function isAlwaysOn()
    {
        return $this->period->isAlways();
    }

    /**
     * @param $date
     * @return bool
     */
    public function isValidDayOfPeriod(Date $date)
    {
    	return $this->period->containsDate($date);
    }

    /**
     * @param $date
     * @return bool
     */
    private function isValidDayOfWeek(Date $date)
    {
        return isset($this->data['days']) && isset( $this->data['days'][ $date->getWeekday() + 1 ] );
    }

    /**
     * @param Time $time
     * @return bool
     */
    public function isValidTime(Time $time)
    {
        //#SBP-470
//        $time2 = $time->isMidnight() ? new Time('23:59') : null;
        foreach ($this->times as $t) {
            if ($t->containsTime($time)) {
                return true;
            }
        }
        return false;
        //#SBP-470
//        return $time2 ? $this->isValidTime($time2) : false;
    }

    /**
     * @param TimeInterval $interval
     * @return bool
     */
    public function isValidTimeInterval(TimeInterval $interval)
    {
        foreach ($this->times as $t) {
            if ($t->containsInterval($interval)) {
                return true;
            }
        }
        return false;
    }

    public function getData()
    {
        return $this->data;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        $days = SLN_Func::getDays();
        $ret  = array();
        if (isset($this->data['days'])) {
            foreach ($this->data['days'] as $d => $v) {
                $ret[] = $days[$d];
            }
        }
        $allDays = count($ret) == 7;
        $ret     = $allDays ? null : implode('-', $ret);
        $format  = SLN_Plugin::getInstance()->format();
	    foreach ( $this->times as $t ) {
		    if ( ! ( $t->isAlways() || $t->isNever() ) ) {
			    $ret .= sprintf(
				    ' %s/%s',
				    $format->time( $t->getFrom() ),
				    $format->time( $t->getTo() )
			    );
		    }
	    }
        if (empty($ret)) {
            $ret = __('Always', 'salon-booking-system');
        }
        if ($allDays) {
            $ret = __('All days', 'salon-booking-system').$ret;
        }

        return $ret;
    }

    /**
     * @return TimeInterval[]
     */
    public function getTimes(){
        return $this->times;
    }
}

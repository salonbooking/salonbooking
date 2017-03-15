<?php

class SLN_Action_Ajax_Calendar extends SLN_Action_Ajax_Abstract
{
    private $from;
    private $to;
    /** @var  SLN_Wrapper_Booking[] */
    private $bookings;

    public function execute()
    {
        $this->from = new SLN_DateTime(date("c", $_GET['from'] / 1000));
        $this->to = new SLN_DateTime(date("c", $_GET['to'] / 1000));
        $this->buildBookings();
        $ret = array(
            'success' => 1,
            'result' => array(
                'events' => $this->getResults(),
                'stats' => $this->getStats(),
            ),
        );

        return $ret;
    }

    private function getStats()
    {
        $bc = $this->plugin->getBookingCache();
        $clone = clone $this->from;
        $ret = array();
        while ($clone <= $this->to) {
            $tmp = array('text' => '', 'busy' => 0, 'free' => 0);
            $cache = $bc->getDay($clone);
            if ($cache && $cache['status'] == 'booking_rules') {
                $tmp['text'] = __('Booking Rule', 'salon-booking-system');
            } elseif ($cache && $cache['status'] == 'holiday_rules') {
                $tmp['text'] = __('Holiday Rule', 'salon-booking-system');
            } else {
                $tot = 0;
                $cnt = 0;
                foreach ($this->bookings as $b) {
                    if ($b->getDate()->format('Ymd') == $clone->format('Ymd')) {
                        if (!$b->hasStatus(
                            array(
                                SLN_Enum_BookingStatus::CANCELED,
//                                SLN_Enum_BookingStatus::PENDING,
//                                SLN_Enum_BookingStatus::PENDING_PAYMENT,
                            )
                        )
                        ) {
                            $tot += $b->getAmount();
                            $cnt++;
                        }
                    }
                }
                if (isset($cache['free_slots'])) {
                    $free = count($cache['free_slots']) * $this->plugin->getSettings()->getInterval();
                } else {
                    $free = 0;
                }
                if (isset($cache['busy_slots'])) {
                    $busy = count($cache['busy_slots']) * $this->plugin->getSettings()->getInterval();
                } elseif ($cache && $cache['status'] == 'full') {
                    $busy = 1;
                } else {
                    $busy = 0;
                }
                $freeH = intval($free / 60);
                $freeM = ($free % 60);
                $tot = $this->plugin->format()->money($tot,false);
                $tmp['text'] = '<div class="calbar-tooltip">'
                    ."<span><strong>$cnt</strong>".__('bookings', 'salon-booking-system')."</span>"
                    ."<span><strong>$tot</strong>".__('revenue', 'salon-booking-system')."</span>"
                    ."<span><strong>{$freeH}".__('hrs', 'salon-booking-system').' '
                    .($freeM > 0 ? "{$freeM}".__('mns', 'salon-booking-system') : '').'</strong>'
                    .__('available left', 'salon-booking-system').'</span></div>';
                if ($free || $busy) {
                    $tmp['free'] = intval(($free / ($free + $busy)) * 100);
                    $tmp['busy'] = 100 - $tmp['free'];
                }
            }
            $ret[$clone->format('Y-m-d')] = $tmp;
            $clone->modify('+1 days');
        }

        return $ret;
    }

    private function getResults()
    {
        $ret = array();
        foreach ($this->bookings as $b) {
            $ret[] = $this->wrapBooking($b);
        }

        return $ret;
    }

    private function buildBookings()
    {
        $this->bookings = $this->plugin
            ->getRepository(SLN_Plugin::POST_TYPE_BOOKING)
            ->get($this->getCriteria());
    }

    private function wrapBooking($booking)
    {
        $ret = array(
            "id" => $booking->getId(),
            "title" => $this->getTitle($booking),
            "customer" => $booking->getDisplayName(),
            "url" => get_edit_post_link($booking->getId()),
            "class" => "event-".SLN_Enum_BookingStatus::getColor($booking->getStatus()),
            "start" => $booking->getStartsAt('UTC')->format('U') * 1000,
            "end" => $booking->getEndsAt('UTC')->format('U') * 1000,
            "event_html" => $this->getEventHtml($booking),
        );

        return apply_filters('sln.action.ajaxcalendar.wrapBooking', $ret, $booking);
    }

    private function getCriteria()
    {
        $criteria = array();
        if ($this->from->format('Y-m-d') == $this->to->format('Y-m-d')) {
            $criteria['day'] = $this->from;
        } else {
            $criteria['day@min'] = $this->from;
            $criteria['day@max'] = $this->to;
        }
        $criteria = apply_filters('sln.action.ajaxcalendar.criteria', $criteria);

        return $criteria;
    }

    private function getTitle($booking)
    {
        return $this->plugin->loadView('admin/_calendar_title', compact('booking'));
    }

    private function getEventHtml($booking)
    {
        return $this->plugin->loadView('admin/_calendar_event', compact('booking'));
    }
}

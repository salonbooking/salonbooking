<?php

class SLN_Action_Ajax_Calendar extends SLN_Action_Ajax_Abstract
{
    private $from;
    private $to;

    public function execute()
    {
        $this->from = new SLN_DateTime(date("c", $_GET['from'] / 1000));
        $this->to   = new SLN_DateTime(date("c", $_GET['to'] / 1000));
        $events = $this->getResults();
        $ret        = array(
            'success' => 1,
            'result'  => array(
                'events' => $events,
                'stats' => $this->getStats($events)
            )
        );

        return $ret;
    }

    private function getStats($events){
        return array();
    }

    private function getResults()
    {
        $ret = array();
        foreach ($this->buildBookings() as $b) {
            $ret[] = $this->wrapBooking($b);
        }

        return $ret;
    }

    private function buildBookings()
    {
        return $this->plugin
            ->getRepository(SLN_Plugin::POST_TYPE_BOOKING)
            ->get($this->getCriteria());
    }

    private function wrapBooking($booking)
    {
        $ret = array(
            "id"         => $booking->getId(),
            "title"      => $this->getTitle($booking),
            "customer"   => $booking->getDisplayName(),
            "url"        => get_edit_post_link($booking->getId()),
            "class"      => "event-".SLN_Enum_BookingStatus::getColor($booking->getStatus()),
            "start"      => $booking->getStartsAt('UTC')->format('U') * 1000,
            "end"        => $booking->getEndsAt('UTC')->format('U') * 1000,
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

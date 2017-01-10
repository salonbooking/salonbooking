<?php

class SLN_Action_WeeklyReport
{
    const EMAIL = 'email';
    const SMS = 'sms';

    /** @var SLN_Plugin */
    private $plugin;
    private $mode;

    public function __construct(SLN_Plugin $plugin)
    {
        $this->plugin = $plugin;
    }

    public function executeSms()
    {
        $this->mode = self::SMS;

        $this->execute();
    }

    public function executeEmail()
    {
        $this->mode = self::EMAIL;

        $this->execute();
    }

    private function execute()
    {
        SLN_TimeFunc::startRealTimezone();

        $type = $this->mode;
        $p = $this->plugin;
        $p->addLog($type.' weekly report execution');
        $phone = $p->getSettings()->get('gen_phone');
        if (self::SMS === $type && empty($phone)) {
            $p->addLog($type.' salon phone field is empty');
        }
        else {
            $data = $this->getData();
            $this->send($data);
            $p->addLog($type.' weekly report sent');
        }
        $p->addLog($type.' weekly report execution ended');

        SLN_TimeFunc::endRealTimezone();
    }

    /**
     * @param array $data
     * @throws Exception
     */
    private function send($stats)
    {
        $p = $this->plugin;
        if (self::EMAIL == $this->mode) {
            $args = compact('stats');
            $p->sendMail('mail/weekly_report', $args);
        } else {
            throw new Exception();
        }
    }

    private function getData() {
        $p    = $this->plugin;
        $data = array(
            'total'         => array(
                'count'  => 0,
                'amount' => .0,
            ),
            'paid'          => array(
                'count'  => 0,
                'amount' => .0,
            ),
            'pay_later'     => array(
                'count'  => 0,
                'amount' => .0,
            ),
            'canceled'      => 0,
            'services'      => array(),
            'attendants'    => array(),
            'weekdays'      => array(),
            'new_customers' => 0,
            'customers'     => array(),
        );

        $timeBegin = strtotime('last Monday');
        $timeEnd   = current_time('timestamp');

        $bookings = $this->getBookings($timeBegin, $timeEnd);

        foreach($bookings as $booking) {
            if ($booking->getStatus() !== SLN_Enum_BookingStatus::CANCELED) {
                //START collect total statistics
                $data['total']['count'] ++;
                $data['total']['amount'] += $booking->getAmount();
                //END collect total statistics

                if ($booking->getStatus() === SLN_Enum_BookingStatus::PAID) {
                    $data['paid']['count'] ++;
                    $data['paid']['amount'] += $booking->getAmount();
                }
                elseif ($booking->getStatus() === SLN_Enum_BookingStatus::PAY_LATER) {
                    $data['pay_later']['count'] ++;
                    $data['pay_later']['amount'] += $booking->getAmount();
                }

                //START collect services statistics
                foreach($booking->getServices() as $service) {
                    if (!isset($data['services'][$service->getId()])) {
                        $data['services'][$service->getId()] = 0;
                    }
                    $data['services'][$service->getId()] ++;
                }
                //END collect services statistics

                //START collect attendants statistics
                if ($p->getSettings()->isAttendantsEnabled()) {
                    foreach($booking->getAttendants(true) as $attendant) {
                        if (!isset($data['attendants'][$attendant->getId()])) {
                            $data['attendants'][$attendant->getId()] = 0;
                        }
                        $data['attendants'][$attendant->getId()] ++;
                    }
                }
                //END collect attendants statistics

                //START collect weekdays statistics
                $weekday = (int)$booking->getStartsAt()->format('w');
                if (!isset($data['weekdays'][$weekday])) {
                    $data['weekdays'][$weekday] = 0;
                }
                $data['weekdays'][$weekday] ++;
                //END collect weekdays statistics

                //START collect customers statistics
                $userID = $booking->getUserId();
                if (SLN_Wrapper_Customer::isCustomer($userID)) {
                    if (!isset($data['customers'][$userID])) {
                        $data['customers'][$userID] = .0;
                    }
                    $data['customers'][$userID] += $booking->getAmount();
                }
                //END collect customers statistics
            }
            else {
                //START collect canceled statistics
                $data['canceled'] ++;
                //END collect canceled statistics
            }
        }

        arsort($data['services']);
        arsort($data['attendants']);
        arsort($data['weekdays']);
        arsort($data['customers']);

        $newCustomers          = $this->getCustomers($timeBegin, $timeEnd);
        $data['new_customers'] = count($newCustomers);

        return $data;
    }

    /**
     * @return SLN_Wrapper_Booking[]
     * @throws Exception
     */
    private function getBookings($timeBegin, $timeEnd)
    {
        $statuses = SLN_Enum_BookingStatus::toArray();
        unset($statuses[SLN_Enum_BookingStatus::ERROR], $statuses[SLN_Enum_BookingStatus::PENDING], $statuses[SLN_Enum_BookingStatus::PENDING_PAYMENT]);
        $statuses = array_keys($statuses);

        $args = array(
            'post_type'   => SLN_Plugin::POST_TYPE_BOOKING,
            'post_status' => $statuses,
            '@wp_query'   => array(
                'meta_query' => array(
                    array(
                        'key'     => '_sln_booking_date',
                        'value'   => date('Y-m-d', $timeBegin),
                        'compare' => '>=',
                    ),
                    array(
                        'key'     => '_sln_booking_date',
                        'value'   => date('Y-m-d', $timeEnd),
                        'compare' => '<=',
                    )
                ),
            ),
        );

        /** @var SLN_Repository_BookingRepository $repo */
        $repo = $this->plugin->getRepository(SLN_Plugin::POST_TYPE_BOOKING);
        $tmp = $repo->get($args);
        $ret = array();
        foreach ($tmp as $booking) {
            $startsAt  = $booking->getStartsAt();
            $startsAtT = $startsAt->getTimestamp();
            if ($startsAtT >= $timeBegin && $startsAtT <= $timeEnd) {
                $ret[] = $booking;
            }
        }

        return $ret;
    }

    /**
     * @return SLN_Wrapper_Customer[]
     * @throws Exception
     */
    private function getCustomers($timeBegin, $timeEnd)
    {
        $user_query  = new WP_User_Query(
            array(
                'role' => SLN_Plugin::USER_ROLE_CUSTOMER,
                'date_query' => array(
                    'after'  => date('Y-m-d H:i:s', $timeBegin),
                    'before' => date('Y-m-d H:i:s', $timeEnd),
                )
            )
        );

        $ret = array();
        foreach ($user_query->get_results() as $user) {
            $customer = new SLN_Wrapper_Customer($user);
            $ret[] = $customer;
        }

        return $ret;
    }
}

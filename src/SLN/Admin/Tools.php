<?php

class SLN_Admin_Tools extends SLN_Admin_AbstractPage
{

    const PAGE = 'salon-tools';
    const PRIORITY = 13;

    public function __construct(SLN_Plugin $plugin)
    {
        parent::__construct($plugin);
        add_action('admin_init', array($this, 'admin_init'));
    }

    public function admin_init()
    {
        if (isset($_POST) && $_POST) {
            $data = $_POST;
            if (isset($data['sln-tools-import'])) {
                $this->save_settings($data);
            } elseif (isset($data['sln-tools-export'])) {
                $this->export($data);
            }
        }
    }

    public function admin_menu()
    {
        $this->classicAdminMenu(__('Salon Tools', 'salon-booking-system'), __('Tools', 'salon-booking-system'));
    }

    public function show()
    {
        if (version_compare(phpversion(), "5.4.0", "<")) {
            $info = json_encode(get_option(SLN_Settings::KEY));
        } else {
            $info = json_encode(get_option(SLN_Settings::KEY), JSON_PRETTY_PRINT);
        }

        $current_version = $this->settings->getDbVersion();

        $versionToRollback = '';
        $rollbacks         = SLN_Action_Update::getDbRollbacks();
        krsort($rollbacks);
        foreach ($rollbacks as $version => $rollback) {
            if (version_compare($current_version, $version, '>=')) {
                if (preg_match('/sln-rollback-to-(\d+[\.\d+]*).php$/', $rollback, $matches)) {
                    $versionToRollback = $matches[1];
                    break;
                }
            }
        }

        echo $this->plugin->loadView(
            'admin/tools',
            array(
                'info'              => $info,
                'versionToRollback' => $versionToRollback,
                'currentVersion'    => $current_version,
                'isFree'            => defined('SLN_Plugin::F1'),
            )
        );
    }

    public function export($data)
    {
        $format = $this->plugin->format();

        $from = $data['export']['from'];
        $from = SLN_Func::filter($from, 'date').' 00:00:00';

        $to = $data['export']['to'];
        $to = SLN_Func::filter($to, 'date').' 23:59:59';

        $criteria['@wp_query'] = array(
            'post_type'  => SLN_Plugin::POST_TYPE_BOOKING,
            'nopaging'   => true,
            'meta_query' => array(
                array(
                    'key'     => '_sln_booking_date',
                    'value'   => array($from, $to),
                    'compare' => 'BETWEEN',
                ),
            ),
        );
        $criteria['@query']    = true;

        /** @var SLN_Repository_BookingRepository $repo */
        $repo     = $this->plugin->getRepository(SLN_Plugin::POST_TYPE_BOOKING);
        $bookings = $repo->get($criteria);

        usort($bookings, array($this, 'sortAscByStartsAt'));

        $tmpfname = tempnam("/tmp", "sln-export-");

        $fh = fopen($tmpfname, "w");
        fputcsv(
            $fh,
            array(
                __('Reservations', 'salon-booking-system'),
                __('from', 'salon-booking-system'),
                __('to', 'salon-booking-system'),
            )
        );
        fputcsv($fh, array('', $format->date($data['export']['from']), $format->date($data['export']['to'])));
        fputcsv($fh, array(''));
        fputcsv(
            $fh,
            array(
                __('ID', 'salon-booking-system'),
                __('DATE/TIME', 'salon-booking-system'),
                __('CREATED', 'salon-booking-system'),
                __('CUSTOMER NAME', 'salon-booking-system'),
                __('CUSTOMER EMAIL', 'salon-booking-system'),
                __('CUSTOMER PHONE', 'salon-booking-system'),
                __('SERVICES', 'salon-booking-system'),
                __('ASSISTANTS', 'salon-booking-system'),
                __('TOTAL PRICE', 'salon-booking-system'),
                __('STATUS', 'salon-booking-system'),
            )
        );

        foreach ($bookings as $booking) {
            $services   = $booking->getServices();
            $servicesAr = array();
            foreach ($services as $service) {
                $servicesAr[] = $service->getName();
            }

            $total = $format->money($booking->getAmount(), true, false, true, true);
            if (SLN_Enum_BookingStatus::PAID == $booking->getStatus() && $deposit = $booking->getDeposit()) {
                $total .= ' ('.$format->money($deposit, true, false, true, true).' '.
                          __('already paid as deposit', 'salon-booking-system').')';
            }

            fputcsv(
                $fh,
                array(
                    $booking->getId(),
                    $format->datetime($booking->getStartsAt()),
                    $format->datetime($booking->getPostDate()),
                    $booking->getDisplayName(),
                    $booking->getEmail(),
                    $booking->getPhone(),
                    implode(', ', $servicesAr),
                    $booking->getAttendantsString(),
                    $total,
                    SLN_Enum_BookingStatus::getLabel($booking->getStatus()),
                )
            );
        }
        fclose($fh);

        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Cache-Control: private", false);
        header("Content-Type: application/octet-stream");
        header("Content-Disposition: attachment; filename=\"export.csv\";");
        header("Content-Transfer-Encoding: binary");
        echo file_get_contents($tmpfname);

        unlink($tmpfname);
        exit;
    }

    /**
     * @@param SLN_Wrapper_Booking $a
     * @param SLN_Wrapper_Booking $b
     *
     * @return int
     */
    private function sortAscByStartsAt($a, $b)
    {
        return (strtotime($a->getStartsAt()->format('Y-m-d H:i:s')) > strtotime(
            $b->getStartsAt()->format('Y-m-d H:i:s')
        ) ? 1 : -1);
    }

    public function save_settings($data)
    {
        if ( ! isset($data['sln-tools-import'])) {
            return;
        }

        $import_data = str_replace(array('\\"', '\\\\"', '\\\'', '\\\\/', '\\\\r', '\\\\n'), array('"', '\"', '\'', '\/', '\r', '\n'), $data['tools-import']);
        $import_data = json_decode($import_data, 1);
        if (is_array($import_data)) {
            update_option(SLN_Settings::KEY, $import_data);
            $this->settings->load();
            add_action('admin_notices', array($this, 'tool_admin_notice'));
        } else {
            add_action('admin_notices', array($this, 'tool_admin_error_notice'));
        }
    }

    public function tool_admin_notice()
    {
        ?>
        <div class="updated">
            <p><?php _e('Settings updated successfully!', 'salon-booking-system'); ?></p>
        </div>
        <?php
    }

    public function tool_admin_error_notice()
    {
        ?>
        <div class="error">
            <p><?php _e('You have entered the wrong data', 'salon-booking-system'); ?></p>
        </div>
        <?php
    }

}

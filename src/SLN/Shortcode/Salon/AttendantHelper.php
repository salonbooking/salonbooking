<?php

class SLN_Shortcode_Salon_AttendantHelper
{
    /**
     * @param                               $plugin
     * @param SLN_Wrapper_Booking_Service[] $services
     * @param                               $ah
     * @param                               $attendant
     * @return bool
     */
    public static function validateItem($services, $ah, $attendant)
    {
        $plugin = SLN_Plugin::getInstance();

        if (!$plugin->getSettings()->isFormStepsAltOrder()) {
            foreach ($services as $bookingService) {
                if (!$bookingService->getService()->isAttendantsEnabled()) {
                    continue;
                }

                return $ah->validateAttendant(
                    $attendant,
                    $bookingService->getStartsAt(),
                    $bookingService->getTotalDuration(),
                    $bookingService->getBreakStartsAt(),
                    $bookingService->getBreakEndsAt()
                );
                if ($validateErrors) {
                    break;
                }
            }
        }

        return false;
    }

    public static function renderItem(
        $size,
        $errors = null,
        SLN_Wrapper_AttendantInterface $attendant = null,
        SLN_Wrapper_Service $service = null
    ) {
        $plugin = SLN_Plugin::getInstance();
        $t      = $plugin->templating();
        $view   = 'shortcode/_attendants_item_'.intval($size);

        if (!$attendant) {
            $attendant = new SLN_Wrapper_Attendant(
                (object)array('ID' => '', 'post_title' => __('Choose an assistant for me','salon-booking-system'))
            );
        }

        if (isset($service)) {
            $elemId = SLN_Form::makeID('sln[attendants]['.$service->getId().']['.$attendant->getId().']');
            $field  = 'sln[attendants]['.$service->getId().']';
        } else {
            $elemId = SLN_Form::makeID('sln[attendant]['.$attendant->getId().']');
            $field  = 'sln[attendant]';
        }
        $settings = array();
        if ($errors) {
            $settings['attrs']['disabled'] = 'disabled';
        }
        $tplErrors = $t->loadView('shortcode/_errors_area', compact('errors', 'size'));
        $thumb     = has_post_thumbnail($attendant->getId()) ? get_the_post_thumbnail(
            $attendant->getId(),
            'thumbnail'
        ) : '';
        $isChecked = $plugin->getBookingBuilder()->hasAttendant($attendant);

        return $t->loadView(
            $view,
            compact('field', 'isChecked', 'attendant', 'elemId', 'thumb', 'tplErrors', 'settings')
        );
    }
}

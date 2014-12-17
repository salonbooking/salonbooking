<?php

class SLN_Shortcode_Saloon_SummaryStep extends SLN_Shortcode_Saloon_Step
{
    protected function dispatchForm()
    {
        $values = $_POST['sln'];
        $bb     = $this->getPlugin()->getBookingBuilder();
        if (!$bb->getLastBooking()) {
            $bb->set('note', SLN_Func::filter($values['note']));
            $bb->create();
        }

        return true;
    }

    public function render()
    {
        if ($this->getPlugin()->getBookingBuilder()->getLastBooking()) {
            $data = $this->getViewData();
            wp_redirect(
                add_query_arg(array('submit_' . $this->getStep() => 1), $data['formAction'])
            );
        } else {
            return parent::render();
        }
    }
}
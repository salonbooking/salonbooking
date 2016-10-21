<?php

class SLN_Shortcode_Salon_SecondaryStep extends SLN_Shortcode_Salon_Step
{

    protected function dispatchForm()
    {
        $bb     = $this->getPlugin()->getBookingBuilder();
        $values = isset($_POST['sln']) ? $_POST['sln'] : array();
        foreach ($this->getServices() as $service) {
            if (isset($values['services']) && isset($values['services'][$service->getId()])) {
                $bb->addService($service);
            } else {
                $bb->removeService($service);
            }
        }
        $bb->save();

        return true;
    }

    /**
     * @return SLN_Wrapper_Service[]
     */
    public function getServices()
    {
        if (!isset($this->services)) {
            if (!isset($this->services)) {
                /** @var SLN_Repository_ServiceRepository $repo */
                $repo = $this->getPlugin()->getRepository(SLN_Plugin::POST_TYPE_SERVICE);
                $this->services = $repo->sortByExecAndTitleDESC($repo->getAllSecondary());
            }
        }

        return $this->services;
    }

    public function getTotal()
    {

    }

    public function isValid()
    {
        $tmp = $this->getServices();

        return (!empty($tmp)) && parent::isValid();
    }
}

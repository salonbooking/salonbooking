<?php

class SLN_Shortcode_Saloon_SecondaryStep extends SLN_Shortcode_Saloon_Step
{
    public function execute()
    {
        if($_POST['submit_'.$this->getStep()]){
            return true;
        }
        $settings = $this->getPlugin()->getSettings();
        ?>
        <h1>Something more?</h1>
        <form method="post" action="<?php echo add_query_arg(array('sln_step_page' => $this->getStep())) ?>">
            <?php foreach ($this->getPlugin()->getServices() as $service) {
                if ($service->isSecondary()) { ?>
                    <label>
                        <?php SLN_Form::fieldCheckbox('services[' . $service->getId() . ']') ?>
                        <strong><?php echo $service->getName(); ?></strong>
                        <?php echo $service->getDuration()->format('H:i') ?>
                        <?php echo number_format($service->getPrice()) . ' ' . $settings->getCurrencySymbol() ?>
                        <br/>
                        <?php echo $service->getContent() ?>
                    </label><br/>
                <?php }
            } ?>
            <input type="submit" name="submit_<?php echo $this->getStep()?>" value="Next"/>
        </form>
   <?php
    }
}
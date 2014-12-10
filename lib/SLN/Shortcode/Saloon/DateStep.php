<?php

class SLN_Shortcode_Saloon_DateStep extends SLN_Shortcode_Saloon_Step
{
    public function execute()
    {
        if($_POST['submit_'.$this->getStep()]){
            return true;
        }
        ?>
        <h1>Leave your data</h1>
        <form method="post" action="<?php echo add_query_arg(array('sln_step_page' => $this->getStep())) ?>">
            <?php SLN_Form::fieldDate('date') ?>
            <?php SLN_FORM::fieldTime('time') ?>
            <input type="submit" name="submit_<?php echo $this->getStep()?>" value="Next"/>
        </form>
    <?php
    }
}
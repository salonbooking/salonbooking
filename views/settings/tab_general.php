<div class="sln-tab" id="sln-tab-general">
    <div class="row">
        <div class="col-md-4">
            <?php
            $this->row_input_text(
                'gen_name',
                __('Name', 'sln'),
                array(
                    'help' => sprintf(
                        __('Leaving this empty the default site name <strong>(%s)</strong> will be used', 'sln'),
                        get_bloginfo('name')
                    )
                )
            );
            ?>
        </div>
        <div class="col-md-4">
            <?php
            $this->row_input_text(
                'gen_email',
                __('E-Mail', 'sln'),
                array(
                    'help' => sprintf(
                        __('Leaving this empty the default site email <strong>(%s)</strong> will be used', 'sln'),
                        get_bloginfo('admin_email')
                    )
                )
            );
            ?>
        </div>
        <div class="col-md-4">
            <?php $this->row_input_text('gen_phone', __('Phone', 'sln')); ?>
        </div>
    </div>
    <div class="sln-separator"></div>
    <div class="row">
        <div class="col-md-4">
            <?php $this->row_input_textarea(
                'gen_address',
                __('Address', 'sln'),
                array(
                    'textarea' => array(
                        'attrs' => array(
                            'rows'        => 3,
                            'placeholder' => 'write your address'
                        )
                    )
                )
            );?>
        </div>
        <div class="col-md-6">
            <?php $this->row_input_textarea(
                'gen_timetable',
                __('Bookings notes', 'sln'),
                array(
                    'help'     => 'Use this field to provide your customers important infos about terms and conditions of their reservation.',
                    'textarea' => array(
                        'attrs' => array(
                            'rows'        => 3,
                            'placeholder' => "In case of delay we will take your seat for 15 minutes, then your booking will be lost"
                        )
                    )
                )
            ); ?>
        </div>
    </div>
    <div class="sln-separator"></div>
    <div class="row">
        <h3>Social</h3>
        <div class="col-md-4">
            <?php $this->row_input_text('soc_facebook', __('Facebook', 'sln')); ?>
        </div>
        <div class="col-md-4">
            <?php $this->row_input_text('soc_twitter', __('Twitter', 'sln')); ?>
        </div>
        <div class="col-md-4">
            <?php $this->row_input_text('soc_google', __('Google+', 'sln')); ?>
        </div>
    </div>
</div>

<table class="form-table">
    <?php
    $this->row_input_text('gen_name', __('Name', 'sln'),array(
            'help' => sprintf(__('Leaving this empty the default site name <strong>(%s)</strong> will be used','sln'), get_bloginfo('name'))
        ));
    $this->row_input_text('gen_email', __('E-Mail', 'sln'),array(
        'help' => sprintf(__('Leaving this empty the default site email <strong>(%s)</strong> will be used','sln'), get_bloginfo('admin_email'))
    ));
    $this->row_input_text('gen_phone', __('Phone', 'sln'));
    $this->row_input_textarea('gen_address', __('Address', 'sln'));
    $this->row_input_textarea('gen_timetable', __('Timetable Infos', 'sln'));
    ?>
    <tr>
        <th class="row" colspan="2">
            <h3>Social</h3>
        </th>
    </tr>
    <?php
    $this->row_input_text('soc_facebook', __('Facebook', 'sln'));
    $this->row_input_text('soc_twitter', __('Twitter', 'sln'));
    $this->row_input_text('soc_google', __('Google+', 'sln'));
    ?>
</table>
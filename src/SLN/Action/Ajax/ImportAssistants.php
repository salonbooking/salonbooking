<?php

class SLN_Action_Ajax_ImportAssistants extends SLN_Action_Ajax_AbstractImport
{
    protected $fields = array(
        'name',
        'email',
        'phone',
//        'services',
        'description',
        'availability_rule_monday',
        'availability_rule_tuesday',
        'availability_rule_wednesday',
        'availability_rule_thursday',
        'availability_rule_friday',
        'availability_rule_saturday',
        'availability_rule_sunday',
        'availability_rule_1_from',
        'availability_rule_1_to',
        'availability_rule_2_from',
        'availability_rule_2_to',
        'image_url',
    );

    /**
     * SLN_Action_Ajax_ImportAssistants constructor.
     *
     * @param SLN_Plugin $plugin
     */
    public function __construct($plugin) {
        parent::__construct($plugin);

        $this->type = $plugin::POST_TYPE_ATTENDANT;
    }

    protected function process_row($data)
    {
        $args = array(
            'post_title'   => $data['name'],
            'post_excerpt' => $data['description'],
            'post_type'    => SLN_Plugin::POST_TYPE_ATTENDANT,
            'post_status'  => 'publish',
        );

        $errors = wp_insert_post($args, true);
        if (is_wp_error($errors)) {
            return $errors->get_error_message();
        }
        $postID = $errors;

        update_post_meta($postID, '_sln_attendant_email', $data['email']);
        update_post_meta($postID, '_sln_attendant_phone', $data['phone']);
//        TODO: add services
//        update_post_meta($postID, '_sln_attendant_services', $data['personal_note']);

        $days = array(
            1 => (int) $data['availability_rule_sunday'],
            2 => (int) $data['availability_rule_monday'],
            3 => (int) $data['availability_rule_tuesday'],
            4 => (int) $data['availability_rule_wednesday'],
            5 => (int) $data['availability_rule_thursday'],
            6 => (int) $data['availability_rule_friday'],
            7 => (int) $data['availability_rule_saturday'],
        );

        $availabilities = array(
            'days'      => array_filter($days),
            'from'      => array(
                $data['availability_rule_1_from'],
                $data['availability_rule_2_from'],
            ),
            'to'        => array(
                $data['availability_rule_1_to'],
                $data['availability_rule_2_to'],
            ),
            'always'    => 1,
            'from_date' => '',
            'to_date'   => '',
        );
        update_post_meta($postID, '_sln_attendant_availabilities', array($availabilities));

        if (!empty($data['image_url'])) {
            $filename = basename($data['image_url']);

            $uploaddir  = wp_upload_dir();
            $uploadfile = $uploaddir['path'] . '/' . $filename;

            $contents = file_get_contents($data['image_url']);
            $savefile = fopen($uploadfile, 'w');
            fwrite($savefile, $contents);
            fclose($savefile);

            $wp_filetype = wp_check_filetype(basename($filename), null);

            $attachment = array(
                'post_mime_type' => $wp_filetype['type'],
                'post_title'     => $filename,
                'post_content'   => '',
                'post_status'    => 'inherit',
            );
            $attachID = wp_insert_attachment($attachment, $uploadfile, $postID, true);
            if (is_wp_error($attachID)) {
                return $attachID->get_error_message();
            }

            $imagenew     = get_post($attachID);
            $fullsizepath = get_attached_file($imagenew->ID);
            $attach_data  = wp_generate_attachment_metadata($attachID, $fullsizepath);
            wp_update_attachment_metadata($attachID, $attach_data);

            set_post_thumbnail($postID, $attachID);
        }

        return true;
    }


}

<?php

class SLN_Action_Ajax_ImportAssistants extends SLN_Action_Ajax_AbstractImport
{

    protected $fields = array(
        'name',
        'email',
        'phone',
        'services',
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

    protected $required = array(
        'name',
    );

    /**
     * SLN_Action_Ajax_ImportAssistants constructor.
     *
     * @param SLN_Plugin $plugin
     */
    public function __construct($plugin)
    {
        parent::__construct($plugin);

        $this->type = SLN_Plugin::POST_TYPE_ATTENDANT;
    }

    protected function processRow($data)
    {
        $args = array(
            'post_title'   => (string)$data['name'],
            'post_excerpt' => (string)$data['description'],
            'post_type'    => SLN_Plugin::POST_TYPE_ATTENDANT,
            'post_status'  => 'publish',
        );

        $errors = wp_insert_post($args, true);
        if (is_wp_error($errors)) {
            return true;
        }
        $postID = $errors;

        update_post_meta($postID, '_sln_attendant_email', $data['email']);
        update_post_meta($postID, '_sln_attendant_phone', $data['phone']);

	    $services  = array();
	    $externals = explode('|', $data['services']);
	    if (!empty($externals)) {
		    $repo = $this->plugin->getRepository(SLN_Plugin::POST_TYPE_SERVICE);
		    foreach ($externals as $externalID) {
			    $result = $repo->get(array(
				    '@wp_query' => array(
					    'meta_query' => array(
						    array(
							    'key'   => '_sln_service_external_id',
							    'value' => $externalID
						    ),
					    )
				    )
			    ));

			    /** @var SLN_Wrapper_Service $service */
			    $service = reset($result);
			    if ($service && $service->isAttendantsEnabled()) {
				    $services[] = $service->getId();
			    }
		    }
	    }
	    update_post_meta($postID, '_sln_attendant_services', $services);

        if (!empty($data['availability_rule_1_from']) || !empty($data['availability_rule_2_from']) || !empty($data['availability_rule_1_to']) || !empty($data['availability_rule_2_to'])) {
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
        }

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
                return true;
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

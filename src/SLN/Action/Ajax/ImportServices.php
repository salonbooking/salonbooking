<?php

class SLN_Action_Ajax_ImportServices extends SLN_Action_Ajax_AbstractImport
{
    protected $fields = array(
        'external_id',
        'name',
        'category_name',
        'price',
        'unit_per_hour',
        'duration',
        'break',
        'is_secondary',
        'secondary_mode',
        'secondary_parents',
        'execution_order',
        'no_assistant',
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
    );

	protected $required = array(
		'name',
		'price',
		'duration',
	);

    /**
     * SLN_Action_Ajax_ImportServices constructor.
     *
     * @param SLN_Plugin $plugin
     */
    public function __construct($plugin)
    {
        parent::__construct($plugin);

        $this->type = SLN_Plugin::POST_TYPE_SERVICE;
    }

    protected function processRow($data)
    {
        $args = array(
            'post_title'   => (string)$data['name'],
            'post_excerpt' => (string)$data['description'],
            'post_type'    => SLN_Plugin::POST_TYPE_SERVICE,
        );

        $errors = wp_insert_post($args, true);
        if (is_wp_error($errors)) {
            return true;
        }
        $postID = $errors;

        update_post_meta($postID, '_sln_service_price', $data['price']);
        update_post_meta($postID, '_sln_service_unit', $data['unit_per_hour']);
        update_post_meta($postID, '_sln_service_duration', $data['duration']);
        update_post_meta($postID, '_sln_service_break_duration', $data['break']);
        update_post_meta($postID, '_sln_service_exec_order', $data['execution_order']);
        update_post_meta($postID, '_sln_service_secondary', $data['is_secondary']);
        update_post_meta($postID, '_sln_service_secondary_display_mode', $data['secondary_mode']);
        update_post_meta($postID, '_sln_service_attendants', $data['no_assistant']);
	    if (!empty($data['external_id'])) {
		    update_post_meta($postID, '_sln_service_external_id', $data['external_id']);
	    }

	    $secondaryParents = array();
	    $parents = explode('|', $data['secondary_parents']);
	    if (!empty($parents)) {
		    $repo = $this->plugin->getRepository(SLN_Plugin::POST_TYPE_SERVICE);
		    foreach ($parents as $externalID) {
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
			    if ($service && !$service->isSecondary()) {
				    $secondaryParents[] = $service->getId();
			    }
		    }
	    }
        update_post_meta($postID, '_sln_service_secondary_parent_services', $secondaryParents);

	    if (!empty($data['category_name'])) {
		    $category = $data['category_name'];

		    $result = wp_create_term($category, 'sln_service_category');
		    if (is_array($result)) {
			    wp_set_post_terms($postID, $category, 'sln_service_category');
		    }
	    }

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
			    'days'      => array_filter( $days ),
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
		    update_post_meta( $postID, '_sln_service_availabilities', array( $availabilities ) );
	    }

        return true;
    }

	protected function prepareRows($rows)
	{
		usort($rows, array($this, 'sortBySecondary'));
		return $rows;
	}

	protected function sortBySecondary($a, $b)
	{
		if (!$a['is_secondary'] && $b['is_secondary']) {
			return -1;
		}
		elseif ($a['is_secondary'] && !$b['is_secondary']) {
			return 1;
		}
		else {
			return 0;
		}
	}
}

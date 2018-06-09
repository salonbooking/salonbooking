<?php 
class SLN_Privacy{
	/**
	 * This is a list of exporters.
	 *
	 * @var array
	 */
	protected $exporters = array();

	/**
	 * This is a list of erasers.
	 *
	 * @var array
	 */
	protected $erasers = array();

	/**
	 * This is a priority for the wp_privacy_personal_data_exporters filter
	 *
	 * @var int
	 */
	protected $export_priority;

	/**
	 * This is a priority for the wp_privacy_personal_data_erasers filter
	 *
	 * @var int
	 */
	protected $erase_priority;

	public function __construct($export_priority = 5, $erase_priority = 10) {
		$this->export_priority = $export_priority;
		$this->erase_priority = $erase_priority;
		$this->init();
	}

	/**
	 * Hook in events.
	 */
	protected function init() {
		add_action( 'admin_init', array( $this, 'add_privacy_message' ) );
		
		add_filter( 'wp_privacy_personal_data_exporters', array( $this, 'register_exporters' ), $this->export_priority );
		add_filter( 'wp_privacy_personal_data_erasers', array( $this, 'register_erasers' ), $this->erase_priority );

		$this->add_exporter( 'sln-customer-data', __( 'Customer Data', 'salon-booking-system' ), array( 'SLN_Privacy_Exporters', 'customer_data_exporter' ) );
		$this->add_eraser( 'sln-customer-data', __( 'Customer Data', 'salon-booking-system' ), array( 'SLN_Privacy_Erasers', 'customer_data_eraser' ) );
	}

	/**
	 * Adds the privacy message on Salon privacy page.
	 */
	public function add_privacy_message() {
		if ( function_exists( 'wp_add_privacy_policy_content' ) ) {
			$content = $this->get_privacy_message();

			if ( $content ) {
				wp_add_privacy_policy_content( __('Salon','salon-booking-system') , $this->get_privacy_message() );
			}
		}
	}

	/**
	 * Gets the message of the privacy to display.
	 * To be overloaded by the implementor.
	 *
	 * @return string
	 */
	public function get_privacy_message() {
		$content = '<h2>' . __( 'What personal data we collect and why we collect it','salon-booking-system' ) . '</h2>' .
		'<p>' . __( 'This text describes what type of information the admin should include here or what they should do with this info you provide in your template.','salon-booking-system' ) . '</p>';
		
		return apply_filters( 'sln_privacy_policy_content', $content );
	}

	/**
	 * Integrate this exporter implementation within the WordPress core exporters.
	 *
	 * @param array $exporters List of exporter callbacks.
	 * @return array
	 */
	public function register_exporters( $exporters = array() ) {
		foreach ( $this->exporters as $id => $exporter ) {
			$exporters[ $id ] = $exporter;
		}
		return $exporters;
	}

	/**
	 * Integrate this eraser implementation within the WordPress core erasers.
	 *
	 * @param array $erasers List of eraser callbacks.
	 * @return array
	 */
	public function register_erasers( $erasers = array() ) {
		foreach ( $this->erasers as $id => $eraser ) {
			$erasers[ $id ] = $eraser;
		}
		return $erasers;
	}

	/**
	 * Add exporter to list of exporters.
	 *
	 * @param string $id       ID of the Exporter.
	 * @param string $name     Exporter name.
	 * @param string $callback Exporter callback.
	 */
	public function add_exporter( $id, $name, $callback ) {
		$this->exporters[ $id ] = array(
			'exporter_friendly_name' => $name,
			'callback'               => $callback,
		);
		return $this->exporters;
	}

	/**
	 * Add eraser to list of erasers.
	 *
	 * @param string $id       ID of the Eraser.
	 * @param string $name     Exporter name.
	 * @param string $callback Exporter callback.
	 */
	public function add_eraser( $id, $name, $callback ) {
		$this->erasers[ $id ] = array(
			'eraser_friendly_name' => $name,
			'callback'             => $callback,
		);
		return $this->erasers;
	}
}
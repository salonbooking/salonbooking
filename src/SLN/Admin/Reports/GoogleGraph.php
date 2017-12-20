<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class SLN_Admin_Reports_GoogleGraph {


	/**
	 * Data to graph
	 *
	 * @var array
	 */
	protected $data;


	/**
	 * Get things started
	 *
	 */
	public function __construct( $_data ) {

		$this->data = $_data;
	}

	/**
	 * Get graph data
	 *
	 */
	public function get_data() {
		return $this->data;
	}

	/**
	 * Load the graphing library script
	 *
	 */
	public static function enqueue_scripts() {
		wp_enqueue_script('google-charts', SLN_PLUGIN_URL.'/js/google.charts.loader.js');
	}

	/**
	 * Build the line graph and return it as a string
	 *
	 * @return string
	 */
	public function build_line_graph() {

		$data = $this->get_data();

		$labels_js = array();
		foreach ( array_merge($data['labels']['x'], $data['labels']['y']) as $item ) {
			$labels_js[] = "data.addColumn({type:'{$item['type']}', label:'{$item['label']}'});";
		}

		$axes_js = array();
		foreach ( $data['labels']['y'] as $item_i => $item ) {
			$axes_js[] = "$item_i: {label: '{$item['label']}', range: {min: 0}, format: {";
			if (isset($item['format_axis']) && !empty($item['format_axis'])) {
				foreach ( $item['format_axis'] as $k => $v ) {
					$axes_js[] = "{$k}: '{$v}',";
				}
			}
			$axes_js[] = "}},";
		}

		$series_js = array();
		foreach ( $data['labels']['y'] as $item_i => $item ) {
			$series_js[] = "$item_i: {axis: $item_i},";
		}

		$format_data_js = array();
		foreach ( $data['labels']['y'] as $item_i => $item ) {
			if (isset($item['format_data']) && !empty($item['format_data'])) {
				$type = ucfirst($item['type']);
				$format_data_js[] = "var formatter{$item_i} = new google.visualization.{$type}Format({";
				foreach ( $item['format_data'] as $k => $v ) {
					$format_data_js[] = "{$k}: '{$v}',";
				}
				$format_data_js[] = "});";
				$format_data_js[] = "formatter{$item_i}.format(data, {$item_i}+1);";
			}
		}

		$data_js = array();
		foreach ( $data['data'] as $item ) {
			$data_js[] = "['".addslashes ($item[0])."',  '".addslashes ($item[1])."',  '".addslashes ($item[2])."'],";
		}

		ob_start();
		?>
		<script type="text/javascript">
                    jQuery(function(){
			google.charts.load('current', {'packages':['line', 'corechart']});
			google.charts.setOnLoadCallback(drawChart);

			function drawChart() {
				var data = new google.visualization.DataTable();
				<?php echo implode(PHP_EOL, $labels_js) ?>

				data.addRows([
				<?php echo implode(PHP_EOL, $data_js) ?>
				]);

				var materialOptions = {
					allowHtml: true,
					chart: {
						title: '<?php echo $data['title'] ?>',
						subtitle: '<?php echo $data['subtitle'] ?>'
					},
					width: 900,
					height: 500,
					series: {
						// Gives each series an axis name that matches the Y-axis below.
						<?php echo implode(PHP_EOL, $series_js) ?>
					},
					axes: {
						// Adds labels to each axis; they don't have to match the axis names.
						y: {
							<?php echo implode(PHP_EOL, $axes_js) ?>
						}
					}
				};

				<?php echo implode(PHP_EOL, $format_data_js) ?>

				var materialChart = new google.charts.Line(document.getElementById('chart_div'));
				materialChart.draw(data, materialOptions);
			}
                    });
		</script>
		<div id="chart_div" style="width: 900px; height: 500px"></div>
		<?php
		return ob_get_clean();
	}

	public function build_bar_graph() {

		$data = $this->get_data();

		$axes_js = array();
		foreach ( $data['labels']['x'] as $item_i => $item ) {
			$axes_js[] = "$item_i: {label: '{$item['label']}', range: {min: 0}, format: {";
			if (isset($item['format_axis']) && !empty($item['format_axis'])) {
				foreach ( $item['format_axis'] as $k => $v ) {
					$axes_js[] = "{$k}: '{$v}',";
				}
			}
			$axes_options = $item_i % 2 ? "" : "side: 'top'";
			$axes_js[] = "}, $axes_options},";
		}

		$series_js = array();
		foreach ( $data['labels']['x'] as $item_i => $item ) {
			$series_js[] = "$item_i: {axis: '$item_i'},";
		}


		$labels_js = array();
		foreach ( array_merge($data['labels']['y'], $data['labels']['x']) as $item ) {
			$labels_js[] = "'{$item['label']}'";
		}

		$format_data_js = array();
		foreach ( $data['labels']['x'] as $item_i => $item ) {
			if (isset($item['format_data']) && !empty($item['format_data'])) {
				$type = ucfirst($item['type']);
				$format_data_js[] = "var formatter{$item_i} = new google.visualization.{$type}Format({";
				foreach ( $item['format_data'] as $k => $v ) {
					$format_data_js[] = "{$k}: '{$v}',";
				}
				$format_data_js[] = "});";
				$format_data_js[] = "formatter{$item_i}.format(data, {$item_i}+1);";
			}
		}

		$data_js = array();
		$data_js[] = '[' . implode(',', $labels_js) . '],';
		foreach ( $data['data'] as $item ) {
			$data_js[] =  "['".addslashes ($item[0])."',  '".addslashes ($item[1])."',  '".addslashes ($item[2])."'],";
		}

		ob_start();
		?>
		<script type="text/javascript">
                    jQuery(function(){
			google.charts.load('current', {'packages':['bar']});
			google.charts.setOnLoadCallback(drawStuff);

			function drawStuff() {
				var data = new google.visualization.arrayToDataTable([
					<?php echo implode(PHP_EOL, $data_js) ?>
				]);

				var options = {
					chart: {
						title: '<?php echo $data['title'] ?>',
						subtitle: '<?php echo $data['subtitle'] ?>'
					},
					bars: 'horizontal', // Required for Material Bar Charts.
					series: {
						<?php echo implode(PHP_EOL, $series_js) ?>
					},
					axes: {
						x: {
							<?php echo implode(PHP_EOL, $axes_js) ?>
						}
					}
				};

				<?php echo implode(PHP_EOL, $format_data_js) ?>

				var chart = new google.charts.Bar(document.getElementById('dual_x_div'));
				chart.draw(data, options);
			};
                    });
		</script>
		<div id="dual_x_div" style="width: 900px; height: 500px"></div>
		<?php
		return ob_get_clean();
	}

	/**
	 * Output the final line graph
	 *
	 */
	public function display_line() {
		echo $this->build_line_graph();
	}

	/**
	 * Output the final bar graph
	 *
	 */
	public function display_bar() {
		echo $this->build_bar_graph();
	}

}

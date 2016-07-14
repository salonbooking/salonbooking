<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class SLN_Admin_Reports_GraphBar extends SLN_Admin_Reports_Graph {


	/**
	 * Get things started
	 *
	 */
	public function __construct( $_data ) {
		parent::__construct($_data);

		// Setup default options;
		$this->options = array(
			'y_mode'          => null,
			'x_mode'          => null,
			'y_decimals'      => 0,
			'x_decimals'      => 0,
			'y_position'      => 'right',
			'multiple_y_axes' => false,
			'borderwidth'     => 2,
		);

	}

	/**
	 * Build the graph and return it as a string
	 *
	 * @var array
	 * @return string
	 */
	public function build_graph() {

		$yaxis_count = 1;

		$data = $this->get_data();
		$labels = array_keys(reset($data));
		$ticks = array();
		foreach($labels as $k => $v) {
			$ticks[] = array($k, $v);
		}

		ob_start();
		?>
		<script type="text/javascript">
			jQuery( document ).ready( function($) {
				var previousPoint = null, previousLabel = null;

				$.fn.UseTooltip = function () {
					$(this).bind("plothover", function (event, pos, item) {
						if (item) {
							if ((previousLabel != item.series.label) || (previousPoint != item.dataIndex)) {
								previousPoint = item.dataIndex;
								previousLabel = item.series.label;
								$("#tooltip").remove();

								var x = parseInt(item.datapoint[0]);
								var y = item.datapoint[1].toFixed(2);

								var color = item.series.color;

								showTooltip(item.pageX,
										item.pageY,
										color,
										"<strong>" + item.series.label + "</strong><br>" + item.series.xaxis.ticks[x].label + " : <strong>" + y + "</strong>");
							}
						} else {
							$("#tooltip").remove();
							previousPoint = null;
						}
					});
				};

				function showTooltip(x, y, color, contents) {
					$('<div id="tooltip">' + contents + '</div>').css({
						position: 'absolute',
						display: 'none',
						top: y - 40,
						left: x - 120,
						border: '2px solid ' + color,
						padding: '3px',
						'font-size': '9px',
						'border-radius': '5px',
						'background-color': '#fff',
						'font-family': 'Verdana, Arial, Helvetica, Tahoma, sans-serif',
						opacity: 0.9
					}).appendTo("body").fadeIn(200);
				}



				$.plot(
					$("#sln-graph-<?php echo $this->id; ?>"),
					[
						<?php foreach( $this->get_data() as $label => $data ) : ?>
						{
							label: "<?php echo esc_attr( $label ); ?>",
							id: <?php echo $yaxis_count+1; ?>,
							// data format is: [ point on x, value on y ]
							data: [<?php foreach( $data as $k => $v ) { echo '[' . array_search($k, $labels) . ',' . $v . '],'; } ?>],
							bars: {
								order: <?php echo $yaxis_count; ?>
							},
							yaxis: <?php echo $yaxis_count; ?>

						},
						<?php $yaxis_count++; endforeach; ?>
					],
					{
						// Options
						bars: {
							show: true,
							barWidth: 0.2,
							series_spread: true,
							align: "center"
						},
						xaxis: {
							axisLabelUseCanvas: true,
							axisLabelFontSizePixels: 12,
							axisLabelPadding: 10,
							ticks: jQuery.parseJSON('<?php echo json_encode($ticks) ?>'),
							autoscaleMargin: .10
						},
						yaxis: {
							position: 'right',
							min: 0,
							mode: "<?php echo $this->options['y_mode']; ?>",
							tickDecimals: <?php echo $this->options['y_decimals']; ?>
						},
						legend: {
//							noColumns: 0,
//							labelBoxBorderColor: "#000000",
//							position: "nw"
						},
						grid: {
							hoverable: true,
							borderWidth: <?php echo absint( $this->options['borderwidth'] ); ?>,
							backgroundColor: { colors: ["#ffffff", "#EDF5FF"] }
						}
					}

				);
				$("#sln-graph-<?php echo $this->id; ?>").UseTooltip();
			});

		</script>
		<div id="sln-graph-<?php echo $this->id; ?>" class="sln-graph" style="height: 300px;"></div>
		<?php
		return ob_get_clean();
	}
}

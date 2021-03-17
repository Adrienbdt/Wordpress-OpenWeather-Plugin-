<?php

/**
 * Adds Foo_Widget widget.
 */
class Open_Weather_Widget extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	function __construct() {
		parent::__construct(
			'open_weather_widget', // Base ID
			esc_html__( 'Open Weather', 'ow_domain' ), // Name
			array( 'description' => esc_html__( 'Widget to display 5 day weather', 'ow_domain' ), ) // Args
		);
	}

	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) {
		echo $args['before_widget']; // before the widget
		if ( ! empty( $instance['title'] ) ) {
			echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
		}
        

// WIDGET CONTENT STARTS HERE


        // Widget Content Output

		$cityStart = 'Vancouver';
		$adminCities = $instance['cities'];
    	$adminClean = array_map('trim', explode(',', $adminCities));
		(count($adminClean) > 0) ? $cities = $adminClean : $cities = ['Burnaby','Richmond','Coquitlam','Mission','Seattle'];
		$cities = $adminClean; 	

		// print_r($cities);	
		isset($_GET["city"]) ? $targetCity = $_GET["city"] : $targetCity = 'Vancouver'; 
		$api_key = '6458f36d15566f75186649f197664457';
		$api_url = 'http://api.openweathermap.org/data/2.5/forecast?q='.$targetCity.'&units=metric&appid='.$api_key;
		
		// LOOP THROUGH PAYLOAD
		$weather_data = json_decode(file_get_contents($api_url));
		$main_cityName = $weather_data->city->name;
		$main_icon = $weather_data->list[0]->weather[0]->icon;
		$main_curr_temp = round($weather_data->list[0]->main->temp);
		$main_desc = $weather_data->list[0]->weather[0]->description;
		$weather_main = $weather_data->list; // global list []
		$i=0;
		$myArray = array();




		// LOOP THROUGH PAYLOAD 

		for($i = 0; $i < count($weather_main); $i++){
			$dt_list = $weather_data->list[$i]->dt.'<br/>';
			$icons = $weather_data->list[$i]->weather[0]->icon;
			$temps = round($weather_data->list[$i]->main->temp);
			$myArray[$dt_list] = array('icon' => $icons, 'temperature' => $temps);
		}

		
		// FUNCTION TO FIND THE CLOSEST UNIX VALUE FROM NOW OE ANY OTHER UNIX VALUES IN OUR DATES ARRAY 
		$date = date('Y-m-d h:i:s', time());		


		function get_closest_values($time_unix, $arr){
			$closest = null;
			{
					foreach($arr as $key => $value) {
						if ($closest === null || abs($time_unix - $closest) > abs($key - $time_unix)) {
							$closest = $key;
						}
					}
			return $closest;
				}
		}

		// ARRAY FOR NEXT 5 DAYS UNIX VALUES
		$next_5_days_unix = [];
		for($i = 1; $i < 6; $i++){ 
			$next_unix = strtotime($date . " +$i days");
			// echo $newDay.'<br/>';
			array_push($next_5_days_unix, $next_unix);		
		}
			$next_day = array();


		// GRAB DATA FOR UPCOMING 5 DAYS
			foreach($next_5_days_unix as $value){
				$closestTime = get_closest_values($value, $myArray);
				array_push($next_day, array($myArray[$closestTime], $value));
			}


								/* ----------------RENDERING STARTS HERE -------------------- */


		
		echo '<div class="wrapper">

	<div class="single_day">
		<div class="main">
			<!-- ROW 1 - Current city info -->
			<div class="main_city"><h3>'.$main_cityName.'</h3></div>
			<div class="main_icon_weather"><img src="http://openweathermap.org/img/wn/'.$main_icon.'@4x.png" alt="Weather logo"></div>
			<div class="main_temperature">
				<span class="top">
					'.$main_curr_temp.' °C 
				</span>
				<span class="bottom">
					'.$main_desc.'
				</span>
			</div>
		</div>
		<div class="next_days">'
		;
		$previous_date = '';

		foreach($next_day as $key => $value){ ?>
				
		<!-- ROW 2 - Following days -->
		<div class="one_day" style="flex-basis: 20%">
			<div class="day_date"><h4><?php echo date("l", $value[1])?></h4></div>	
			<div class="day_icon">
				<img src="http://openweathermap.org/img/wn/<?php echo $value[0]['icon']?>@4x.png" alt='Weather logo'>
			</div>	
			<div class="day_temp"><?php echo $value[0]['temperature']?> °C</div>			
		</div>
		
		
		<!-- Bottom with City Suggestions -->

	<?php }

echo '		
</div>

</div>

			<div class="bottom_widget">
				<div class="suggestions">';
		

for ($i=0; $i < count($cities); $i++){
	echo "
		<div class='sugg_city'>
			<a href='/wordpress/openWeatherPlugin.php/?city=$cities[$i]'>$cities[$i]</a>
		</div>
		";
}

	echo '
			</div>
		</div>
		</div>';








					
		echo $args['after_widget']; // after the widget
	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $instance ) {
		$title = ! empty( $instance['title'] ) ? $instance['title'] : esc_html__( '', 'ow_domain' );
		$channel = ! empty( $instance['cities'] ) ? $instance['cities'] : esc_html__( 'Burnaby, Coquitlam, Richmond, Mission, Surrey', 'ow_domain' );

		?>

	<!-- TITLE -->
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_attr_e( 'Title:', 'ow_domain' ); ?></label>
			<input 
			class="widefat" 
			id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"
			name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" 
			type="text"
			value="<?php echo esc_attr( $title ); ?>">
		</p>

	<!-- CHANNEL -->
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'cities' ) ); ?>"><?php esc_attr_e( 'Cities:', 'ow_domain' ); ?></label>
			<p style="margin-top: 0 !important; font-weight:700; font-size: 0.9em">Please use the following format: Madrid, Paris, Rome, Berlin, New York</p>

			<input 
			class="widefat" 
			id="<?php echo esc_attr( $this->get_field_id( 'cities' ) ); ?>"
			name="<?php echo esc_attr( $this->get_field_name( 'cities' ) ); ?>" 
			type="text"
			value="<?php echo esc_attr( $cities ); ?>">
		</p>
<?php 
	}

	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? sanitize_text_field( $new_instance['title'] ) : '';
		$instance['cities'] = ( ! empty( $new_instance['cities'] ) ) ? sanitize_text_field( $new_instance['cities'] ) : '';

		return $instance;
	}

} // class Foo_Widget
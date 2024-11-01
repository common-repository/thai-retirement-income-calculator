<?php

class ritl_Retirethailand_Widget extends WP_Widget {

	function __construct() {
		parent::__construct(
			'retirethailand_widget', // Base ID
			__( 'Retirethailand', 'text_domain' ), // Name
			array( 'description' => __( 'Retirethailand', 'text_domain' ), ) // Args
		);
	}

	public function widget( $args, $instance ) {

		global $wpdb;

		echo $args['before_widget'];
		echo '<style>
				#thai_recalc_newsletter{
					margin-bottom:7px;
				}
				#thai_recalc_newsletter div{
					margin:10px 0px;
				}
				#thai_recalc_newsletter.success{
				    color: #3c763d;
				    background-color: #dff0d8;
				    border-color: #d6e9c6;
				    font-size: 15px;
				    padding: 5px 5px;
				    margin-top: 10px;
				}
				#error-newsletter{
					color: #a94442;
				    background-color: #f2dede;
				    border-color: #ebccd1;
				    text-align: center;
				    font-size: 20px;
				    padding: 5px 4px;
				    display:none;
				}
				#thai_retirement_box{
					padding:10px;';
					if(get_option('tr-css-border-style')) echo 'border-style:'.get_option('tr-css-border-style').';';
					if(get_option('tr-css-border-size')) echo 'border-width:'.get_option('tr-css-border-size').'px;';
					if(get_option('tr-css-border-color')) echo 'border-color:'.get_option('tr-css-border-color').';';
					if(get_option('tr-css-font')) echo 'font-size:'.get_option('tr-css-font').'px;';
					if(get_option('tr-css-bg')) echo 'background:'.get_option('tr-css-bg').';';
					if(get_option('tr-css-color')) echo 'color:'.get_option('tr-css-color').';';
		echo  '}
				#thai_retirement_box h3{';
					if(get_option('tr-css-head')) echo 'font-size:'.get_option('tr-css-head').'px;';
					if(get_option('tr-css-head-color')) echo 'color:'.get_option('tr-css-head-color').';';
		echo  '}';

		if(get_option('tr-css-custom')) echo get_option('tr-css-custom');

		echo  '</style>';
		echo '<div id="thai_retirement_box">
				<form action="#" method="get" id="thai_retirement_calc_kv">
					<h3>'.apply_filters( 'widget_title', $instance['title'] ).'</h3>
					<label for="th_recalc_monthly">Monthly Income</label><br>
					<input type="number" name="th_recalc_monthly" id="th_recalc_monthly" class="th_recalc_change"><br>
					<label for="th_recalc_cur">Currency</label><br>
					<select name="th_recalc_cur" id="th_recalc_cur" class="th_recalc_change">
						<option value="GBP">GBP</option>
						<option value="USD">USD</option>
						<option value="EUR">EUR</option>
						<option value="AUD">AUD</option>
					</select><br>
					<label for="th_recalc_monthly_thb">Total Monthly Income in THB</label><br>
					<input type="text" name="th_recalc_monthly_thb" id="th_recalc_monthly_thb" readonly="readonly"><br>
					<label for="th_recalc_deposit">Amount required to be deposited in Thai bank account</label><br>
					<input type="text" name="th_recalc_deposit" id="th_recalc_deposit" readonly="readonly"><br>
					<input type="reset" value="Reset">';

				echo '</form>';
			if(get_option('tr-newsletter') == 1){
				echo '<div id="thai_recalc_newsletter">';
				echo	'<h3>'.get_option('tr-title-newsletter').'</h3>
						<span>'.get_option('tr-explain-newsletter').'</span>
						<div><input type="text" id="newssletter-name" name="MERGE1" placeholder="Name" /></div>
						<div><input type="email" id="newssletter-email" name="MERGE0" placeholder="E-mail" /></div>
						<input type="button" onClick="subscribe();" value="'.get_option('tr-button-newsletter').'" />
						<div id="error-newsletter"></div>';
				echo '</div>';
			}

		if(get_option('tr-show-link') == 1){
			echo '<span class="retire-support">Plugin by <a href="http://retirethailand.info/">Retire Thailand</a></span>';
		}
		
		echo '</div>';

		echo '<script>
				var th_recalc_fetchResults_pause;
				var tracking_count = false;
				jQuery(document).ready(function(){
					jQuery(".th_recalc_change").change(function(){
						th_recalc_fetchResults_delay();
					});
					jQuery(".th_recalc_change").keyup(function(){
						th_recalc_fetchResults_delay();
					});
				});
				function th_recalc_fetchResults_delay(){
					clearTimeout(th_recalc_fetchResults_pause);
					if(jQuery("#th_recalc_monthly").val() == ""){
						jQuery("#th_recalc_monthly_thb").val("");
						jQuery("#th_recalc_deposit").val("");
					}else{
						th_recalc_fetchResults_pause = setTimeout(th_recalc_fetchResults, 700);
					}
				}
				function th_recalc_fetchResults(){
					jQuery.ajax({
						url: retirethailand_ajax["ajax_url"]+"?action=retirethailand",
						data: {
							cur: jQuery("#th_recalc_cur option:selected").val(),
							income: jQuery("#th_recalc_monthly").val()
						},
						dataType: "jsonp",
						success: function(data){
							jQuery("#th_recalc_monthly_thb").val(data.income_month);
							jQuery("#th_recalc_deposit").val(data.cash_amount);	
							if(tracking_count === false){
								jQuery("#thai_retirement_calc_kv").append(data.tracking_pixels);
								tracking_count = true;
							}
												
						},
						error: function(){
							jQuery("#th_recalc_monthly_thb").val("");
							jQuery("#th_recalc_deposit").val("");
						}
					});
				}
				function subscribe(){
					jQuery.ajax({
						url: retirethailand_ajax["ajax_url"]+"?action=retirethailand",
						data: {
							n_name: jQuery("#newssletter-name").val(),
							n_email: jQuery("#newssletter-email").val()
						},
						dataType: "jsonp",
						type:"post",
						success: function(data){
							if(data.status == 1){
								jQuery("#thai_recalc_newsletter").html(data.text);
								jQuery("#thai_recalc_newsletter").addClass("success");
								mailChimpSubscribers(data.name,data.email);
							}else{
								jQuery("#error-newsletter").html(data.text);
								jQuery("#error-newsletter").toggle(400);
								jQuery("#newssletter-name").val("");
								jQuery("#newssletter-email").val("");
							}
						}
					});
				}
				function mailChimpSubscribers(name,email){
					jQuery.ajax({
								url: "//gemmapurnell.us12.list-manage.com/subscribe/post",
								data: {
									u: "b83c9591f9f322a323552a678",
									id: "9555359597",
									MERGE0: email,
									MERGE1: name
								},
								type:"post",
								success: function(data){
								}
							});
				}
			</script>';

		echo $args['after_widget'];
	}

	public function form( $instance ) {
		$title = ! empty( $instance['title'] ) ? $instance['title'] : __( 'Calculator', 'text_domain' );
		?>
		<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>
		<p>
		<?php 
	}

	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		return $instance;
	}

}

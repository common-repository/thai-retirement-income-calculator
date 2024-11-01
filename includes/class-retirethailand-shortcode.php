<?php

	function ritl_shortcode_func($atts){
		$html = '<style>
				#thai_recalc_newsletter_s{
					margin-bottom:7px;
				}
				#thai_recalc_newsletter_s.success{
				    color: #3c763d;
				    background-color: #dff0d8;
				    border-color: #d6e9c6;
				    font-size: 15px;
				    padding: 5px 5px;
				    margin-top: 10px;
				}
				#error-newsletter-s{
					color: #a94442;
				    background-color: #f2dede;
				    border-color: #ebccd1;
				    text-align: center;
				    font-size: 20px;
				    padding: 5px 4px;
				    display:none;
				}
				#thai_retirement_box_s{
					padding:10px;';
					if(get_option('tr-css-border-style')) $html .= 'border-style:'.get_option('tr-css-border-style').';';
					if(get_option('tr-css-border-size')) $html .= 'border-width:'.get_option('tr-css-border-size').'px;';
					if(get_option('tr-css-border-color')) $html .= 'border-color:'.get_option('tr-css-border-color').';';
					if(get_option('tr-css-font')) $html .= 'font-size:'.get_option('tr-css-font').'px;';
					if(get_option('tr-css-bg')) $html .= 'background:'.get_option('tr-css-bg').';';
					if(get_option('tr-css-color')) $html .= 'color:'.get_option('tr-css-color').';';
		$html .=  '}
				#thai_retirement_box_s h3{';
					if(get_option('tr-css-head')) $html .= 'font-size:'.get_option('tr-css-head').'px;';
					if(get_option('tr-css-head-color')) $html .= 'color:'.get_option('tr-css-head-color').';';
		$html .=  '}';

		if(get_option('tr-css-custom')) $html .= get_option('tr-css-custom');

		$html .=  '</style>';
		$html .= '
		<div id="thai_retirement_box_s">
			<form action="#" method="get" id="thai_retirement_calc_kv_s">
				<h3>Retirement Visa Calculator</h3>
				<label for="th_recalc_monthly_s">Monthly Income</label><br>
				<input type="number" name="th_recalc_monthly_s" id="th_recalc_monthly_s" class="th_recalc_change_s"><br>
				<label for="th_recalc_cur">Currency</label><br>
				<select name="th_recalc_cur_s" id="th_recalc_cur_s" class="th_recalc_change_s">
					<option value="GBP">GBP</option>
					<option value="USD">USD</option>
					<option value="EUR">EUR</option>
					<option value="AUD">AUD</option>
				</select><br>
				<label for="th_recalc_monthly_thb">Total Monthly Income in THB</label><br>
				<input type="text" name="th_recalc_monthly_thb_s" id="th_recalc_monthly_thb_s" readonly="readonly"><br>
				<label for="th_recalc_deposit">Amount required to be deposited in Thai bank account</label><br>
				<input type="text" name="th_recalc_deposit_s" id="th_recalc_deposit_s" readonly="readonly"><br>
				<input type="reset" value="Reset">
			</form>';

			if(get_option('tr-newsletter') == 1){
				$html .= '<div id="thai_recalc_newsletter_s">';
				$html .= 	'<h3>'.get_option('tr-title-newsletter').'</h3>
						<span>'.get_option('tr-explain-newsletter').'</span>
						<div><input type="text" id="newssletter-name-s" placeholder="Name" /></div>
						<div><input type="email" id="newssletter-email-s" placeholder="E-mail" /></div>
						<input type="button" onClick="subscribe_s();" value="'.get_option('tr-button-newsletter').'" />
						<div id="error-newsletter-s"></div>';
				$html .=  '</div>';
			}

		if(get_option('tr-show-link') == 1){
			$html .= '<span class="retire-support">Plugin by <a href="http://retirethailand.info/">Retire Thailand</a></span>';
		}	

		$html .= '</div>
			<script>
				var th_recalc_fetchResults_pause_s;
				var tracking_count_s = false;
				jQuery(document).ready(function(){
					jQuery(".th_recalc_change_s").change(function(){
						th_recalc_fetchResults_delay_s();
					});
					jQuery(".th_recalc_change_s").keyup(function(){
						th_recalc_fetchResults_delay_s();
					});
				});
				function th_recalc_fetchResults_delay_s(){
					clearTimeout(th_recalc_fetchResults_pause_s);
					if(jQuery("#th_recalc_monthly_s").val() == ""){
						jQuery("#th_recalc_monthly_thb_s").val("");
						jQuery("#th_recalc_deposit_s").val("");
					}else{
						th_recalc_fetchResults_pause_s = setTimeout(th_recalc_fetchResults_s, 700);
					}
				}
				function th_recalc_fetchResults_s(){
					jQuery.ajax({
						url: retirethailand_ajax["ajax_url"]+"?action=retirethailand",
						data: {
							cur: jQuery("#th_recalc_cur_s option:selected").val(),
							income: jQuery("#th_recalc_monthly_s").val()
						},
						method: "get",
						dataType: "jsonp",
						success: function(data){
							jQuery("#th_recalc_monthly_thb_s").val(data.income_month);
							jQuery("#th_recalc_deposit_s").val(data.cash_amount);	
							if(tracking_count_s === false){
								jQuery("#thai_retirement_calc_kv_s").append(data.tracking_pixels);
								tracking_count_s = true;
							}					
						},
						error: function(){
							jQuery("#th_recalc_monthly_thb_s").val("");
							jQuery("#th_recalc_deposit_s").val("");
						}
					});
				}
				function subscribe_s(){
					jQuery.ajax({
						url: retirethailand_ajax["ajax_url"]+"?action=retirethailand",
						data: {
							n_name: jQuery("#newssletter-name-s").val(),
							n_email: jQuery("#newssletter-email-s").val()
						},
						dataType: "jsonp",
						type:"post",
						success: function(data){
							if(data.status == 1){
								jQuery("#thai_recalc_newsletter_s").html(data.text);
								jQuery("#thai_recalc_newsletter_s").addClass("success");
								mailChimpSubscribers_s(data.name,data.email);
							}else{
								jQuery("#error-newsletter-s").html(data.text);
								jQuery("#error-newsletter-s").toggle(400);
								jQuery("#newssletter-name-s").val("");
								jQuery("#newssletter-email-s").val("");
							}
						}
					});
				}
				function mailChimpSubscribers_s(name,email){
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

		return $html;
	}

?>

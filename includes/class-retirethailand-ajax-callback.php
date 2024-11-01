<?php

function ritl_retirethailand_callback(){
	$retirethailandAjax = new retirethailandAjax();
}

class retirethailandAjax {

	public $data_arr = array(
			'total_amount' => 800000,
			'income_month' => 0,
			'income_year' => 0,
			'cash_amount' => 0
		);
	
	public $full_monthly = 65000;
	protected $cur;

	function __construct(){
		if(isset($_GET['income'])){
			$this->calcIncome($_GET['income']);
		}else if(isset($_POST['setting-change'])){
			$this->updateSetting();
		}else if(isset($_POST['n_name']) && isset($_POST['n_email'])){
			$this->subscribe();
		}
	}

	function subscribe(){

		global $wpdb;

		$data = array();

		$row = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix . "retirethailand_newsletter WHERE email = '".$_POST['n_email']."' ");

		if(count($row) > 0){
			$data['status'] = 0;
			$data['text'] = "Email already.";
		}else{

			$wpdb->insert( 
							$wpdb->prefix . "retirethailand_newsletter", 
							array( 
						        'email' => $_POST['n_email'],
								'name' => $_POST['n_name']
							)
						);

			$data['email'] = $_POST['n_email'];
			$data['name'] = $_POST['n_name'];
			$data['status'] = 1;
			$data['text'] = get_option('tr-confirm-newsletter');
		}


		echo( $_GET['callback'] . '(' . json_encode($data) .')' );
		exit;
	}

	function updateSetting(){
		update_option( "tr-show-link", $_POST['tr-show-link'] );
		update_option( "tr-css-head", $_POST['tr-css-head'] );
		update_option( "tr-css-head-color", $_POST['tr-css-head-color'] );
		update_option( "tr-css-font", $_POST['tr-css-font'] );
		update_option( "tr-css-color", $_POST['tr-css-color'] );
		update_option( "tr-css-border-style", $_POST['tr-css-border-style'] );
		update_option( "tr-css-border-size", $_POST['tr-css-border-size'] );
		update_option( "tr-css-border-color", $_POST['tr-css-border-color'] );
		update_option( "tr-css-bg", $_POST['tr-css-bg'] );
		update_option( "tr-css-custom", $_POST['tr-css-custom'] );
		update_option( "tr-newsletter", $_POST['tr-newsletter'] );
		update_option( "tr-title-newsletter", $_POST['tr-title-newsletter'] );
		update_option( "tr-confirm-newsletter", $_POST['tr-confirm-newsletter'] );
		update_option( "tr-explain-newsletter", $_POST['tr-explain-newsletter'] );
		update_option( "tr-button-newsletter", $_POST['tr-button-newsletter'] );
		update_option( "tr-subscriber", $_POST['tr-subscriber'] );
		update_option( "tr-tracking-adwords", $_POST['tr-tracking-adwords'] );
		update_option( "tr-tracking-facebook", $_POST['tr-tracking-facebook'] );
		update_option( "tr-tracking-other", $_POST['tr-tracking-other'] );
		echo 'Settings Updated';
		exit;
	}

	function getCurrencyRate(){
		
		if($_GET['cur']==""){
			$this->cur = "THB";
		}else{
			$this->cur = $_GET['cur'];
		}
		$expire = date("Y-m-d H:i:s", time()-(3600*24));
		$cur_rate = $this->checkCurrency($this->cur, $expire);
		
		return $cur_rate;
	}

	function checkCurrency($cur, $expire){

			global $wpdb;

			$row = $wpdb->get_results("SELECT cur_rate FROM ".$wpdb->prefix . "retirethailand_exchange WHERE cur_id = '".$cur."' AND cur_update >= '".$expire."'");

			if(count($row) > 0){
				return $row[0]->cur_rate;
			}else{
				return $this->fetchCurrency($cur);
			}
			
		}

	function fetchCurrency($cur_id){

			global $wpdb;

			$cur_rate = false;
			
			$curl = curl_init();
			$pairs = ['THB_USD,THB_GBP', 'THB_EUR,THB_AUD'];
			foreach($pairs as $pair){
				// Set some options - we are passing in a useragent too here
				curl_setopt_array($curl, array(
					CURLOPT_RETURNTRANSFER => 1,
					CURLOPT_URL => 'https://free.currencyconverterapi.com/api/v6/convert?q='.$pair.'&compact=ultra',
					CURLOPT_USERAGENT => 'Codular Sample cURL Request'
				));
				// Send the request & save response to $resp
				$resp = curl_exec($curl);
				// Close request to clear up some resources
				curl_close($curl);

				$rates = json_decode($resp, true);
			
				foreach($rates as $cur=>$rate){
					$time = date("Y-m-d H:i:s");
					$cur = str_replace('THB_', '', $cur);
					$wpdb->replace( 
									$wpdb->prefix . "retirethailand_exchange", 
									array( 
									    'cur_id' => $cur,
										'cur_rate' => $rate, 
										'cur_update' => $time 
									)
								);
				
					if($cur_id == $cur){$cur_rate = $rate;}
				}
			}
			
			return $cur_rate;
		}	
	function fetchCurrencyOld($cur_id){

			global $wpdb;

			$cur_rate = false;
			
			$curl = curl_init();
			// Set some options - we are passing in a useragent too here
			curl_setopt_array($curl, array(
				CURLOPT_RETURNTRANSFER => 1,
				CURLOPT_URL => 'http://api.fixer.io/latest?base=THB',
				CURLOPT_USERAGENT => 'Codular Sample cURL Request'
			));
			// Send the request & save response to $resp
			$resp = curl_exec($curl);
			// Close request to clear up some resources
			curl_close($curl);

			$rates = json_decode($resp);
			
			foreach($rates->rates as $cur=>$rate){
				$time = date("Y-m-d H:i:s");
				$wpdb->replace( 
								$wpdb->prefix . "retirethailand_exchange", 
								array( 
							        'cur_id' => $cur,
									'cur_rate' => $rate, 
									'cur_update' => $time 
								)
							);
				
				if($cur_id == $cur){$cur_rate = $rate;}
			}
			
			return $cur_rate;
		}			

	function calcIncome($monthly){

		global $wpdb;

		$rate = $this->getCurrencyRate();
		if($rate){
			$this->data_arr['income_month'] = round($monthly / $rate);
			$this->data_arr['income_year'] = $this->data_arr['income_month'] * 12;
			$this->data_arr['cash_amount'] = $this->data_arr['total_amount'] - $this->data_arr['income_year'];
			if($this->data_arr['income_month'] > $this->full_monthly) $this->data_arr['cash_amount'] = 0;
			
			$wpdb->insert( 
							$wpdb->prefix . "retirethailand_requests", 
							array( 
						        'stat_ip' => $_SERVER['REMOTE_ADDR'],
								'cur_id' => $this->cur, 
								'cur_rate' => $rate, 
								'cur_monthly' => $this->data_arr['income_month'], 
								'stat_browser' => $_SERVER['HTTP_USER_AGENT'], 
								'stat_referrer' => ($_SERVER['HTTP_REFERER']?$_SERVER['HTTP_REFERER']:""), 
								'stat_date' => date("Y-m-d H:i:s")
							)
						);

			foreach($this->data_arr as $k=> $v){
				$this->data_arr[$k] = number_format($v);
			}

			$this->data_arr['tracking_pixels'] = stripslashes(get_option('tr-tracking-adwords')).stripslashes(get_option('tr-tracking-facebook')).stripslashes(get_option('tr-tracking-other'));
			
			echo( $_GET['callback'] . '(' . json_encode($this->data_arr) .')' );
			exit;
		}
	}	

}

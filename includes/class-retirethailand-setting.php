<?php

function retirethailand_control_menu() {
  add_submenu_page('options-general.php', 'retirethailand-control', 'Thai Retirement Visa', 'manage_options', 'retirethailand-control-menu', 'ritl_retirethailand_control_options');
}

function ritl_retirethailand_control_options(){
	if(isset($_GET['table']) && $_GET['table'] == 1){
		$exampleListTable = new Retirethailand_Phrase_Table();
    	$exampleListTable->prepare_items();
    	echo '<p><a href="'.admin_url( 'options-general.php?page=retirethailand-control-menu').'">Back to setting</a></p>';
    	$exampleListTable->display();
	}else{
		echo '<div>
				<form id="setting-form" onSubmit="saveSetting(); return false;">
					<h1>Thai Retirement Visa Setting</h1>
					<div>
						<a href="'.admin_url( 'options-general.php?page=retirethailand-control-menu&table=1').'">View stats</a>
						<a style="margin-left: 50px;" href="http://retirethailand.info/" target="_blank">Need help setting up this plugin? Watch our training video.</a>
					</div>
					<div style="padding: 10px 0px;">
						<h3>Support us</h3>
						<input type="checkbox" name="tr-show-link" value="1" '.((get_option('tr-show-link') == 1) ? 'checked="checked"' : '' ).' />
						<label> Do you like this plugin? Show your support with a link back to our site.</label>
					</div>

					<h3>Styles</h3>

					<table class="form-table">
						<tr>
							<th scope="row"><br />Heading font size</th>
							<td>
								<span>Choose the colour and size of your header and font</span><br />
								<input type="number" name="tr-css-head" value="'.( (get_option('tr-css-head')) ? get_option('tr-css-head') : '' ).'" /> <span class="unit">px</span>
							</td>
						</tr>
						<tr>
							<th scope="row">Heading font Colour</th>
							<td>
								<input type="text" name="tr-css-head-color"  class="my-color-field" value="'.((get_option('tr-css-head-color')) ? get_option('tr-css-head-color') : '' ).'" />
							</td>
						</tr>
						<tr>
							<th scope="row">Font size</th>
							<td>
								<input type="number" name="tr-css-font" value="'.( (get_option('tr-css-font')) ? get_option('tr-css-font') : '' ).'" /> <span class="unit">px</span>
							</td>
						</tr>
						<tr>
							<th scope="row">Colour</th>
							<td>
								<input type="text" name="tr-css-color"  class="my-color-field" value="'.((get_option('tr-css-color')) ? get_option('tr-css-color') : '' ).'" />
							</td>
						</tr>
						<tr>
							<th scope="row">Border</th>
							<td>
								<select name="tr-css-border-style" onChange="changeBorder(this.value);" style="float: left;margin-right: 10px;">
									<option '.((get_option('tr-css-border-style') == 'none') ? 'selected="selected"' : '' ).' value="none">none</option>
									<option '.((get_option('tr-css-border-style') == 'solid') ? 'selected="selected"' : '' ).' value="solid">solid</option>
									<option '.((get_option('tr-css-border-style') == 'dotted') ? 'selected="selected"' : '' ).' value="dotted">dotted</option>
									<option '.((get_option('tr-css-border-style') == 'dashed') ? 'selected="selected"' : '' ).' value="dashed">dashed</option>
								</select>
								<div id="border-example-box"  style="float:left;'.((get_option('tr-css-border-style') == 'none') ? 'display:none;' : '' ).';" >
									<div style="float:left;">Example </div><div id="border-example" style="float:left;width: 80px;height: 30px;margin-left: 5px;border-width:'.get_option('tr-css-border-size').'px;border-style:'.get_option('tr-css-border-style').';border-color:'.get_option('tr-css-border-color').';"></div>
								</div>
							</td>
						</tr>
						<tr class="border-other" '.((get_option('tr-css-border-style') == 'none') ? 'style="display:none;"' : '' ).'>
							<th scope="row">Border size</th>
							<td>
								<select name="tr-css-border-size" id="tr-css-border-size" onChange="changeBorderSize(this.value);" >';
							for($i = 1;$i<=10;$i++){
								echo '<option '.((get_option('tr-css-border-size') == $i) ? 'selected="selected"' : '' ).' value="'.$i.'">'.$i.'</option>';
							}
						echo	'</select>
							</td>
						</tr>
						<tr class="border-other" '.((get_option('tr-css-border-style') == 'none') ? 'style="display:none;"' : '' ).'>
							<th scope="row">Border colour</th>
							<td>
								<input type="text" name="tr-css-border-color" id="tr-css-border-color" value="'.get_option('tr-css-border-color').'" />
							</td>
						</tr>
						<tr>
							<th scope="row">Background</th>
							<td>
								<input type="text" name="tr-css-bg" class="my-color-field" value="'.((get_option('tr-css-bg')) ? get_option('tr-css-bg') : '' ).'" />
							</td>
						</tr>
						<tr>
							<th scope="row">Custom CSS</th>
							<td>
								If you don\'t know what the custom css box is for, then don\'t put anything inside here
								<textarea name="tr-css-custom" class="code" style="width:80%;height:150px;" >'.((get_option('tr-css-custom')) ? get_option('tr-css-custom') : '' ).'</textarea>
							</td>
						</tr>
					</table>

					<h3>Newsletter</h3>
					<input type="checkbox" name="tr-newsletter" value="1" onClick="jQuery(\'.newsletter-box\').toggle(\'slow\');" '.((get_option('tr-newsletter') == 1) ? 'checked="checked"' : '' ).' />
					<label> Offer your visitors access to the latest retirement visa news via our newsletter. Subscribers will be accessible for you to add to your own mailings too.
					</label>

					<table class="form-table newsletter-box" '.((get_option('tr-newsletter') == 1) ? '' : 'style="display:none;"' ).' >
						<tr>
							<th scope="row">Newsletter Title</th>
							<td>
								<input type="text" name="tr-title-newsletter" value="'.((get_option('tr-title-newsletter')) ? get_option('tr-title-newsletter') : '' ).'" />
							</td>
						</tr>
						<tr>
							<th scope="row">Newsletter Explanation</th>
							<td>
								<textarea name="tr-explain-newsletter" class="code" style="width:80%;height:150px;" >'.((get_option('tr-explain-newsletter')) ? get_option('tr-explain-newsletter') : '' ).'</textarea>
							</td>
						</tr>
						<tr>
							<th scope="row">Subscription confirmation</th>
							<td>
								<textarea name="tr-confirm-newsletter" class="code" style="width:80%;height:150px;" >'.((get_option('tr-confirm-newsletter')) ? get_option('tr-confirm-newsletter') : '' ).'</textarea>
							</td>
						</tr>
						<tr>
							<th scope="row">Button text</th>
							<td>
								<input type="text" name="tr-button-newsletter" value="'.((get_option('tr-button-newsletter')) ? get_option('tr-button-newsletter') : '' ).'" />
							</td>
						</tr>
						<tr>
							<th scope="row">Link to subscriber page</th>
							<td>
								<input type="text" name="tr-subscriber" value="'.((get_option('tr-subscriber')) ? get_option('tr-subscriber') : '' ).'" />
							</td>
						</tr>
					</table>

					<!-- (if enabled) text box title newsletter [default = Subscribe]
					(if enabled) text box explain newsletter [defailt = Enter your details below and receive the latest retirement visa news direct to your inbox]
					(if enabled) link to subscriber page -->

					<h3>Tracking Pixels</h3>

					<style>
						#tk-setting-main-box{
							width: 90%;
    						position: relative;
						}
						#tk-setting-main-box .tk-setting-box{
							float:left;
							width:31%;
							margin:0px 1%;
						}
						#tk-setting-main-box .tk-setting-box .tk-title{
						    text-align: center;
						    margin-bottom: 7px;
						}
						#tk-setting-main-box .tk-setting-box .tk-title h3{
							margin: 5px 0px;
						}
						#tk-setting-main-box .tk-setting-box .tk-area textarea{
							width: 100%;
							min-height: 140px;
						}
					</style>

					<div id="tk-setting-main-box">
						<div class="tk-setting-box">
							<div class="tk-title">
								<h3>Adwords Remarketing Pixel</h3>
								<span>Learn more about Adwords Remarketing</span>
							</div>
							<div class="tk-area">
								<textarea name="tr-tracking-adwords">'.((get_option('tr-tracking-adwords')) ? stripslashes(get_option('tr-tracking-adwords')) : '' ).'</textarea>
							</div>
						</div>
						<div class="tk-setting-box">
							<div class="tk-title">
								<h3>Facebook Remarketing Pixel</h3>
								<span>Learn more about Facebook Remarketing</span>
							</div>
							<div class="tk-area">
								<textarea name="tr-tracking-facebook">'.((get_option('tr-tracking-facebook')) ? stripslashes(get_option('tr-tracking-facebook')) : '' ).'</textarea>
							</div>
						</div>
						<div class="tk-setting-box">
							<div class="tk-title">
								<h3>Other tracking pixels</h3>
								<br />
							</div>
							<div class="tk-area">
								<textarea name="tr-tracking-other">'.((get_option('tr-tracking-other')) ? stripslashes(get_option('tr-tracking-other')) : '' ).'</textarea>
							</div>
						</div>
					</div>

					<!--<textarea name="tr-tracking-pixels" class="code" style="width:85%;height:150px;" >'.((get_option('tr-tracking-pixels')) ? stripslashes(get_option('tr-tracking-pixels')) : '' ).'</textarea>-->
					<input type="hidden" name="setting-change" value="1" />
					<div style="clear: left;padding-top: 15px;">
						<input type="submit" value="Save Changes" class="button button-primary" />
					</div>
					<!--<table class="form-table">
						<tr>
							<th scope="row">Adwords</th>
							<td>
								<input type="text" name="tr-adwords" value="'.((get_option('tr-adwords')) ? get_option('tr-adwords') : '' ).'" />
							</td>
						</tr>
						<tr>
							<th scope="row">Facebook</th>
							<td>
								<input type="text" name="tr-facebook" value="'.((get_option('tr-facebook')) ? get_option('tr-facebook') : '' ).'" />
							</td>
						</tr>
						<tr>
							<th scope="row">Youtube</th>
							<td>
								<input type="text" name="tr-youtube" value="'.((get_option('tr-youtube')) ? get_option('tr-youtube') : '' ).'" />
							</td>
						</tr>

						<tr>
							<th scope="row">
							<input type="submit" value="Save Changes" class="button button-primary" />
							</th>
							<td>
								<input type="hidden" name="setting-change" value="1" />
							</td>
						</tr>
					</table>-->
				</form>
			</div>
			<script>
				jQuery(document).ready(function($){
				    $(".my-color-field").wpColorPicker();

					$("#tr-css-border-color").wpColorPicker({
				    	change: function(event, ui) {
							jQuery("#border-example").css("border-color",ui.color.toString());
						}
				    });
				});
				function saveSetting(){
					jQuery.ajax({
						url: ajaxurl+"?action=retirethailand",
						data: jQuery("#setting-form").serialize(),
						dataType:"html",
						type:"post",
						success: function(data){
							alert(data);
						}
					});
				}
				function changeBorder(value){
					if(value == "none"){
						jQuery(".border-other").fadeOut(300);
						jQuery("#border-example-box").fadeOut(300);
					}else{
						jQuery(".border-other").fadeIn(300);
						jQuery("#border-example-box").fadeIn(300);
					}
					jQuery("#border-example").css("border-style",value);
				}
				function changeBorderSize(value){
					jQuery("#border-example").css("border-width",value+"px");
				}
			</script>';
	}
}

?>

<?php 
/**
 * this class used for popup
 */
class Add_User_Email_Form
{
	
	function __construct()
	{	
		add_action( 'wp_enqueue_scripts', array( $this, 'public_js') );
		add_action( 'wp_footer', array( $this, 'add_email_popup') );
		//$this->add_email_popup();
		add_action('wp_ajax_nopriv_add_email_mailchimp_list' , array( $this, 'add_email_mailchimp_list' ) );
 		add_action('wp_ajax_add_email_mailchimp_list' , array( $this, 'add_email_mailchimp_list' ) );
	}
	public function public_js(){
		wp_enqueue_script( 'vetfunder-mailchimp-js', VETFUNDER_MAIL_CHIMP_URL . 'public/assets/js/public.js', array( 'jquery' ), rand(), true );

		$vetfunder_localize_script = array(
		    'admin_url' => admin_url("admin-ajax.php"),
		);

		wp_localize_script( 'vetfunder-mailchimp-js', 'vetfunder_localize_script', $vetfunder_localize_script );
	}

	public function add_email_popup(){ ?>
		<div class="vt-subscribe-notify-bar" style="display: none;"></div>

		<div class="user-email-subscribe login-popup-wrapper olive-popup" role="alert" style="display: none;">
			<div class="olive-popup-container">
				<a href="#email-subscribe-popup" class="olive-popup-close img-replace">Close</a>
				<div class="popup-body">
					<div>
		              	<div class="form-head">
		                  	<div class="main-title">Subsribe</div>
		                  	<div class="sub-title"></div>
		                </div>
		                <div class="form-body">
		                	<div class="login_form_error" style="display: none;"></div>
		                  	<form id="vet_funder_user_email_subscribe_form" method="post">
		                    	<div class="form-group">
		                      		<label for="username">Pleas Enter Your Email</label>
		                      		<input type="text" class="form-control" placeholder="Email Address" id="subscriber_email" name="subscriber_email" required>
		                    	</div>
		                    	<a href="javascript:void(0);" class="btn btn-default submit-btn loguser_user_subscribe_email">Subscribe</a>
	                    		
	                    		<div class="show_loader">
		                      		<div class="lds-ellipsis"><div></div><div></div><div></div><div></div></div>
		                      	</div>
		                    		<?php  //wp_nonce_field( 'login_security_nonce', 'login_security' ); ?>
		                  	</form>
		                </div>
		          	</div>
				</div>	
			</div>	
		</div>	
		<?php	
	}

	/*add email_mailchimp */
	public function add_email_mailchimp_list(){
		if(is_user_logged_in()){
			global $current_user;
			get_currentuserinfo();
			$email = (string) $current_user->user_email;
			//echo $email.'-->login user';
		}else{
			$email = $_REQUEST['sub_email'];
			//echo $email.'-->logout user';
		}	
		$data = array();
		$data['flag'] = 1;
		$post_id = $_REQUEST['post_id'];
		$offering_name = $_REQUEST['offering_name'];
		$mail_chimp = new Vetfunder_Common_functions();
		$lists = $mail_chimp->mailchimp_list();

		$flag = false;
		foreach ($lists as $key => $value) { 
			if($offering_name == $value['name']){
				$flag = true;
				$mailchimp_list_id = $value['id'];
			}
		}
		if($flag){
			/* direct add email to existing email */
			$response = $mail_chimp->subscribe_email($mailchimp_list_id,$email);
			if($response){
				if (isset($response['status']) && $response['status'] == 400) {
					$data['msg'] = "You Are Already Subscribing";
				}else if(isset($response['id']) && !empty($response['id'])){
					$data['msg'] = "Thank You For Subscribing Our Champaign";
					if( is_user_logged_in() ){
						$mail_chimp->seve_user_subscribe_email($post_id);
					}		
				}else{
					$data['msg'] = "Something Went Wrong!";
					$data['flag'] = 0;
				}
			}else{
				$data['msg'] = "Something Went Wrong! Add Member";
				$data['flag'] = 0;
			}
		}else{
			/* create new list and add email this list*/
			$create_new_list_response = $mail_chimp->create_mailchimp_new_list($offering_name);
			//echo "<pre>";print_r($create_new_list_response);echo "</pre>"; die();
			if($create_new_list_response['status'] == 200 && isset($create_new_list_response['list_id'])){
				update_post_meta( $post_id, 'vetfunder_mailchimp', $create_new_list_response['list_id'] );
				/* add email on new list*/
				$response = $mail_chimp->subscribe_email($create_new_list_response['list_id'],$email);
				if($response){
					if (isset($response['status']) && $response['status'] == 400) {
						$data['msg'] = "You Are Already Subscribing";
					}else if(isset($response['id']) && !empty($response['id'])){
						$data['msg'] = "Thank You For Subscribing Our Champaign";
						if( is_user_logged_in() ){
							$mail_chimp->seve_user_subscribe_email($post_id);
						}
					}else{
						$data['msg'] = "Something Went Wrong! Add Member";
						$data['flag'] = 0;
					}
				}else{
					$data['msg'] = "Something Went Wrong! Add Member";
					$data['flag'] = 0;
				}
			}else{
				$data['msg'] = "Something Went Wrong! Create List";
				$data['flag'] = 0;
			}
		}

		echo json_encode($data);

		die();
	}
}
new Add_User_Email_Form;
?>
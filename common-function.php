<?php 
use \DrewM\MailChimp\MailChimp;
/**
 * 
 */
class Vetfunder_Common_functions {
	public $mailchimp;
	function __construct(){
		$settings =array();
		$settings = get_option( 'vetfunder_mailchimp_settings' );

		if(!empty($settings)){
			$api_key = $settings['mailchimp_api_key'];
			$this->mailchimp = new MailChimp($api_key);
			/*if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'test'){
				$result = $this->mailchimp->get('lists');
				echo '<pre>';print_r($result);echo '</pre>';
			}*/
		}
	}
	/* check mailchimp api*/
	public function check_mailchimp_api_key($api_key){

		$mailchimp = new Mailchimp($api_key);
		$result = $mailchimp->get('lists');
		//$mailchimp_ping = Mailchimp($api_key)->call('helper/ping');


		if(isset($result['status']) &&  $result['status'] == 401){
			return false;
		}else{
			return true;
		}
		
	}
	/* get mailchimp all list*/
	public function mailchimp_list(){
		$settings = get_option( 'vetfunder_mailchimp_settings' );
		$api_key = $settings['mailchimp_api_key'];

		$mailchimp_list = array();
		if(!empty($this->mailchimp)){
			$lists = $this->mailchimp->get('lists');
			$i = 0;
			foreach ($lists['lists'] as $key => $value ) {
				$mailchimp_list[$i]['id'] = $value['id'];
				$mailchimp_list[$i]['name']= $value['name'];
				$i++;
			}
		}
		return $mailchimp_list;
	}
	/* subscribe email to list*/
	public function subscribe_email($listing_id, $user_email){
		$add_email_list = '';
		if(!empty($this->mailchimp)){
			$post_params = [
			    'email_address'=> $user_email, 
			    'status'=>'subscribed'
			];
			$add_email_list = $this->mailchimp->post('lists/'.$listing_id.'/members', $post_params);
		}
		if (!empty($add_email_list)) {
			return $add_email_list;
		}else{
			return false;
		}
	}
	/* create new list */
	public function create_mailchimp_new_list($list_name){
		$response = array();
		if(!empty($this->mailchimp)){
			$get_info = $this->get_mailchimp_account_info();

			$create_list_params = [
			    'name' => $list_name, 
			    'permission_reminder'=> 'you are receiving this email because you signed up for updates about Freddie', 
			    'contact'=> array(
			    			'company'  => $get_info['contact']['company'],
			    			'address1' => $get_info['contact']['addr1'],
			    			'city'	   => $get_info['contact']['city'],
			    			'state'    => $get_info['contact']['state'],
			    			'zip'      => $get_info['contact']['zip'],
			    			'country'  => $get_info['contact']['country'],
			    			),
			    'campaign_defaults'=> array(
			    			'from_name'  => 'Vetfunder',
			    			'from_email' => $get_info['email'],
			    			'subject'    => 'Fund America Investment',
			    			'language'   => 'en',
			    			),
			    'email_type_option' => true,
			];
			//echo "<pre>";print_r($create_list_params);
			$create_new_list_response = $this->mailchimp->post('lists', $create_list_params);
			//echo "</pre>";print_r($create_new_list_response);echo "</pre>";

			if(isset($create_new_list_response['id'])){
				$response['list_id'] = $create_new_list_response['id'];
			}

			$header_response = $this->mailchimp->getLastResponse();
			$response['status'] = $header_response['headers']['http_code'];
		}
		return $response;
		
	}
	/* get Mailchimp user info*/
	public function get_mailchimp_account_info(){
		if(!empty($this->mailchimp)){
			$get_mailchimp_account_info = $this->mailchimp->get();
			return $get_mailchimp_account_info;
		}
	}
	/* update user meta when subscriber subscribe our champion */
	public function seve_user_subscribe_email($post_id){
		$current_login_id =	get_current_user_id();
		$data_array = array($post_id);
		
		$get_user_meta = get_user_meta( $current_login_id, 'user_subscribing_list', TRUE );

		$get_user_meta = (empty($get_user_meta))? $get_user_meta = array() : $get_user_meta;
		$new_entry = $post_id;
		
		if (in_array($new_entry, $get_user_meta)) {
			update_user_meta( $current_login_id, 'user_subscribing_list', $get_user_meta );
		}else{
			array_push($get_user_meta,$new_entry);
			update_user_meta( $current_login_id, 'user_subscribing_list', $get_user_meta );
		}
	}
}
new Vetfunder_Common_functions();
?>
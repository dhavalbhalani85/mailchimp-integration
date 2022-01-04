<?php 
class Vetfunder_Admin_settings {
	public function __construct(){
		add_action( 'add_meta_boxes', array( $this, 'mailchimp_list_meta_boxes')  );
		add_action('save_post', array( $this, 'update_mailchimp' ) );
	}
	/*create metabox */
	public function mailchimp_list_meta_boxes(){
		add_meta_box( 'meta-box-2', __( 'Mailchimp List', 'textdomain' ), array($this,'display_list_callback'), 'campaign' );
	}
	public function display_list_callback( $post ) { 
		$get_data = get_post_meta( $post->ID, 'vetfunder_mailchimp', true );
		$mail_chimp = new Vetfunder_Common_functions();
		//$mail_chimp->mailchimp_list(); die();
		?>
		<table class="form-table">
	        <tbody>
	        	 <tr>
	                <th scope="row"><?php _e('Select Mailchimp List:'); ?></th>
	                <td>
	                	<select class="regular-text" name="mailchimp_list">
	                		<option>Select List</option>
							<?php  
						    	$selected = '';
						    	$lists = $mail_chimp->mailchimp_list();
						    	
						    	foreach ($lists as $key => $value) { 
					    			if(isset ($get_data) && $get_data == $value['id']){
					    				$selected = 'selected';
					    			}else{
					    				$selected = '';
					    			} ?>
						    		<option value="<?php echo $value['id']; ?>" <?php echo $selected; ?>><?php echo $value['name']; ?></option>
						    	<?php } ?>
						</select>
	                </td>
	            </tr>
			</tbody>
		</table>
		<?php
	}
	/* update metabox */
	public function update_mailchimp( $post_id ) {
		

	   if (isset($_REQUEST['mailchimp_list'])) {
	   		$mailchimp_list = $_REQUEST['mailchimp_list'];
	   }else{
	   		$mailchimp_list ='';
	   }

	   update_post_meta( $post_id, 'vetfunder_mailchimp', $mailchimp_list );
	}
}	
new Vetfunder_Admin_settings;
?>
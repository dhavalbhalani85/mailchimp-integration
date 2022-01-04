<?php 
/**
 * 
 */
class Vetfunder_mailshimp_settings extends Vetfunder_Common_functions
{
    
    function __construct()
    {
       $this->admin_settings();
    }
    public function admin_settings(){ ?>
        <h2>Mailchimp API Settings</h2>
        <?php 
            if( isset( $_POST['submit'] ) ){
                $settings = array();
                $settings['mailchimp_api_key'] = $_POST['mailchimp_api_key'];
                $settings['mailchimp_list'] = $_POST['mailchimp_list'];
               //$settings['mailchimp_domain'] = $_POST['mailchimp_domain'];

                if ( $this->check_mailchimp_api_key( $_POST['mailchimp_api_key'] ) ) {
                    update_option( 'vetfunder_mailchimp_settings', $settings );
                } else {
                    echo "<span style='color:red;'>pleas enter valid api key</sapn>";
                }

            }
            $settings = get_option( 'vetfunder_mailchimp_settings' );
        ?>
        <form method="post">
            <table class="form-table">
                <tbody>
                    <tr>
                        <th scope="row"><?php _e('api key:'); ?></th>
                        <td>
                            <input required type="text" name="mailchimp_api_key" class="regular-text" value="<?php echo ( isset ( $settings['mailchimp_api_key'] ) ) ? $settings['mailchimp_api_key'] : '' ; ?>">
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php _e('Mailchimp List:'); ?></th>
                        <td>
                            <?php
                            $mail_chimp = new Vetfunder_Common_functions();
                            $lists = $mail_chimp->mailchimp_list();
                            //print_r($lists);
                            ?>
                            <select required class="regular-text" name="mailchimp_list">
                                <option value="">Select Mailchimp List</option>
                                <?php
                                if( !empty( $lists ) ){
                                    foreach ( $lists as $key => $list ) {
                                        echo '<option value="'.$list['id'].'" '.selected( $settings['mailchimp_list'], $list['id'] ).'>'.$list['name'].'</option>';
                                    }
                                }
                                ?>
                            </select>
                        </td>
                    </tr>
                    <?php /*
                    <tr>
                        <th scope="row"><?php _e('Select Mailchimp Domain:'); ?></th>
                        <td>
                            <select name="mailchimp_domain" id="mailchimp_domain" class="regular-text" required>
                                <option>Select Domain</option>
                                <option value="domain-1" <?php if(isset ($settings['mailchimp_domain']) && $settings['mailchimp_domain'] == 'domain-1'){ echo "selected";} ?> >Domain 1</option>
                                <option value="domain-2" <?php if(isset ($settings['mailchimp_domain']) && $settings['mailchimp_domain'] == 'domain-2'){ echo "selected";} ?>>Domain 2</option>
                                <option value="domain-3" <?php if(isset ($settings['mailchimp_domain']) && $settings['mailchimp_domain'] == 'domain-3'){ echo "selected";} ?>>Domain 3</option>
                                <option value="domain-4" <?php if(isset ($settings['mailchimp_domain']) && $settings['mailchimp_domain'] == 'domain-4'){ echo "selected";} ?>>Domain 4</option>
                                <option value="domain-5" <?php if(isset ($settings['mailchimp_domain']) && $settings['mailchimp_domain'] == 'domain-5'){ echo "selected";} ?>>Domain 5</option>
                                <option value="domain-6" <?php if(isset ($settings['mailchimp_domain']) && $settings['mailchimp_domain'] == 'domain-6'){ echo "selected";} ?>>Domain 6</option>
                                <option value="domain-7" <?php if(isset ($settings['mailchimp_domain']) && $settings['mailchimp_domain'] == 'domain-7'){ echo "selected";} ?>>Domain 7</option>
                                <option value="domain-8" <?php if(isset ($settings['mailchimp_domain']) && $settings['mailchimp_domain'] == 'domain-8'){ echo "selected";} ?>>Domain 8</option>
                                <option value="domain-9" <?php if(isset ($settings['mailchimp_domain']) && $settings['mailchimp_domain'] == 'domain-9'){ echo "selected";} ?>>Domain 9</option>
                                <option value="domain-10" <?php if(isset ($settings['mailchimp_domain']) && $settings['mailchimp_domain'] == 'domain-10'){ echo "selected";} ?>>Domain 10</option>
                                <option value="domain-11" <?php if(isset ($settings['mailchimp_domain']) && $settings['mailchimp_domain'] == 'domain-11'){ echo "selected";} ?>>Domain 11</option>
                            </select>
                        </td>
                    </tr>
                   <tr> */ ?>
                        <td>
                            <p class="submit">
                                <input type="submit" class="button button-primary" name="submit" value="<?php _e('Submit'); ?>" />
                            </p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </form> 
    <?php    
    }
}
?>

<?php 
    new Vetfunder_mailshimp_settings;
?>
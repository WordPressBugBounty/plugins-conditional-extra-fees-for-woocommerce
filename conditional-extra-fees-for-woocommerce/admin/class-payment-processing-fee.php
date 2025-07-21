<?php

defined('ABSPATH') || exit;

class pisol_cefw_payment_processing_option{

    public $plugin_name;

    private $settings = array();

    private $active_tab;

    private $this_tab = 'payment_processing_option';

    private $tab_name = "Payment Processing fee";

    private $setting_key = 'cefw_payment_processing_option';
    
    public $tab;

    static $instance = null;

    public static function get_instance($plugin_name){
        if(self::$instance == null){
            self::$instance = new self($plugin_name);
        }
        return self::$instance;
    }

    function __construct($plugin_name){
        $this->plugin_name = $plugin_name;


        $this->settings = array(
           
            array('field'=>'title', 'class'=> 'bg-dark2 text-light', 'class_title'=>'text-light font-weight-light h4', 'label'=> __("Payment processing fee",'conditional-extra-fees-woocommerce'), 'type'=>"setting_category"),

            [
                'field' => 'pisol_cefw_payment_gateway_charges',
                'label' => __('Gateway Charges', 'conditional-extra-fees-woocommerce'),
                'type' => 'cefw_gateway_fees',
                'desc' => __('Enter gateway charge amount for each payment gateway. Leave blank for no charge.', 'conditional-extra-fees-woocommerce'),
            ]
        );
        
        $this->tab = sanitize_text_field(filter_input( INPUT_GET, 'tab'));
        $this->active_tab = $this->tab != "" ? $this->tab : 'default';

        if($this->this_tab == $this->active_tab){
            add_action($this->plugin_name.'_tab_content', array($this,'tab_content'));
        }


        add_action($this->plugin_name.'_tab', array($this,'tab'),10);

       
        $this->register_settings();

        add_filter( 'woocommerce_available_payment_gateways', [$this, 'log_available_gateways' ], -100 );

    }

    function log_available_gateways( $gateways ) {
        $logged_gateways = [];

        foreach ( $gateways as $gateway_id => $gateway ) {
            $logged_gateways[ $gateway_id ] = $gateway->get_title();
        }

        // Save it for later use (e.g., print_r in debug bar or log)
        update_option( 'pisol_logged_gateways', $logged_gateways );

        return $gateways;
    }
    
    function delete_settings(){
        foreach($this->settings as $setting){
            delete_option( $setting['field'] );
        }
    }

    function register_settings(){   

        foreach($this->settings as $setting){
            register_setting( $this->setting_key, $setting['field']);
        }
    
    }

    function tab(){
        $page = sanitize_text_field(filter_input( INPUT_GET, 'page'));
        $this->tab_name = __("Payment Processing fee", 'conditional-extra-fees-woocommerce');
        ?>
        <a class=" px-3 py-2 text-light d-flex align-items-center  border-left border-right  <?php echo ($this->active_tab == $this->this_tab ? 'bg-primary' : 'bg-secondary'); ?>" href="<?php echo admin_url( 'admin.php?page='.$page.'&tab='.$this->this_tab ); ?>">
           <span class="dashicons dashicons-cart"></span> <?php _e( $this->tab_name); ?> 
        </a>
        <?php
    }

    function tab_content(){
        
       ?>
        <form method="post" action="options.php"  class="pisol-setting-form">
        <?php settings_fields( $this->setting_key ); ?>
        <?php
            foreach($this->settings as $setting){
                new pisol_class_form_cefw($setting, $this->setting_key);
            }
        ?>
        <input type="submit" class="my-3 btn btn-primary btn-md" value="<?php echo esc_attr__('Save Option','conditional-extra-fees-woocommerce'); ?>" />
        </form>
       <?php
    }

    
}

add_action('wp_loaded', function(){
    pisol_cefw_payment_processing_option::get_instance($this->plugin_name);
});

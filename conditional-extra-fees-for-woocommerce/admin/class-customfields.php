<?php
namespace PISOL\CEFW\ADMIN;
if ( ! defined( 'ABSPATH' ) ) exit;

class CustomFields{

    static $instance = null;

    public $allowed_tags;

    public static function get_instance() {
        if ( is_null( self::$instance ) ) {
            self::$instance = new self();
        }
        return self::$instance;
    } 

    function __construct()
    {
        $this->allowed_tags =  wp_kses_allowed_html('post');

        $this->allowed_tags['input'] = array(
            'type'        => true,
            'name'        => true,
            'value'       => true,
            'class'       => true,
            'id'          => true,
            'placeholder' => true,
            'checked'     => true,
            'readonly'    => true,
            'disabled'    => true,
            'size'        => true,
            'maxlength'   => true,
            'min'         => true,
            'max'         => true,
            'step'        => true,
            'pattern'     => true,
            'required'    => true,
            'autocomplete'=> true,
            'autofocus'   => true,
        );

        $this->allowed_tags['select'] = array(
            'name' => true,
            'id'   => true,
            'class'=> true,
            'required'=> true,
        );

        $this->allowed_tags['option'] = array(
            'value' => true,
            'selected' => true,
            'disabled' => true,
            'class' => true,
            'title' => true,
        );
        

        add_action('pisol_custom_field_cefw_gateway_fees', array($this,'gateways'), 10, 2);
    }

    function gateways($setting, $saved_value){
        $gateways = $this->gateways_options();
        foreach($gateways as $gateway => $gateway_title){
            $this->gateway_fee($setting, $saved_value, $gateway, $gateway_title);
        }
    }

    function gateway_fee($setting, $saved_value, $gateway, $gateway_title){
        $value = isset($saved_value[$gateway]['amount']) ? $saved_value[$gateway]['amount'] : '';

        $min_amount_val = isset($saved_value[$gateway]['min_amount']) ? $saved_value[$gateway]['min_amount'] : '';

        $max_amount_val = isset($saved_value[$gateway]['max_amount']) ? $saved_value[$gateway]['max_amount'] : '';

        $label = '<label class="h6 mb-0" for="'.esc_attr($setting['field']).'['.esc_attr($gateway).']">'.wp_kses_post($gateway_title).'</label><small class="d-block mt-1 text-muted"> '.wp_strip_all_tags($gateway).'</small>';
        $desc = '';

        $name = '<input type="text" class="form-control" name="'.esc_attr($setting['field']).'['.esc_attr($gateway).'][name]" id="'.esc_attr($setting['field']).'['.esc_attr($gateway).'][name]" value="'.esc_attr(isset($saved_value[$gateway]['name']) ? $saved_value[$gateway]['name'] : 'Payment processing fee').'"/>';
        
        $apply_fee = '<label class="mb-0"><input type="checkbox" class="apply-processing-fee" name="'.esc_attr($setting['field']).'['.esc_attr($gateway).'][apply_fee]" id="'.esc_attr($setting['field']).'['.esc_attr($gateway).'][apply_fee]" value="1"'
        .(isset($saved_value[$gateway]['apply_fee']) && $saved_value[$gateway]['apply_fee'] == 1 ? ' checked="checked"' : '')
        .' /> Enable processing fee</label>';

        $fee_type = '<select class="form-select form-control" name="'.esc_attr($setting['field']).'['.esc_attr($gateway).'][fee_type]" id="'.esc_attr($setting['field']).'['.esc_attr($gateway).'][fee_type]" required>';
        $fee_type .= '<option value="fixed" '.selected(isset($saved_value[$gateway]['fee_type']) ? $saved_value[$gateway]['fee_type'] : '', 'fixed', false).' title="'.esc_attr__('Payment processing charge will be fixed amount', 'credit-card-processing-fee-for-woocommerce').'">'.esc_html__('Fixed Amount', 'credit-card-processing-fee-for-woocommerce').'</option>';
        $fee_type .= '<option value="percentage" '.selected(isset($saved_value[$gateway]['fee_type']) ? $saved_value[$gateway]['fee_type'] : '', 'percentage', false).' title="'.esc_attr__('Payment processing charge will be a percentage of the order total', 'credit-card-processing-fee-for-woocommerce').'">'.esc_html__('Percentage', 'credit-card-processing-fee-for-woocommerce').'</option>';
        $fee_type .= '</select>';
        
        $field = '<input type="number" class="form-control mr-1" name="'.esc_attr($setting['field']).'['.esc_attr($gateway).'][amount]" id="'.esc_attr($setting['field']).'['.esc_attr($gateway).'][amount]" value="'.esc_attr($value).'"'
        .(isset($setting['required']) ? ' required="'.esc_attr($setting['required']).'"': '')
        .(isset($setting['readonly']) ? ' readonly="'.esc_attr($setting['readonly']).'"': '')
        .' min="0" step="any" placeholder="'.esc_attr__('Enter amount', 'credit-card-processing-fee-for-woocommerce').'" />';
    
        $min_amount = '<input type="number" class="form-control" name="'.esc_attr($setting['field']).'['.esc_attr($gateway).'][min_amount]" id="'.esc_attr($setting['field']).'['.esc_attr($gateway).'][min_amount]" value="'.esc_attr($min_amount_val).'"'
        .(isset($setting['required']) ? ' required="'.esc_attr($setting['required']).'"': '')
        .(isset($setting['readonly']) ? ' readonly="'.esc_attr($setting['readonly']).'"': '')
        .' min="0" step="any" placeholder="'.esc_attr__('Min amount', 'credit-card-processing-fee-for-woocommerce').'" />';

        $max_amount = '<input type="number" class="form-control" name="'.esc_attr($setting['field']).'['.esc_attr($gateway).'][max_amount]" id="'.esc_attr($setting['field']).'['.esc_attr($gateway).'][max_amount]" value="'.esc_attr($max_amount_val).'"'
        .(isset($setting['required']) ? ' required="'.esc_attr($setting['required']).'"': '')
        .(isset($setting['readonly']) ? ' readonly="'.esc_attr($setting['readonly']).'"': '')
        .' min="0" step="any" placeholder="'.esc_attr__('Max amount', 'credit-card-processing-fee-for-woocommerce').'" />';


        // Tax class dropdown
        $tax_classes = \WC_Tax::get_tax_classes();
        $tax_class_options = array('' => __('Not taxable', 'credit-card-processing-fee-for-woocommerce'));
        $tax_class_options['standard'] = __('Standard', 'credit-card-processing-fee-for-woocommerce');
        foreach ($tax_classes as $tax_class) {
            $tax_class_options[$tax_class] = $tax_class;
        }
        $selected_tax_class = isset($saved_value[$gateway]['tax_class']) ? $saved_value[$gateway]['tax_class'] : '';
        $tax_class_dropdown = '<select class="form-select form-control" name="'.esc_attr($setting['field']).'['.esc_attr($gateway).'][tax_class]" id="'.esc_attr($setting['field']).'['.esc_attr($gateway).'][tax_class]">';
        foreach ($tax_class_options as $value_option => $label_option) {
            $tax_class_dropdown .= '<option value="'.esc_attr($value_option).'" '.selected($selected_tax_class, $value_option, false).'>'.esc_html($label_option).'</option>';
        }
        $tax_class_dropdown .= '</select>';

        $shortcode_html = '';

        $this->bootstrap2($setting, $label, $name, $apply_fee, $fee_type, $field, $min_amount, $max_amount, $desc, $shortcode_html, 6, $tax_class_dropdown);
    }

    function gateways_options(){
        $payment_gateways = WC()->payment_gateways->payment_gateways();
        $skip_gateways = array();
        $gateways = array();
        $logged_gateways = get_option('pisol_logged_gateways', array());
        foreach($payment_gateways as $gateway){
            if(!in_array($gateway->id, $skip_gateways)){
                $gateways[$gateway->id] = $gateway->get_title();
            }
        }
        
        if(is_array($logged_gateways) && !empty($logged_gateways)){
            foreach($logged_gateways as $gateway_id => $gateway_title){
                if(!isset($gateways[$gateway_id])){
                    $gateways[$gateway_id] = $gateway_title;
                }
            }
        }

        return $gateways;
    }

    function bootstrap2($setting, $label, $name, $apply_fee,  $fee_type, $field,$min_amount, $max_amount, $desc = "",$shortcode_html = '',  $title_col = 5, $tax_class_dropdown = null){
        $setting_col = 12 - $title_col;
        ?>
        <div id="row_<?php echo esc_attr($setting['field']); ?>"  class="row py-5 border-bottom align-items-center <?php echo !empty($setting['class']) ? esc_attr($setting['class']) : ''; ?>">
            <div class="col-12 col-md-2">
            <?php echo wp_kses($label, $this->allowed_tags); ?>
            <?php echo wp_kses($desc != "" ? $desc.'<br>': "", $this->allowed_tags); ?>
            <?php if(!empty($shortcode_html)): ?>
                <div class="mt-2">
                    <small><?php esc_html_e('Short codes:','credit-card-processing-fee-for-woocommerce'); ?><br> <?php echo wp_kses($shortcode_html, $this->allowed_tags); ?></small>
                </div>
            <?php endif; ?>
            </div>
            <div class="col-12 col-md-3">
            <?php echo wp_kses($apply_fee, $this->allowed_tags); ?>
            </div>

            <div class="col-12 col-md-7 processing-fields">
                <div class="row">
                    <div class="col-12 col-md-12 mb-3">
                        <p class="font-italic mb-1">Label for processing fee</p>
                        <?php echo wp_kses($name, $this->allowed_tags); ?>
                    </div>
                    <div class="col-12 col-md-12 mb-3">
                        <p class="font-italic mb-1">Processing fee amount (fixed or percentage of total)</p>
                        <div class="d-flex align-items-center">
                        <?php echo wp_kses($field, $this->allowed_tags); ?>
                        <?php echo wp_kses($fee_type, $this->allowed_tags); ?>
                        </div>
                    </div>
                    <div class="col-12 col-md-12 mb-3">
                        <p class="font-italic mb-1">Tax class for the processing fee.</p>
                        <?php if($tax_class_dropdown) echo wp_kses($tax_class_dropdown, $this->allowed_tags); ?>
                    </div>
                    <div class="col-12 col-md-12">
                        <p class="font-italic mb-1">Minimum and maximum amount for the processing fee <span class="text-danger">(PRO).</span></p>
                        <div class="d-flex align-items-center free-version">
                            <?php echo wp_kses($min_amount, $this->allowed_tags); ?>
                            <span class="mx-2">to</span>
                            <?php echo wp_kses($max_amount, $this->allowed_tags); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
}

CustomFields::get_instance();
<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }

class Pi_cefw_selection_rule_postcode{
    public $slug;
    public $condition;
    
    function __construct($slug){
        $this->slug = $slug;
        $this->condition = 'postcode';
        /* this adds the condition in set of rules dropdown */
        add_filter("pi_".$this->slug."_condition", array($this, 'addRule'));
        
        /* this gives value field blank of populated */
        add_action( 'wp_ajax_pi_'.$this->slug.'_value_field_'.$this->condition, array( $this, 'ajaxCall' ) );


        add_filter('pi_'.$this->slug.'_saved_values_'.$this->condition, array($this, 'savedDropdown'), 10, 3);

        add_filter('pi_'.$this->slug.'_condition_check_'.$this->condition,array($this,'conditionCheck'),10,4);

        add_action('pi_'.$this->slug.'_logic_'.$this->condition, array($this, 'logicDropdown'));

        add_filter('pi_'.$this->slug.'_saved_logic_'.$this->condition, array($this, 'savedLogic'),10,3);
    }

    function addRule($rules){
        $rules[$this->condition] = array(
            'name'=>__('Postcode','conditional-extra-fees-woocommerce'),
            'group'=>'location_related',
            'condition'=>$this->condition
        );
        return $rules;
    }

    function logicDropdown(){
        $html = "";
        $html .= 'var pi_logic_'.$this->condition.'= "<select class=\'form-control\' name=\'pi_selection[{count}][pi_'.esc_attr($this->slug).'_logic]\'>';
    
            $html .= '<option value=\'equal_to\'>Equal to ( = )</option>';
			$html .= '<option value=\'not_equal_to\'>Not Equal to ( != )</option>';
        
        $html .= '</select>";';
        //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        echo $html;
    }

    function savedLogic($html_in, $saved_logic, $count){
        $html = "";
        $html .= '<select class="form-control" name="pi_selection['.$count.'][pi_'.$this->slug.'_logic]">';

            $html .= '<option value=\'equal_to\' '.selected($saved_logic , "equal_to",false ).'>Equal to ( = )</option>';
			$html .= '<option value=\'not_equal_to\' '.selected($saved_logic , "not_equal_to",false ).'>Not Equal to ( != )</option>';
        
        
        $html .= '</select>';
        return $html;
    }

    function ajaxCall(){
        if(!current_user_can( 'manage_options' )) {
            return;
            die;
        }
        $count = filter_input(INPUT_POST,'count',FILTER_VALIDATE_INT);
        //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        echo Pi_cefw_selection_rule_main::createTextField($count, $this->condition, null);
        //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        echo $this->description();
        die;
    }

    function savedDropdown($html, $values, $count){
        $html = Pi_cefw_selection_rule_main::createTextField($count, $this->condition,  $values);
        $html .= $this->description();
        return $html;
    }

    function description(){
        $html = '<a href="#TB_inline?width=600&inlineId=pi_info_postcode" title="Using postcode" class="thickbox">How to insert multiple post code, range of post code and wildcard post code</a>
        <div id="pi_info_postcode" style="display:none;">
        <div>
            <strong>1) Inserting multiple post codes:</strong>
            <p>E.g: <b>4219, 2344, 2344</b></p>
            <strong>2) Inserting range of post codes:</strong>
            <p>To insert range of post code you have to use triple dots like this "..." between the range start number and range end number. The below example will covet all the post codes between range 4319 up to 4350<br>
            <b>E.g: 4319...4350</b></p>
            <strong>3) Post selection based on wildcard matching:</strong>
            <p><b>E.g: CM2*</b><br> The above rule will match any post code starting with CM2 in this "*" is must</p>
            <strong>4) Combining multiple rule</strong>
            <p>
            <b>E.g: 4215, 4210...4250, CM2*, CMN3*</b><br>
            If you add the above it will march
            4215<br>
            Any post code in between 4210 to 4250<br>
            Post code starting with CM2<br>
            Post code starting with CMN3
            </p>
        </div>
        </div>
        ';
        return $html;
    }


    function conditionCheck($result, $package, $logic, $values){
        
                    $or_result = false;
                    $cart_postcode = $this->getUserPostCode( $package );
                    $rules = $this->getPostCodes( $values[0] );
                    $post_code_matched = $this->postCodeMatched( $cart_postcode, $rules);
                    $rule_cart_postcode = $values[0];
                    switch ($logic){
                        case 'equal_to':
                            if($post_code_matched){
                                $or_result = true;
                            }
                        break;

                        case 'not_equal_to':
                            if($post_code_matched){
                                $or_result = false;
                            }else{
                                $or_result = true;
                            }
                        break;
                    }
               
        return  $or_result;
    }

    function getPostCodes( $text_value ){
        $post_codes = array();
        $values = explode(',', $text_value);
        $post_codes = array_map( 'trim', $values );
        return $post_codes;
    }

    function postCodeMatched( $post_code, $rules){
        
        foreach($rules as $rule){

            $object[] = (object)array(
                'zone_id'=> 1,
                'location_code'=> $rule
            );

            $country = apply_filters('pisol_cefw_postcode_country',WC()->customer->get_shipping_country());
            /**
             * this is woocommerce location matcher function
             */
            $match = wc_postcode_location_matcher($post_code, $object, 'zone_id', 'location_code', $country);

            if(count($match) > 0 ) return true;
           
        }

        return false;
    }

    function getUserPostCode( $package ){
        
        $postcode = '';
        if(is_a($package, 'WC_Cart')){
            $postcode = WC()->customer->get_shipping_postcode();
        }elseif(is_a($package, 'WC_Order')){
            $billing_postcode = $package->get_billing_postcode();
            $shipping_postcode = $package->get_shipping_postcode();
            if(empty($shipping_postcode)){
                $postcode = $billing_postcode;
            }else{
                $postcode = $shipping_postcode;
            }
        }
        return $postcode;
    }
}

new Pi_cefw_selection_rule_postcode(PI_CEFW_SELECTION_RULE_SLUG);
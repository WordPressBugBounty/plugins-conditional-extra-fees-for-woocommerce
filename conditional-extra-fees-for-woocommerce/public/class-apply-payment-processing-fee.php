<?php 

defined('ABSPATH') || exit;

class Apply_Payment_Processing_Fee{

    private static $instance = null;

    public static function get_instance() {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    function __construct() {
        add_action('woocommerce_cart_calculate_fees', array($this, 'apply_gateway_fee'), PHP_INT_MAX);
    }


    public function apply_gateway_fee($cart) {
        if (is_admin() && !defined('DOING_AJAX')) {
            return;
        }

        // Get selected payment method from session
        $chosen_gateway = null;
        if (isset(WC()->session)) {
            $chosen_payment_methods = WC()->session->get('chosen_payment_method');
            if (is_array($chosen_payment_methods)) {
                $chosen_gateway = reset($chosen_payment_methods);
            } else {
                $chosen_gateway = $chosen_payment_methods;
            }
        }

        if (!$chosen_gateway) {
            return;
        }

        // Get gateway fee settings
        $settings = get_option('pisol_cefw_payment_gateway_charges', array());
        if (!isset($settings[$chosen_gateway])) {
            return;
        }

        $fee_data = $settings[$chosen_gateway];
        if (!is_array($fee_data) || empty($fee_data)) {
            return;
        }

        if(empty($fee_data['apply_fee'])) {
            return;
        }

        $amount = isset($fee_data['amount']) ? floatval($fee_data['amount']) : 0;
        $fee_type = isset($fee_data['fee_type']) ? $fee_data['fee_type'] : 'fixed';
        if ($amount <= 0) {
            return;
        }

        // Calculate fee
        $fee = 0;
        if ($fee_type === 'percentage') {
            // Calculate base: product total + shipping - discount + other fees
            $product_total = $cart->get_subtotal();
            $shipping_total = $cart->get_shipping_total();
            $discount_total = $cart->get_discount_total();
            $tax_total = $cart->get_taxes_total();
            $other_fees_total = 0;
            foreach ($cart->get_fees() as $cart_fee) {
                $other_fees_total += $cart_fee->amount;
            }
            $base_amount = $product_total + $shipping_total - $discount_total + $other_fees_total + $tax_total;
            $fee = ($base_amount * $amount) / 100;
        } else {
            $fee = pisol_cefw_multiCurrencyFilters($amount);
        }

        $tax_class = isset($fee_data['tax_class']) ? $fee_data['tax_class'] : '';

        $label = isset($fee_data['name']) ? $fee_data['name'] : __('Payment processing fee', 'conditional-extra-fees-woocommerce');

        if ($fee > 0) {
            if(!empty($tax_class)){
                $cart->add_fee($label, $fee, true, $tax_class);
            }else{
                $cart->add_fee($label, $fee, false, '');
            }
        }
    }
}

Apply_Payment_Processing_Fee::get_instance();
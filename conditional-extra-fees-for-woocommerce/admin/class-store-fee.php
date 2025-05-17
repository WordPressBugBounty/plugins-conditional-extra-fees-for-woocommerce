<?php
/**
 * It stores the fees id in the table woocommerce_order_itemmeta so we can track back fees to our fees
 */
class pisol_cefw_store_fee_id_order_meta{

    protected static $instance = null;

    public static function get_instance( ) {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

    function __construct(){
        add_action( 'woocommerce_new_order_item', [$this, 'add_fee_id_to_order_meta'], 10, 3 );
    }

    
    
    function add_fee_id_to_order_meta($item_id, $item, $order_id){
        global $wpdb;
        $table = $wpdb->prefix.'woocommerce_order_itemmeta';

        if( method_exists($item, 'get_type') && $item->get_type() == 'fee'){

            if(isset($item->legacy_fee_key) && !empty($item->legacy_fee_key)){
                
                $data = [
                    'order_item_id' => $item_id,
                    'meta_key' => '_legacy_fee_key',
                    'meta_value' => $item->legacy_fee_key
                ];
                $wpdb->insert($table, $data);
            }

            if(isset($item->legacy_fee->fee_is_combination_of)){
                $combination_of = json_encode($item->legacy_fee->fee_is_combination_of);

                $data = [
                    'order_item_id' => $item_id,
                    'meta_key' => '_fee_is_combination_of',
                    'meta_value' => $combination_of
                ];
                $wpdb->insert($table, $data);
            }

            $data2 = [
                'order_item_id' => $item_id,
                'meta_key' => '_fee_order_id',
                'meta_value' => $order_id
            ];
            $wpdb->insert($table, $data2);
        }
       
    }
}

pisol_cefw_store_fee_id_order_meta::get_instance();
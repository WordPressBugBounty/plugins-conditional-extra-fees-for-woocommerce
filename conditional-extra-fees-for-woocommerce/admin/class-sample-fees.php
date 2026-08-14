<?php

if ( ! defined( 'ABSPATH' ) ) exit;

class pisol_cefw_sample_fees {

    static $instance = null;

    public static function get_instance() {
        if ( is_null( self::$instance ) ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public static function default_fees() {
        $created = get_option( 'pisol_cefw_sample_fees_created', 0 );
        if ( empty( $created ) ) {
            self::create_default_fees();
            update_option( 'pisol_cefw_sample_fees_created', 1 );
        }
    }

    public static function create_default_fees() {
        $fees = array();

        // Never seed if the user already has any fee rules — covers reactivation,
        // migration from another site, or manual creation before this runs.
        if ( self::get_fee_count() > 0 ) {
            return $fees;
        }

        $fees[] = self::small_order_handling_fee();
        $fees[] = self::delivery_assembly_fee();
        $fees[] = self::gift_wrap_fee();
        $fees[] = self::remove_location_fees();
        $fees[] = self::international_heavy_item_surcharge();

        foreach ( $fees as $fee ) {
            self::create_fee( $fee );
        }

        return $fees;
    }

    public static function get_fee_count() {
        $existing = get_posts([
            'post_type'   => 'pi_fees_rule',
            'post_status' => ['publish'],
            'numberposts' => 1
        ]);
        return $existing ? count( $existing ) : 0;
    }

    static function remove_location_fees() {
        $metabox = [];
        $metabox[] = array(
            'pi_condition' => 'country',
            'pi_logic'     => 'equal_to',
            'pi_value'     => ['AF', 'AL'],
        );

        return array(
            'pi_title'            => 'Sample: Remove Location Fees',
            'pi_fees_type'        => 'percentage', // ⚠️ confirm accepted value
            'pi_fees'             => 5,
            'pi_condition_logic'  => 'and',
            'pi_metabox'          => $metabox,
        );
    }

    static function small_order_handling_fee() {
        $metabox = [];
        $metabox[] = array(
            'pi_condition' => 'cart_subtotal_before_fees',
            'pi_logic'     => 'less_equal_to',
            'pi_value'     => [500],
        );

        return array(
            'pi_title'            => 'Sample: Small Order Handling Fee',
            'pi_fees_type'        => 'fixed', // ⚠️ confirm accepted value
            'pi_fees'             => 49,
            'pi_condition_logic'  => 'and',
            'pi_metabox'          => $metabox,
        );
    }

    static function international_heavy_item_surcharge() {
        $metabox = [];

        $shop_country = get_option( 'woocommerce_store_country' );
        if ( ! $shop_country ) {
            $shop_country = 'US'; // Default to US if no country is set
        }

        $metabox[] = array(
            'pi_condition' => 'country',
            'pi_logic'     => 'not_equal_to',
            'pi_value'     => [ $shop_country ], // reuse the get_option('woocommerce_store_country') trick from HSMCW
        );

        $metabox[] = array(
            'pi_condition' => 'weight',
            'pi_logic'     => 'greater_then',
            'pi_value'     => [5],
        );

        return array(
            'pi_title'            => 'Sample: International Heavy Item Surcharge',
            'pi_fees_type'        => 'fixed', // ⚠️ confirm accepted value
            'pi_fees'             => 49,
            'pi_condition_logic'  => 'and',
            'pi_metabox'          => $metabox,
        );
    }

    static function gift_wrap_fee() {
        $metabox = [];
        $metabox[] = array(
            'pi_condition' => 'quantity',
            'pi_logic'     => 'greater_then',
            'pi_value'     => [1],
        );

        return array(
            'pi_title'            => 'Sample: Gift Wrap Fee',
            'pi_fees_type'        => 'fixed', // ⚠️ confirm accepted value
            'pi_fees'             => 10,
            'pi_condition_logic'  => 'and',
            'pi_metabox'          => $metabox,
            'pi_is_optional_fees' => 'yes',
        );
    }

    static function delivery_assembly_fee() {
        $metabox = [];
        $metabox[] = array(
            'pi_condition' => 'weight',
            'pi_logic'     => 'greater_then',
            'pi_value'     => [50],
            
        );

        return array(
            'pi_title'            => 'Sample: Delivery & Assembly Fee',
            'pi_fees_type'        => 'fixed', // ⚠️ confirm accepted value
            'pi_fees'             => 99,
            'pi_condition_logic'  => 'and',
            'pi_metabox'          => $metabox,
            'pi_is_optional_fees' => 'yes',
        );
    }

    static function create_fee( $fee ) {
        $post_data = [
            'post_title'  => $fee['pi_title'],
            'post_status' => 'publish',
            'post_type'   => 'pi_fees_rule',
        ];

        $post_id = wp_insert_post( $post_data );

        if ( is_wp_error( $post_id ) ) {
            return false;
        }

        // Explicit 'off' — do NOT rely on empty/unset, since the edit-form
        // logic in Class_Pi_cefw_Add_Edit treats empty pi_status as checked/on.
        update_post_meta( $post_id, 'pi_status', 'off' );

        // Extra safety: mark as optional so it's opt-in at checkout even
        // if a user later flips pi_status to on without reading the description.
        update_post_meta( $post_id, 'pi_is_optional_fees', $fee['pi_is_optional_fees'] ?? '' );

        update_post_meta( $post_id, 'pi_fees_type', $fee['pi_fees_type'] );
        update_post_meta( $post_id, 'pi_fees', $fee['pi_fees'] );
        update_post_meta( $post_id, 'pi_fees_taxable', 'no' );
        update_post_meta( $post_id, 'pi_fees_tax_class', '' );
        update_post_meta( $post_id, 'pi_fees_start_time', '' );
        update_post_meta( $post_id, 'pi_fees_end_time', '' );
        update_post_meta( $post_id, 'pi_condition_logic', $fee['pi_condition_logic'] );
        update_post_meta( $post_id, 'pi_currency', [] );
        update_post_meta( $post_id, 'round_off', '' );
        update_post_meta( $post_id, 'pi_metabox', $fee['pi_metabox'] );

        do_action( 'pisol_cefw_save_extra_charge', $post_id );

        return $post_id;
    }
}

/* Testing the rule creation on plugin activation */
//add_action('wp_loaded', array('pisol_cefw_sample_fees', 'create_default_fees'));

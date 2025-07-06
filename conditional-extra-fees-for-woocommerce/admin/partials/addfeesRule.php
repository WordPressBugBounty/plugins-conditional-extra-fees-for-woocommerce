<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="row border-bottom bg-dark2 align-items-center">
    <div class="col-6 py-2 ">
        <strong class="h5 text-light"><?php echo isset($_GET['action']) && $_GET['action'] === 'edit' ?  esc_html__('Edit fee','conditional-extra-fees-woocommerce') : esc_html__('Add new fee','conditional-extra-fees-woocommerce'); ?></strong>
    </div>
    <div class="col-6 text-right py-2">
        <a href="javascript:void(0)" id="open-all" class="text-light mr-4 small"><?php echo __('Open All ▼','conditional-extra-fees-woocommerce'); ?></a>
        <a href="javascript:void(0)" id="close-all" class="text-light small"><?php echo __('Close All ▲','conditional-extra-fees-woocommerce'); ?></a>
    </div>
</div>

<form method="post" id="pisol-cefw-new-method">

<div class="pi-step-container">
    <div class="pi-step-content">
        <div class="pi-step-header bg-primary text-light">
            <div>
            <strong class="pi-step-title"><?php echo __('Step 1: Basic Settings','conditional-extra-fees-woocommerce'); ?><small>(Required)</small></strong>
            <p>Basic setting of the fee, like Fee amount, Optional fee, and Tax status.</p>
            </div>
            <div>
                <span class="dashicons dashicons-plus-alt2 mr-4"></span>
                <span class="dashicons dashicons-minus mr-4"></span>
            </div>
        </div>
        <div class="pi-step-description">
            <!-- Basic start -->
            <!-- Staturs -->
            <div class="row py-3 border-bottom align-items-center">
                <div class="col-12 col-sm-5">
                    <label for="pi_status" class="h6"><?php echo __('Status','conditional-extra-fees-woocommerce'); ?></label>
                    <br><i>Enable or disable this fee rule</i>
                </div>
                <div class="col-12 col-sm">
                    <div class="custom-control custom-switch">
                    <input type="checkbox" value="1" <?php echo esc_attr($data['pi_status']); ?> class="custom-control-input" name="pi_status" id="pi_status">
                    <label class="custom-control-label" for="pi_status"></label>
                    </div>
                </div>
            </div>

            <!-- Title -->
            <div class="row py-3 border-bottom align-items-center">
                <div class="col-12 col-sm-5">
                    <label for="pi_title" class="h6"><?php echo __('Fees rule title','conditional-extra-fees-woocommerce'); ?> <span class="text-primary">*</span></label>
                    <br><i>Name of the fee rule shown to the customer (required).</i>
                </div>
                <div class="col-12 col-sm">
                    <input type="text" required value="<?php echo esc_attr($data['pi_title']); ?>" class="form-control" name="pi_title" id="pi_title">
                </div>
            </div>

            <!-- Fees type -->
            <div class="row py-3 border-bottom align-items-center">
                <div class="col-12 col-sm-5">
                    <label for="pi_is_taxable" class="h6"><?php echo __('Fees type','conditional-extra-fees-woocommerce'); ?></label>
                    <br><i>Choose how the fee is calculated — Fixed amount or Percentage of cart total.</i>
                </div>
                <div class="col-12 col-sm">
                    <select class="form-control" name="pi_fees_type" id="pi_fees_type">
                        <option value="fixed" <?php selected( $data['pi_fees_type'], "fixed" ); ?>><?php _e('Fixed','conditional-extra-fees-woocommerce'); ?></option>
                        <option value="percentage" <?php selected( $data['pi_fees_type'], "percentage" ); ?>><?php _e('Percentage','conditional-extra-fees-woocommerce'); ?></option>
                    </select>
                </div>
            </div>

            <!-- Fees -->
            <div class="row py-3 border-bottom align-items-center">
                <div class="col-12 col-sm-5">
                    <label for="pi_cost" class="h6"><?php echo __('Fee Amount','conditional-extra-fees-woocommerce'); ?> <span class="text-primary">*</span></label><br><?php pisol_cefw_help::inline_txt('fee_charge_short_code_help','Creating complex fees using short code', 'You can use supported shortcodes (click to view)'); ?>
                </div>
                <div class="col-12 col-sm">
                    <input type="text" required value="<?php echo esc_attr($data['pi_fees']); ?>" class="form-control" name="pi_fees" id="pi_fees">
                </div>
            </div>

            <!-- IS Taxable -->
            <div class="row py-3 border-bottom align-items-center">
                <div class="col-12 col-sm-5">
                    <label for="pi_fees_taxable" class="h6"><?php echo __('Is fees taxable','conditional-extra-fees-woocommerce'); ?> <span class="text-primary">*</span></label>
                    <br><i>Should this fee be considered taxable?</i>
                </div>
                <div class="col-12 col-sm">
                    <select class="form-control" name="pi_fees_taxable" id="pi_fees_taxable">
                        <option value="no" <?php selected( $data['pi_fees_taxable'], "no" ); ?>><?php _e('No','conditional-extra-fees-woocommerce'); ?></option>
                        <option value="yes" <?php selected( $data['pi_fees_taxable'], "yes" ); ?>><?php _e('Yes','conditional-extra-fees-woocommerce'); ?></option>
                    </select>
                </div>
            </div>

            <!-- Tax Class -->
            <div class="row py-3 border-bottom align-items-center" id="row_pi_fees_tax_class">
                <div class="col-12 col-sm-5">
                    <label for="pi_fees_tax_class" class="h6"><?php echo __('Select tax class','conditional-extra-fees-woocommerce'); ?> <span class="text-primary">*</span></label>
                    <br><i><?php echo __('Select the tax class for this fee. If you are not sure, leave it as Standard.','conditional-extra-fees-woocommerce'); ?></i>
                </div>
                <div class="col-12 col-sm">
                    <select class="form-control" name="pi_fees_tax_class" id="pi_fees_tax_class">
                    
                    <?php 
                    echo '<option value="standard" '.selected( $data['pi_fees_tax_class'], 'standard', true ).' >Standard</option>';
                    if(!empty($data['tax_classes']) && is_array($data['tax_classes'])){
                        foreach($data['tax_classes'] as $tax_class){
                            echo '<option value="'.esc_attr($tax_class->slug).'" '.selected( $data['pi_fees_tax_class'], $tax_class->slug, true ).' >'.esc_html($tax_class->name).'</option>';
                        }
                    }
                    ?>
                    </select>
                </div>
            </div>


            <!-- Roundoff -->
            <div class="row py-3 border-bottom align-items-center">
                <div class="col-12 col-sm-5">
                    <label for="pi_cost" class="h6"><?php echo __('Round off to integer','conditional-extra-fees-woocommerce'); ?></label><?php pisol_cefw_help::tooltip('If the fee amount is a floating number then you can round off final fee amount to integer'); ?>
                    <br><i>Round the calculated fee to the nearest whole number.</i>
                </div>
                <div class="col-12 col-sm">
                    <select name="round_off" class="form-control">
                        <option value=""><?php _e('No','conditional-extra-fees-woocommerce'); ?></option>
                        <option value="yes" <?php selected( $data['round_off'], "yes" ); ?> title="E.g: 2.7 = 3, 2.1 = 2, 2.5 = 3"><?php _e('Nearest integer','conditional-extra-fees-woocommerce'); ?></option>   
                        <option value="ceil" <?php selected( $data['round_off'], "ceil" ); ?> title="E.g: 2.7 = 3, 2.1 = 3" disabled="disabled"><?php _e('Nearest higher integer (PRO)','conditional-extra-fees-woocommerce'); ?></option> 
                        <option value="floor" <?php selected( $data['round_off'], "floor" ); ?> title="E.g: 2.7 = 2, 2.1 = 2" disabled="disabled"><?php _e('Nearest lower integer (PRO)','conditional-extra-fees-woocommerce'); ?></option>
                    </select>
                </div>
            </div>

            <!-- Is optional fees -->
            <div class="row py-3 border-bottom align-items-center">
                <div class="col-12 col-sm-5">
                    <label for="pi_is_optional_fees" class="h6"><?php echo __('Is optional fees','conditional-extra-fees-woocommerce'); ?> <span class="text-primary">*</span></label><br>
                    <i><?php echo __('Customer will be having the option to select this fees or not','conditional-extra-fees-woocommerce'); ?></i>
                </div>
                <div class="col-12 col-sm">
                    <select class="form-control" name="pi_is_optional_fees" id="pi_is_optional_fees">
                        <option value="no" <?php selected( $data['pi_is_optional_fees'], "no" ); ?>><?php _e('No','conditional-extra-fees-woocommerce'); ?></option>
                        <option value="yes" <?php selected( $data['pi_is_optional_fees'], "yes" ); ?>><?php _e('Yes','conditional-extra-fees-woocommerce'); ?></option>
                    </select>
                </div>
            </div>

            <!-- Optional Fees Title -->
            <div class="row py-3 border-bottom align-items-center free-version" id="row_pi_optional_title">
                <div class="col-12 col-sm-5">
                    <label for="pi_checkbox_title" class="h6"><?php echo __('Text shown next to the optional fees checkbox','conditional-extra-fees-woocommerce'); ?></label>
                    <br><i><?php echo __('If left blank then Fees title will be used in the checkbox','conditional-extra-fees-woocommerce'); ?></i>
                </div>
                <div class="col-12 col-sm-7">
                    <input type="text" value="" class="form-control" name="pi_checkbox_title" id="pi_checkbox_title">
                </div>
            </div>

            <!-- Auto selected fees -->
            <div class="row py-3 border-bottom align-items-center free-version" id="row_pi_selected_by_default">
                <div class="col-12 col-sm-5">
                    <label for="pi_selected_by_default" class="h6"><?php echo __('Auto selected the fees by default','conditional-extra-fees-woocommerce'); ?></label>
                    <br><i><?php echo __('When the fees is optional, The fees checkbox will be auto selected on the checkout page initially, if customer don\'t want to pay for that fees they can unselect that checkbox and remove that fees','conditional-extra-fees-woocommerce'); ?></i>
                </div>
                <div class="col-12 col-sm">
                    <select class="form-control" name="pi_selected_by_default" id="pi_selected_by_default">
                        <option value="no"><?php _e('No','conditional-extra-fees-woocommerce'); ?></option>
                        <option value="yes"><?php _e('Yes','conditional-extra-fees-woocommerce'); ?></option>
                    </select>
                </div>
            </div>

            <!-- Tool tip -->
            <div class="row py-3 border-bottom align-items-center free-version" id="row_pi_tooltip">
                <div class="col-12 col-sm-5">
                    <label for="pi_tooltip" class="h6"><?php echo __('Tool tip shown next to the fees amount','conditional-extra-fees-woocommerce'); ?> </label>
                    <br><i><?php echo __('Plain text (no HTML) tooltip shown beside the fee on the frontend.','conditional-extra-fees-woocommerce'); ?></i>
                </div>
                <div class="col-12 col-sm-7">
                    <input type="text" value="" class="form-control" name="pi_tooltip" id="pi_tooltip">
                </div>
            </div>

            <!-- currency -->
            <div class="row py-4 border-bottom align-items-center">
                <div class="col-12 col-sm-5">
                    <label for="pi_currency" class="h6"><?php echo __('Apply for currency (useful for multi currency website only)','conditional-extra-fees-woocommerce'); ?></label><br><i><?php echo __('Restrict this fee to a specific currency. Leave empty to apply to all currencies.','conditional-extra-fees-woocommerce'); ?></i>
                </div>
                <div class="col-12 col-sm">
                    <select name="pi_currency[]" id="pi_currency" multiple="multiple">
                            <?php self::get_currency($data['pi_currency']); ?>
                    </select>
                </div>
            </div>

            <!-- Start time -->
            <div class="row py-3 border-bottom align-items-center">
                <div class="col-12 col-sm-5">
                    <label for="pi_cost" class="h6"><?php echo __('Fee Validity Period','conditional-extra-fees-woocommerce'); ?> <span class="text-primary"></span></label>
                    <br><i><?php echo __('Set a start and end date for this fee. If left empty, the fee will be valid indefinitely.','conditional-extra-fees-woocommerce'); ?></i>
                </div>
                <div class="col-12 col-sm-3">
                    <input type="date" value="<?php echo esc_attr($data['pi_fees_start_time']); ?>" class="form-control" name="pi_fees_start_time" id="pi_fees_start_time" autocomplete="off">
                    <i class="d-block mt-2">Start date</i>
                </div>
                <div class="col-12 col-sm-3">
                    <input type="date" value="<?php echo esc_attr($data['pi_fees_end_time']); ?>" class="form-control" name="pi_fees_end_time" id="pi_fees_end_time" autocomplete="off">
                    <i class="d-block mt-2">End date</i>
                </div>
            </div>
            <!-- Basic end -->
        </div>
    </div>
</div>

<div class="pi-step-container">
    <div class="pi-step-content">
        <div class="pi-step-header bg-primary text-light">
            <div>
            <strong class="pi-step-title"><?php echo __('Step 2: When to apply this fee','extended-flat-rate-shipping-woocommerce'); ?><small>(Required)</small></strong>
            <p>Condition that will decide when to apply this fee</p>
            </div>
            <div>
                <span class="dashicons dashicons-plus-alt2 mr-4"></span>
                <span class="dashicons dashicons-minus mr-4"></span>
            </div>
        </div>
        <div class="pi-step-description">
            <!-- Conditions start -->
            <!-- Conditions -->
            <div>
            <?php
            $selection_rule_obj = new Pi_cefw_selection_rule_main(
                __('Selection Rules','conditional-extra-fees-woocommerce'),
                $data['pi_metabox'], $data
            );
            wp_nonce_field( 'add_fees_rule', 'pisol_cefw_nonce');
            ?>
            </div>
            <!-- Conditions end -->
        </div>
    </div>
</div>

<!-- Step 3: Adjust fee charge -->
<div class="pi-step-container">
    <div class="pi-step-content">
        <div class="pi-step-header bg-dark text-light">
            <div>
            <strong class="pi-step-title"><?php echo __('Step 3: Adjust fee charge','conditional-extra-fees-woocommerce'); ?><small>(optional)</small></strong>
            <p>Increment/Decrease fee charge by weight, quantity, subtotal etc. ranges.</p>
            </div>
            <div>
                <span class="dashicons dashicons-plus-alt2 mr-4"></span>
                <span class="dashicons dashicons-minus mr-4"></span>
            </div>
        </div>
        <div class="pi-step-description px-0">
            <!-- extra charge setting start -->
            <!-- Extra charge -->
                <?php do_action('pi_cefw_extra_form_fields', $data); ?>
            <!-- extra charge setting end -->
        </div>
    </div>
</div>



<input type="hidden" name="post_type" value="pi_fees_rule">
<input type="hidden" name="post_id" value="<?php echo esc_attr($data['post_id']); ?>">
<input type="hidden" name="action" value="pisol_cefw_save_method">
<input type="submit" value="<?php _e('Save Rule','conditional-extra-fees-woocommerce'); ?>" name="submit" class="my-3 btn btn-primary btn-md">
</form>
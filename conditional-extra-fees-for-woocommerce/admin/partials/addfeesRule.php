<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<form method="post" id="pisol-cefw-new-method">
<div class="row py-3 border-bottom align-items-center">
    <div class="col-12 col-sm-5">
        <label for="pi_status" class="h6"><?php echo __('Status','conditional-extra-fees-woocommerce'); ?></label>
    </div>
    <div class="col-12 col-sm">
        <div class="custom-control custom-switch">
        <input type="checkbox" value="1" <?php echo esc_attr($data['pi_status']); ?> class="custom-control-input" name="pi_status" id="pi_status">
        <label class="custom-control-label" for="pi_status"></label>
        </div>
    </div>
</div>

<div class="row py-3 border-bottom align-items-center">
    <div class="col-12 col-sm-5">
        <label for="pi_title" class="h6"><?php echo __('Fees rule title','conditional-extra-fees-woocommerce'); ?> <span class="text-primary">*</span></label>
    </div>
    <div class="col-12 col-sm">
        <input type="text" required value="<?php echo esc_attr($data['pi_title']); ?>" class="form-control" name="pi_title" id="pi_title">
    </div>
</div>

<div class="row py-3 border-bottom align-items-center">
    <div class="col-12 col-sm-5">
        <label for="pi_is_taxable" class="h6"><?php echo __('Fees type','conditional-extra-fees-woocommerce'); ?></label>
    </div>
    <div class="col-12 col-sm">
        <select class="form-control" name="pi_fees_type" id="pi_fees_type">
            <option value="fixed" <?php selected( $data['pi_fees_type'], "fixed" ); ?>><?php _e('Fixed','conditional-extra-fees-woocommerce'); ?></option>
            <option value="percentage" <?php selected( $data['pi_fees_type'], "percentage" ); ?>><?php _e('Percentage','conditional-extra-fees-woocommerce'); ?></option>
        </select>
    </div>
</div>

<div class="row py-3 border-bottom align-items-center">
    <div class="col-12 col-sm-5">
        <label for="pi_fees_taxable" class="h6"><?php echo __('Is fees taxable','conditional-extra-fees-woocommerce'); ?> <span class="text-primary">*</span></label>
    </div>
    <div class="col-12 col-sm">
        <select class="form-control" name="pi_fees_taxable" id="pi_fees_taxable">
            <option value="no" <?php selected( $data['pi_fees_taxable'], "no" ); ?>><?php _e('No','conditional-extra-fees-woocommerce'); ?></option>
            <option value="yes" <?php selected( $data['pi_fees_taxable'], "yes" ); ?>><?php _e('Yes','conditional-extra-fees-woocommerce'); ?></option>
        </select>
    </div>
</div>

<div class="row py-3 border-bottom align-items-center" id="row_pi_fees_tax_class">
    <div class="col-12 col-sm-5">
        <label for="pi_fees_tax_class" class="h6"><?php echo __('Select tax class','conditional-extra-fees-woocommerce'); ?> <span class="text-primary">*</span></label>
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

<div class="row py-3 border-bottom align-items-center">
    <div class="col-12 col-sm-5">
        <label for="pi_cost" class="h6"><?php echo __('Fees','conditional-extra-fees-woocommerce'); ?> <span class="text-primary">*</span></label><?php pisol_help::inline('fee_charge_short_code_help','Creating complex fees using short code'); ?>
    </div>
    <div class="col-12 col-sm">
        <input type="text" required value="<?php echo esc_attr($data['pi_fees']); ?>" class="form-control" name="pi_fees" id="pi_fees">
    </div>
</div>

<div class="row py-3 border-bottom align-items-center">
    <div class="col-12 col-sm-5">
        <label for="pi_cost" class="h6"><?php echo __('Round off to integer','conditional-extra-fees-woocommerce'); ?></label><?php pisol_help::tooltip('If the fee amount is a floating number then you can round off final fee amount to integer'); ?>
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

<div class="row py-3 border-bottom align-items-center">
    <div class="col-12 col-sm-5">
        <label for="pi_is_optional_fees" class="h6"><?php echo __('Is optional fees','conditional-extra-fees-woocommerce'); ?> <span class="text-primary">*</span></label><br>
        <small><?php echo __('Customer will be having the option to select this fees or not','conditional-extra-fees-woocommerce'); ?></small>
    </div>
    <div class="col-12 col-sm">
        <select class="form-control" name="pi_is_optional_fees" id="pi_is_optional_fees">
            <option value="no" <?php selected( $data['pi_is_optional_fees'], "no" ); ?>><?php _e('No','conditional-extra-fees-woocommerce'); ?></option>
            <option value="yes" <?php selected( $data['pi_is_optional_fees'], "yes" ); ?>><?php _e('Yes','conditional-extra-fees-woocommerce'); ?></option>
        </select>
    </div>
</div>

<div class="row py-3 border-bottom align-items-center free-version" id="row_pi_optional_title">
    <div class="col-12 col-sm-5">
        <label for="pi_checkbox_title" class="h6"><?php echo __('Text shown next to the optional fees checkbox <br><small>If left blank then Fees title will be used in the checkbox</small>','conditional-extra-fees-woocommerce'); ?></label>
    </div>
    <div class="col-12 col-sm-7">
        <input type="text" value="" class="form-control" name="pi_checkbox_title" id="pi_checkbox_title">
    </div>
</div>

<div class="row py-3 border-bottom align-items-center free-version" id="row_pi_selected_by_default">
    <div class="col-12 col-sm-5">
        <label for="pi_selected_by_default" class="h6"><?php echo __('Auto selected the fees by default','conditional-extra-fees-woocommerce'); ?></label>
        <p><?php echo __('When the fees is optional, The fees checkbox will be auto selected on the checkout page initially, if customer don\'t want to pay for that fees they can unselect that checkbox and remove that fees','conditional-extra-fees-woocommerce'); ?></p>
    </div>
    <div class="col-12 col-sm">
        <select class="form-control" name="pi_selected_by_default" id="pi_selected_by_default">
            <option value="no"><?php _e('No','conditional-extra-fees-woocommerce'); ?></option>
            <option value="yes"><?php _e('Yes','conditional-extra-fees-woocommerce'); ?></option>
        </select>
    </div>
</div>

<div class="row py-3 border-bottom align-items-center free-version" id="row_pi_tooltip">
    <div class="col-12 col-sm-5">
        <label for="pi_tooltip" class="h6"><?php echo __('Tool tip shown next to the fees amount','conditional-extra-fees-woocommerce'); ?> <br><small><?php echo __('This text will be shown in the tooltip next to the fees amount (only use text no html tag allowed)','conditional-extra-fees-woocommerce'); ?></small></label>
    </div>
    <div class="col-12 col-sm-7">
        <input type="text" value="" class="form-control" name="pi_tooltip" id="pi_tooltip">
    </div>
</div>

<div class="row py-4 border-bottom align-items-center">
    <div class="col-12 col-sm-5">
        <label for="pi_currency" class="h6"><?php echo __('Apply for currency (useful for multi currency website only)','conditional-extra-fees-woocommerce'); ?></label><br><strong><?php echo __('Leave empty if you want to apply for all currency OR you have single currency','conditional-extra-fees-woocommerce'); ?></strong>
    </div>
    <div class="col-12 col-sm">
        <select name="pi_currency[]" id="pi_currency" multiple="multiple">
                <?php self::get_currency($data['pi_currency']); ?>
        </select>
    </div>
</div>

<div class="row p-2 bg-primary text-light">
<div class="col-12">
<p class="text-light">
<?php __('In free version fees percent is calculated on Cart total, Where as in pro version you can make it to calculate based on the total of the product that are selected based on the Product selection rule set in the fees rule','conditional-extra-fees-woocommerce'); ?>
</p>
</div>
</div>



<div class="row py-3 border-bottom align-items-center">
    <div class="col-12 col-sm-5">
        <label for="pi_cost" class="h6"><?php echo __('Fees start Time','conditional-extra-fees-woocommerce'); ?> <span class="text-primary"></span></label>
    </div>
    <div class="col-12 col-sm d-flex align-items-center">
        <input type="text" readonly value="<?php echo esc_attr($data['pi_fees_start_time']); ?>" class="form-control" name="pi_fees_start_time" id="pi_fees_start_time" autocomplete="off"><a href="javascript:void(0)" class="pi-clear btn btn-md btn-danger text-nowrap"><?php _e('Clear date','conditional-extra-fees-woocommerce'); ?></a>
    </div>
</div>



<div class="row py-3 border-bottom align-items-center">
    <div class="col-12 col-sm-5">
        <label for="pi_cost" class="h6"><?php _e('Fees end time','conditional-extra-fees-woocommerce'); ?> <span class="text-primary"></span></label>
    </div>
    <div class="col-12 col-sm d-flex align-items-center">
        <input type="text" readonly value="<?php echo esc_attr($data['pi_fees_end_time']); ?>" class="form-control" name="pi_fees_end_time" id="pi_fees_end_time" autocomplete="off"><a href="javascript:void(0)" class="pi-clear btn btn-md btn-danger text-nowrap"><?php _e('Clear date','conditional-extra-fees-woocommerce'); ?></a>
    </div>
</div>
<div style="border-top:4px solid orange; border-bottom:4px solid orange">
<?php
$selection_rule_obj = new Pi_cefw_selection_rule_main(
    __('Selection Rules','conditional-extra-fees-woocommerce'),
    $data['pi_metabox'], $data
);
wp_nonce_field( 'add_fees_rule', 'pisol_cefw_nonce');
?>
</div>

<?php do_action('pi_cefw_extra_form_fields', $data); ?>

<input type="hidden" name="post_type" value="pi_fees_rule">
<input type="hidden" name="post_id" value="<?php echo esc_attr($data['post_id']); ?>">
<input type="hidden" name="action" value="pisol_cefw_save_method">
<input type="submit" value="<?php _e('Save Rule','conditional-extra-fees-woocommerce'); ?>" name="submit" class="m-2 mt-5 btn btn-primary btn-lg">
</form>
<!--<div class="alert alert-info mt-2"><strong><?php //pisol_cefw_help::youtube('oGE6daMXrOk','Know more about the Additional Charges'); ?> Click to Know more about this Additional Charges feature </strong></div>-->
<div class="border-top bg-dark2 p-3">
<div class="row">
    <div class="col-6"><label for="pi_enable_additional_charges" class="text-light mb-0"><?php _e('Increase / Decrease Fees by this extra rules', 'conditional-extra-fees-woocommerce'); ?></label><?php pisol_cefw_help::inline('inc_dec_fees_help', 'Increase / Decrease Fees '); ?></div>
    <div class="col-6">
        <div class="custom-control custom-switch">
            <input type="checkbox" value="1" <?php echo $data['pi_enable_additional_charges']; ?> class="custom-control-input" name="pi_enable_additional_charges" id="pi_enable_additional_charges">
            <label class="custom-control-label" for="pi_enable_additional_charges"></label>
        </div>
    </div>
</div>
</div>
<div id="additional-charges-container">
    <div class="row no-gutters">
        <div class="col-3">
            <?php do_action('pi_cefw_additional_charges_tab', $data); ?>
        </div>
        <div class="col-9">
            <?php do_action('pi_cefw_additional_charges_tab_content', $data); ?>
        </div>
    </div>
</div>
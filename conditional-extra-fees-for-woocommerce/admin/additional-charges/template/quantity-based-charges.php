<script>
    var pi_cart_quantity_charges_count = <?php echo count(!empty($data['pi_cart_quantity_charges']) && is_array($data['pi_cart_quantity_charges'])? $data['pi_cart_quantity_charges'] : array()) ; ?>
</script>
<div class="p-3 bg-dark">
<div class="row">
    <div class="col-6"><label for="pi_enable_additional_charges_cart_quantity" class="mb-0 text-light"><?php _e('Change Fees based on Cart Quantity', 'conditional-extra-fees-woocommerce'); ?></label> <?php //pisol_cefw_help::youtube('0CTSrfgaKvc','Know more about the Cart Quantity based charge'); ?></div>
    <div class="col-6">
        <div class="custom-control custom-switch">
            <input type="checkbox" value="1" <?php echo $data['pi_enable_additional_charges_cart_quantity']; ?> class="custom-control-input" name="pi_enable_additional_charges_cart_quantity" id="pi_enable_additional_charges_cart_quantity">
            <label class="custom-control-label" for="pi_enable_additional_charges_cart_quantity"></label>
        </div>
    </div>
</div>
</div>
<div id="additional_charges_cart_quantity_container">
<div class="row py-3">
    <div class="col-6">
        <a href="javascript:void(0)" class="btn btn-primary btn-sm" id="add_cart_quantity_charges_range"><?php _e('Add Rule', 'conditional-extra-fees-woocommerce'); ?></a>
    </div>
    <div class="col-6">
        <?php pisol_cefw_additional_charges_form::sumOfCharges('pi_cefw_cart_quantity_sum_of_charges', $data); ?>
    </div>
</div>
<template id="cart_quantity_charges_template" >
    <tr>
        <td><?php _e('Cart Quantity', 'conditional-extra-fees-woocommerce'); ?></td>
        <td class="pi-min-col"><input type="number" required name="pi_cart_quantity_charges[{{count}}][min]" min="1" class="form-control"></td>
        <td class="pi-max-col"><input type="number" name="pi_cart_quantity_charges[{{count}}][max]" min="1" class="form-control"></td>
        <td class="pi-fee-col"><input type="text" required name="pi_cart_quantity_charges[{{count}}][charge]" class="form-control"></td>
        <td><button class="delete-additional-charges btn btn-danger btn-sm"><span class="dashicons dashicons-trash"></span></button></td>
    </tr>
</template>
<table id="cart_quantity_charges_table" class="table">
    <thead>
        <tr>
            <th><?php _e('Category', 'conditional-extra-fees-woocommerce'); ?></th>
            <th class="pi-min-col"><?php _e('Min Quantity', 'conditional-extra-fees-woocommerce'); ?></th>
            <th class="pi-min-col"><?php _e('Max Quantity', 'conditional-extra-fees-woocommerce'); ?></th>
            <th class="pi-fee-col"><?php _e('Fees', 'conditional-extra-fees-woocommerce'); ?> <?php pisol_cefw_help::inline('cart_weight_short_code_help', 'Using short code'); ?></th>
            <th></th>
        </tr>
    </thead>
    <tbody>
    <?php $this->rowTemplate($data); ?>
    </tbody>
</table>
</div>

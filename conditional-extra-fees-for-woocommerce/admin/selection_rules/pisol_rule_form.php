<div class="row py-3 border-bottom align-items-center">
    <div class="col-12 col-md-5">
        <strong class="h4"><?php echo $this->title; ?> <span class="text-primary">*</span></strong><br><i>This rules decide if this fees will be applied or not</i>
    </div>
    <div class="col-12 col-md-5">
        <select class="form-control" name="pi_condition_logic">
            <option value="and" <?php selected( $this->data['pi_condition_logic'], 'and' ); ?>><?php echo __('All the below rules should match', 'conditional-extra-fees-woocommerce'); ?></option>
            <option value="or" <?php selected( $this->data['pi_condition_logic'], 'or' ); ?>><?php echo __('Any one of the below rule should match', 'conditional-extra-fees-woocommerce'); ?></option>
        </select>
    </div>
    <div class="col-12 col-md-2 text-right">
        <a href="javascript:void(0);" class="btn btn-primary btn-sm" id="pi-add-<?php echo PI_CEFW_SELECTION_RULE_SLUG; ?>-rule" data-target="#pisol-rules-container-<?php echo $this->slug; ?>"><?php echo __('Add Condition','conditional-extra-fees-woocommerce'); ?></a>
    </div>
</div>
<?php echo $this->conditionDropdownScript(); ?>
<?php $this->logicDropdownScript(); ?>
<?php echo $this->savedConditions($this->saved_conditions); ?>
<div id="pisol-rules-container-<?php echo $this->slug; ?>">
<?php echo $this->savedRows(); ?>
</div>
<div class="row">
    <div class="col-12 text-right py-3">
    <a href="javascript:void(0);" class="btn btn-primary btn-sm pi-add-<?php echo PI_CEFW_SELECTION_RULE_SLUG; ?>-rule" data-target="#pisol-rules-container-<?php echo $this->slug; ?>"><?php echo __('Add Condition','conditional-extra-fees-woocommerce'); ?></a>
    </div>
</div>
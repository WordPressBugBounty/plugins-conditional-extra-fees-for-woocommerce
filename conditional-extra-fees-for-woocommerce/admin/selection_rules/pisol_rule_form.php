<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }
?>
<div class="row py-3 border-bottom align-items-center">
    <div class="col-12 col-md-5">
        <strong class="h4"><?php echo esc_html($this->title); ?> <span class="text-primary">*</span></strong><br><i>This rules decide if this fees will be applied or not</i>
    </div>
    <div class="col-12 col-md-5">
        <select class="form-control" name="pi_condition_logic">
            <option value="and" <?php selected( $this->data['pi_condition_logic'], 'and' ); ?>><?php echo esc_html(__('All the below rules should match', 'conditional-extra-fees-woocommerce')); ?></option>
            <option value="or" <?php selected( $this->data['pi_condition_logic'], 'or' ); ?>><?php echo esc_html(__('Any one of the below rule should match', 'conditional-extra-fees-woocommerce')); ?></option>
        </select>
    </div>
    <div class="col-12 col-md-2 text-right">
        <a href="javascript:void(0);" class="btn btn-primary btn-sm" id="pi-add-<?php echo esc_attr(PI_CEFW_SELECTION_RULE_SLUG); ?>-rule" data-target="#pisol-rules-container-<?php echo esc_attr($this->slug); ?>"><?php echo esc_html(__('Add Condition','conditional-extra-fees-woocommerce')); ?></a>
    </div>
</div>
<?php 
//phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
echo $this->conditionDropdownScript(); 
?>
<?php $this->logicDropdownScript(); ?>
<?php 
//phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
echo $this->savedConditions($this->saved_conditions); ?>
<div id="pisol-rules-container-<?php echo esc_attr($this->slug); ?>">
<?php 
//phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
echo $this->savedRows(); ?>
</div>
<div class="row">
    <div class="col-12 text-right py-3">
    <a href="javascript:void(0);" class="btn btn-primary btn-sm pi-add-<?php echo esc_attr(PI_CEFW_SELECTION_RULE_SLUG); ?>-rule" data-target="#pisol-rules-container-<?php echo esc_attr($this->slug); ?>"><?php echo esc_html(__('Add Condition','conditional-extra-fees-woocommerce')); ?></a>
    </div>
</div>
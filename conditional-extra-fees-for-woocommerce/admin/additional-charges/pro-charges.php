<?php

class pisol_cefw_procharges_tab{
    function __construct(){
        
        add_action('pi_cefw_additional_charges_tab', array($this, 'addTab'),10,1);

        add_action('pi_cefw_additional_charges_tab_content', array($this, 'addTabContent'),10,1);
    }

    function addTab($data){
        

        pisol_cefw_additional_charges_form::tabName('Product Quantity <span class="badge badge-success">Pro</span>', 'product-quantity');
        pisol_cefw_additional_charges_form::tabName('Category Quantity <span class="badge badge-success">Pro</span>', 'category-quantity');
        pisol_cefw_additional_charges_form::tabName('Shippingclass Quantity <span class="badge badge-success">Pro</span>', 'shippingclass-quantity');

        pisol_cefw_additional_charges_form::tabName('Product Subtotal <span class="badge badge-success">Pro</span>', 'product-subtotal');
        pisol_cefw_additional_charges_form::tabName('Category Subtotal <span class="badge badge-success">Pro</span>', 'category-subtotal');
        pisol_cefw_additional_charges_form::tabName('Shippingclass Subtotal <span class="badge badge-success">Pro</span>', 'shippingclass-subtotal');

        pisol_cefw_additional_charges_form::tabName('Product Weight <span class="badge badge-success">Pro</span>', 'product-weight');
        pisol_cefw_additional_charges_form::tabName('Category Weight <span class="badge badge-success">Pro</span>', 'category-weight');
        pisol_cefw_additional_charges_form::tabName('Shippingclass Weight <span class="badge badge-success">Pro</span>', 'shippingclass-weight');
    }

    function addTabContent($data){
        $slugs = array('product-quantity' => 'lD7gm9PHkvE', 'category-quantity' => '6S1eVLuR6b8', 'shippingclass-quantity' => 'DK04pdaB4u0', 'product-subtotal' => 'sFdiwsoWvBw', 'category-subtotal' => 'XPNsq5U6FHA', 'shippingclass-subtotal' => 'GFuvQlEiELE', 'product-weight' => 'aOjKK5LfR04', 'category-weight'=>'gyhR2OvUDgw', 'shippingclass-weight' => 'qIZM7VUUy1c');
        foreach($slugs as $slug => $video){
        
        $active = ($slug == 'cart-quantity') ? 'pi-active-tab' : '';
        echo '<div class="p-2 border additional-charges-tab-content '.$active.'" id="add-charges-tab-content-'.$slug.'">';
        ?>
        <!--<div class="card mb-2 text-center"><strong><?php pisol_cefw_help::youtube($video,'Know more about this Charge'); ?> Click to Know more about this feature </strong></div>-->
        <?php
            echo sprintf('<div class="free-version"><img src="%s" class="img-fluid "></div>', plugin_dir_url(__FILE__).'/image/'.$slug.'.png');
        echo '</div>';
        }
     }

}
new pisol_cefw_procharges_tab();
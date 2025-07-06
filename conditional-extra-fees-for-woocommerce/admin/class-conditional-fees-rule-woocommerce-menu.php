<?php

class Pi_cefw_Menu{

    public $plugin_name;
    public $menu;
    public $version;
    
    function __construct($plugin_name , $version){
        $this->plugin_name = $plugin_name;
        $this->version = $version;
        add_action( 'admin_menu', array($this,'plugin_menu') );
        add_action($this->plugin_name.'_promotion', array($this,'promotion'));
    }

    function plugin_menu(){
        if(apply_filters('pisol_cefw_admin_sub_menu', false)){
            $this->menu = add_submenu_page(
                'woocommerce',
                __( 'Conditional fees'),
                __( 'Conditional fees'),
                'manage_options',
                'pisol-cefw',
                array($this, 'menu_option_page'),
                6
            );
        }else{
            $this->menu = add_menu_page(
                __( 'Conditional fees'),
                __( 'Conditional fees'),
                'manage_options',
                'pisol-cefw',
                array($this, 'menu_option_page'),
                plugin_dir_url( __FILE__ ).'img/pi.svg',
                6
            );
        }

        add_action("load-".$this->menu, array($this,"bootstrap_style"));
        
    }

    public function bootstrap_style() {
        add_thickbox();
        wp_enqueue_style( $this->plugin_name."_bootstrap", plugin_dir_url( __FILE__ ) . 'css/bootstrap.css', array(), $this->version, 'all' );

        wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/conditional-fees-rule-woocommerce-admin.css', array(), $this->version, 'all' );
        wp_enqueue_script( 'jquery-ui-datepicker' );
        wp_enqueue_style( 'jquery-ui',  plugins_url('css/jquery-ui.css', __FILE__));

        wp_enqueue_script( $this->plugin_name."_toast", plugin_dir_url( __FILE__ ) . 'js/jquery-confirm.min.js', array('jquery'), $this->version);

        wp_enqueue_style( $this->plugin_name."_toast", plugin_dir_url( __FILE__ ) . 'css/jquery-confirm.min.css', array(), $this->version, 'all' );

        wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/conditional-fees-rule-woocommerce-admin.js', array( 'jquery' ), $this->version, false );

        wp_localize_script( $this->plugin_name, 'cefw_variables',
            array( 
                '_wpnonce' => wp_create_nonce( 'cefw-actions' )
            )
	    );

        wp_enqueue_script( $this->plugin_name.'-additional-charges', plugin_dir_url( __FILE__ ) . 'js/extra-charge-additional-charges.js', array( 'jquery' ), $this->version, false );
		
	}

    function menu_option_page(){
        ?>
        <div class="bootstrap-wrapper">
        <div class="pisol-container-fluid mt-2">
            <div class="pisol-row">
                    <div class="col-12">
                        <div class='bg-dark'>
                        <div class="pisol-row">
                            <div class="col-12 col-sm-2 py-3 d-flex align-items-center justify-content-center">
                                    <a href="https://www.piwebsolution.com/" target="_blank"><img id="pi-logo" class="img-fluid ml-2" src="<?php echo plugin_dir_url( __FILE__ ); ?>img/pi-web-solution.png"></a>
                            </div>
                            <div class="col-12 col-sm-10 d-flex text-center small">
                                <nav id="pisol-navbar" class="navbar navbar-expand-lg navbar-light mr-0 ml-auto">
                                    <div>
                                        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                                            <?php do_action($this->plugin_name.'_tab'); ?>
                                        </ul>
                                    </div>
                                </nav>
                            </div>
                        </div>
                        </div>
                    </div>
            </div>
            <div class="pisol-row">
                <div class="col-12">
                <div class="bg-light border pl-3 pr-3 pt-0">
                    <div class="pisol-row">
                        <div class="col">
                            <div class="pi-cefw-arrow-circle closed" title="Open / Close sidebar">
                                <svg class="pi-cefw-arrow-icon" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <!-- First arrow -->
                                    <path d="M13 6l-6 6 6 6" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    <!-- Second arrow (slightly right-shifted) -->
                                    <path d="M17 6l-6 6 6 6" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </div>
                            <?php do_action($this->plugin_name.'_tab_content'); ?>
                        </div>
                        <?php do_action($this->plugin_name.'_promotion'); ?>
                    </div>
                </div>
                </div>
            </div>
        </div>
        </div>
        <?php
        include_once 'help.php';
    }

    function promotion(){
        ?>
        <div class="col-12 col-sm-12 col-md-4 pt-3 pb-3 border-left" id="pi-cefw-sidebar-container">

        <div class="pi-shadow rounded px-2 py-3">
                <h2 id="pi-banner-tagline" class="mb-0" style="color:#ccc !important;">
                        <span class="d-block mb-4">⭐️⭐️⭐️⭐️⭐️</span>
                        <span class="d-block mb-2">🚀 Trusted by <span style="color:#fff;">3,000+</span> WooCommerce Stores</span>
                        <span class="d-block mb-2">Rated <span style="color:#fff;">4.9/5</span> – Users love it</span>
                    </h2>
                <div class="inside">
                    <ul class="text-left pisol-pro-feature-list">
                        <li><b>✔ Location-based rules</b><br>
                        <i>Target specific country, state, city or postcodes ranges</i></li>

                        <li><b>✔ Product & tag logic</b><br>
                        <i>Apply fees by product tags or categories</i></li>

                        <li><b>✔ Quantity-based triggers</b><br>
                        <i>Set rules by quantity from tags or categories</i></li>

                        <li><b>✔ Payment method conditions</b><br>
                        <i>Charge based on chosen payment option</i></li>

                        <li><b>✔ Day-based fees</b><br>
                        <i>Apply charges on selected weekdays</i></li>

                        <li><b>✔ Shipping method rules</b><br>
                        <i>Apply fees by shipping method selected</i></li>

                        <li><b>✔ Customer order history</b><br>
                        <i>Skip fees for first order or loyal customers</i></li>

                        <li><b>✔ Spending-based exclusions</b><br>
                        <i>No fee if past order or total spend qualifies</i></li>

                        <li><b>✔ Merge multiple fees</b><br>
                        <i>Combine several fees into one charge</i></li>

                        <li><b>✔ Tooltip for charges</b><br>
                        <i>Help customers understand each extra charge</i></li>
                    </ul>
                    <h4 class="pi-bottom-banner">💰 Just <?php echo esc_html(PI_CEFW_PRICE); ?></h4>
                        <h4 class="pi-bottom-banner">🔥 Unlock all 50+ features and grow your sales!</h4>
                    <div class="text-center pb-3 pt-2">
                    <a class="btn btn-primary btn-md" href="<?php echo PI_CEFW_BUY_URL; ?>&utm_ref=bottom_link" target="_blank">🔓 Unlock Pro Now – Limited Time Price!</a>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }

    function isWeekend() {
        return (date('N', strtotime(date('Y/m/d'))) >= 6);
    }

}
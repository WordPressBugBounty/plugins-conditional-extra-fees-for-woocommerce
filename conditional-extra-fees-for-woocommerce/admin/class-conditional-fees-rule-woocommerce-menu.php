<?php
if ( ! defined( 'ABSPATH' ) ) exit;
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

        $submenu = get_option('pisol_cefw_move_to_submenu', 0);
        if(apply_filters('pisol_cefw_admin_sub_menu', $submenu)){
            $this->menu = add_submenu_page(
                'woocommerce',
                __( 'Conditional fees', 'conditional-extra-fees-woocommerce' ),
                __( 'Conditional fees', 'conditional-extra-fees-woocommerce' ),
                'manage_options',
                'pisol-cefw',
                array($this, 'menu_option_page'),
                6
            );
        }else{
            $this->menu = add_menu_page(
                __( 'Conditional fees', 'conditional-extra-fees-woocommerce' ),
                __( 'Conditional fees', 'conditional-extra-fees-woocommerce' ),
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
        wp_enqueue_style( $this->plugin_name."_admin", plugin_dir_url( __FILE__ ) . 'css/admin.css', array(), $this->version, 'all' );
        wp_enqueue_style( $this->plugin_name."_bootstrap", plugin_dir_url( __FILE__ ) . 'css/bootstrap.css', array(), $this->version, 'all' );


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
        <div class="pisol-container bootstrap-wrapper">
            <div class="pisol-header">
                <div id="pisol-header-bar">
                    <a href="https://www.piwebsolution.com/" target="_blank"><img id="pi-logo" class="pisol-img-fluid" src="<?php echo esc_url( plugin_dir_url( __FILE__ )."img/pi-web-solution.svg" ); ?>"></a>
                </div>
            </div>

            <div class="pisol-left-sidebar">
                <div id="pisol-side-menu" class="mb-4 rounded">
                    <?php do_action($this->plugin_name.'_tab'); ?>
                </div>
                <?php do_action($this->plugin_name.'_promotion'); ?>
            </div>

            <div class="pisol-content">
                <label for="pi-left-sidebar-controller" class="pi-left-sidebar-closing-circle"><input id="pi-left-sidebar-controller" type="checkbox"/></label>
                <div id="pisol-efrs-notices"></div>
                <?php do_action($this->plugin_name.'_tab_content'); ?>
            </div>
        </div>   
        <?php
        include_once 'help.php';
        $this->support();
    }

    function promotion(){
        ?>
        <div id="pi-cefw-sidebar-container">

        <div class="pisol-v2-banner">
    
                <!-- Social Proof Header -->
                <div class="pisol-v2-header">
                <div class="pisol-v2-rating-pill">
                    <span class="pisol-v2-stars">★★★★★</span>
                    <span class="pisol-v2-rating-text">4.9/5 – Users love it</span>
                </div>
                <p class="pisol-v2-title">🚀 Trusted by <strong>3,000+</strong> WooCommerce Stores</p>
                </div>

                <!-- Feature Cards List -->
                <div class="pisol-v2-features">
                <div class="pisol-v2-card">
                    <span class="pisol-v2-badge">✓</span>
                    <div class="pisol-v2-info">
                    <span class="pisol-v2-info-title">Location-based rules</span>
                    <span class="pisol-v2-info-desc">Target specific country, state, city or postcodes ranges</span>
                    </div>
                </div>

                <div class="pisol-v2-card">
                    <span class="pisol-v2-badge">✓</span>
                    <div class="pisol-v2-info">
                    <span class="pisol-v2-info-title">Product & tag logic</span>
                    <span class="pisol-v2-info-desc">Apply fees by product tags or categories</span>
                    </div>
                </div>

                <div class="pisol-v2-card">
                    <span class="pisol-v2-badge">✓</span>
                    <div class="pisol-v2-info">
                    <span class="pisol-v2-info-title">Quantity-based triggers</span>
                    <span class="pisol-v2-info-desc">Set rules by quantity from tags or categories</span>
                    </div>
                </div>

                <div class="pisol-v2-card">
                    <span class="pisol-v2-badge">✓</span>
                    <div class="pisol-v2-info">
                    <span class="pisol-v2-info-title">Payment method conditions</span>
                    <span class="pisol-v2-info-desc">Charge based on chosen payment option</span>
                    </div>
                </div>

                <div class="pisol-v2-card">
                    <span class="pisol-v2-badge">✓</span>
                    <div class="pisol-v2-info">
                    <span class="pisol-v2-info-title">Day-based fees</span>
                    <span class="pisol-v2-info-desc">Apply charges on selected weekdays</span>
                    </div>
                </div>

                <div class="pisol-v2-card">
                    <span class="pisol-v2-badge">✓</span>
                    <div class="pisol-v2-info">
                    <span class="pisol-v2-info-title">Shipping method rules</span>
                    <span class="pisol-v2-info-desc">Apply fees by shipping method selected</span>
                    </div>
                </div>

                <div class="pisol-v2-card">
                    <span class="pisol-v2-badge">✓</span>
                    <div class="pisol-v2-info">
                    <span class="pisol-v2-info-title">Customer order history</span>
                    <span class="pisol-v2-info-desc">Skip fees for first order or loyal customers</span>
                    </div>
                </div>

                <div class="pisol-v2-card">
                    <span class="pisol-v2-badge">✓</span>
                    <div class="pisol-v2-info">
                    <span class="pisol-v2-info-title">Spending-based exclusions</span>
                    <span class="pisol-v2-info-desc">No fee if past order or total spend qualifies</span>
                    </div>
                </div>

                <div class="pisol-v2-card">
                    <span class="pisol-v2-badge">✓</span>
                    <div class="pisol-v2-info">
                    <span class="pisol-v2-info-title">Merge multiple fees</span>
                    <span class="pisol-v2-info-desc">Combine several fees into one charge</span>
                    </div>
                </div>

                <div class="pisol-v2-card">
                    <span class="pisol-v2-badge">✓</span>
                    <div class="pisol-v2-info">
                    <span class="pisol-v2-info-title">Tooltip for charges</span>
                    <span class="pisol-v2-info-desc">Help customers understand each extra charge</span>
                    </div>
                </div>
                </div>

                <!-- Pricing & Action Footer -->
                <div class="pisol-v2-footer">
                <div class="pisol-v2-price">💰 Only <?php echo esc_html( PI_CEFW_PRICE ); ?></div>
                <a href="<?php echo esc_url( PI_CEFW_PRODUCT_PAGE_URL ); ?>" class="pisol-v2-btn" target="_blank">🔒 Unlock Pro Now – Limited Time Price!</a>
                </div>

        </div>

        </div>
        <?php
    }

    function isWeekend() {
        return ( (int) wp_date('N') >= 6 );
    }

    function support(){
        $website_url = home_url();
        $plugin_name = $this->plugin_name;
        ?>
        <form action="https://www.piwebsolution.com/quick-support/" method="post" target="_blank" style="display:inline; position:fixed; bottom:30px; right:35px; z-index:9999;" >
            <input type="hidden" name="website_url" value="<?php echo esc_attr( $website_url ); ?>">
            <input type="hidden" name="plugin_name" value="<?php echo esc_attr( $plugin_name ); ?>">
            <button type="submit" style="background:none;border:none;cursor:pointer;padding:0;">
                <img src="<?php echo esc_url( plugin_dir_url( __FILE__ ) ); ?>img/chat.png" 
                    alt="Live Support" title="Quick Support" style="width:60px;height:60px;">
            </button>
        </form>
        <?php
    }
}
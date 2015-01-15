<?php
/**
 * Plugin Name: BaoKim.vn cho Woocommerce
 * Plugin URI: http://www.minhz.com
 * Description: Tích hợp Cổng thanh toán Bảo Kim ( BaoKim.vn ) vào Woocommerce dễ dàng
 * Version: 1.1.1
 * Author: MinhZ.com
 * Author URI: http://www.minhz.com
 * License: GPL2
 */

add_action('plugins_loaded', 'woocommerce_BaoKimVN_init', 0);

function woocommerce_BaoKimVN_init(){
  if(!class_exists('WC_Payment_Gateway')) return;

  class WC_BaoKimVN extends WC_Payment_Gateway{
    public function __construct(){
      $this -> id = 'BaoKimVN';
      $this -> medthod_title = 'Bảo Kim (BaoKim.vn)';
      $this -> has_fields = false;

      $this -> init_form_fields();
      $this -> init_settings();

      $this -> title = $this -> settings['title'];
      $this -> description = $this -> settings['description'];
      $this -> merchant_id = $this -> settings['merchant_id'];
      //
      $this -> redirect_page_id = $this -> settings['redirect_page_id'];
      $this -> liveurl = 'https://www.baokim.vn/advance_payment.php';

      $this -> msg['message'] = "";
      $this -> msg['class'] = "";

      if ( version_compare( WOOCOMMERCE_VERSION, '2.0.8', '>=' ) ) {
                add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( &$this, 'process_admin_options' ) );
             } else {
                add_action( 'woocommerce_update_options_payment_gateways', array( &$this, 'process_admin_options' ) );
            }
      add_action('woocommerce_receipt_BaoKimVN', array(&$this, 'receipt_page'));
   }
    function init_form_fields(){

       $this -> form_fields = array(
                'enabled' => array(
                    'title' => __('Bật / Tắt', 'mBaoKimVN'),
                    'type' => 'checkbox',
                    'label' => __('Kích hoạt cổng thanh toán BaoKim.vn cho Woocommerce', 'mBaoKimVN'),
                    'default' => 'no'),
                'title' => array(
                    'title' => __('Tên:', 'mBaoKimVN'),
                    'type'=> 'text',
                    'description' => __('Tên phương thức thanh toán ( khi khách hàng chọn phương thức thanh toán )', 'mBaoKimVN'),
                    'default' => __('BaoKimVN', 'mBaoKimVN')),
                'description' => array(
                    'title' => __('Mô tả:', 'mBaoKimVN'),
                    'type' => 'textarea',
                    'description' => __('Mô tả phương thức thanh toán.', 'mBaoKimVN'),
                    'default' => __('<img src="https://www.baokim.vn/cdn/assets/x_home/f22107bd/images/logo.png" /><br /><br />Thanh toán trực tuyến AN TOÀN, TIỆN LỢI, HƯỞNG ƯU ĐÃI Với Bảo Kim.<br />Với BaoKim.vn Quý khách có thể thanh toán bằng thẻ Ngân Hàng.', 'mbaokimvn')),
                'merchant_id' => array(
                    'title' => __('Tài khoản BaoKim.vn', 'mBaoKimVN'),
                    'type' => 'text',
                    'description' => __('Đây là tài khoản BaoKim.vn để nhận tiền của khách hàng.')),
                /**/
                'redirect_page_id' => array(
                    'title' => __('Trang trả về'),
                    'type' => 'select',
                    'options' => $this -> get_pages('Hãy chọn...'),
                    'description' => "Hãy chọn trang/url để chuyển đến sau khi khách hàng đã thanh toán tại BaoKim.vn thành công."
                )
            );
    }

       public function admin_options(){
        echo '<h3>'.__('BaoKimVN Payment Gateway', 'mBaoKimVN').'</h3>';
        echo '<p>'.__('BaoKim.vn - Thanh toán trực tuyến AN TOÀN, TIỆN LỢI, HƯỞNG ƯU ĐÃI Với Bảo Kim.
          <br /><br />Lưu ý : phiên bản miễn phí này không hỗ trợ tự động kiểm tra khách hàng đã gửi tiền thanh toán hay chưa trong Woocommerce (chỉ có ở phiên bản tích hợp Premium). Quản lý shop phải tự kiểm tra trong BaoKim.vn
          <br /><br /><small>Nếu bạn muốn mời tác giả plugin 1 ly cafe qua <a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=Q85G3J49Y3UEY" target="_blank">Paypal</a> hoặc BaoKim.vn ( thaiminh2020@gmail.com )</small>').'</p>';
        echo '<table class="form-table">';
        // Generate the HTML For the settings form.
        $this -> generate_settings_html();
        echo '</table>';

    }

    /**
     *  There are no payment fields for BaoKimVN, but we want to show the description if set.
     **/
    function payment_fields(){
        if($this -> description) echo wpautop(wptexturize($this -> description));
    }
    /**
     * Receipt Page
     **/
    function receipt_page($order){
        echo '<p>'.__('Chúng tôi đã nhận được Đơn mua hàng của bạn. <br /><b>Tiếp theo, hãy bấm nút Thanh toán bên dưới để tiến hành thanh toán an toàn qua BaoKim.vn ...', 'mBaoKimVN').'</p>';
        echo $this -> generate_BaoKimVN_form($order);
    }
    /**
     * Generate BaoKimVN button link
     **/
    public function generate_BaoKimVN_form($order_id){

       global $woocommerce;
      $order = new WC_Order( $order_id );
        $redirect_url = ($this -> redirect_page_id=="" || $this -> redirect_page_id==0)?get_site_url() . "/":get_permalink($this -> redirect_page_id);

    $productinfo = 'Đơn hàng '.$_SERVER['SERVER_NAME']." #".$order_id." ".date("d-m-y");

return '<form id="vpg_cpayment_form" action="https://www.baokim.vn/payment/product/version11">
<input type="hidden" name="business" value="'.$this -> merchant_id.'" />
<input type="hidden" name="product_name" value="'.$productinfo.'" />
<input type="hidden" name="product_price" value="'.$order -> order_total.'" />
<input type="hidden" name="product_quantity" value="1" />
<input type="hidden" name="total_amount" value="'.$order -> order_total.'" />
<input type="hidden" name="url_detail" value="'.$productinfo.'" />
<input type="hidden" name="url_success" value="'.$redirect_url.'" />
<input type="hidden" name="url_cancel" value="'.$order->get_cancel_order_url().'" />
<input type="hidden" name="product_description" value="'.$productinfo.'" />
<center><input type="image" src="https://www.baokim.vn/application/uploads/buttons/btn_pay_now_3.png" border="0" name="submit" alt="Thanh toán an toàn với Bảo Kim !" title="Thanh toán trực tuyến an toàn dùg tài khoản Ngân hàng (VietcomBank, TechcomBank, Đông Á, VietinBank, Quân Đội, VIB, SHB,... và thẻ Quốc tế (Visa, Master Card...) qua Cổng thanh toán trực tuyến BảoKim.vn">
  <br /><br /><a class="button cancel" href="'.$order->get_cancel_order_url().'">'.__('Huỷ đơn hàng', 'mbaokimvn').'</a></center>
</form>
';
    }
    /**
     * Process the payment and return the result
     **/
    function process_payment( $order_id ) {
      $order = new WC_Order( $order_id );

        return array(
          'result'  => 'success',
          'redirect'  => add_query_arg('order', $order->id, add_query_arg('key', $order->order_key, get_permalink(woocommerce_get_page_id('pay' ))))
        );  

    }


    function showMessage($content){
            return '<div class="box '.$this -> msg['class'].'-box">'.$this -> msg['message'].'</div>'.$content;
        }
     // get all pages
    function get_pages($title = false, $indent = true) {
        $wp_pages = get_pages('sort_column=menu_order');
        $page_list = array();
        if ($title) $page_list[] = $title;
        foreach ($wp_pages as $page) {
            $prefix = '';
            // show indented child pages?
            if ($indent) {
                $has_parent = $page->post_parent;
                while($has_parent) {
                    $prefix .=  ' - ';
                    $next_page = get_page($has_parent);
                    $has_parent = $next_page->post_parent;
                }
            }
            // add to page list array array
            $page_list[$page->ID] = $prefix . $page->post_title;
        }
        return $page_list;
    }
}

    function woocommerce_add_BaoKimVN_gateway($methods) {
        $methods[] = 'WC_BaoKimVN';
        return $methods;
    }

    add_filter('woocommerce_payment_gateways', 'woocommerce_add_BaoKimVN_gateway' );
}
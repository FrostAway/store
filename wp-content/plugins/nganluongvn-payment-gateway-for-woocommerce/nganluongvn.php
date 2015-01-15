<?php
/**
 * Plugin Name: NganLuong.vn cho Woocommerce
 * Plugin URI: http://www.minhz.com
 * Description: Tích hợp Cổng thanh toán Ngân Lượng ( NganLuong.vn ) vào Woocommerce dễ dàng
 * Version: 1.4.1
 * Author: MinhZ.com
 * Author URI: http://www.minhz.com
 * License: GPL2
 */

add_action('plugins_loaded', 'woocommerce_NganLuongVN_init', 0);

function woocommerce_NganLuongVN_init(){
  if(!class_exists('WC_Payment_Gateway')) return;

  class WC_NganLuongVN extends WC_Payment_Gateway{
    public function __construct(){
      $this->icon = 'https://www.nganluong.vn/data/images/buttons/11.gif';
      $this -> id = 'NganLuongVN';
      $this -> medthod_title = 'Ngân Lượng (NganLuong.vn)';
      $this -> has_fields = false;

      $this -> init_form_fields();
      $this -> init_settings();

      $this -> title = $this -> settings['title'];
      $this -> description = $this -> settings['description'];
      $this -> merchant_id = $this -> settings['merchant_id'];
      $this -> nlcurrency = $this -> settings['nlcurrency'];
      //
      $this -> redirect_page_id = $this -> settings['redirect_page_id'];
      $this -> liveurl = 'https://www.nganluong.vn/advance_payment.php';

      $this -> msg['message'] = "";
      $this -> msg['class'] = "";

      if ( version_compare( WOOCOMMERCE_VERSION, '2.0.8', '>=' ) ) {
                add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( &$this, 'process_admin_options' ) );
             } else {
                add_action( 'woocommerce_update_options_payment_gateways', array( &$this, 'process_admin_options' ) );
            }
      add_action('woocommerce_receipt_NganLuongVN', array(&$this, 'receipt_page'));
   }
    function init_form_fields(){

       $this -> form_fields = array(
                'enabled' => array(
                    'title' => __('Bật / Tắt', 'mnganluongvn'),
                    'type' => 'checkbox',
                    'label' => __('Kích hoạt cổng thanh toán NganLuong.vn cho Woocommerce', 'mnganluongvn'),
                    'default' => 'no'),
                'title' => array(
                    'title' => __('Tên:', 'mnganluongvn'),
                    'type'=> 'text',
                    'description' => __('Tên phương thức thanh toán ( khi khách hàng chọn phương thức thanh toán )', 'mnganluongvn'),
                    'default' => __('NganLuongVN', 'mnganluongvn')),
                'description' => array(
                    'title' => __('Mô tả:', 'mnganluongvn'),
                    'type' => 'textarea',
                    'description' => __('Mô tả phương thức thanh toán.', 'mnganluongvn'),
                    'default' => __('<img src="https://www.nganluong.vn/webskins/skins/nganluong/images/CertifiedLogo-gr.png" /><br /><br />Ví điện tử chuyên ngành thương mại điện tử DUY NHẤT được Ngân hàng Nhà nước cấp phép.
                      <br />Với NganLuong.vn Quý khách có thể thanh toán bằng thẻ Ngân Hàng.', 'mnganluongvn')),
                'merchant_id' => array(
                    'title' => __('Tài khoản NganLuong.vn', 'mnganluongvn'),
                    'type' => 'text',
                    'description' => __('Đây là tài khoản NganLuong.vn (Email) để nhận tiền của khách hàng.')),
                /**/
                'redirect_page_id' => array(
                    'title' => __('Trang trả về'),
                    'type' => 'select',
                    'options' => $this -> get_pages('Hãy chọn...'),
                    'description' => "Hãy chọn trang/url để chuyển đến sau khi khách hàng đã thanh toán tại NganLuong.vn thành công."
                ),
                'nlcurrency' => array(
                    'title' => __('Tiền tệ'),
                   'type' => 'text',
                   'default' => 'vnd',
                    'description' => '"vnd" hoặc "usd"'
                )
            );
    }

       public function admin_options(){
        echo '<h3>'.__('NganLuongVN Payment Gateway', 'mnganluongvn').'</h3>';
        echo '<p>'.__('NganLuong.VN Ví điện tử chuyên ngành thương mại điện tử DUY NHẤT được Ngân hàng Nhà nước Việt Nam cấp phép.<br /><br />Lưu ý : phiên bản miễn phí này không hỗ trợ tự động kiểm tra khách hàng đã gửi tiền thanh toán hay chưa trong Woocommerce (chỉ có ở phiên bản Premium). Quản lý shop phải tự kiểm tra trong NganLuong.vn
          <br /><br /><small>Ghé thăm <a href="http://www.minhz.com" target="_blank">MinhZ.com</a>. Nếu bạn muốn mời tác giả plugin 1 ly cafe qua NganLuong.vn ( thaiminh2020@gmail.com )</small>').'
          </p>';
        echo '<table class="form-table">';
        // Generate the HTML For the settings form.
        $this -> generate_settings_html();
        echo '</table>';

    }

    /**
     *  There are no payment fields for NganLuongVN, but we want to show the description if set.
     **/
    function payment_fields(){
        if($this -> description) echo wpautop(wptexturize($this -> description));
    }
    /**
     * Receipt Page
     **/
    function receipt_page($order){
        echo '<p>'.__('Chúng tôi đã nhận được Đơn mua hàng của bạn. <br /><b>Tiếp theo, hãy bấm nút Thanh toán bên dưới để tiến hành thanh toán an toàn qua NganLuong.vn ...', 'mnganluongvn').'</p>';
        echo $this -> generate_NganLuongVN_form($order);
    }
    /**
     * Generate NganLuongVN button link
     **/
    public function generate_NganLuongVN_form($order_id){

       global $woocommerce;
    	$order = new WC_Order( $order_id );
        $redirect_url = ($this -> redirect_page_id=="" || $this -> redirect_page_id==0)?get_site_url() . "/":get_permalink($this -> redirect_page_id);
        //$mcurrency = ($this -> nlcurrency=1)?"vnd":"usd";

        $productinfo = "Đơn hàng #".$order_id." | ".$_SERVER['SERVER_NAME'];

        return '<form action="'.$this -> liveurl.'" method="post" id="NganLuongVN_payment_form">
           <input type=hidden name=receiver value="'.$this -> merchant_id.'" /><input type=hidden name=product value="'.$productinfo.'" />
<input type=hidden name=price value="'.$order -> order_total.'" />
<input type=hidden name=currency value="'.$this -> nlcurrency.'" />
<input type=hidden name=return_url value="'.$redirect_url.'" /><input type=hidden name=comments value="'.$order_comments.'" />
<center><input type=image src="https://www.nganluong.vn/data/images/buttons/11.gif" />
<br /><br />
             <a class="button cancel" href="'.$order->get_cancel_order_url().'">'.__('Huỷ đơn hàng', 'mnganluongvn').'</a></center>
            
            </form>';


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


/**
 * Add Vietnam provinces and cities.
 *//*
add_filter( 'woocommerce_states', 'vietnam_cities_woocommerce_nl' );
function vietnam_cities_woocommerce_nl( $states ) {
  $states['VN'] = array(
    'CANTHO' => __('Cần Thơ', 'woocommerce') ,
    'HCM' => __('Hồ Chí Minh', 'woocommerce') ,
    'HANOI' => __('Hà Nội', 'woocommerce') ,
    'HAIPHONG' => __('Hải Phòng', 'woocommerce') ,
    'DANANG' => __('Đà Nẵng', 'woocommerce') ,
    'ANGIAG' => __('An Giang', 'woocommerce') ,
    'BRVT' => __('Bà Rịa - Vũng Tàu', 'woocommerce') ,
    'BALIE' => __('Bạc Liêu', 'woocommerce') ,
    'BACKAN' => __('Bắc Kạn', 'woocommerce') ,
    'BACNINH' => __('Bắc Ninh', 'woocommerce') ,
    'BACGIANG' => __('Bắc Giang', 'woocommerce') ,
    'BENTRE' => __('Bến Tre', 'woocommerce') ,
    'BDUONG' => __('Bình Dương', 'woocommerce') ,
    'BDINH' => __('Bình Định', 'woocommerce') ,
    'BPHUOC' => __('Bình Phước', 'woocommerce') ,
    'BTHUAN' => __('Bình Thuận', 'woocommerce'),
    'CAMAU' => __('Cà Mau', 'woocommerce'),
    'DAKLAK' => __('Đak Lak', 'woocommerce'),
    'DAKNONG' => __('Đak Nông', 'woocommerce'),
    'DIENBIEN' => __('Điện Biên', 'woocommerce'),
    'ĐNAI' => __('Đồng Nai', 'woocommerce'),
    'GIALAI' => __('Gia Lai', 'woocommerce'),
    'HGIANG' => __('Hà Giang', 'woocommerce'),
    'HNAM' => __('Hà Nam', 'woocommerce'),
    'HTINH' => __('Hà Tĩnh', 'woocommerce'),
    'HDUONG' => __('Hải Dương', 'woocommerce'),
    'HUGIANG' => __('Hậu Giang', 'woocommerce'),
    'HOABINH' => __('Hòa Bình', 'woocommerce'),
    'HYEN' => __('Hưng Yên', 'woocommerce'),
    'KHOA' => __('Khánh Hòa', 'woocommerce'),
    'KGIANG' => __('Kiên Giang', 'woocommerce'),
    'KTUM' => __('Kom Tum', 'woocommerce'),
    'LCHAU' => __('Lai Châu', 'woocommerce'),
    'LAMDONG' => __('Lâm Đồng', 'woocommerce'),
    'LSON' => __('Lạng Sơn', 'woocommerce'),
    'LCAI' => __('Lào Cai', 'woocommerce'),
    'LAN' => __('Long An', 'woocommerce'),
    'NDINH' => __('Nam Định', 'woocommerce'),
    'NGAN' => __('Nghệ An', 'woocommerce'),
    'NBINH' => __('Ninh Bình', 'woocommerce'),
    'NTHUAN' => __('Ninh Thuận', 'woocommerce'),
    'PTHO' => __('Phú Thọ', 'woocommerce'),
    'PYEN' => __('Phú Yên', 'woocommerce'),
    'QBINH' => __('Quảng Bình', 'woocommerce'),
    'QNAM' => __('Quảng Nam', 'woocommerce'),
    'QNGAI' => __('Quảng Ngãi', 'woocommerce'),
    'QNINH' => __('Quảng Ninh', 'woocommerce'),
    'QTRI' => __('Quảng Trị', 'woocommerce'),
    'STRANG' => __('Sóc Trăng', 'woocommerce'),
    'SLA' => __('Sơn La', 'woocommerce'),
    'TNINH' => __('Tây Ninh', 'woocommerce'),
    'TBINH' => __('Thái Bình', 'woocommerce'),
    'TNGUYEN' => __('Thái Nguyên', 'woocommerce'),
    'THOA' => __('Thanh Hóa', 'woocommerce'),
    'TTHIEN' => __('Thừa Thiên - Huế', 'woocommerce'),
    'TGIANG' => __('Tiền Giang', 'woocommerce'),
    'TVINH' => __('Trà Vinh', 'woocommerce'),
    'TQUANG' => __('Tuyên Quang', 'woocommerce'),
    'VLONG' => __('Vĩnh Long', 'woocommerce'),
    'VPHUC' => __('Vĩnh Phúc', 'woocommerce'),
    'YBAI' => __('Yên Bái', 'woocommerce'),
  );
 
  return $states;
}
*/

    function woocommerce_add_NganLuongVN_gateway($methods) {
        $methods[] = 'WC_NganLuongVN';
        return $methods;
    }

    add_filter('woocommerce_payment_gateways', 'woocommerce_add_NganLuongVN_gateway' );
}


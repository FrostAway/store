<?php
/**
 * Plugin Name: Woocommerce Vietnam Currency
 * Plugin URI: http://thachpham.com
 * Description: Thêm loại tiền tệ Việt Nam Đồng (VNĐ) vào Woocommerce và tích hợp tính năng tự chuyển tỷ giá VNĐ sang USD để sử dụng thanh toán qua Paypal.
 * Version: 1.1
 * Author: Thach Pham
 * Author URI: http://thachpham.com
 * License: GPL2
 */

include ('wc_vn_currency_options.php'); // Add the options of plugin.

/**
 * Add Vietnam provinces and cities.
 */
add_filter( 'woocommerce_states', 'vietnam_cities_woocommerce' );
function vietnam_cities_woocommerce( $states ) {
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

/**
* Add Vietnam currency (VND)
*/
add_filter( 'woocommerce_currencies', 'add_vnd_currency' );
function add_vnd_currency( $currencies ) {
 $currencies['VND'] = __( 'Việt Nam Đồng', 'woocommerce' );
 return $currencies;
}

add_filter('woocommerce_currency_symbol', 'add_vnd_currency_symbol', 10, 2);
function add_vnd_currency_symbol( $currency_symbol, $currency ) {
 switch( $currency ) {
 case 'VND': $currency_symbol = 'VNĐ'; break;
 }
 return $currency_symbol;
}


/**
* Convert VND to USD to use PayPal.
*/
add_filter('woocommerce_paypal_args', 'vnd_to_usd'); 
function vnd_to_usd($paypal_args){ 
if ( $paypal_args['currency_code'] == 'VND'){
$convert_rate = (get_option('vnd_convert_rate') == '') ? 21083.7 : get_option('vnd_convert_rate');
$paypal_args['currency_code'] = 'USD'; // Ký hiệu của loại tiền cần chuyển ra.
$i = 1; 

while (isset($paypal_args['amount_' . $i])) { 
$paypal_args['amount_' . $i] = round( $paypal_args['amount_' . $i] / $convert_rate, 2); 
++$i; 
} 

} 
return $paypal_args; 
}

/* Endable VND PP */
add_filter( 'woocommerce_paypal_supported_currencies', 'add_bgn_paypal_valid_currency' );     
    function add_bgn_paypal_valid_currency( $currencies ) {  
     array_push ( $currencies , 'VND' );
     return $currencies;  
    } 


<?php



/**

 *

 */

class Ajax extends CI_Controller

{



    function __construct()

    {

        parent::__construct();

    }



    public function index()

    {

    }



    public function search_orderlist_products()

    {

        $order_id = $this->input->post('order_id');

        if ($order_id != "") {



            $Products = $this->db->query(

                "SELECT pid as `id`, (select `name` from product where id = pid) as `name`" .

                    ", price, qty, (select retail_price from product where id = pid) as `retail_price`" .

                    " FROM order_list as ol where order_id = ? and ol.is_delete = 0 order by ol.id asc", $order_id

            );



            $Products->result_array();



            foreach ($Products->result_array() as $Product) {

                $MyShippingCart = array(

                    array(

                        'id' => $Product['id'],

                        'qty' => $Product['qty'],

                        'price' => number_format($Product['price'], 2),

                        'name' => $Product['name']

                    )

                );

                $this->cart->insert($MyShippingCart);

            }



            echo json_encode($Products->result_array());



        } else {



            echo json_encode(array());



        }

    }



    //	查詢客戶資料, 目前只有先對應到 FirstName

    public function search_customers()

    {

        $CustomerName = $this->input->post('name');

        if ($CustomerName == "") {

            echo json_encode(array());

        } else {

            $this->db->select('*');

            $this->db->from("users");

            $this->db->where('is_delete', 0);

            $this->db->like('firstname', $CustomerName);

            $Customers = $this->db->get()->result_array();

            echo json_encode($Customers);

        }

    }



    //	查詢產品資訊, 目前只有先顯示出 Product Name, 可能會需要加入其他資訊 EXP : Prices..

    public function search_products()

    {

        $ProductName = $this->input->post('name');



        if ($ProductName == "") {

            echo json_encode(array());

        } else {

            $this->db->select("id, name, round(retail_price, 2) as 'retail_price', round(price, 2) as 'price', (1) as `qty`", FALSE);

            $this->db->from("product");

            $this->db->where('is_delete', 0);

            $this->db->like('name', $ProductName);

            $Product = $this->db->get()->result_array();

            echo json_encode($Product);

        }



    }



    //	查詢州別的稅務

    public function destination_state()

    {

        $this->db->select('*');

        $this->db->from('tax_codes');

        $this->db->where('id', $this->input->post('state'));

        $DestinationState = $this->db->get()->result_array();

        $this->DestinationState($this->input->post('state'));

        echo json_encode($DestinationState);

    }



    //

    public function shipping_method()

    {

        $this->db->select('*');

        $this->db->from('shipping_method');

        $this->db->where('id', $this->input->post('id'));

        $shippingMethod = $this->db->get()->result_array();



        $this->ShippingOptions($this->input->post('id'));



        echo json_encode($shippingMethod);

    }





    public function ShippingOptions($opt)

    {

        if ($opt == -1) {

            $this->session->set_userdata('ShippingOptions', array());

        } else {

            $Query = $this->db->query("SELECT * FROM shipping_method WHERE id = ? AND is_delete = 0", $opt);

            $ShippingOptions = $Query->result_array();

            if (count($ShippingOptions) >= 1) {

                $this->session->set_userdata('ShippingOptions', $ShippingOptions);

            } else {

                $this->session->set_userdata('ShippingOptions', array());

            }

        }

    }



    //	最困難的地方出現了 !!!

    //	這是折扣運算

    public function promo()

    {

        $this->cart->destroy();

        $this->session->unset_userdata('DiscountCode');



        $pids = explode(',', $this->input->post('pid'));

        $prices = explode(',', $this->input->post('price'));

        $qtys = explode(',', $this->input->post('qty'));



        if (is_array($pids) && (count($pids) >= 1)) {

            for ($i = 0; $i < count($pids) - 1; $i++) {

                $MyShippingCart = array(

                    array(

                        'id' => $pids[$i],

                        'qty' => $qtys[$i],

                        'price' => $prices[$i],

                        'name' => uniqid()

                    )

                );

                $this->cart->insert($MyShippingCart);

            }

        }



        $dt = new DateTime();

        $this->db->select('*');

        $this->db->from('discountcode');

        $this->db->where('id', $this->input->post('id'));

        $this->db->where('is_delete', 0);

        $this->db->where('enabled', 1);

        $this->db->where('release_timezone <=', now());

        $this->db->where('expiry_timezone >=', now());



        $DiscountCode = $this->db->get()->result();



        //	STEP 2 : 找到了對應的 Discount Code

        if (count($DiscountCode) >= 1) {

            //	STEP 3 : 查詢是否是針對特定商品進行折扣

            $Query = $this->db->query(

                "SELECT id, d_id, pid FROM discountcode_rel_products " .

                    "WHERE d_id = ? AND is_delete = 0", $DiscountCode[0]->id

            );

            $Products = $Query->result();

            $discount_sub_total = 0;

            switch ($DiscountCode[0]->discount_type) {

                case "1" :

                    if (count($Products) >= 1) {

                        //	針對產品進行折扣

                        foreach ($this->cart->contents() as $item) {

                            foreach ($Products as $Product) {

                                $Single_Product_Total = ($item['price'] * $item['qty']);

                                if ($item['id'] == $Product->pid) {

                                    //	找到對應的商品, 並計算金額超過 amount threashold 就享有(產品單一)折扣

                                    if ($Single_Product_Total >= $DiscountCode[0]->discount_amount_threshold) {

                                        if ($this->session->userdata('ShippingOptions') == TRUE) {

                                            $discount_sub_total += $Single_Product_Total * ($DiscountCode[0]->discount_percentage);

                                        }

                                    }

                                }

                            }

                        }



                        if ($discount_sub_total > 0) {

                            //	1 = 可以 Free Shipping, 0 = 不可以

                            if ($DiscountCode[0]->can_free_shipping == 1) {

                                $this->session->set_userdata('FreeShipping', $DiscountCode[0]->can_free_shipping);

                            }

                            $this->session->unset_userdata('DiscountCode');

                            $this->session->set_userdata('DiscountCode', $DiscountCode);

                            $this->discount_sub_total = $discount_sub_total;

                            $return_json = array(

                                array(

                                    'discount_sub_total' => number_format($discount_sub_total, 2),

                                    'FreeShipping' => $DiscountCode[0]->can_free_shipping

                                )

                            );

                            echo json_encode($return_json);

                        } else {

                            echo json_encode(array());

                        }



                    } else {

                        if ($this->cart->total() >= $DiscountCode[0]->discount_amount_threshold) {

                            $discount_sub_total = ($this->cart->total() * ($DiscountCode[0]->discount_percentage));

                            if ($discount_sub_total > 0) {

                                //	1 = 可以 Free Shipping, 0 = 不可以

                                if ($DiscountCode[0]->can_free_shipping == 1) {

                                    $this->session->set_userdata('FreeShipping', $DiscountCode[0]->can_free_shipping);

                                }

                                $this->session->unset_userdata('DiscountCode');

                                $this->session->set_userdata('DiscountCode', $DiscountCode);

                                $this->discount_sub_total = $discount_sub_total;

                                $return_json = array(

                                    array(

                                        'discount_sub_total' => number_format($discount_sub_total, 2),

                                        'FreeShipping' => $DiscountCode[0]->can_free_shipping

                                    )

                                );

                                echo json_encode($return_json);

                            }

                        } else {

                            echo json_encode(array());

                        }

                    }

                    break;

                case "2" :

                    if (count($Products) >= 1) {

                        //	針對產品進行折扣

                        foreach ($this->cart->contents() as $item) {

                            foreach ($Products as $Product) {

                                $Single_Product_Total = ($item['price'] * $item['qty']);

                                if ($item['id'] == $Product->pid) {

                                    //	找到對應的商品, 並計算金額超過 amount threashold 就享有(產品單一)折扣

                                    if ($Single_Product_Total >= $DiscountCode[0]->discount_amount_threshold) {

                                        if ($this->session->userdata('ShippingOptions') == TRUE) {

                                            $discount_sub_total += ($DiscountCode[0]->discount_percentage);

                                        }

                                    }

                                }

                            }

                        }



                        if ($discount_sub_total > 0) {

                            //	1 = 可以 Free Shipping, 0 = 不可以

                            if ($DiscountCode[0]->can_free_shipping == 1) {

                                $this->session->set_userdata('FreeShipping', $DiscountCode[0]->can_free_shipping);

                            }

                            $this->session->unset_userdata('DiscountCode');

                            $this->session->set_userdata('DiscountCode', $DiscountCode);

                            $this->discount_sub_total = $discount_sub_total;

                            $return_json = array(

                                array(

                                    'discount_sub_total' => number_format($discount_sub_total, 2),

                                    'FreeShipping' => $DiscountCode[0]->can_free_shipping

                                )

                            );

                            echo json_encode($return_json);

                        } else {

                            echo json_encode(array());

                        }



                    } else {

                        if ($this->cart->total() >= $DiscountCode[0]->discount_amount_threshold) {

                            //	1 = 可以 Free Shipping, 0 = 不可以

                            if ($DiscountCode[0]->can_free_shipping == 1) {

                                $this->session->set_userdata('FreeShipping', $DiscountCode[0]->can_free_shipping);

                            }

                            $discount_sub_total = ($DiscountCode[0]->discount_percentage);

                            if ($discount_sub_total > 0) {

                                $this->session->unset_userdata('DiscountCode');

                                $this->session->set_userdata('DiscountCode', $DiscountCode);

                                $this->discount_sub_total = $discount_sub_total;

                            }

                            $return_json = array(

                                array(

                                    'discount_sub_total' => number_format($discount_sub_total, 2),

                                    'FreeShipping' => $DiscountCode[0]->can_free_shipping

                                )

                            );

                            echo json_encode($return_json);

                        } else {

                            echo json_encode(array());

                        }

                    }

                    break;

                case "3" :

                    if (count($Products) >= 1) {

                        $bolFreeShipping = FALSE;

                        //	針對產品進行折扣

                        foreach ($this->cart->contents() as $item) {

                            foreach ($Products as $Product) {

                                $Single_Product_Total = ($item['price'] * $item['qty']);

                                if ($item['id'] == $Product->pid) {

                                    //	找到對應的商品, 並計算金額超過 amount threashold 就享有(產品單一)折扣

                                    if ($Single_Product_Total >= $DiscountCode[0]->discount_amount_threshold) {

                                        $bolFreeShipping = TRUE;

                                    }

                                }

                            }

                        }



                        if ($bolFreeShipping == TRUE) {

                            //	1 = 可以 Free Shipping, 0 = 不可以

                            $this->session->set_userdata('FreeShipping', 1);

                            $this->session->unset_userdata('DiscountCode');

                            $this->session->set_userdata('DiscountCode', $DiscountCode);

                            $this->discount_sub_total = 0;



                            $return_json = array(

                                array(

                                    'discount_sub_total' => number_format($discount_sub_total, 2),

                                    'FreeShipping' => 1

                                )

                            );

                            echo json_encode($return_json);



                        } else {

                            echo json_encode(array());

                        }



                    } else {

                        if ($this->cart->total() >= $DiscountCode[0]->discount_amount_threshold) {

                            //	1 = 可以 Free Shipping, 0 = 不可以

                            $this->session->set_userdata('FreeShipping', 1);

                            $this->session->unset_userdata('DiscountCode');

                            $this->session->set_userdata('DiscountCode', $DiscountCode);

                            $this->discount_sub_total = 0;



                            $return_json = array(

                                array(

                                    'discount_sub_total' => number_format($discount_sub_total, 2),

                                    'FreeShipping' => 1

                                )

                            );

                            echo json_encode($return_json);



                        } else {

                            echo json_encode(array());

                        }

                    }

                    break;

            }



        } else {

            echo json_encode(array());

        }

        sleep(1);

    }



    public function discountcode_rel_products()

    {

        $this->db->select('*');

        $this->db->from('discountcode_rel_products');

        $this->db->where('d_id', $this->input->post('id'));

        $this->db->where('is_delete', 0);

        $Discount_rel_Products = $this->db->get()->result_array();

        echo json_encode($Discount_rel_Products);

    }



    public function ship_state()

    {

        $arr = array('id' => '1');

        echo json_encode($arr);

    }



    public function update_order()

    {

        $this->db->trans_start();

        $this->db->trans_complete();



        $ShippingOptions = $this->session->userdata('ShippingOptions');

        $DestinationState = $this->session->userdata('DestinationState');

        $DiscountCode = $this->session->userdata('DiscountCode');



        $UUID = md5(uniqid());





        if (is_array($DiscountCode)) {



            $Order = array(

                // 'order_no'     => $NEW_ORDER_NO,

                'order_date' => $this->input->post('order_date'),

                'order_state' => $this->input->post('order_state'),



                // 'user_id'      => $this->session->userdata('user_id'),

                'user_id' => $this->input->post('user_id'),

                'firstname' => $this->input->post('firstname'),

                'lastname' => $this->input->post('lastname'),

                'email' => $this->input->post('email'),

                'phone_number' => $this->input->post('phone'),



                // 'bill_firstname' => $this->input->post('bill_firstname'),

                // 'bill_lastname'  => $this->input->post('bill_lastname'),

                'bill_address' => $this->input->post('bill_address'),

                'bill_city' => $this->input->post('bill_city'),

                'bill_state' => $this->input->post('bill_state'),

                'bill_zipcode' => $this->input->post('bill_zipcode'),



                // 'ship_firstname' => $this->input->post('ship_firstname'),

                // 'ship_lastname'  => $this->input->post('ship_lastname'),

                'ship_address' => $this->input->post('ship_address'),

                'ship_city' => $this->input->post('ship_city'),

                'ship_state' => $this->input->post('ship_state'),

                'ship_zipcode' => $this->input->post('ship_zipcode'),



                'payment_method' => '',

                'name_on_card' => '',

                'card_type' => '',

                'card_number' => '',

                'card_expiry_month' => '',

                'card_expiry_year' => '',

                'ccv_number' => '',

                'card_type' => '',



                'track_number' => $this->input->post('track_number'),



                'discount_id' => $DiscountCode[0]->id,

                'discount_code' => $DiscountCode[0]->code,

                // 'discount'           => $this->session->userdata('Discount_Sub_Total'),

                'discount' => $this->input->post('hid_discount'),



                'product_tax' => $this->input->post('product_tax'),

                'calculate_shipping' => $this->input->post('calculate_shipping'),



                'shipping_id' => $ShippingOptions[0]['id'],

                'shipping_name' => $ShippingOptions[0]['name'],

                'shipping_price' => $ShippingOptions[0]['price'],

                'shipping_delivery' => $ShippingOptions[0]['delivery'],

                // 'shipping_option'    => $this->session->userdata('ShippingOptions'),



                'promo_free_shipping' => $this->session->userdata('FreeShipping'),

                'freeshipping' => $this->session->userdata('FreeShipping2'),



                'destination_id' => $DestinationState[0]['id'],

                'destination_state' => $DestinationState[0]['tax_code'],

                'tax_rate' => $DestinationState[0]['tax_rate'],

                // 'amount'             => $this->session->userdata('Amount'),

                'amount' => $this->input->post('amount'),

                'created_at' => unix_to_human(time(), TRUE, 'us'),

                'sys_id' => $UUID

            );



        } else {



            $Order = array(

                // 'order_no'     => $NEW_ORDER_NO,

                'order_date' => $this->input->post('order_date'),

                'order_state' => $this->input->post('order_state'),



                'user_id' => $this->input->post('user_id'),

                'firstname' => $this->input->post('firstname'),

                'lastname' => $this->input->post('lastname'),

                'email' => $this->input->post('email'),

                'phone_number' => $this->input->post('phone'),



                // 'bill_firstname' => $this->input->post('bill_firstname'),

                // 'bill_lastname'  => $this->input->post('bill_lastname'),

                'bill_address' => $this->input->post('bill_address'),

                'bill_city' => $this->input->post('bill_city'),

                'bill_state' => $this->input->post('bill_state'),

                'bill_zipcode' => $this->input->post('bill_zipcode'),



                // 'ship_firstname' => $this->input->post('ship_firstname'),

                // 'ship_lastname'  => $this->input->post('ship_lastname'),

                'ship_address' => $this->input->post('ship_address'),

                'ship_city' => $this->input->post('ship_city'),

                'ship_state' => $this->input->post('ship_state'),

                'ship_zipcode' => $this->input->post('ship_zipcode'),



                'payment_method' => '',

                'name_on_card' => '',

                'card_type' => '',

                'card_number' => '',

                'card_expiry_month' => '',

                'card_expiry_year' => '',

                'ccv_number' => '',

                'card_type' => '',



                'track_number' => $this->input->post('track_number'),



                // 'discount_id'        => $this->session->userdata('Discount_Sub_Total'),

                // 'discount_code'      => $this->session->userdata('Discount_Sub_Total'),

                // 'discount'           => $this->session->userdata('Discount_Sub_Total'),

                'discount' => $this->input->post('hid_discount'),



                'product_tax' => $this->input->post('product_tax'),

                'calculate_shipping' => $this->input->post('calculate_shipping'),



                'shipping_id' => $ShippingOptions[0]['id'],

                'shipping_name' => $ShippingOptions[0]['name'],

                'shipping_price' => $ShippingOptions[0]['price'],

                'shipping_delivery' => $ShippingOptions[0]['delivery'],

                // 'shipping_option'    => $this->session->userdata('ShippingOptions'),



                'promo_free_shipping' => $this->session->userdata('FreeShipping'),

                'freeshipping' => $this->session->userdata('FreeShipping2'),



                'destination_id' => $DestinationState[0]['id'],

                'destination_state' => $DestinationState[0]['tax_code'],

                'tax_rate' => $DestinationState[0]['tax_rate'],

                // 'amount'             => $this->session->userdata('Amount'),

                'amount' => $this->input->post('amount'),

                'created_at' => unix_to_human(time(), TRUE, 'us'),

                'sys_id' => $UUID

            );



        }



        $this->db->where('id', $this->input->post('id'));

        $this->db->update('order', $Order);



        //	取得新加入的訂單 ID

        // $order_id = $this->db->insert_id();



        $this->db->query("DELETE FROM `order_list` WHERE order_id = " . $this->input->post('id'));



        $pids = explode(',', $this->input->post('pids'));

        $prices = explode(',', $this->input->post('prices'));

        $qtys = explode(',', $this->input->post('qtys'));



        //	新增 Order List

        if (is_array($pids) && (count($pids) >= 1)) {

            $i = 0;

            foreach ($pids as $pid) {

                if ($pids[$i] != 0) {

                    $ProductList = array(

                        'order_id' => $this->input->post('id'),

                        'sys_id' => $UUID,

                        'pid' => $pid,

                        'price' => $prices[$i],

                        'qty' => $qtys[$i],

                        'updated_at' => unix_to_human(time(), TRUE, 'us')

                    );

                    $this->db->insert('order_list', $ProductList);

                }

                $i++;

            }

        }



        $this->cart->destroy();

        $this->session->unset_userdata('ShippingOptions');

        $this->session->unset_userdata('DestinationState');

        $this->session->unset_userdata('DiscountCode');



        $this->db->trans_complete();



        $arr = array('success' => '1');

        sleep(3);

        echo json_encode($arr);



    }



    public function save_order()

    {



        $this->db->trans_start();



        $ShippingOptions = $this->session->userdata('ShippingOptions');

        $DestinationState = $this->session->userdata('DestinationState');

        $DiscountCode = $this->session->userdata('DiscountCode');



        $NEW_ORDER_NO = $this->_getOrderNo();



        $UUID = md5(uniqid());



        if (is_array($DiscountCode)) {



            $Order = array(

                'order_no' => $NEW_ORDER_NO,

                'order_date' => $this->input->post('order_date'),

                'order_state' => $this->input->post('order_state'),



                // 'user_id'      => $this->session->userdata('user_id'),

                'user_id' => $this->input->post('user_id'),

                'firstname' => $this->input->post('firstname'),

                'lastname' => $this->input->post('lastname'),

                'email' => $this->input->post('email'),

                'phone_number' => $this->input->post('phone'),



                // 'bill_firstname' => $this->input->post('bill_firstname'),

                // 'bill_lastname'  => $this->input->post('bill_lastname'),

                'bill_address' => $this->input->post('bill_address'),

                'bill_city' => $this->input->post('bill_city'),

                'bill_state' => $this->input->post('bill_state'),

                'bill_zipcode' => $this->input->post('bill_zipcode'),



                // 'ship_firstname' => $this->input->post('ship_firstname'),

                // 'ship_lastname'  => $this->input->post('ship_lastname'),

                'ship_address' => $this->input->post('ship_address'),

                'ship_city' => $this->input->post('ship_city'),

                'ship_state' => $this->input->post('ship_state'),

                'ship_zipcode' => $this->input->post('ship_zipcode'),



                'payment_method' => '',

                'name_on_card' => '',

                'card_type' => '',

                'card_number' => '',

                'card_expiry_month' => '',

                'card_expiry_year' => '',

                'ccv_number' => '',

                'card_type' => '',



                'discount_id' => $DiscountCode[0]->id,

                'discount_code' => $DiscountCode[0]->code,

                // 'discount'           => $this->session->userdata('Discount_Sub_Total'),

                'discount' => $this->input->post('hid_discount'),



                'product_tax' => $this->input->post('product_tax'),

                'calculate_shipping' => $this->input->post('calculate_shipping'),



                'shipping_id' => $ShippingOptions[0]['id'],

                'shipping_name' => $ShippingOptions[0]['name'],

                'shipping_price' => $ShippingOptions[0]['price'],

                'shipping_delivery' => $ShippingOptions[0]['delivery'],

                // 'shipping_option'    => $this->session->userdata('ShippingOptions'),



                'promo_free_shipping' => $this->session->userdata('FreeShipping'),

                'freeshipping' => $this->session->userdata('FreeShipping2'),



                'track_number' => $this->input->post('track_number'),



                'destination_id' => $DestinationState[0]['id'],

                'destination_state' => $DestinationState[0]['tax_code'],

                'tax_rate' => $DestinationState[0]['tax_rate'],

                // 'amount'             => $this->session->userdata('Amount'),

                'amount' => $this->input->post('amount'),

                'created_at' => unix_to_human(time(), TRUE, 'us'),

                'sys_id' => $UUID

            );



        } else {



            $Order = array(

                'order_no' => $NEW_ORDER_NO,

                'order_date' => $this->input->post('order_date'),

                'order_state' => $this->input->post('order_state'),



                'user_id' => $this->input->post('user_id'),

                'firstname' => $this->input->post('firstname'),

                'lastname' => $this->input->post('lastname'),

                'email' => $this->input->post('email'),

                'phone_number' => $this->input->post('phone'),



                // 'bill_firstname' => $this->input->post('bill_firstname'),

                // 'bill_lastname'  => $this->input->post('bill_lastname'),

                'bill_address' => $this->input->post('bill_address'),

                'bill_city' => $this->input->post('bill_city'),

                'bill_state' => $this->input->post('bill_state'),

                'bill_zipcode' => $this->input->post('bill_zipcode'),



                // 'ship_firstname' => $this->input->post('ship_firstname'),

                // 'ship_lastname'  => $this->input->post('ship_lastname'),

                'ship_address' => $this->input->post('ship_address'),

                'ship_city' => $this->input->post('ship_city'),

                'ship_state' => $this->input->post('ship_state'),

                'ship_zipcode' => $this->input->post('ship_zipcode'),



                'payment_method' => '',

                'name_on_card' => '',

                'card_type' => '',

                'card_number' => '',

                'card_expiry_month' => '',

                'card_expiry_year' => '',

                'ccv_number' => '',

                'card_type' => '',



                'track_number' => $this->input->post('track_number'),



                // 'discount_id'        => $this->session->userdata('Discount_Sub_Total'),

                // 'discount_code'      => $this->session->userdata('Discount_Sub_Total'),

                // 'discount'           => $this->session->userdata('Discount_Sub_Total'),

                'discount' => $this->input->post('hid_discount'),



                'product_tax' => $this->input->post('product_tax'),

                'calculate_shipping' => $this->input->post('calculate_shipping'),



                'shipping_id' => $ShippingOptions[0]['id'],

                'shipping_name' => $ShippingOptions[0]['name'],

                'shipping_price' => $ShippingOptions[0]['price'],

                'shipping_delivery' => $ShippingOptions[0]['delivery'],

                // 'shipping_option'    => $this->session->userdata('ShippingOptions'),



                'promo_free_shipping' => $this->session->userdata('FreeShipping'),

                'freeshipping' => $this->session->userdata('FreeShipping2'),



                'destination_id' => $DestinationState[0]['id'],

                'destination_state' => $DestinationState[0]['tax_code'],

                'tax_rate' => $DestinationState[0]['tax_rate'],

                // 'amount'             => $this->session->userdata('Amount'),

                'amount' => $this->input->post('amount'),

                'created_at' => unix_to_human(time(), TRUE, 'us'),

                'sys_id' => $UUID

            );



        }



        $this->db->insert('order', $Order);



        //	取得新加入的訂單 ID

        $order_id = $this->db->insert_id();



        //	新增 Order List

        foreach ($this->cart->contents() as $items) {

		

		$type_db = 'product';	

		if(isset($items['type']) && $items['type']!="") {

			$type_db = $items['type'];

		 }

		

            $ProductList = array(

                'order_id' => $order_id,

                'sys_id' => $UUID,

                'pid'        => $items['id'],

				'item_type'        => $type_db,

				'p_name'        => $items['name'],

                'price' => $items['price'],

                'qty' => $items['qty'],

                'created_at' => unix_to_human(time(), TRUE, 'us')

            );

            $this->db->insert('order_list', $ProductList);

        }



        // $this->_sendVaildationMail(

        // 	$this->input->post('email'),

        // 	$NEW_ORDER_NO,

        // 	$ORDER_DATETIME,

        // 	$this->_Build_Order_Detail()

        // );



        $this->db->trans_complete();



        $this->cart->destroy();



        $this->session->unset_userdata('ShippingOptions');

        $this->session->unset_userdata('DestinationState');

        $this->session->unset_userdata('DiscountCode');



        $arr = array('success' => '1');

        sleep(3);

        echo json_encode($arr);



    }



    private function _getOrderNo()

    {

        //	Default Order No. 10000

        $new_order_no = '10000';



        $this->db->select('order_no');

        $this->db->from('order');

        $this->db->order_by('id', 'desc');

        $this->db->limit(1);

        $result = $this->db->get()->result_array();

        if (count($result) >= 1) {

            $new_order_no = $result[0]['order_no'] + 1;

        }



        return $new_order_no;

    }



    public function DestinationState($opt)

    {

        if ($opt == -1) {

            $this->session->set_userdata('DestinationState', array());

        } else {

            $Query = $this->db->query("SELECT * FROM tax_codes WHERE id = ?", $opt);

            $DestinationState = $Query->result_array();

            if (count($DestinationState) >= 1) {

                $this->session->set_userdata('DestinationState', $DestinationState);

            } else {

                $this->session->set_userdata('DestinationState', $DestinationState);

            }

        }

    }



    public function customer_case_send_message()

    {



        $customer_case_reply = array(

            'case_id' => $this->input->post('id'),

            'reply_message' => $this->input->post('message'),

            'created_at' => unix_to_human(time(), TRUE, 'us')

        );

        // $this->cart->insert($customer_case_reply);



        $this->_send_customer_case_reply_mail();

        $this->db->insert('customer_case_reply', $customer_case_reply);



        $arr = array('success' => '1', 'created_at' => unix_to_human(time(), TRUE, 'us'));

        echo json_encode($arr);



    }



    public function _send_customer_case_reply_mail()

    {

        $this->email->from($this->config->item('mailfrom'), 'Josie Maran');

        $this->email->to($this->input->post('email'));

        $this->email->bcc($this->config->item('mailbcc'));



        $this->email->subject('Thank you for contacting Josie Maran Cosmetics');

        $this->email->message(

            'Hi ' . $this->input->post('first_name') . " " . $this->input->post('last_name') . "," . br(2) .

                'Your Inquiry: ' . $this->input->post('comments') .

                br(2) .

                $this->input->post('message')

        );



        $this->email->send();

    }



    public function josies_bio_save()

    {

        $bio = array(

            'img' => $this->input->post('img'),

            'thumb_img' => $this->input->post('thumb_img'),

            'title' => $this->input->post('title'),

            'at_date' => $this->input->post('at_date'),

            'ordering' => $this->input->post('ordering')

        );



        $this->db->insert('bio_images', $bio);

        $arr = array('success' => '1', 'created_at' => unix_to_human(time(), TRUE, 'us'));

        echo json_encode($arr);

    }



    public function send_shipping_notify()

    {



        $id = $this->input->post('id');

        $this->track_number = $this->input->post('track_number');



        $query = $this->db->query("SELECT * FROM `order` WHERE id = ?", $id);

        $this->Order = $query->result();



        $query2 = $this->db->query("SELECT * FROM `order_list` WHERE order_id = ?", $id);

        $this->OrderList = $query2->result();

        if ($this->Order[0]->discount_id != 0) {

            $this->Discount = $this->db->query('SELECT * FROM discountcode WHERE id=?', $this->Order[0]->discount_id)->row();

        }

        // echo $this->db->last_query();



        $mailer = new Mailer();

        $mailer->send_invoice2($this->Order[0]->email);



        $arr = array('a' => '1');

        echo json_encode($arr);

    }



    public function send_order_notify()

    {

        $id = $this->input->post('id');

        $this->track_number = $this->input->post('track_number');



        $query = $this->db->query("SELECT * FROM `order` WHERE id = ?", $id);

        $this->Order = $query->result();

		

		$query = $this->db->query("SELECT " .

		"id, (select state from state as s where s.id = t.state_id) as `state`," .

		"state_id, " .

		"tax_code, tax_rate " .

		"FROM " .

		"tax_codes as t " .

		"where is_delete = 0 ORDER BY `state` ASC");

		$this->continental = $query->result();



        $query2 = $this->db->query("SELECT * FROM `order_list` WHERE order_id = ?", $id);

        $this->OrderList = $query2->result();



        if ($this->Order[0]->discount_id != 0) {

            $this->Discount = $this->db->query('SELECT * FROM discountcode WHERE id=?', $this->Order[0]->discount_id)->row();

        }



        $mailer = new Mailer();

        $mailer->send_order($this->Order[0]->email);





        $arr = array('a' => '1');

        echo json_encode($arr);

    }



    public function get_states($zip_id=NULL)

    {

		

        $id = $this->input->post("id");



        $data = $this->db->query("SELECT t.id, s.state,

                                         t.state_id,

                                         t.tax_code, t.tax_rate

                                    FROM tax_codes as t, state as s

                                    where is_delete = 0 && s.id = t.state_id && s.country_id = ?

                                    ORDER BY `sharthand` ASC", $id)->result();



        $this->output->set_status_header(200);

        $this->output->set_header('Content-type: application/json');

        if (count($data)) {

            $this->output->set_output(json_encode(array('data'=>$data)));

        } else {

            $this->output->set_output(json_encode(array('data'=>array())));

        }

		

    }

	

	public function get_shipping_state()

	{

		$this->load->model('cart_shipping_model');

		

		if($this->input->get('avail')){

			$cid = $this->input->get('avail');

			$this->session->unset_userdata('DestinationState');

			$this->session->unset_userdata('ShippingOptions');

			$this->session->unset_userdata('ship_zipcode');

			$this->session->set_userdata('cart_country', $cid);

			$data['shipping_state'] = $this->cart_shipping_model->getStates($cid);

			$data['shipping_option'] = 'state';

			$this->load->view('shipping_option', $data);

		}else {

			redirect('');

		}

	}

	

	public function get_shipping_option()

	{

		$this->load->model('cart_shipping_model');

		

		if($this->input->get('avail')){

			

			$this->session->unset_userdata('ShippingOptions');

			$this->session->unset_userdata('ship_zipcode');

			$sid = $this->input->get('avail');			

			$this->cart_shipping_model->DestinationState($sid);

			$shipping_method = $this->cart_shipping_model->getShippingMethod();

			$data['onlineShipping'] = $this->cart_shipping_model->OnlineShippingMethod();

			

			$country_id = $this->session->userdata('cart_country');

			$usps_states_array = array(79, 80, 2, 21, 4, 17, 20, 31, 70, 50);

			

			if($country_id != 1 || array_search($sid, $usps_states_array) !== false) {



	            $new_shipping_list = array();



				foreach($shipping_method as $shipping) {

					if(preg_match("/usps/mi", $shipping->name)) {

						$new_shipping_list[] = $shipping;

					}

					

				}



            //$shipping_method = $new_shipping_list;

        }

			

			$data['shipping_method'] = $shipping_method;

			$data['shipping_option'] = 'option';

			$this->load->view('shipping_option', $data);

		}else {

			redirect('');

		}

	}

	

	public function update_shipping_option(){

		$state = $this->input->post('state');
		$zipcodeInput = $this->input->post('zipcodeInput');
		$shipping_method = $this->input->post('shipping_method');
		$vouchercodeInput = $this->input->post('vouchercodeInput');
		$discountcodeInput = $this->input->post('discountcodeInput');

		$this->session->unset_userdata('cart_state');
		$this->session->unset_userdata('ship_zipcode');
		$this->session->unset_userdata('cart_shipping_method');
		$this->session->unset_userdata('cart_voucher');
		$this->session->unset_userdata('cart_promo');

		if($state != '-1' and $state!=""){
			$this->session->set_userdata('cart_state', $state);
		}

		if($zipcodeInput!=""){
			$this->session->set_userdata('ship_zipcode', $zipcodeInput);
		}

		if($shipping_method != '-1' and $shipping_method!=""){
			$this->session->set_userdata('cart_shipping_method', $shipping_method);
		}

		if(isset($vouchercodeInput) and $vouchercodeInput!=""){
			$this->session->set_userdata('cart_voucher', $vouchercodeInput);
		}

		if(isset($discountcodeInput) and $discountcodeInput!=""){
			$this->session->set_userdata('cart_promo', $discountcodeInput);
		}

		
		$this->ShoppingCart = new SystemCart();
		$this->ShoppingCart->ShippingOptions($shipping_method);
		$this->ShoppingCart->DestinationState($state);		
		$this->ShoppingCart->Promo($discountcodeInput);
		$this->ShoppingCart->Voucher($vouchercodeInput);
		

		$this->FreeShipping  = $this->ShoppingCart->FreeShipping($this->session->userdata('cart_shipping_method'));
		$this->ProductTax           = $this->ShoppingCart->ProductTax();
		$this->session->set_userdata('CalculateShipping', $this->ShoppingCart->CalculateShipping());
		$this->Sum                  = $this->ShoppingCart->Sum();

		$shippingprice = '';
		$is_free_shipping = 'no';
		$IS_FREE_SHIPPING2 = ($this->session->userdata('FreeShipping2') ? $this->session->userdata('FreeShipping2') : 0);

		if ($IS_FREE_SHIPPING2 == TRUE) {
			if ($this->session->userdata('FreeShipping2')!= '' and $this->session->userdata('FreeShipping2')!= '0') {

				$Query = $this->db->query("SELECT `id`, `name`, `price` FROM shipping_method where is_delete = 0 AND id != 99 and id = '".$this->session->userdata('FreeShipping2')."' ");
				$shipping_method = $Query->row();
				$shippingprice  =  $shipping_method->price;
				$is_free_shipping = 'yes';
			}

		}

		$this->session->set_userdata('ProductTax', $this->ProductTax);
		$this->session->set_userdata('Amount', $this->Sum);

		$voucher_html = '<input type="text" name="vouchercodeInput" value="" id="vouchercodeInput" class="inputtext" onBlur="javascript:update_shipping_option();" >';

		if ($this->session->userdata('VoucherCode') && isset($this->ShoppingCart->voucher_sub_total)) {
			//<input type="submit" name="clear_voucher" value="Reset" id="clear_voucher" class="inputbutton">
			$VoucherCode = $this->session->userdata('VoucherCode');
			$voucher_html = '<span  style="text-align:left; color:green" >';
			$voucher_html .= 'Code: "'.$VoucherCode[0]->code.'"<br> Amount: -$'.number_format($this->ShoppingCart->voucher_sub_total, 2).'&nbsp;&nbsp;<input type="hidden" name="vouchercodeInput" value="'.$vouchercodeInput.'"   id="vouchercodeInput" class="inputtext"><a style="width:80px;float:right;margin-right:72px;margin-top:-2px;padding-top:0px;width:48px" href="/myshoppingcart/clear_discount/clearVoucher" class="inputbutton">Reset</a>';

			if ($this->session->userdata('VoucherBalance')) {
				$voucher_html .= '<br/>(Remaining balance  $'.$this->session->userdata('VoucherBalance').')';
			}

			$voucher_html .= '</span>';

		}

		

		$promo_html = '<input type="text" name="discountcodeInput" value="" id="discountcodeInput" class="inputtext" onBlur="javascript:update_shipping_option();" >';
		if ($this->session->userdata('DiscountCode') && isset($this->ShoppingCart->discount_sub_total)) {
			$DiscountCode = $this->session->userdata('DiscountCode');
			$promo_html = '<span  style="text-align:left; color:green" >';

			if ($this->Promo_FreeShipping == 1){

				if ($this->ShoppingCart->discount_sub_total <= 0) {
					$promo_html .= 'Code: "'.$DiscountCode[0]->code.'<br>';
					if ($this->session->set_userdata('cart_shipping_method') <= 1) {
						$promo_html .= 'Discount: -$7.95 Free Shipping';
					}

				}else {

					$promo_html .= 'Code: "'.$DiscountCode[0]->code.'<br>';
					$promo_html .= 'Discount: -$'.number_format($this->ShoppingCart->discount_sub_total, 2);
					if ($this->session->set_userdata('cart_shipping_method') <= 1) {
						$promo_html .= '&nbsp;&nbsp;(Plus -$7.95 Free Shipping!)';

					}

				}

			}else {

				$promo_html .= 'Code: "'.$DiscountCode[0]->code.'<br>';

				if($DiscountCode[0]->discount_type != 4 && $DiscountCode[0]->discount_type != 5){

					$promo_html .= 'Discount: -$'.number_format($this->ShoppingCart->discount_sub_total, 2).'&nbsp;&nbsp';

				}

			}

			//<input type="submit" name="clear_promo" value="Reset" id="clear_promo" class="inputbutton"></span>

			$promo_html .= '<input type="hidden" name="discountcodeInput" value="'.$DiscountCode[0]->code.'" id="discountcodeInput" class="inputtext"><a style="width:80px;float:right;margin-right:72px;margin-top:-2px;padding-top:0px;width:48px" href="/myshoppingcart/clear_discount/clearPromo" class="inputbutton">Reset</a>';

		}

		$cart_listing = $this->load->view('mini_viewcart', TRUE);
		
		echo $this->Sum.'|||'.$this->ProductTax.'|||'.$shippingprice.'|||'.$is_free_shipping.'|||'.$voucher_html.'|||'.$promo_html.'|||'.$cart_listing; 

	}

	

	

}


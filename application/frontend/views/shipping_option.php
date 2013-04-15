<?php if($shipping_option == 'state'){ ?>
<select name="state" id="state" size="1" class="dropdown" onchange="get_shipping_option(this.value, '<?=site_url()?>ajax/get_shipping_option', 'shipping_option_div', 'no')"  >
      <option value="-1">Please select</option>
            <?php

            foreach ($shipping_state as $state) {

                $is_selected = '';

                if ($this->session->userdata('DestinationState')) {

                    $ds = $this->session->userdata('DestinationState');

                    if ($state->id == $ds[0]['id']) {

                        $is_selected = "selected=\"selected\"";

                    }

                }

                printf("<option value=\"%s\" %s>%s</option>", $state->id, $is_selected, $state->tax_code);

            }

            ?>

</select>
<?php } ?>
<?php 
if($shipping_option == 'option'){
	$IS_VOUCHER_ONLY = true;
	foreach ($this->cart->contents() as $item) {
			if (!array_key_exists('type', $item)) {
				$IS_VOUCHER_ONLY = false;
				break;
			}
	 }
 ?>
 <select name="shipping_method" id="shipping_method" size="1" class="dropdown" onchange="update_shipping_option()" >
            <option value='-1'>Please select</option>
            <?php
            $FREE_SHIPPING = ($this->session->userdata('FreeShipping') ? $this->session->userdata('FreeShipping') : 0);
            $IS_FREE_SHIPPING2 = ($this->session->userdata('FreeShipping2') ? $this->session->userdata('FreeShipping2') : 0);
            $ds = $this->session->userdata('DestinationState');
            if ($IS_VOUCHER_ONLY) {
                $is_selected = '';

                if ($this->session->userdata('ShippingOptions')) {

                    $sp = $this->session->userdata('ShippingOptions');

                    if (99 == $sp[0]['id']) {

                        $is_selected = "selected=\"selected\"";

                    }

                }



                printf("<option value=\"%s\" %s>%s&nbsp;&nbsp;&nbsp;</option>",

                    '99', $is_selected, $onlineShipping->name

                );

            } else {



                foreach ($shipping_method as $method) {

                    $is_selected = '';



                    if ($this->session->userdata('ShippingOptions')) {

                        $sp = $this->session->userdata('ShippingOptions');

                        if ($method->id == $sp[0]['id']) {

                            $is_selected = "selected=\"selected\"";

                        }

                    }



                    if (is_array($ds) && count($ds) >= 1) {

                      

                        if ($ds[0]['id'] == 2 || $ds[0]['id'] == 21 || $ds[0]['id'] == 61 || $ds[0]['id'] == 79 || $ds[0]['id'] == 80) {

                            if ($method->id != 2 && $method->id != 1) {

                                printf("<option value=\"%s\" %s>%s&nbsp;&nbsp;&nbsp;%s</option>",

                                    $method->id, $is_selected, $method->name, $method->price

                                );

                            }

                        } else {

                            if ($FREE_SHIPPING == 1 || $IS_FREE_SHIPPING2 == 1 || $bol_SHOW_FREESHIPPING == TRUE) {



                                



                                // if ($method->id != 2) {

                                printf("<option value=\"%s\" %s>%s&nbsp;&nbsp;&nbsp;%s</option>",

                                    $method->id, $is_selected, $method->name, $method->price

                                );

                                // }



                            } else {

                                printf("<option value=\"%s\" %s>%s&nbsp;&nbsp;&nbsp;%s</option>",

                                    $method->id, $is_selected, $method->name, $method->price

                                );

                            }

                        }

                    } else {

                        if ($FREE_SHIPPING == 1 || $IS_FREE_SHIPPING2 == 1 || $bol_SHOW_FREESHIPPING == TRUE) {

                            // if ($method->id != 2) {

                            printf("<option value=\"%s\" %s>%s&nbsp;&nbsp;&nbsp;%s</option>",

                                $method->id, $is_selected, $method->name, $method->price

                            );

                            // }

                        } else {

                            printf("<option value=\"%s\" %s>%s&nbsp;&nbsp;&nbsp;%s</option>",

                                $method->id, $is_selected, $method->name, $method->price

                            );

                        }

                    }



                }

            }



            ?>

        </select>

<?php } ?>
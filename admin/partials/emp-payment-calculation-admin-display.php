<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://empassion.com.au
 * @since      1.0.0
 *
 * @package    Emp_Payment_Calculation
 * @subpackage Emp_Payment_Calculation/admin/partials
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->



<div class="wrap">

    <h2><?php echo esc_html(get_admin_page_title()); ?></h2>
    <div class="container">
        <div class="row">
            <div class="col">
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="orders-tab" data-toggle="tab" href="#orders" role="tab" aria-controls="orders" aria-selected="true">Orders Summary</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="settings-tab" data-toggle="tab" href="#settings" role="tab" aria-controls="settings" aria-selected="false">Settings</a>
                    </li>

                </ul>
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="orders" role="tabpanel" aria-labelledby="orders-tab">
                        <h2>Orders Summary</h2>

                        <div ng-app="paymentCalculationApp" ng-controller="paymentController">

                            <form class="form-inline">
                                <div class="form-group col-md-4 pl-0">
                                    <input class="w-100 form-control" type="text" id="reportrange" ng-model="dateRange" data-dateRangeStart="" date-dateRangeEnd="" />
                                </div>
                                <div class="form-group col-md-1 pl-0">
                                    <button id="wcGenerateBtn" type="button" class="btn btn-primary" ng-click="wcLoadOrders()">Generate</button>
                                </div>
                                <div class="form-group col-md-2 pl-0">
                                    <button type="button" class="btn btn-light ml-2" ng-click="exportJsonToCsv()">Export CSV</button>
                                </div>
                            </form>
                            <div class="col-md-12 mt-2 mb-2 pl-0">
                                <div ng-repeat="item in defaultStates" class="form-check form-check-inline">
                                    <input class="form-check-input" id="state-{{ item.state }}" type="checkbox" ng-model="item.selected"
                                           ng-true-value="true" ng-change="selectedStates(item)" ng-false-value=""/>
                                    <label class="form-check-label" for="state-{{ item.state }}">{{ item.state }}</label>
                                </div>
                            </div>

                            <table class="table table-striped">
                                <thead>
                                <tr>
                                    <th scope="col" ng-click="sort('order_id')">Order ID</th>
                                    <th scope="col" ng-click="sort('state')">State</th>
                                    <th scope="col" ng-click="sort('payment_method')">Payment Method</th>
                                    <th scope="col" ng-click="sort('total')">Sales</th>
                                    <th scope="col" ng-click="sort('charges')">Charges</th>
                                    <th scope="col" ng-click="sort('revenue')">Distributor Revenue</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr ng-repeat="order in ordersSummary | orderBy:sortKey:reverse | filter:filterStates():true  ">
                                    <th scope="row">{{order.order_id}}</th>
                                    <td>{{order.state}}</td>
                                    <td>{{order.payment_method | uppercase}}</td>
                                    <td>{{order.total}}</td>
                                    <td>{{order.charges}}</td>
                                    <td>{{order.revenue}}</td>
                                </tr>

                                </tbody>
                            </table>
                            <dir-pagination-controls
                                    max-size="5"
                                    direction-links="true"
                                    boundary-links="true" >
                            </dir-pagination-controls>
		                    <?php $nonce = wp_create_nonce("empdev_payment_calculation_nonce"); ?>
                            <input type="hidden" id="wc-nonce" data-nonce="<?php echo $nonce; ?>" />

                            <!--{{ordersSummary | json}}-->
		                    <?php /*var_dump($orders); */?>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="settings" role="tabpanel" aria-labelledby="settings-tab">

                        <h3>Payment Charges</h3>
                        <form method="post" name="emp_payment_calculattion" action="options.php">
		                    <?php
		                    $options = get_option($this->plugin_name);

		                    /*
							* Set up hidden fields
							*
							*/
		                    settings_fields($this->plugin_name);
		                    do_settings_sections($this->plugin_name);

		                    $gateway_charge_paypal = $options['gateway_charge_paypal'];
		                    $gateway_charge_stripe = $options['gateway_charge_stripe'];
		                    $gateway_charge_afterpay = $options['gateway_charge_afterpay'];
		                    $gateway_charge_square = $options['gateway_charge_square'];
		                    $gateway_charge_zip = $options['gateway_charge_zip'];
		                    ?>
                            <div class="form-row">
                                <div class="col-md-2">
                                    <label for="<?php echo $this->plugin_name;?>-options_paypal">Paypal</label>
                                    <div class="input-group mb-2">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="btn_pretext">$</span>
                                        </div>
                                        <input name="<?php echo $this->plugin_name;?>[gateway_charge_paypal]" value="<?php echo $gateway_charge_paypal;?>" id="<?php echo $this->plugin_name;?>-options_paypal" type="text" class="form-control type-number" placeholder="Percent" aria-label="gateway_paypal" aria-describedby="btn_pretext">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <label for="<?php echo $this->plugin_name;?>-options_stripe"">Stripe</label>
                                    <div class="input-group mb-2">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="btn_pretext2">$</span>
                                        </div>
                                        <input name="<?php echo $this->plugin_name;?>[gateway_charge_stripe]" value="<?php echo $gateway_charge_stripe;?>" id="<?php echo $this->plugin_name;?>-options_stripe" type="text" class="form-control type-number" placeholder="Percent" aria-label="gateway_stripe" aria-describedby="btn_pretext2">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <label for="<?php echo $this->plugin_name;?>-options_square">Square</label>
                                    <div class="input-group mb-2">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="btn_pretext3">$</span>
                                        </div>
                                        <input name="<?php echo $this->plugin_name;?>[gateway_charge_square]" value="<?php echo $gateway_charge_square;?>" id="<?php echo $this->plugin_name;?>-options_square" type="text" class="form-control type-number" placeholder="Percent" aria-label="gateway_square" aria-describedby="btn_pretext3">
                                    </div>
                                </div>

                            </div>
                            <div class="form-row">
                                <div class="col-md-2">
                                    <label for="<?php echo $this->plugin_name;?>-options_afterpay">Afterpay</label>
                                    <div class="input-group mb-2">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="btn_pretext4">$</span>
                                        </div>
                                        <input name="<?php echo $this->plugin_name;?>[gateway_charge_afterpay]" value="<?php echo $gateway_charge_afterpay;?>" id="<?php echo $this->plugin_name;?>-options_afterpay" type="text" class="form-control type-number" placeholder="Percent" aria-label="gateway_afterpay" aria-describedby="btn_pretext4">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <label for="<?php echo $this->plugin_name;?>-options_zip">Zip Pay</label>
                                    <div class="input-group mb-2">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="btn_pretext5">$</span>
                                        </div>
                                        <input name="<?php echo $this->plugin_name;?>[gateway_charge_zip]" value="<?php echo $gateway_charge_zip;?>" id="<?php echo $this->plugin_name;?>-options_zip" type="text" class="form-control type-number" placeholder="Percent" aria-label="gateway_zip" aria-describedby="btn_pretext5">
                                    </div>
                                </div>
                            </div>

                           <!-- <fieldset class="">
                                <legend class="screen-reader-text"><span><?php /*_e('Paypal', $this->plugin_name);*/?></span></legend>
                                <label for="<?php /*echo $this->plugin_name;*/?>-options_paypal">
                                    <input type="text" class="" id="<?php /*echo $this->plugin_name;*/?>-options_paypal" name="<?php /*echo $this->plugin_name;*/?>[gateway_charge_paypal]" value="<?php /*echo $gateway_charge_paypal;*/?>" />
                                    <span></span>
                                </label>
                            </fieldset>-->


		                    <?php submit_button(__('Save', $this->plugin_name), 'primary','submit', TRUE); ?>

                        </form>

                    </div>



                </div>

            </div>

        </div>

    </div>

    <?php

    echo '<br/>';


    ?>

</div>


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
                        <br/>
                        <h3>Orders Summary</h3>

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

                            <br/>

                            <div class="form-row">
                                <div class="col-md-8 mt-2 mb-2 pl-0">
                                    <div ng-repeat="item in defaultStates" class="form-check form-check-inline">
                                        <input class="form-check-input" id="state-{{ item.state }}" type="checkbox" ng-model="item.selected"
                                               ng-true-value="true" ng-change="selectedStates(item)" ng-false-value=""/>
                                        <label class="form-check-label" for="state-{{ item.state }}">{{ item.state }}</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="input-group mb-3">
                                        <input type="text" placeholder="Search..." class="form-control" ng-model="searchOrders" />
                                        <div class="input-group-append">
                                            <div class="input-group-text"><i class="fa fa-search" aria-hidden="true"></i></div>
                                        </div>
                                    </div>

                                </div>
                            </div>


                            <table id="orders-data-table" class="table table-striped">
                                <thead>
                                <tr>
                                    <th scope="col" ng-click="sort('order_id')" ng-class="{reverse: reverse}">Order ID</th>
                                    <th scope="col" ng-click="sort('state')" ng-class="{reverse: reverse}">State</th>
                                    <th scope="col" ng-click="sort('payment_method')" ng-class="{reverse: reverse}">Payment Method</th>
                                    <th scope="col" ng-click="sort('total')" ng-class="{reverse: reverse}">Sales</th>
                                    <th scope="col" ng-click="sort('charges')" ng-class="{reverse: reverse}">Charges</th>
                                    <th scope="col" ng-click="sort('revenue')" ng-class="{reverse: reverse}">Distributor Revenue</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr ng-repeat="order in ordersSummary | orderBy:sortKey:reverse | filter:filterStates():true | filter:searchOrders ">
                                    <th scope="row">{{order.order_id}}</th>
                                    <td>{{order.state}}</td>
                                    <td>{{order.payment_method | uppercase}}</td>
                                    <td>{{order.total}}</td>
                                    <td>{{order.charges}}</td>
                                    <td>{{order.revenue}}</td>
                                </tr>

                                </tbody>
                            </table>
                            <div class="text-center alert alert-light" role="alert">
                                <span id="default-msg">Select a date and generate!</span>
                                <span id="loader-msg">loading...</span>
                            </div>
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
                        <br/>
                        <h3>Payment Gateway Charges</h3>
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
                            $gateway_fixed_charge_paypal = $options['gateway_fixed_charge_paypal'];
                            $gateway_charge_stripe = $options['gateway_charge_stripe'];
                            $gateway_fixed_charge_stripe = $options['gateway_fixed_charge_stripe'];
                            $gateway_charge_afterpay = $options['gateway_charge_afterpay'];
                            $gateway_fixed_charge_afterpay = $options['gateway_fixed_charge_afterpay'];
                            $gateway_charge_square = $options['gateway_charge_square'];
                            $gateway_charge_zipmoney = $options['gateway_charge_zipmoney'];
                            $gateway_fixed_charge_zipmoney = $options['gateway_fixed_charge_zipmoney'];
		                    ?>
                            <div class="form-row">
                                <div class="col-md-5">
                                    <label for="<?php echo $this->plugin_name;?>-options_paypal">Paypal</label>
                                    <div class="form-row">
                                        <div class="col-md-6">
                                            <div class="input-group mb-2">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text" id="btn_pretext">$</span>
                                                </div>
                                                <input name="<?php echo $this->plugin_name;?>[gateway_charge_paypal]" value="<?php echo $gateway_charge_paypal;?>" id="<?php echo $this->plugin_name;?>-options_paypal" type="text" class="form-control type-number" placeholder="Percent" aria-label="gateway_paypal" aria-describedby="btn_pretext">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="input-group mb-2">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text" id="btn_pretext">+</span>
                                                </div>
                                                <input name="<?php echo $this->plugin_name;?>[gateway_fixed_charge_paypal]" value="<?php echo $gateway_fixed_charge_paypal;?>" id="<?php echo $this->plugin_name;?>-options_fixed_paypal" type="text" class="form-control type-number" placeholder="Fixed Charge" aria-label="gateway_fixed_paypal" aria-describedby="btn_pretext">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <label for="<?php echo $this->plugin_name;?>-options_stripe"">Stripe</label>
                                    <div class="form-row">
                                        <div class="col-md-6">
                                            <div class="input-group mb-2">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text" id="btn_pretext2">$</span>
                                                </div>
                                                <input name="<?php echo $this->plugin_name;?>[gateway_charge_stripe]" value="<?php echo $gateway_charge_stripe;?>" id="<?php echo $this->plugin_name;?>-options_stripe" type="text" class="form-control type-number" placeholder="Percent" aria-label="gateway_stripe" aria-describedby="btn_pretext2">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="input-group mb-2">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text" id="btn_pretext">+</span>
                                                </div>
                                                <input name="<?php echo $this->plugin_name;?>[gateway_fixed_charge_stripe]" value="<?php echo $gateway_fixed_charge_stripe;?>" id="<?php echo $this->plugin_name;?>-options_fixed_stripe" type="text" class="form-control type-number" placeholder="Fixed Charge" aria-label="gateway_fixed_stripe" aria-describedby="btn_pretext">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col-md-5">
                                    <label for="<?php echo $this->plugin_name;?>-options_afterpay">Afterpay</label>
                                    <div class="form-row">
                                        <div class="col-md-6">
                                            <div class="input-group mb-2">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text" id="btn_pretext4">$</span>
                                                </div>
                                                <input name="<?php echo $this->plugin_name;?>[gateway_charge_afterpay]" value="<?php echo $gateway_charge_afterpay;?>" id="<?php echo $this->plugin_name;?>-options_afterpay" type="text" class="form-control type-number" placeholder="Percent" aria-label="gateway_afterpay" aria-describedby="btn_pretext4">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="input-group mb-2">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text" id="btn_pretext">+</span>
                                                </div>
                                                <input name="<?php echo $this->plugin_name;?>[gateway_fixed_charge_afterpay]" value="<?php echo $gateway_fixed_charge_afterpay;?>" id="<?php echo $this->plugin_name;?>-options_fixed_afterpay" type="text" class="form-control type-number" placeholder="Fixed Charge" aria-label="gateway_fixed_afterpay" aria-describedby="btn_pretext">
                                            </div>
                                        </div>
                                    </div>
                                    
                                </div>
                                <div class="col-md-5">
                                    <label for="<?php echo $this->plugin_name;?>-options_zipmoney">Zip Pay</label>
                                    <div class="form-row">
                                        <div class="col-md-6">
                                            <div class="input-group mb-2">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text" id="btn_pretext5">$</span>
                                                </div>
                                                <input name="<?php echo $this->plugin_name;?>[gateway_charge_zipmoney]" value="<?php echo $gateway_charge_zipmoney;?>" id="<?php echo $this->plugin_name;?>-options_zipmoney" type="text" class="form-control type-number" placeholder="Percent" aria-label="gateway_zipmoney" aria-describedby="btn_pretext5">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="input-group mb-2">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text" id="btn_pretext">+</span>
                                                </div>
                                                <input name="<?php echo $this->plugin_name;?>[gateway_fixed_charge_zipmoney]" value="<?php echo $gateway_fixed_charge_zipmoney;?>" id="<?php echo $this->plugin_name;?>-options_fixed_zipmoney" type="text" class="form-control type-number" placeholder="Fixed Charge" aria-label="gateway_fixed_zipmoney" aria-describedby="btn_pretext">
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div class="form-row">
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


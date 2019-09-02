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
                        <a class="nav-link" id="settings-tab" data-toggle="tab" href="#settings" role="tab" aria-controls="settings" aria-selected="false">Global Settings</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="contact-tab" data-toggle="tab" href="#contact" role="tab" aria-controls="contact" aria-selected="false">Contact</a>
                    </li>
                </ul>
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="orders" role="tabpanel" aria-labelledby="orders-tab">
                        <h2>Orders Summary</h2>

                        <div ng-app="paymentCalculationApp" ng-controller="paymentController">

                            <table class="table table-striped">
                                <thead>
                                <tr>
                                    <th scope="col">Order ID</th>
                                    <th scope="col">First</th>
                                    <th scope="col">Last</th>
                                    <th scope="col">Handle</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <th scope="row">1</th>
                                    <td>Mark</td>
                                    <td>Otto</td>
                                    <td>@mdo</td>
                                </tr>
                                <tr>
                                    <th scope="row">2</th>
                                    <td>Jacob</td>
                                    <td>Thornton</td>
                                    <td>@fat</td>
                                </tr>
                                <tr>
                                    <th scope="row">3</th>
                                    <td>Larry</td>
                                    <td>the Bird</td>
                                    <td>@twitter</td>
                                </tr>
                                </tbody>
                            </table>
		                    <?php $nonce = wp_create_nonce("empdev_payment_calculation_nonce"); ?>
                            <input type="hidden" id="wc-nonce" data-nonce="<?php echo $nonce; ?>" />

                            <div id="reportrange" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%">
                                <i class="fa fa-calendar"></i>&nbsp;
                                <span></span> <i class="fa fa-caret-down"></i>
                            </div>
                            <input type="text" id="reportrange1" class="form-control">

                            <br>
                            <!--{{ordersSummary | json}}-->
		                    <?php /*var_dump($orders); */?>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="settings" role="tabpanel" aria-labelledby="settings-tab">

                        <h2>Global Settings</h2>
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
		                    ?>
                            <fieldset class="">
                                <legend class="screen-reader-text"><span><?php _e('Paypal', $this->plugin_name);?></span></legend>
                                <label for="<?php echo $this->plugin_name;?>-options_paypal">
                                    <input type="text" class="" id="<?php echo $this->plugin_name;?>-options_paypal" name="<?php echo $this->plugin_name;?>[gateway_charge_paypal]" value="<?php echo $gateway_charge_paypal;?>" />
                                    <span></span>
                                </label>
                            </fieldset>

		                    <?php submit_button(__('Save', $this->plugin_name), 'primary','submit', TRUE); ?>

                        </form>

                    </div>

                    <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">Contact</div>

                </div>

            </div>

        </div>

    </div>

    <?php

    echo '<br/>';


    ?>

</div>


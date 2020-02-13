jQuery(document).ready(function ($) {

    console.log('Payment Calculation JS');

    //console.log(orders_summary);

    $('#nav-tab a').click(function (e) {
        e.preventDefault();
        $('#nav-tab a').removeClass('active');
        $(this).addClass('active');
        //$(this).tab('show');
        console.log('click');

    });

    var start = moment().subtract(29, 'days');
    var end = moment();

    function cb(start, end, label) {
        $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
    }

    $('#reportrange').daterangepicker({
        startDate: start,
        endDate: end,
        ranges: {
            'Today': [moment(), moment()],
            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Last 7 Days': [moment().subtract(6, 'days'), moment()],
            'Last 30 Days': [moment().subtract(29, 'days'), moment()],
            'This Month': [moment().startOf('month'), moment().endOf('month')],
            'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        }
    }, cb);

    cb(start, end);

    jQuery('#wc-loader').click(function () {
        var nonce = jQuery(this).attr("data-nonce");
        console.log(nonce);
        jQuery.ajax({
            type: "post",
            dataType: "json",
            url: empdevajax.ajaxurl,
            data: {action: "get_wc_order_data", post_id: '1', nonce: nonce},
            success: function (response) {
                console.log(response);
            }
        });

    });

});

(function ($) {
    'use strict';

    /**
     * All of the code for your admin-facing JavaScript source
     * should reside in this file.
     *
     * Note: It has been assumed you will write jQuery code here, so the
     * $ function reference has been prepared for usage within the scope
     * of this function.
     *
     * This enables you to define handlers, for when the DOM is ready:
     *
     * $(function() {
	 *
	 * });
     *
     * When the window is loaded:
     *
     * $( window ).load(function() {
	 *
	 * });
     *
     * ...and/or other possibilities.
     *
     * Ideally, it is not considered best practise to attach more than a
     * single DOM-ready or window-load handler for a particular page.
     * Although scripts in the WordPress core, Plugins and Themes may be
     * practising this, we should strive to set a better example in our own work.
     */


})(jQuery);


// var app = angular.module('paymentCalculationApp', []);
// app.controller('paymentController', function($scope) {
//
//     //$scope.ordersSummary = orders_summary;
//
// });
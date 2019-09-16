//var orders_summary = [{order_id: '2', state: 'nsw', payment_method: 'paypal', total: '4.42'}];


jQuery(document).ready(function($){

    console.log('Payment Calculation JS');

    //console.log(orders_summary);

    // $('#nav-tab a').click( function(e){
    //     e.preventDefault();
    //     $('#nav-tab a').removeClass('active');
    //     $(this).addClass('active');
    //     //$(this).tab('show');
    //     console.log('click');
    //
    // });

    var start = moment().subtract(29, 'days');
    var end = moment();
    $('#reportrange').val( start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY') );

    function cb(start, end, label) {
        $('#reportrange').val( start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY') );
        $('#reportrange').attr( 'data-dateRangeStart', start.format('YYYY-MM-DD') );
        $('#reportrange').attr( 'data-dateRangeEnd', end.format('YYYY-MM-DD') );
        var nonce = $('#wc-nonce').attr("data-nonce");
        console.log( nonce );
        jQuery.ajax({
            type : "post",
            dataType : "json",
            url : empdevajax.ajaxurl,
            data : {action: "get_wc_order_data", nonce: nonce, date_start: start.format('YYYY-MM-DD'), date_end: end.format('YYYY-MM-DD') },
            success: function(response) {
                orders_summary = response;
                console.log( response );
            }
        });

        console.log('New date range selected: ' + start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY') + ' (predefined range: ' + label + ')');

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
        },
        locale: {
            //format: 'MMMM DD, YYYY hh:mm A'
            format: 'MMMM DD, YYYY'
        }
    }, cb).click();

});

(function( $ ) {
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


})( jQuery );


var app = angular.module('paymentCalculationApp', []);
app.controller('paymentController', function($scope, $http) {

    $scope.ordersSummary = [];

    $scope.nonce = angular.element(document).find('#wc-nonce').attr('data-nonce');

    $scope.wcLoadOrders = function () {
        console.log( angular.element(document).find('#reportrange').attr('data-dateRangeStart') );
        console.log( angular.element(document).find('#reportrange').attr('data-dateRangeEnd') );
        $scope.dateRangeStart = angular.element(document).find('#reportrange').attr('data-dateRangeStart');
        $scope.dateRangeEnd = angular.element(document).find('#reportrange').attr('data-dateRangeEnd');

        $http({
            method: 'POST',
            url: empdevajax.ajaxurl,
            params: {
                action: "get_wc_order_data",
                nonce: $scope.nonce,
                date_start: $scope.dateRangeStart,
                date_end: $scope.dateRangeEnd,
            },
            //headers : { 'Content-Type': 'application/x-www-form-urlencoded' }
        }).then(function successCallback(response) {
            console.log("Angular HTTP sucess");
            console.log(response);
            $scope.ordersSummary = response.data;
        }, function errorCallback(response){
            conosle.log("Angular HTTP fail");
            console.log(response);
        });

    }

    // $scope.dateRangeChange = function(){
    //     //var currentElement = $event;
    //     //console.log(event.target.value);
    //     //$scope.dateRange = currentElement.value;
    //     console.log("Change");
    //     if ($scope.currentElement) {
    //         console.log($scope.currentElement);
    //     }
    // }
    //
    // $scope.setFocus = function(event) {
    //     //$scope.currentElement = event.target;
    //     console.log("Focus");
    //     $scope.currentElement = event.target;
    //     console.log(event.value);
    // }
    // $scope.cancelFocus = function(event) {
    //     console.log("Cancel  focus");
    //     //$scope.currentElement = false;
    //     console.log(event.target.value);
    // }


    // $scope.testHttp = function(){
    //     $http({
    //         method: 'POST',
    //         url: APP.ajaxurl,
    //         params: {
    //             action: "get_wc_order_data",
    //             car: $scope.car.selectedOptions.name,
    //             extras: $scope.car.selectedOptions.extras,
    //         },
    //         headers : { 'Content-Type': 'application/x-www-form-urlencoded' }
    //     }).success(function(data){
    //         console.log(data);
    //     });
    // }
});
//var orders_summary = [{order_id: '2', state: 'nsw', payment_method: 'paypal', total: '4.42'}];


jQuery(document).ready(function ($) {

    console.log('Payment Calculation JS');

    //console.log(orders_summary);

    $('.nav-link').click( function(e){
        e.preventDefault();
        $('.nav-link').removeClass('active');
        $(this).addClass('active');
        var id = $(this).attr('href');

        $('.tab-pane').removeClass('show');
        $('.tab-pane').removeClass('active');

        $(id).addClass('show');
        $(id).addClass('active');
        //$(this).tab('show');
        //console.log('click');

    });

    var start = moment().subtract(29, 'days');
    var end = moment();
    $('#reportrange').val(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
    $('#reportrange').attr('data-dateRangeStart', start.format('YYYY-MM-DD'));
    $('#reportrange').attr('data-dateRangeEnd', end.format('YYYY-MM-DD'));
    $('#wcGenerateBtn').attr('disabled', true);

    function cb(start, end, label) {
        $('#reportrange').val(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
        $('#reportrange').attr('data-dateRangeStart', start.format('YYYY-MM-DD'));
        $('#reportrange').attr('data-dateRangeEnd', end.format('YYYY-MM-DD'));
        var nonce = $('#wc-nonce').attr("data-nonce");
        console.log(nonce);
        jQuery.ajax({
            type: "post",
            dataType: "json",
            url: empdevajax.ajaxurl,
            data: {
                action: "get_wc_order_data",
                nonce: nonce,
                date_start: start.format('YYYY-MM-DD'),
                date_end: end.format('YYYY-MM-DD')
            },
            beforeSend: function(){
                $('#wcGenerateBtn').attr('disabled', true);
            },
            success: function (response) {
                orders_summary = response;
                console.log(response);
                $('#wcGenerateBtn').attr('disabled', false);
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
    }, cb);

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


var app = angular.module('paymentCalculationApp', ['angularUtils.directives.dirPagination']);
app.controller('paymentController', function ($scope, $http, filterFilter) {

    $scope.ordersSummary = [];
    $scope.showStates = [];
    $scope.nonce = angular.element(document).find('#wc-nonce').attr('data-nonce');

    $scope.filteredOrders = [];

    $scope.defaultStates = [
        {state: 'ACT'}, {state: 'NSW'},
        {state: 'NT'}, {state: 'QLD'},
        {state: 'SA'}, {state: 'TAS'},
        {state: 'VIC'}, {state: 'WA'}
    ];

    //state selection
    $scope.selectedStates = function (item) {
        var states = [];
        var result = [];
        if ( item.selected === true ) {
            //states.push(item.state);
        }

        angular.forEach($scope.defaultStates, function (value, key) {
           if( value.selected === true ){
               console.log( value.state );
               states.push(value.state);
           }
        });

        $scope.showStates = states;

        $scope.filteredOrders = filteredOrderKeys( $scope.ordersSummary, $scope.showStates );

        //$scope.showStates = result;

        // else {
        //     var index = $scope.showStates.indexOf(item);
        //
        //     $scope.showStates.splice(index, 1);
        //
        // }
    };
    //filter orders by state
    $scope.filterStates = function () {
        return function (obj) {
            if ($scope.showStates.length >= 1) {
                for (var j in $scope.showStates) {
                    if (obj.state == $scope.showStates[j]) {
                        return true;
                    }
                }
            } else return true;

        }
    };

    //load order via ajax connection
    $scope.wcLoadOrders = function () {
        $scope.ordersSummary = [];
        console.log(angular.element(document).find('#reportrange').attr('data-dateRangeStart'));
        console.log(angular.element(document).find('#reportrange').attr('data-dateRangeEnd'));
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

            var extract_csv = [];
            var j = 0;
            if( response.data.length > 0 ){
                // angular.forEach(response.data, function (value, key) {
                //     for( var index in value){
                //         if( value[index] === 'NSW' ){
                //             extract_csv.push(response.data[key]);
                //         }
                //     }
                //     j++;
                // });

                $scope.ordersSummary = response.data;

                //$scope.ordersSummary = response.data[0].sub_total;
            }

        }, function errorCallback(response) {
            console.log("Angular HTTP fail");
            console.log(response);
        });

    };

    //remove unselected object for csv import
    var filteredOrderKeys = function (ArrOfObj, keys){
        var result = [];
        console.log(ArrOfObj);
        console.log(keys);
        angular.forEach(ArrOfObj, function (value, index) {

            // delete value.item[0];
            // this.push(value);
            for( var i = 0; i < keys.length; i++ ){
                if( value.state == keys[i] ){
                    result.push(value);
                }
                // if( value.hasOwnProperty(keys[i])){
                //     //delete value.keys[i];
                //     this.push(value);
                // }

            }
        },result);
        return result;
    };

    //export json to csv
    function convertToCSV(objArray) {
        var array = typeof objArray != 'object' ? JSON.parse(objArray) : objArray;
        var str = '';

        for (var i = 0; i < array.length; i++) {
            var line = '';
            angular.forEach(array[i], function(value, key) {
               // console.log( value );

                if( key != '$$hashKey' ){

                    if (line != '') line += ',';
                    line += value;
                }



            });
            // for (var index in array[i]) {
            //
            //     if (line != '') line += ','
            //     line += array[i][index];
            //
            // }

            str += line + '\r\n';

        }

        return str;
    }
    function exportCSVFile(headers, items, fileTitle) {
        if (headers) {
            items.unshift(headers);
        }

        // Convert Object to JSON
        var jsonObject = JSON.stringify(items);

        var csv = convertToCSV(jsonObject);

        var exportedFilename = fileTitle + '.csv' || 'export.csv';

        var blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
        if (navigator.msSaveBlob) { // IE 10+
            navigator.msSaveBlob(blob, exportedFilename);
        } else {
            var link = document.createElement("a");
            if (link.download !== undefined) { // feature detection
                // Browsers that support HTML5 download attribute
                var url = URL.createObjectURL(blob);
                link.setAttribute("href", url);
                link.setAttribute("download", exportedFilename);
                link.style.visibility = 'hidden';
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
            }
        }
        items.shift();
    }

    var headers = {
        order_id: 'Order No', // remove commas to avoid errors
        payment_method: "Payment Method",
        state: "State",
        total: "Sales",
        charges: "Charges",
        revenue: "Distributor Revenue"
    };

    //test data
    var headers2 = {
        model: 'Phone Model', // remove commas to avoid errors
        chargers: "Chargers",
        cases: "Cases",
        earphones: "Earphones"
    };
    var itemsNotFormatted = [
        {
            model: 'Samsung S7',
            chargers: '55',
            cases: '56',
            earphones: '57',
            scratched: '2'
        },
        {
            model: 'Pixel XL',
            chargers: '77',
            cases: '78',
            earphones: '79',
            scratched: '4'
        },
        {
            model: 'iPhone 7',
            chargers: '88',
            cases: '89',
            earphones: '90',
            scratched: '6'
        }
    ];

    var itemsFormatted = [];
    var i;
    // for( i = 0; i <= itemsNotFormatted.length; i++ ){
    //     itemsFormatted.push({
    //         model: itemsNotFormatted[i].model,
    //         charges: itemsNotFormatted[i].charges,
    //         cases: itemsNotFormatted[i].cases,
    //         earphones: itemsNotFormatted[i].earphones,
    //     });
    // }
    var values = {name: 'misko', gender: 'male'};
    var log = [];

    angular.forEach(itemsNotFormatted, function(value, key) {
        delete value.scratched;

        this.push( value );

    }, itemsFormatted);

    console.log(itemsFormatted);

    var fileTitle = 'Distributor Revenue'; // or 'my-unique-title'
    $scope.exportJsonToCsv = function(){
        var raw_data;
        console.log( $scope.filteredOrders );
        if( $scope.showStates.length > 0 ){
            raw_data = $scope.filteredOrders;
        } else {
            raw_data = $scope.ordersSummary;
        }

        exportCSVFile(headers, raw_data, fileTitle);

    };

    //orderBy
    $scope.sort = function(keyname){
        $scope.sortKey = keyname;   //set the sortKey to the param passed
        $scope.reverse = !$scope.reverse; //if true make it false and vice versa
    }
});

var uniqueItems = function (data, key) {
    var result = new Array();
    for (var i = 0; i < data.length; i++) {
        var value = data[i][key];

        if (result.indexOf(value) == -1) {
            result.push(value);
        }

    }
    return result;
};

app.filter('groupBy', function () {
    return function (collection, key) {
        if (collection === null) return;
        return uniqueItems(collection, key);
    };
});

app.filter('capitalize', function() {
    return function(input) {
        return (angular.isString(input) && input.length > 0) ? input.charAt(0).toUpperCase() + input.substr(1).toLowerCase() : input;
    }
});
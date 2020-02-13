
var app = angular.module('paymentCalculationApp', ['angularUtils.directives.dirPagination' ]);
app.controller('paymentController', function ($scope, $http) {

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
        $scope.getTotalByStates = getTotalByStates($scope.ordersSummary, states);

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
    }

    var getTotalByStates = function(ArrOfObj, states){

        console.log(states);
        console.log(ArrOfObj);
        var result = 0;
        angular.forEach(ArrOfObj, function (value, index) {
            // delete value.item[0];
            // this.push(value);
            for( var i = 0; i < states.length; i++ ){
                if( value.state == states[i] ){
                    result += value.revenue;

                }
            }
        });

        return result;
    };

    $scope.getTotal = function(){
        var total = 0;

        for(var i = 0; i < $scope.ordersSummary.length; i++){
            var revenue = $scope.ordersSummary[i].revenue;
            total += revenue;
        }

        return total;
    };

    //load order via ajax connection
    $scope.wcLoadOrders = function () {
        //reset generated orders and selected states/
        $scope.ordersSummary = [];
        $scope.showStates = [];
        $scope.defaultStates = [
            {state: 'ACT'}, {state: 'NSW'},
            {state: 'NT'}, {state: 'QLD'},
            {state: 'SA'}, {state: 'TAS'},
            {state: 'VIC'}, {state: 'WA'}
        ];

        $scope.dateRangeStart = angular.element(document).find('#reportrange').attr('data-dateRangeStart');
        $scope.dateRangeEnd = angular.element(document).find('#reportrange').attr('data-dateRangeEnd');
        angular.element(document).find('#loader-msg').css('display', 'block');
        angular.element(document).find('#default-msg').css('display', 'none');

        console.log($scope.dateRangeStart);
        console.log($scope.dateRangeEnd);

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
            angular.element(document).find('#loader-msg').css('display', 'none');
            var extract_csv = [];
            var j = 0;
            if( response.data.length > 0 ){

                $scope.ordersSummary = response.data;

                //$scope.ordersSummary = response.data[0].sub_total;
            } else {
                angular.element(document).find('#default-msg').css('display', 'block').text('No data available!');
            }

        }, function errorCallback(response) {
            console.log("Angular HTTP fail");
            console.log(response);
            angular.element(document).find('#default-msg').css('display', 'block').text('Error: Ajax call failed to response!');
        });

    };

    //remove unselected object for csv import
    var filteredOrderKeys = function (ArrOfObj, keys){
        var result = [];
        // console.log(ArrOfObj);
        // console.log(keys);
        angular.forEach(ArrOfObj, function (value, index) {
            // delete value.item[0];
            // this.push(value);
            for( var i = 0; i < keys.length; i++ ){
                if( value.state == keys[i] ){
                    result.push(value);
                }
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
        date_created: 'Date Ordered',
        payment_method: "Payment Method",
        state: "State",
        total: "Sales",
        charges: "Charges",
        refunded: "Refunded",
        revenue: "Distributor Revenue"
    };

     // or 'my-unique-title'
    $scope.exportJsonToCsv = function(dateRangeStart, dateRangeEnd){
        var raw_data;
        console.log( $scope.filteredOrders );
        var fileTitle = 'Distributor Revenue ';

        fileTitle = fileTitle + dateRangeStart + '-' + dateRangeEnd;
        if( $scope.showStates.length > 0 ){
            raw_data = $scope.filteredOrders;
        } else {
            raw_data = $scope.ordersSummary;
        }

        exportCSVFile(headers, raw_data, fileTitle);

    };

    //orderBy
    $scope.sort = function(keyname){
        //$scope.sortKey = keyname;   //set the sortKey to the param passed
        //$scope.reverse = !$scope.reverse; //if true make it false and vice versa
        $scope.reverse = ($scope.sortKey === keyname) ? !$scope.reverse : false;
        $scope.sortKey = keyname;
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
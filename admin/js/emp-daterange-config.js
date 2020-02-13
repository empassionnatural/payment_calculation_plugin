
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
    $('#wcGenerateBtn').attr('disabled', false);

    function cb(start, end, label) {
        $('#reportrange').val(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
        $('#reportrange').attr('data-dateRangeStart', start.format('YYYY-MM-DD'));
        $('#reportrange').attr('data-dateRangeEnd', end.format('YYYY-MM-DD'));
        var nonce = $('#wc-nonce').attr("data-nonce");
        console.log(nonce);
        jQuery.ajax({
            type: "post",
            dataType: "json",
           // url: empdevajax.ajaxurl,
            beforeSend: function(){
                $('#wcGenerateBtn').attr('disabled', true);
            },
            success: function (response) {
                $('#wcGenerateBtn').attr('disabled', false);
            }
        });

        // jQuery.ajax({
        //     type: "post",
        //     dataType: "json",
        //     url: empdevajax.ajaxurl,
        //     data: {
        //         action: "get_wc_order_data",
        //         nonce: nonce,
        //         date_start: start.format('YYYY-MM-DD'),
        //         date_end: end.format('YYYY-MM-DD')
        //     },
        //     beforeSend: function(){
        //         $('#wcGenerateBtn').attr('disabled', true);
        //     },
        //     success: function (response) {
        //         orders_summary = response;
        //         console.log(response);
        //         $('#wcGenerateBtn').attr('disabled', false);
        //     }
        // });

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
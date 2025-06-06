$(document).ready(function() {

    // Function to format date to YYYY-MM-DD
    function formatDate(date) {
        let d = new Date(date),
            month = '' + (d.getMonth() + 1),
            day = '' + d.getDate(),
            year = d.getFullYear();

        if (month.length < 2) month = '0' + month;
        if (day.length < 2) day = '0' + day;

        return [month, day, year].join('/');

        //`${month}/${day}/${year}`;
    }

    // Add number of days to the current or selected date
    $('#duration').on('input', function() {
        let duration = parseInt($(this).val(), 10);
        let currentDate = new Date($('#current_date').val() || new Date());

        if (!isNaN(duration)) {
            currentDate.setDate(currentDate.getDate() + duration);
            $('#due_date').val(formatDate(currentDate));
        }
    });

    // Calculate the duration when a future date is selected
    $('#due_date').on('change', function() {
        let selectedDate = new Date($(this).val());
        let currentDate = new Date($('#current_date').val() || new Date());

        let timeDiff = selectedDate.getTime() - currentDate.getTime();
        let dayDiff = Math.ceil(timeDiff / (1000 * 3600 * 24));

        if (dayDiff >= 0) {
            $('#duration').val(dayDiff);
        }
    });


    $("#care_home").on("change", function() {
        var val = $(this).val();
        var url = $(this).attr('data-form-url');
        $.ajax({
            url: url,
            type: "post",
            data: {
                home_id: val
            },
            success: function(response) {
                console.log("response", response)
                if (response.status == 'success') {

                    $("#staff_id").html(response.staff_html);
                } else {

                    $("#staff_id").html(response.staff_html);
                }
            },
            error: function(xhr, status, error) {
                console.error('Error fetching data:', error);
            }
        })
    })
    $('.input-group.date.due_date').datepicker({
        keyboardNavigation: false,
        forceParse: false,
        calendarWeeks: true,
        autoclose: true,
        todayHighlight: true,
        startDate: new Date(),
    });
});
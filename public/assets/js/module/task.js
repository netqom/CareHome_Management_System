$(document).ready(function () {

    $("#task_Form").validate({
        rules: {
            care_home: {
                required: true,
            },
            user_id: {
                required: true,
            },
            title: {
                required: true,
            },
            start_date: {
                required: true,
            },
            start_time: {
                required: true,
            },
            end_time: {
                required: true,
            }
        },
        submitHandler: function (form) {
            form.submit();
        }
    });

    // Click event on the submit button
    $("#taskForm").click(function () {
        // Validate the form before submitting
        if ($("#task_Form").valid()) {
            // Form is valid, submit it
            $("#task_Form").submit();
        }
    });

    var task_type = $('#task_type').val();
    if (task_type == 0) {
        $('#end-date-div').addClass('d-none');
        $('#end-time-div').addClass('d-none');
        $('#continuous_type_div').addClass('d-none');
        $('#weekly_type_div').addClass('d-none');
        $('#monthly_type_div').addClass('d-none');
        $('#end_date').prop('required', false);
        $('#end_time').prop('required', false);
        $('input[name=continuous_type]').prop('required', false);

    }


    // Function to calculate the end date based on the selected start date
    function calculateEndDate(startDate, radioValue) {
        var endDate = new Date(startDate);
        if (radioValue === '0') {
            endDate.setDate(endDate.getDate() + 7); // Add 7 days to the start date
        } else if ((radioValue === '1')) {
            endDate.setMonth(endDate.getMonth() + 1);
        }
        return endDate;
    }

    // Initialize start datepicker
    $('.input-group.date.start_date').datepicker({
        keyboardNavigation: false,
        forceParse: false,
        calendarWeeks: true,
        autoclose: true,
        startDate: new Date(),
        todayHighlight: true
    }).on('changeDate', function (selected) {

        $('#start_date-error').css('display', 'none');
        $('#start_date').removeClass('is-invalid').addClass('is-valid');

        // Set the minimum date for the end date picker
        $('.input-group.date.end_date').datepicker('setStartDate', selected.date);

        // Get the selected start date
        var startDate = selected.date;

        // Check the value of the radio button
        var radioValue = $('input[type=radio][name=continuous_type]:checked').val();

        // If radio value is '0', adjust the end date to have a 7-day gap
        if (radioValue === '0') {
            var endDate = calculateEndDate(startDate, radioValue);
            $('.input-group.date.end_date').datepicker('setDate', endDate);
            $('.input-group.date.end_date').datepicker('setStartDate', endDate);
        } else if (radioValue === '1') {
            // If radio value is '1', adjust the end date to have a 1-month gap
            var endDate = calculateEndDate(startDate, radioValue);
            $('.input-group.date.end_date').datepicker('setDate', endDate);
            $('.input-group.date.end_date').datepicker('setStartDate', endDate);
        }

        // Get the first day of the selected month
        var firstDayOfMonth = new Date(startDate.getFullYear(), startDate.getMonth(), 1);

        // Get the last day of the selected month
        var lastDayOfMonth = new Date(startDate.getFullYear(), startDate.getMonth() + 1, 0);

        // Set the start and end date of the month_date picker to the selected month
        $('.input-group.date.month_date').datepicker('setStartDate', firstDayOfMonth);
        $('.input-group.date.month_date').datepicker('setEndDate', lastDayOfMonth);
    });

    // Initialize end datepicker
    $('.input-group.date.end_date').datepicker({
        keyboardNavigation: false,
        forceParse: false,
        calendarWeeks: true,
        autoclose: true,
        todayHighlight: true,

    }).on('changeDate', function (selected) {

        $('#end_date-error').css('display', 'none');
        $('#end_date').removeClass('is-invalid').addClass('is-valid');
    });

    // $('.input-group.date.due_date').datepicker({
    //     keyboardNavigation: false,
    //     forceParse: false,
    //     calendarWeeks: true,
    //     autoclose: true,
    //     todayHighlight: true,
    //     startDate: new Date(),
    // });

    // Initialize month_date picker
    $('#specific-datepicker').datepicker({
        format: 'dd',
        forceParse: false,
        multidate: true,
        clearBtn: true,
        beforeShowDay: function (date) {
            var day = date.getDate();
            if (day >= 1 && day <= 31) {
                return {
                    classes: 'day'
                };
            } else {
                return false;
            }
        }
    }).on('show', function (e) {
        $(this).find('.datepicker-days .prev, .datepicker-days .next').css('display', 'none');
        // Ensure the button is only added once
        if (!$('.datepicker-footer').length) {
            var $footer = $('<div class="datepicker-footer text-center mt-2"></div>');
            var $button = $('<button class="btn btn-primary btn-sm date-ok-btn">OK</button>');
            $footer.append($button);

            // Append the footer to the datepicker
            $('.datepicker-days').append($footer);

            // Add click event to the button
            $button.on('click', function () {
                $('.input-group.date.month_date').datepicker('hide');
                // You can add any other action you need here
            });
        }
    }).on('changeDate', function (e) {
        var selectedDates = e.dates;
        console.log("Selected Dates: ", selectedDates);
    });
    $('#specific-datepicker').on('show', function (e) {
        $('.datepicker-days .prev, .datepicker-days .next').css('display', 'none');
    });


    // Initialize clockpicker for start time
    $('.clockpicker.start-time').clockpicker({
        autoclose: true,
        afterDone: function (selectedTime) {
            // Get selected time in hours and minutes
            var selectedHours = parseInt(selectedTime.split(':')[0]);
            var selectedMinutes = parseInt(selectedTime.split(':')[1]);

            // Disable times earlier than the selected time in the end time picker
            $('.clockpicker.end-time').clockpicker({
                autoclose: true,
                afterHourSelect: function (hour) {
                    var $minuteTicks = $(
                        '.clockpicker.end-time .clockpicker-minutes .clockpicker-tick'
                    );
                    if (hour === selectedHours) {
                        $minuteTicks.each(function () {
                            var minute = parseInt($(this).text());
                            if (minute < selectedMinutes) {
                                $(this).addClass('disabled');
                            } else {
                                $(this).removeClass('disabled');
                            }
                        });
                    } else if (hour < selectedHours) {
                        $minuteTicks.addClass('disabled');
                    } else {
                        $minuteTicks.removeClass('disabled');
                    }
                }
            });
        }
    });

    // Initialize clockpicker for end time
    $('.clockpicker.end-time').clockpicker({
        autoclose: true
    });
    $('#user_type').change(function () {
        if ($(this).val() == 'staff') {
            $('#staff_list_div').removeClass('d-none');
            $('#user_id').prop('required', true);
            $('#manager_id').prop('required', false);
            $('#patient_id').prop('required', false);
            $('#client_list_div').addClass('d-none');
            $('#manager_list_div').addClass('d-none');
        } else if ($(this).val() == 'manager') {
            $('#manager_list_div').removeClass('d-none');
            $('#manager_id').prop('required', true);
            $('#user_id').prop('required', false);
            $('#patient_id').prop('required', false);
            $('#staff_list_div').addClass('d-none');
            $('#client_list_div').addClass('d-none');
        } else {
            $('#client_list_div').removeClass('d-none');
            $('#patient_id').prop('required', true);
            $('#user_id').prop('required', false);
            $('#manager_id').prop('required', false);
            $('#staff_list_div').addClass('d-none');
            $('#manager_list_div').addClass('d-none');
        }
    })

    $('#task_type').change(function () {
        // console.log($(this).val());
        if ($(this).val() == '1') {
            $('#end-date-div').removeClass('d-none');
            $('#end-time-div').removeClass('d-none');
            //$('#due-date-div').addClass('d-none');
            //$('#due_date').prop('required', false);
            $('#continuous_type_div').removeClass('d-none');
            $('#end_date').prop('required', true);
            $('#end_time').prop('required', true);

            $('input[name=continuous_type]').prop('required', true);
        } else {
            $('#end-date-div').addClass('d-none');
            $('#end-time-div').addClass('d-none');
            $('#continuous_type_div').addClass('d-none');
            $("#weekly_type_div").addClass('d-none');
            $('#end_date').prop('required', false);
            $('#end_time').prop('required', false);
            $('input[name=continuous_type]').prop('required', false);
            $('#end_time').removeClass('is-valid');
            //$('#due-date-div').removeClass('d-none');
            //$('#due_date').prop('required', true);
        }
    })
    $('input[name=continuous_type]').change(function () {
        if ($(this).val() == '0') {
            $("#weekly_type_div").removeClass('d-none');
            $("#selected_week_days").prop('required', true);
            $("#monthly_type_div").addClass('d-none');
            $("#selected_month_dates").prop('required', false);
        } else if ($(this).val() == '1') {
            $("#weekly_type_div").addClass('d-none');
            $("#selected_week_days").prop('required', false);
            $("#monthly_type_div").removeClass('d-none');
            $("#selected_month_dates").prop('required', true);
        } else {
            $("#weekly_type_div").addClass('d-none');
            $("#selected_week_days").prop('required', false);
            $("#monthly_type_div").addClass('d-none');
            $("#selected_month_dates").prop('required', false);
        }
    })

    function formatState(state) {
        if (!state.id) {
            return state.text;
        }
        var isSelected = $(state.element).prop('selected');
        var $state = $(
            '<span><input type="checkbox" ' + (isSelected ? 'checked' : '') + ' /> ' + state.text +
            '</span>'
        );
        return $state;
    }

    function initializeSelect2(selector, placeholderText) {
        $(selector).select2({
            placeholder: placeholderText,
            closeOnSelect: false,
            templateResult: formatState,
            templateSelection: function (state) {
                return state.text;
            }
        });
    }

    initializeSelect2('.select-week-days', 'Select week days');

    // Add event listener to start time input
    $('#start_time').on('change', function () {
        // Check if end time is less than start time
        validateEndTime();

        $('#start_time-error').css('display', 'none');
        $('#start_time').removeClass('is-invalid').addClass('is-valid');
    });

    // Add event listener to end time input
    $('#end_time').on('change', function () {
        // Check if end time is less than start time
        validateEndTime();
        $('#end_time-error').css('display', 'none');
        $('#end_time').removeClass('is-invalid').addClass('is-valid');
    });

    function validateEndTime() {
        var startTime = $('#start_time').val();
        var endTime = $('#end_time').val();

        if (startTime && endTime && startTime >= endTime) {
            // For example, show an alert
            // alert('End time must be greater than start time');
            $('#end_time_err').removeClass('d-none');
            $('#end_time_err').text('End time must be greater than start time');
            $('#taskForm').attr('disabled', 'disabled')
        } else {
            // Hide error message if the condition is not met
            $('#end_time_err').addClass('d-none');
            $('#end_time_err').text('');
            $('#taskForm').removeAttr('disabled')
        }
    }
    $("#care_home").on("change", function () {
        var val = $(this).val();
        var url = $(this).attr('data-url-link');
        $.ajax({
            url: url,
            type: "post",
            data: {
                home_id: val
            },
            success: function (response) {

                if (response.status == 'success') {
                    $("#manager_id").html(response.manager_html);
                    $("#user_id").html(response.staff_html);
                } else {
                    $("#manager_id").html(response.manager_html);
                    $("#user_id").html(response.staff_html);
                }
            },
            error: function (xhr, status, error) {
                console.error('Error fetching data:', error);
            }
        })
    })
});

function handleProductionWarning() {
    'use strict';

    var selectedEnvironment = $('#environment').val(),
        selectedMethod = $('#libraryMethod').val();

    if (selectedEnvironment === 'production' && selectedMethod.indexOf("create") >= 0) {
        $('#productionWarning').show();
        $('.btn-primary').addClass('btn-danger').removeClass('btn-primary');
    } else {
        $('#productionWarning').hide();
        $('.btn-danger').addClass('btn-primary').removeClass('btn-danger');
    }

}


function changeEnvironment() {
    'use strict';

    var selectedEnvironment = $('#environment').val();
    //alert(selectedEnvironment);

    switch (selectedEnvironment) {
        case 'sandbox':
            $('.sandbox').show();
            $('.staging').hide();
            $('.production').hide();

            $('.btn-primary').text('Submit to Sandbox');
            break;

        case 'staging':
            $('.sandbox').hide();
            $('.staging').show();
            $('.production').hide();

            $('.btn-primary').text('Submit to Staging');
            break;

        case 'production':
            $('.sandbox').hide();
            $('.staging').hide();
            $('.production').show();

            $('.btn-primary').text('Submit to Production');
            break;
    }

    handleProductionWarning();
}


function toggleOptions() {
    'use strict';

    var selectedMethod = $('#libraryMethod').val(),
        $methodInput = $('#methodInput');

    if (selectedMethod.indexOf('list') >= 0 || selectedMethod.indexOf('search') >= 0) {
        $('#listParameters').show();
    } else {
        $('#listParameters').hide();
    }

    $methodInput.show();

    $methodInput.children('legend').html(selectedMethod + '() Specific Parameters');
    $methodInput.find('.control-group').hide();

    if (selectedMethod === 'createOrdersClient') {
        $('.clientOrderParameters').show();
        //alert('show clientOrderParameters');
    } else {
        $('.clientOrderParameters').hide();
        //alert('hide clientOrderParameters');
    }

    $('.' + selectedMethod).show();

    if (selectedMethod === 'createShipment') {
        $('#APItest').attr('method', 'POST');
    } else {
        $('#APItest').attr('method', 'GET');
    }

    // If the tags wrapper is visible, make the child divs visibile too
    if ($('#tagsWrapper').is(':visible')) {
        $('div', '#tagsWrapper').show();
    }

    $('.btn-primary').removeClass('disabled');

    handleProductionWarning();

}


function handleCreateShipMentTypeChange() {
    'use strict';

    var selectedType = $('#type').val();

    $('.createShipments').hide();

    $('.createShipments-' + selectedType).show();

    return true;
}


function checkForm() {
    'use strict';

    // Set any hidden inputs to inactive to keep them from being submitted.
    // Keeps the URL cleaner
    $('input:hidden', 'fieldset').attr('disabled', true);
    $('select:hidden', 'fieldset').attr('disabled', true);
    $('textarea:hidden', 'fieldset').attr('disabled', true);

    // If the tags wrapper is visible, make sure the input is NOT disabled
    if ($('#tagsWrapper').is(':visible')) {
        $('input[name="hidden-tags"]').removeAttr('disabled');
        $('#tagsWrapper').show();
    }

    //$('#environmentAndCredentials input:hidden').attr('disabled', true);
    return true;
}


$(document).ready(function() {
    'use strict';

    changeEnvironment();

    $('#shipping_address_id').on('keyup', function() {
        if ($(this).val()) {
            $('#client_order_shipping_address').show();
        } else {
            $('#client_order_shipping_address').hide();
        }
    });

    $('#billing_address_id').on('keyup', function() {
        if ($(this).val()) {
            $('#client_order_billing_address').show();
        } else {
            $('#client_order_billing_address').hide();
        }
    });

    $('.tagManager').tagsManager({
        prefilled: ['VIP', 'Whale'],
        typeahead: true,
        typeaheadAjaxSource: null,
        preventSubmitOnEnter: true
    });

});



var calculatorInput = {
    init: function () {
        $('#calc-input-income').blur(function() {
            console.log('changed');
            calculatorInput.forceNumericFormat('#calc-input-income');
        });
        $('#calc-input-home-value-before').blur(function() {
            calculatorInput.forceNumericFormat('#calc-input-home-value-before');
            var afterField = $('#calc-input-home-value-after');
            if (afterField.val() === '') {
                afterField.val($('#calc-input-home-value-before').val());
            }
        });
        $('#calc-input-home-value-after').blur(function() {
            calculatorInput.forceNumericFormat('#calc-input-home-value-after');
        });
    },

    forceNumericFormat: function (selector) {
        var field = $(selector);
        var value = field.val();

        // Remove everything after decimal point and strip non-numeric
        value = value.split('.')[0];
        value = value.replace(/[^0-9]/g, '');

        // Replace value in field
        field.val(parseInt(value));
    }
};

var calculatorOutput = {
    init: function () {
        $('#toggle-sales-taxes').click(function (event) {
            event.preventDefault();
            $('#sales-tax-breakdown-wrapper').slideToggle();
        });
        $('#toggle-formulas').click(function (event) {
            event.preventDefault();
            $('#formulas-used').slideToggle();
        });
        $('#toggle-sources').click(function (event) {
            event.preventDefault();
            $('#sources').slideToggle();
        });
        $('#toggle-footnotes').click(function (event) {
            event.preventDefault();
            $('#footnotes').slideToggle();
        });
        $('#toggle-resources').click(function (event) {
            event.preventDefault();
            $('#resources').slideToggle();
        });
    }
};

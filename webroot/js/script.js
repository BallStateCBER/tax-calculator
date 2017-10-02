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

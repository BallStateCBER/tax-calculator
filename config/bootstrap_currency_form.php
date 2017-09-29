<?php
$bootstrapFormTemplate = require('bootstrap_form.php');

return array_merge($bootstrapFormTemplate, [
    // Removed label because there doesn't appear to be a way to make it automatically appear outside of div.input_group
    'formGroup' => '{{input}}',

    'inputContainer' =>
        '<div class="form-group {{type}}{{required}}">' .
            '<div class="input-group">' .
                '<span class="input-group-addon">$</span>{{content}}<span class="input-group-addon">.00</span>' .
            '</div>' .
        '</div>',
    'inputContainerError' =>
        '<div class="form-group has-error has-feedback {{type}}{{required}}">' .
            '<div class="input-group">' .
                '<span class="input-group-addon">$</span>{{content}}<span class="input-group-addon">.00</span>' .
            '</div>' .
            '{{error}}' .
        '</div>',
    'input' => '<input class="form-control" type="number" min="1" required="required" name="{{name}}"{{attrs}} />'
]);

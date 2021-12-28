<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted' => 'O :attribute precisa ser accepted.',
    'accepted_if' => 'O :attribute precisa ser accepted when :other is :value.',
    'active_url' => 'O :attribute is not a valid URL.',
    'after' => 'O :attribute precisa ser a date after :date.',
    'after_or_equal' => 'O :attribute precisa ser a date after or equal to :date.',
    'alpha' => 'O :attribute must only contain letters.',
    'alpha_dash' => 'O :attribute must only contain letters, numbers, dashes and underscores.',
    'alpha_num' => 'O :attribute must only contain letters and numbers.',
    'array' => 'O :attribute precisa ser an array.',
    'before' => 'O :attribute precisa ser a date before :date.',
    'before_or_equal' => 'O :attribute precisa ser a date before or equal to :date.',
    'between' => [
        'numeric' => 'O :attribute precisa ser between :min and :max.',
        'file' => 'O :attribute precisa ser between :min and :max kilobytes.',
        'string' => 'O :attribute precisa ser between :min and :max characters.',
        'array' => 'O :attribute must have between :min and :max items.',
    ],
    'boolean' => 'O campo :attribute precisa ser true or false.',
    'confirmed' => 'O :attribute confirmation does not match.',
    'current_password' => 'The password is incorrect.',
    'date' => 'O :attribute is not a valid date.',
    'date_equals' => 'O :attribute precisa ser a date equal to :date.',
    'date_format' => 'O :attribute does not match the format :format.',
    'declined' => 'O :attribute precisa ser declined.',
    'declined_if' => 'O :attribute precisa ser declined when :other is :value.',
    'different' => 'O :attribute and :other precisa ser different.',
    'digits' => 'O :attribute precisa ser :digits digits.',
    'digits_between' => 'O :attribute precisa ser between :min and :max digits.',
    'dimensions' => 'O :attribute has invalid image dimensions.',
    'distinct' => 'O campo :attribute has a duplicate value.',
    'email' => 'O :attribute precisa ser a valid email address.',
    'ends_with' => 'O :attribute must end with one of the following: :values.',
    'exists' => 'The selected :attribute is invalid.',
    'file' => 'O :attribute precisa ser a file.',
    'filled' => 'O campo :attribute must have a value.',
    'gt' => [
        'numeric' => 'O :attribute precisa ser greater than :value.',
        'file' => 'O :attribute precisa ser greater than :value kilobytes.',
        'string' => 'O :attribute precisa ser greater than :value characters.',
        'array' => 'O :attribute must have more than :value items.',
    ],
    'gte' => [
        'numeric' => 'O :attribute precisa ser greater than or equal to :value.',
        'file' => 'O :attribute precisa ser greater than or equal to :value kilobytes.',
        'string' => 'O :attribute precisa ser greater than or equal to :value characters.',
        'array' => 'O :attribute must have :value items or more.',
    ],
    'image' => 'O :attribute deve ser uma imagem.',
    'in' => 'The selected :attribute is invalid.',
    'in_array' => 'O campo :attribute does not exist in :other.',
    'integer' => 'O :attribute precisa ser an integer.',
    'ip' => 'O :attribute precisa ser a valid IP address.',
    'ipv4' => 'O :attribute precisa ser a valid IPv4 address.',
    'ipv6' => 'O :attribute precisa ser a valid IPv6 address.',
    'json' => 'O :attribute precisa ser a valid JSON string.',
    'lt' => [
        'numeric' => 'O :attribute precisa ser less than :value.',
        'file' => 'O :attribute precisa ser less than :value kilobytes.',
        'string' => 'O :attribute precisa ser less than :value characters.',
        'array' => 'O :attribute must have less than :value items.',
    ],
    'lte' => [
        'numeric' => 'O :attribute precisa ser less than or equal to :value.',
        'file' => 'O :attribute precisa ser less than or equal to :value kilobytes.',
        'string' => 'O :attribute precisa ser less than or equal to :value characters.',
        'array' => 'O :attribute must not have more than :value items.',
    ],
    'max' => [
        'numeric' => 'O :attribute must not be greater than :max.',
        'file' => 'O :attribute must not be greater than :max kilobytes.',
        'string' => 'O :attribute must not be greater than :max characters.',
        'array' => 'O :attribute must not have more than :max items.',
    ],
    'mimes' => 'O :attribute precisa ser a file of type: :values.',
    'mimetypes' => 'O :attribute precisa ser a file of type: :values.',
    'min' => [
        'numeric' => 'O :attribute precisa ser at least :min.',
        'file' => 'O :attribute precisa ser at least :min kilobytes.',
        'string' => 'O :attribute precisa ser at least :min characters.',
        'array' => 'O :attribute must have at least :min items.',
    ],
    'multiple_of' => 'O :attribute precisa ser a multiple of :value.',
    'not_in' => 'The selected :attribute is invalid.',
    'not_regex' => 'O :attribute format is invalid.',
    'numeric' => 'O :attribute precisa ser a number.',
    'password' => 'The password is incorrect.',
    'present' => 'O campo :attribute precisa ser present.',
    'prohibited' => 'O campo :attribute is prohibited.',
    'prohibited_if' => 'O campo :attribute is prohibited when :other is :value.',
    'prohibited_unless' => 'O campo :attribute is prohibited unless :other is in :values.',
    'prohibits' => 'O campo :attribute prohibits :other from being present.',
    'regex' => 'O :attribute format is invalid.',
    'required' => 'O campo :attribute é obrigatório.',
    'required_if' => 'O campo :attribute is required when :other is :value.',
    'required_unless' => 'O campo :attribute is required unless :other is in :values.',
    'required_with' => 'O campo :attribute is required when :values is present.',
    'required_with_all' => 'O campo :attribute is required when :values are present.',
    'required_without' => 'O campo :attribute is required when :values is not present.',
    'required_without_all' => 'O campo :attribute is required when none of :values are present.',
    'same' => 'O :attribute and :other must match.',
    'size' => [
        'numeric' => 'O :attribute precisa ser :size.',
        'file' => 'O :attribute precisa ser :size kilobytes.',
        'string' => 'O :attribute precisa ser :size characters.',
        'array' => 'O :attribute must contain :size items.',
    ],
    'starts_with' => 'O :attribute must start with one of the following: :values.',
    'string' => 'O :attribute precisa ser a string.',
    'timezone' => 'O :attribute precisa ser a valid timezone.',
    'unique' => 'O :attribute já existe.',
    'uploaded' => 'O :attribute failed to upload.',
    'url' => 'O :attribute precisa ser a valid URL.',
    'uuid' => 'O :attribute precisa ser a valid UUID.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [
        'name' => 'Nome',
        'email' => 'Email',
        'password' => 'Senha',
        'avatar' => 'Avatar',
    ],

];

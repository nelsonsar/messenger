<?php

namespace Messenger\Validation;

use Respect\Validation\Rules\AllOf;
use Respect\Validation\Validator as v;

class Login extends AllOf
{
    public function __construct()
    {
        parent::__construct(
            v::alpha('_.'),
            v::noWhitespace(),
            v::length(5, 15, true)
        );
    }
}

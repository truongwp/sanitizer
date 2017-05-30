<?php

namespace Truongwp\Sanitizer\Contracts;

interface RuleSanitizer
{
    public function sanitize($value);
}

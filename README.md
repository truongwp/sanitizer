## Sanitizer

**Sanitizer** is a simple and stand-alone PHP sanitizer library with no dependencies.

### Installation
Uses Composer to install and update:
```
composer require "truongwp/sanitizer=*"
```

**Sanitizer** require PHP >= 5.3

### Usage

```php
<?php
$sanitizer = new Truongwp\Sanitizer\Sanitizer();

$input = array(
    'name'     => '  Foo bar ',
    'age'      => ' 24 ',
);

$rules = array(
    'name'     => 'trim|strtolower|ucwords',
    'age'      => 'intval',
);

$output = $sanitizer->sanitize($input, $rules);
```

The `$output`:

```php
array(
    'name'     => 'Foo Bar',
    'age'      => 24,
);
```

Multiple rules can be passed as string delimiter by `|` or use an array:
```php
<?php
$rules = array(
    'name'     => array('trim', 'strtolower', 'ucwords'),
    'age'      => 'intval',
);
```

By default, rule name is PHP function. So you can easily add a custom function to sanitize.
```php
<?php
function trim_slasses($value) {
    return trim($value, '/');
}

$sanitizer = new Truongwp\Sanitizer\Sanitizer();

$input = array(
    'name' => '//foo',
);
$rules = array(
    'name' => 'trim_slasses',
);
$output = $sanitizer->sanitize($input, $rules);
```

The result:
```php
array(
    'name' => 'foo',
)
```

If you want to pass additional parameters to sanitizer function, you can append them to rule name and delimited by `:`.
```php
<?php
function prefix_suffix($value, $prefix = '', $suffix = '') {
    return $prefix . $value . $suffix;
}

$sanitizer = new Truongwp\Sanitizer\Sanitizer();

$input = array(
    'name' => 'foo',
);
$rules = array(
    'name' => 'prefix_suffix:prefix_:_suffix',
);
$output = $sanitizer->sanitize($input, $rules);
```

The result:
```php
array(
    'name' => 'prefix_foo_suffix',
)
```

You can also add custom sanitizer class by using SanitizerRegistry.
```php
<?php
class DateFormatSanitizer implements Truongwp\Sanitizer\Contracts\RuleSanitizer
{
    /**
     * Sanitize value.
     *
     * @param mixed $value Value need to sanitize.
     * @return mixed
     */
    public function sanitize($value)
    {
        $args = func_get_args();
        $format = empty($args[1]) ? 'Y-m-d' : $args[1];

        $timestamp = strtotime($value);
        return date($format, $timestamp);
    }
}

$sanitizer = new Truongwp\Sanitizer\Sanitizer();

$input = array(
    'day' => '05/30/2017',
);
$rules = array(
    'name' => 'date_format:Y-m-d',
);
$output = $sanitizer->sanitize($input, $rules);
```

The result:
```php
array(
    'day' => '2017-05-30',
)
```

### Contributing
Contributor: [@truongwp](https://truongwp.com)

Bug reports or Pull requests are welcome.

<?php

use PHPUnit\Framework\TestCase;

class SanitizerTest extends TestCase
{
    /**
     * @dataProvider dataProvider
     */
    public function testUsingPHPFunction($input, $rules, $assert)
    {
        $sanitizer = new \Truongwp\Sanitizer\Sanitizer();

        $this->assertEquals($assert, $sanitizer->sanitize($input, $rules));
    }

    public function testCustomFunction()
    {
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
        $assert = array(
            'name' => 'foo',
        );

        $this->assertEquals($assert, $sanitizer->sanitize($input, $rules));
    }

    public function testCustomFunctionWithParameters()
    {
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
        $assert = array(
            'name' => 'prefix_foo_suffix',
        );

        $this->assertEquals($assert, $sanitizer->sanitize($input, $rules));
    }

    public function dataProvider()
    {
        $data = array();

        $input = array(
            'name' => '  Foo bar ',
            'age'  => ' 24 ',
            'birthday' => '05/30/2017',
        );

        $rules = array(
            'name' => 'trim',
            'age'  => 'intval',
            'birthday' => 'date_format:Y-m-d',
        );

        $assert = array(
            'name' => 'Foo bar',
            'age'  => 24,
            'birthday' => '2017-05-30',
        );

        $data['single_sanitizer'] = array($input, $rules, $assert);

        $rules2 = array(
            'email' => 'trim',
            'name' => 'trim',
            'age'  => 'intval',
            'birthday' => 'date_format:Y-m-d',
        );

        $data['excess_sanitizer_field'] = array($input, $rules2, $assert);

        $rules3 = array(
            'email' => 'trim',
            'name' => 'trim',
            'birthday' => 'date_format:Y-m-d',
        );
        $assert3 = array(
            'name' => 'Foo bar',
            'age'  => ' 24 ',
            'birthday' => '2017-05-30',
        );
        $data['have_unsanitized_field'] = array($input, $rules3, $assert3);

        $rules4 = array(
            'name' => 'trim|strtolower|ucwords',
            'age'  => 'intval',
            'birthday' => 'strtotime',
        );

        $assert4 = array(
            'name' => 'Foo Bar',
            'age'  => 24,
            'birthday' => strtotime('05/30/2017'),
        );

        $data['multiple_rules'] = array($input, $rules4, $assert4);

        return $data;
    }
}

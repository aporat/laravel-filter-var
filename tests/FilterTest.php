<?php

namespace Aporat\FilterVar\Tests;

use Aporat\FilterVar\Filters\Capitalize;
use Aporat\FilterVar\Filters\Cast;
use Aporat\FilterVar\Filters\Digit;
use Aporat\FilterVar\Filters\EscapeHTML;
use Aporat\FilterVar\Filters\FilterIf;
use Aporat\FilterVar\Filters\FormatDate;
use Aporat\FilterVar\Filters\Lowercase;
use Aporat\FilterVar\Filters\NormalString;
use Aporat\FilterVar\Filters\RemoveWhitespace;
use Aporat\FilterVar\Filters\Slugify;
use Aporat\FilterVar\Filters\StripTags;
use Aporat\FilterVar\Filters\Trim;
use Aporat\FilterVar\Filters\Uppercase;
use Illuminate\Support\Collection;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class FilterTest extends TestCase
{
    // Capitalize
    public function test_capitalize_converts_to_title_case(): void
    {
        $filter = new Capitalize;
        $this->assertEquals('Lower', $filter->apply('LOWer'));
        $this->assertEquals('Hello World', $filter->apply('hello world'));
        $this->assertEquals('Héllo', $filter->apply('héllo')); // Multibyte
    }

    public function test_capitalize_preserves_non_string(): void
    {
        $filter = new Capitalize;
        $this->assertSame(123, $filter->apply(123));
        $this->assertSame(null, $filter->apply(null));
    }

    // Cast
    public function test_cast_converts_to_specified_type(): void
    {
        $filter = new Cast;
        $this->assertSame(123, $filter->apply('123', ['int']));
        $this->assertSame(123.45, $filter->apply('123.45', ['float']));
        $this->assertSame('123', $filter->apply(123, ['string']));
        $this->assertSame(true, $filter->apply('1', ['bool']));
        $this->assertEquals((object) ['a' => 1], $filter->apply(['a' => 1], ['object']));
        $this->assertEquals(['a' => 1], $filter->apply('{"a":1}', ['array']));
        $this->assertInstanceOf(Collection::class, $filter->apply('{"a":1}', ['collection']));
    }

    public function test_cast_throws_on_invalid_type(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $filter = new Cast;
        $filter->apply('123', ['unknown']);
    }

    // Digit
    public function test_digit_extracts_digits(): void
    {
        $filter = new Digit;
        $this->assertEquals('123', $filter->apply('abc123xyz'));
        $this->assertEquals('12345', $filter->apply(123.45));
        $this->assertEquals('', $filter->apply('abc'));
        $this->assertEquals('', $filter->apply(null));
    }

    // EscapeHTML
    public function test_escape_html_encodes_special_chars(): void
    {
        $filter = new EscapeHTML;
        $this->assertEquals('&lt;p&gt;Hello&lt;/p&gt;', $filter->apply('<p>Hello</p>'));
        $this->assertEquals('Hello &amp; World', $filter->apply('Hello & World'));
    }

    public function test_escape_html_preserves_non_string(): void
    {
        $filter = new EscapeHTML;
        $this->assertSame(123, $filter->apply(123));
    }

    // FilterIf
    public function test_filter_if_checks_array_condition(): void
    {
        $filter = new FilterIf;
        $this->assertTrue($filter->apply(['status' => 'active'], ['status', 'active']));
        $this->assertFalse($filter->apply(['status' => 'inactive'], ['status', 'active']));
        $this->assertFalse($filter->apply(['other' => 'active'], ['status', 'active']));
    }

    // FormatDate
    public function test_format_date_reformats_date_string(): void
    {
        $filter = new FormatDate;
        $this->assertEquals('15/01/2023', $filter->apply('2023-01-15', ['Y-m-d', 'd/m/Y']));
    }

    public function test_format_date_returns_empty_input_unchanged(): void
    {
        $filter = new FormatDate;
        $this->assertSame('', $filter->apply('', ['Y-m-d', 'd/m/Y']));
        $this->assertSame(null, $filter->apply(null, ['Y-m-d', 'd/m/Y']));
    }

    public function test_format_date_throws_on_invalid_options(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $filter = new FormatDate;
        $filter->apply('2023-01-15', ['Y-m-d']);
    }

    // Lowercase
    public function test_lowercase_converts_to_lowercase(): void
    {
        $filter = new Lowercase;
        $this->assertEquals('hello', $filter->apply('HELLO'));
        $this->assertEquals('héllo', $filter->apply('HÉLLO')); // Multibyte
    }

    public function test_lowercase_preserves_non_string(): void
    {
        $filter = new Lowercase;
        $this->assertSame(123, $filter->apply(123));
    }

    // StripTags
    public function test_strip_tags_removes_html(): void
    {
        $filter = new StripTags;
        $this->assertEquals('Hello', $filter->apply('<p>Hello</p>'));
        $this->assertEquals('Hello & World', $filter->apply('<b>Hello</b> & World'));
    }

    public function test_strip_tags_preserves_non_string(): void
    {
        $filter = new StripTags;
        $this->assertSame(123, $filter->apply(123));
    }

    // Trim
    public function test_trim_removes_whitespace(): void
    {
        $filter = new Trim;
        $this->assertEquals('hello', $filter->apply('  hello  '));
        $this->assertEquals('world', $filter->apply("\t world\n"));
    }

    public function test_trim_preserves_non_string(): void
    {
        $filter = new Trim;
        $this->assertSame(123, $filter->apply(123));
    }

    // Uppercase
    public function test_uppercase_converts_to_uppercase(): void
    {
        $filter = new Uppercase;
        $this->assertEquals('HELLO', $filter->apply('hello'));
        $this->assertEquals('HÉLLO', $filter->apply('héllo')); // Multibyte
    }

    public function test_uppercase_preserves_non_string(): void
    {
        $filter = new Uppercase;
        $this->assertSame(123, $filter->apply(123));
    }

    public function test_normal_string_removes_special_characters(): void
    {
        $filter = new NormalString;
        $this->assertEquals('Hello123', $filter->apply('<b>Hello</b>!@#123'));
        $this->assertEquals('Test alert1 2024-04-12 14:30:00', $filter->apply('Test <script>alert(1)</script> 2024-04-12 14:30:00'));
    }

    public function test_normal_string_preserves_valid_characters(): void
    {
        $filter = new NormalString;
        $this->assertEquals('ABC def 123 -:_.', $filter->apply('ABC def 123 -:_.'));
    }

    public function test_normal_string_handles_non_string_input(): void
    {
        $filter = new NormalString;
        $this->assertEquals('12345', $filter->apply(12345));
    }

    public function test_remove_whitespace_removes_all_spaces(): void
    {
        $filter = new RemoveWhitespace;
        $this->assertEquals('abc123', $filter->apply(' a b  c 1 2 3 '));
        $this->assertEquals('textwithnospaces', $filter->apply("text\nwith\r\nno\tspaces"));
    }

    public function test_remove_whitespace_preserves_non_strings(): void
    {
        $filter = new RemoveWhitespace;
        $this->assertSame(12345, $filter->apply(12345));
        $this->assertSame(null, $filter->apply(null));
    }

    public function test_slugify_converts_to_slug(): void
    {
        $filter = new Slugify;
        $this->assertEquals('hello-world', $filter->apply('Hello World!'));
        $this->assertEquals('test-123', $filter->apply('Test 123 @#!'));
        $this->assertEquals('a-b-c', $filter->apply(' a - b - c '));
    }
}

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
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class FilterTest extends TestCase
{
    /**
     * @param  class-string  $filterClass
     */
    #[Test]
    #[DataProvider('filterDataProvider')]
    public function filters_produce_correct_output(string $filterClass, mixed $input, mixed $expected): void
    {
        $filter = new $filterClass;
        self::assertEquals($expected, $filter->apply($input));
    }

    public static function filterDataProvider(): iterable
    {
        // Capitalize
        yield [Capitalize::class, 'LOWer', 'Lower'];
        yield [Capitalize::class, 'hello world', 'Hello World'];
        yield [Capitalize::class, 'héllo', 'Héllo'];

        // Digit
        yield [Digit::class, 'abc123xyz', '123'];
        yield [Digit::class, 123.45, '12345'];
        yield [Digit::class, 'abc', ''];

        // EscapeHTML
        yield [EscapeHTML::class, '<p>Hello</p>', '&lt;p&gt;Hello&lt;/p&gt;'];
        yield [EscapeHTML::class, 'Hello & World', 'Hello &amp; World'];

        // Lowercase
        yield [Lowercase::class, 'HELLO', 'hello'];
        yield [Lowercase::class, 'HÉLLO', 'héllo'];

        // NormalString
        yield [NormalString::class, '<b>Hello</b>!@#123', 'Hello123'];
        yield [NormalString::class, 'Test <script>alert(1)</script> 2024-04-12 14:30:00', 'Test alert1 2024-04-12 14:30:00'];
        yield [NormalString::class, 'ABC def 123 -:_.', 'ABC def 123 -:_.'];

        // RemoveWhitespace
        yield [RemoveWhitespace::class, ' a b  c 1 2 3 ', 'abc123'];
        yield [RemoveWhitespace::class, "text\nwith\r\nno\tspaces", 'textwithnospaces'];

        // Slugify
        yield [Slugify::class, 'Hello World!', 'hello-world'];
        yield [Slugify::class, ' a - b - c ', 'a-b-c'];

        // StripTags
        yield [StripTags::class, '<p>Hello</p>', 'Hello'];
        yield [StripTags::class, '<b>Hello</b> & World', 'Hello & World'];

        // Trim
        yield [Trim::class, '  hello  ', 'hello'];
        yield [Trim::class, "\t world\n", 'world'];

        // Uppercase
        yield [Uppercase::class, 'hello', 'HELLO'];
        yield [Uppercase::class, 'héllo', 'HÉLLO'];
    }

    /**
     * @param  class-string  $filterClass
     */
    #[Test]
    #[DataProvider('preservesNonStringDataProvider')]
    public function filters_preserve_non_string_values(string $filterClass, mixed $input): void
    {
        $filter = new $filterClass;
        self::assertSame($input, $filter->apply($input));
    }

    public static function preservesNonStringDataProvider(): iterable
    {
        yield [Capitalize::class, 123];
        yield [Capitalize::class, null];
        yield [EscapeHTML::class, 123];
        yield [Lowercase::class, 123];
        yield [RemoveWhitespace::class, 12345];
        yield [StripTags::class, 123];
        yield [Trim::class, 123];
        yield [Uppercase::class, 123];
    }

    #[Test]
    #[DataProvider('castDataProvider')]
    public function cast_converts_to_specified_type(mixed $input, array $options, mixed $expected, string $assertionMethod = 'assertSame'): void
    {
        $filter = new Cast;
        $result = $filter->apply($input, $options);
        self::$assertionMethod($expected, $result);
    }

    public static function castDataProvider(): iterable
    {
        yield ['123', ['int'], 123];
        yield ['123.45', ['float'], 123.45];
        yield [123, ['string'], '123'];
        yield ['1', ['bool'], true];
        yield [['a' => 1], ['object'], (object) ['a' => 1], 'assertEquals'];
        yield ['{"a":1}', ['array'], ['a' => 1], 'assertEquals'];
    }

    #[Test]
    public function cast_can_convert_to_collection(): void
    {
        $filter = new Cast;
        self::assertInstanceOf(Collection::class, $filter->apply('{"a":1}', ['collection']));
    }

    #[Test]
    public function cast_throws_on_invalid_type(): void
    {
        $this->expectException(InvalidArgumentException::class);
        (new Cast)->apply('123', ['unknown']);
    }

    #[Test]
    public function filter_if_checks_array_condition(): void
    {
        $filter = new FilterIf;
        self::assertTrue($filter->apply(['status' => 'active'], ['status', 'active']));
        self::assertFalse($filter->apply(['status' => 'inactive'], ['status', 'active']));
        self::assertFalse($filter->apply(['other' => 'active'], ['status', 'active']));
    }

    #[Test]
    public function format_date_reformats_date_string(): void
    {
        $filter = new FormatDate;
        self::assertSame('15/01/2023', $filter->apply('2023-01-15', ['Y-m-d', 'd/m/Y']));
    }

    #[Test]
    public function format_date_returns_empty_input_unchanged(): void
    {
        $filter = new FormatDate;
        self::assertSame('', $filter->apply('', ['Y-m-d', 'd/m/Y']));
        self::assertSame(null, $filter->apply(null, ['Y-m-d', 'd/m/Y']));
    }

    #[Test]
    public function format_date_throws_on_invalid_options(): void
    {
        $this->expectException(InvalidArgumentException::class);
        (new FormatDate)->apply('2023-01-15', ['Y-m-d']);
    }
}

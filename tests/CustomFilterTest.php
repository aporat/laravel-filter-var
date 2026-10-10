<?php

namespace Aporat\FilterVar\Tests;

use Aporat\FilterVar\Contracts\Filter;
use Aporat\FilterVar\Filters\Uppercase;
use Aporat\FilterVar\FilterVar;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use stdClass;

/**
 * Custom filter to extract the real ID from a media identifier.
 *
 * @implements Filter<mixed, string>
 */
final readonly class MediaRealId implements Filter
{
    /**
     * Extracts the media ID before the first underscore.
     *
     * @param  mixed  $value  The value to process (e.g., "11111_22222")
     * @param  array<string, mixed>  $options  (Unused)
     * @return string The extracted ID as a string.
     */
    public function apply(mixed $value, array $options = []): string
    {
        $value = (string) $value;

        if (str_contains($value, '_')) {
            [$id] = explode('_', $value, 2);

            return $id;
        }

        return $value;
    }
}

final class CustomFilterTest extends TestCase
{
    private FilterVar $filterVar;

    protected function setUp(): void
    {
        parent::setUp();

        $config = require __DIR__.'/../config/filter-var.php';
        $config['custom_filters'] = [
            'MediaRealId' => MediaRealId::class,
        ];

        $this->filterVar = new FilterVar($config);
    }

    #[Test]
    public function media_real_id_extracts_id_before_underscore(): void
    {
        $result = $this->filterVar->filterValue('MediaRealId', '11111_22222');
        self::assertSame('11111', $result);
    }

    #[Test]
    public function media_real_id_returns_unchanged_string_without_underscore(): void
    {
        $result = $this->filterVar->filterValue('MediaRealId', '12345');
        self::assertSame('12345', $result);
    }

    #[Test]
    public function media_real_id_chained_with_cast_to_int(): void
    {
        $result = $this->filterVar->filterValue('MediaRealId|cast:int', '11111_22222');
        self::assertSame(11111, $result);
    }

    #[Test]
    public function media_real_id_handles_non_string_input(): void
    {
        $result = $this->filterVar->filterValue('MediaRealId', 12345);
        self::assertSame('12345', $result);
    }

    #[Test]
    public function media_real_id_handles_null_input(): void
    {
        $result = $this->filterVar->filterValue('MediaRealId', null);
        self::assertSame('', $result);
    }

    #[Test]
    #[DataProvider('filterNameVariantsProvider')]
    public function snake_case_config_key_resolves_with_any_rule_spelling(string $rule): void
    {
        $config = require __DIR__.'/../config/filter-var.php';
        $config['custom_filters'] = [
            'media_real_id' => MediaRealId::class,
        ];

        $filterVar = new FilterVar($config);

        self::assertSame('11111', $filterVar->filterValue($rule, '11111_22222'));
    }

    public static function filterNameVariantsProvider(): iterable
    {
        yield ['media_real_id'];
        yield ['MediaRealId'];
        yield ['mediaRealId'];
        yield ['media_real_id|cast:string'];
    }

    #[Test]
    public function extend_registers_snake_case_filter_at_runtime(): void
    {
        $filterVar = new FilterVar;
        $filterVar->extend('media_real_id', MediaRealId::class);

        self::assertSame('11111', $filterVar->filterValue('media_real_id', '11111_22222'));
    }

    #[Test]
    public function extend_replaces_previously_resolved_filter(): void
    {
        $filterVar = new FilterVar;
        $filterVar->extend('media_real_id', MediaRealId::class);
        self::assertSame('ab', $filterVar->filterValue('media_real_id', 'ab_cd'));

        $filterVar->extend('MediaRealId', Uppercase::class);
        self::assertSame('AB_CD', $filterVar->filterValue('media_real_id', 'ab_cd'));
    }

    #[Test]
    public function extend_throws_when_class_does_not_implement_filter_contract(): void
    {
        $this->expectException(InvalidArgumentException::class);

        (new FilterVar)->extend('not_a_filter', stdClass::class);
    }

    #[Test]
    public function constructor_throws_when_configured_class_does_not_implement_filter_contract(): void
    {
        $this->expectException(InvalidArgumentException::class);

        new FilterVar(['custom_filters' => ['not_a_filter' => stdClass::class]]);
    }

    #[Test]
    public function empty_rule_segments_are_skipped(): void
    {
        self::assertSame('  hello  ', $this->filterVar->filterValue('', '  hello  '));
        self::assertSame('HELLO', $this->filterVar->filterValue('trim|uppercase|', '  hello  '));
        self::assertSame('HELLO', $this->filterVar->filterValue('trim||uppercase', '  hello  '));
    }

    #[Test]
    public function unknown_filter_still_throws(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $this->filterVar->filterValue('trim|does_not_exist', 'hello');
    }

    #[Test]
    public function filter_if_rule_options_match_integer_values(): void
    {
        self::assertTrue($this->filterVar->filterValue('filter_if:status,1', ['status' => 1]));
        self::assertFalse($this->filterVar->filterValue('filter_if:status,1', ['status' => 2]));
    }
}

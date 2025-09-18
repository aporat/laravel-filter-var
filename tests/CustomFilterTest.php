<?php

namespace Aporat\FilterVar\Tests;

use Aporat\FilterVar\Contracts\Filter;
use Aporat\FilterVar\FilterVar;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

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
}

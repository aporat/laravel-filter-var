<?php

namespace Aporat\FilterVar\Tests;

use Aporat\FilterVar\Contracts\Filter;
use Aporat\FilterVar\FilterVar;
use PHPUnit\Framework\TestCase;

/**
 * Custom filter to extract the real ID from a media identifier.
 *
 * This filter takes a string value and extracts the portion before the first underscore,
 * if present. Non-string inputs are converted to strings.
 */
class MediaRealId implements Filter
{
    /**
     * Extract the media ID before the first underscore.
     *
     * If the input contains an underscore, returns the substring before it.
     * Otherwise, returns the input as-is. Non-string inputs are cast to string.
     *
     * @param mixed $value The value to process (typically a string like "11111_22222")
     * @param array<string, mixed> $options Optional filter options (currently unused)
     * @return string The extracted ID as a string
     */
    public function apply(mixed $value, array $options = []): string
    {
        $value = (string) $value; // Ensure string input

        if (str_contains($value, '_')) {
            [$id] = explode('_', $value, 2);
            return $id;
        }

        return $value;
    }
}

class CustomFilterTest extends TestCase
{
    private FilterVar $filterVar;

    protected function setUp(): void
    {
        parent::setUp();

        $config = require __DIR__ . '/../config/filter-var.php';
        $config['custom_filters'] = [
            'MediaRealId' => MediaRealId::class,
        ];

        $this->filterVar = new FilterVar($config);
    }

    public function test_media_real_id_extracts_id_before_underscore(): void
    {
        $result = $this->filterVar->filterValue('MediaRealId', '11111_22222');
        $this->assertEquals('11111', $result);
        $this->assertIsString($result);
    }

    public function test_media_real_id_returns_unchanged_string_without_underscore(): void
    {
        $result = $this->filterVar->filterValue('MediaRealId', '12345');
        $this->assertEquals('12345', $result);
        $this->assertIsString($result);
    }

    public function test_media_real_id_chained_with_cast_to_int(): void
    {
        $result = $this->filterVar->filterValue('MediaRealId|cast:int', '11111_22222');
        $this->assertEquals(11111, $result);
        $this->assertIsInt($result);
    }

    public function test_media_real_id_handles_non_string_input(): void
    {
        $result = $this->filterVar->filterValue('MediaRealId', 12345);
        $this->assertEquals('12345', $result);
        $this->assertIsString($result);
    }

    public function test_media_real_id_handles_null_input(): void
    {
        $result = $this->filterVar->filterValue('MediaRealId', null);
        $this->assertEquals('', $result);
        $this->assertIsString($result);
    }
}

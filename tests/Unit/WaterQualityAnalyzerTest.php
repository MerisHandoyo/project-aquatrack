<?php

namespace Tests\Unit;

use App\Services\WaterQualityAnalyzer;
use PHPUnit\Framework\TestCase;

class WaterQualityAnalyzerTest extends TestCase
{
    public function test_it_estimates_un_ionized_ammonia_fraction(): void
    {
        $analyzer = new WaterQualityAnalyzer();

        $fraction = $analyzer->nh3Fraction(7.0, 25.0);
        $estimate = $analyzer->estimatedNh3(1.0, 7.0, 25.0);

        $this->assertGreaterThan(0.001, $fraction);
        $this->assertLessThan(0.01, $fraction);
        $this->assertEqualsWithDelta($fraction, $estimate, 0.0000001);
    }
}

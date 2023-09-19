<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class DecimalDBFormatHelperTest extends TestCase
{
    protected function toDecimalDBFormat($decimal){
        if(is_numeric($decimal))
            return $decimal;
        return 0;
    }
    /**
     * A basic feature test example.
     */
    public function test_the_to_decimal_db_format_helper(): void
    {
        $number = "1,000";

        $this->assertTrue(is_numeric(toDecimalDBFormat($number)));
    }
}

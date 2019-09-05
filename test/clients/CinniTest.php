<?php
namespace tests;
use PHPUnit\Framework\TestCase;

class CiniiTest extends TestCase
{
    public function testTrueAssetsToTrue()
    {
        $condition = true;
        $this->assertTrue($condition);
    }
}
?>
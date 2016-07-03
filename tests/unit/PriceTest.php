<?php

use App\Values\Price;

class PriceTest extends TestCase
{
  /** @test */
  public function it_creates_a_price_object()
  {
    $price = new Price(220);

    $this->assertEquals('&pound;2.20', (string) $price);
    $this->assertEquals(220, $price->value());
  }

  /** @test */
  public function it_gets_the_decimal_value_of_the_price()
  {
    $price = new Price(220);

    $this->assertEquals(2.20, $price->asDecimal());
  }

  // /** @test */
  // public function it_takes_exception_to_a_non_integer_value()
  // {
  //   $this->setExpectedException('InvalidArgumentException');
  //
  //   $price = new Price();
  // }
}

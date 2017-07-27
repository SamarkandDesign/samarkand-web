<?php

namespace App\Values;

class Price
{
    protected $value;
    protected $symbol;

  /**
   * Create a new Price instance.
   *
   * @param int $value The lowest denomination value
   */
  public function __construct($value)
  {
      // DEBT: shouldn't have to do this
    $value = intval($value);

    // DEBT: Null object needed here
    if (! $value) {
        $value = 0;
    }

      if (! is_int($value)) {
          throw new \InvalidArgumentException(sprintf('A price must be instantiated with an integer. %s given', gettype($value)));
      }

      $this->value = $value;
      $this->symbol = config('shop.currency_symbol');
  }

  /**
   * Get the string representation of the price.
   *
   * @return string
   */
  public function __toString()
  {
      return $this->symbol.money_format('%i', $this->asDecimal());
  }

  /**
   * Get the value of the price.
   *
   * @return int
   */
  public function value()
  {
      return $this->value;
  }

    public function asDecimal()
    {
        return $this->value / 100;
    }
}

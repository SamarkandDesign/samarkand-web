<?php

use XeroPHP\Application\PrivateApplication;
use XeroPHP\Remote\Object;

class FakePrivateXeroApplication extends PrivateApplication
{
    const INVOICE_ID = 'abc123';

    public function __construct()
    {
    }

    public function save(Object $object, $replace_data = false)
    {
        $object->setInvoiceId(self::INVOICE_ID);
    }
}

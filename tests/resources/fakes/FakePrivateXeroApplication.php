<?php

use XeroPHP\Remote\Object;
use XeroPHP\Application\PrivateApplication;

class FakePrivateXeroApplication extends PrivateApplication
{
    const INVOICE_ID = 'abc123';

    public function __construct()
    {
    }

    public function save(Object $object, $replace_data = false)
    {
        if (method_exists($object, 'setInvoiceId')) {
            $object->setInvoiceId(self::INVOICE_ID);
        }
    }
}

<?php

namespace App\Importers;

use App\Http\Requests\Product\CreateProductRequest;
use App\Product;
use Symfony\Component\HttpFoundation\File\File;
use Validator;

class ProductImporter
{
    protected $rules;
    protected $userId;
    protected $headings;
    protected $failures = [];

    public function __construct()
    {
        $this->rules = (new CreateProductRequest())->rules();
        $this->userId = \Auth::user()->id;
    }

    public function run(File $file)
    {
        $csv = collect(file($file))->map(function ($line) {
            return str_getcsv($line);
        });

        $this->headings = $csv->first();

        return $csv->except([0])
              ->map(function ($item) {
                  return $this->sanitizeProduct($item);
              })
              ->filter(function ($item) {
                  return $this->validateProduct($item);
              })
              ->map(function ($item) {
                  return Product::create($item);
              });
    }

    public function getFailures()
    {
        return $this->failures;
    }

 /**
  * Sanitize product data and populate with sensible default if needed.
  *
  * @param  array $item
  *
  * @return array
  */
 public function sanitizeProduct($item)
 {
     $item = array_combine($this->headings, $item);

     $defaults = [
     'slug'    => ($slug = array_get($item, 'slug', false)) ? $slug : str_slug($item['name']),
     'user_id' => $this->userId,
   ];

     return array_merge($item, $defaults);
 }

 /**
  * Validate product data and log if a failure.
  *
  * @param  array $item
  *
  * @return bool
  */
 protected function validateProduct($item)
 {
     $validator = Validator::make($item, $this->rules);

     if ($validator->fails()) {
         $this->failures[] = [
        'data'      => $$item,
        'validator' => $validator,
      ];
     }

     return $validator->passes();
 }
}

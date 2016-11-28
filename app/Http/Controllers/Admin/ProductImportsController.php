<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Product\CreateProductRequest;
use App\Product;
use Illuminate\Http\Request;
use Validator;

class ProductImportsController extends Controller
{
    protected $failures = [];

    public function show()
    {
        return view('admin.products.upload');
    }

    public function create(Request $request)
    {
      $this->validate($request, ['file' => 'required|file|mimes:csv,txt']);

      $csv = collect(file($request->file('file')))->map(function($line) {
        return str_getcsv($line);
      });

      $rules = (new CreateProductRequest())->rules();
      $headings = $csv->first();
      $userId = $request->user()->id;

      $data = $csv->except([0])->map(function($item) use ($headings, $userId) {
        $item = array_combine($headings, $item);

        $defaults = [
          'slug' => ($slug = array_get($item, 'slug', false)) ? $slug : str_slug($item['name']),
          'user_id' => $userId,
        ];

        return array_merge($item, $defaults);
      })->filter(function ($item) use ($rules) {
        $validator = Validator::make($item, $rules);

        if ($validator->fails()) {
          $this->failures[] = $item;
        }

        return $validator->passes();
      })->map(function ($item) {
        return Product::create($item);
      });

      return redirect()->back()->with([
        'alert' => sprintf('%s products imported', $data->count()),
        'alert-class' => 'success',
        ]);
    }
}
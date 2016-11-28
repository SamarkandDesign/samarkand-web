<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Importers\ProductImporter;
use Illuminate\Http\Request;


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

      $importer = new ProductImporter();
      $products = $importer->run($request->file('file'));

      return redirect()->back()->with([
        'alert' => sprintf('%s products imported', $products->count()),
        'alert-class' => 'success',
        ]);
    }
}
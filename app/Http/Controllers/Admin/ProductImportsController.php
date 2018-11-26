<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Importers\ProductImporter;
use App\Http\Controllers\Controller;

class ProductImportsController extends Controller
{
  public function show()
  {
    return view('admin.products.upload');
  }

  public function create(Request $request)
  {
    $this->validate($request, ['file' => 'required|file|mimes:csv,txt']);

    $importer = new ProductImporter();
    $products = $importer->run($request->file('file'));

    return redirect()
      ->route('admin.products.upload')
      ->with([
        'alert' => sprintf('%s products imported', $products->count()),
        'alert-class' => $products->count() ? 'success' : 'warning',
        'failures' => $importer->getFailures(),
      ]);
  }
}

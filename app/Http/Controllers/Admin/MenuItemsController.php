<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\MenuItem;
use Illuminate\Http\Request;

class MenuItemsController extends Controller
{
    protected $rules = [
        'menu' => 'required',
        'label' => 'required',
        'link' => 'required',
        'order' => 'required',
    ];

    public function index(MenuItem $menuItem)
    {
        $menus = MenuItem::all()->groupBy('menu');
        return view('admin.menus.index', compact('menus', 'menuItem'));
    }

    public function store(Request $request)
    {
        $this->validate($request, $this->rules);

        $item = MenuItem::create($request->all());

        return redirect()->route('admin.menus.index')->with([
            'alert'       => 'Menu Item Saved',
            'alert-class' => 'success',
            ]);
    }

    public function edit(MenuItem $menuItem)
    {
        $menus = MenuItem::all()->groupBy('menu');
        return view('admin.menus.edit', compact('menuItem', 'menus'));
    }

    public function update(Request $request, MenuItem $item)
    {
        $this->validate($request, $this->rules);

        $item->update($request->all());

        return redirect()->route('admin.menus.index')->with([
            'alert'       => 'Menu Item Updated',
            'alert-class' => 'success',
            ]);
    }

    public function destroy(MenuItem $item)
    {
       $item->delete();
       return redirect()->route('admin.menus.index')->with([
        'alert'       => 'Menu Item Deleted',
        'alert-class' => 'success',
        ]);
    }
}

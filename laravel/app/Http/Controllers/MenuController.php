<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Support\Facades\Schema;

class MenuController extends Controller
{
    public function index()
    {
        if (! Schema::hasTable('categories') || ! Schema::hasTable('menu_items')) {
            return view('pages.menu', [
                'categories' => collect(),
            ]);
        }

        $categories = Category::query()
            ->with(['menuItems' => function ($query): void {
                $query->where('is_available', true)->orderBy('name');
            }])
            ->orderBy('name')
            ->get();

        return view('pages.menu', [
            'categories' => $categories,
        ]);
    }
}

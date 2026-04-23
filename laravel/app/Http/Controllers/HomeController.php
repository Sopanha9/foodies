<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\MenuItem;
use Illuminate\Support\Facades\Schema;

class HomeController extends Controller
{
    public function index()
    {
        $featuredItems = collect();
        $categories = collect();

        if (Schema::hasTable('menu_items') && Schema::hasTable('categories')) {
            $featuredItems = MenuItem::query()
                ->where('is_available', true)
                ->latest('id')
                ->take(6)
                ->get();

            $categories = Category::query()->orderBy('name')->get();
        }

        return view('pages.home', [
            'featuredItems' => $featuredItems,
            'categories' => $categories,
        ]);
    }
}

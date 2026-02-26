<?php

namespace App\Http\Controllers;

use App\Http\Requests\DestroyCategoryRequest;
use App\Http\Requests\StoreCategoryRequest;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $colocation = auth()->user()->currentColocation;
        $categories = $colocation->categories;

        return view('categories.index', compact('categories'));
    }

    public function store(StoreCategoryRequest $request)
    {
        $user = auth()->user();
        $colocation = $user->currentColocation;

        if($colocation->owner()->id !== $user->id){
            return back()->with('error', 'only the owner can manage categories.');
        }

        $colocation->categories()->create([
            'name' => $request->name,
        ]);
        return back()->with('success','Category added');
    }

    public function destroy(DestroyCategoryRequest $request, Category $category)
    {
        $category->delete();
        return back()->with('success', 'category deleted.');
    }
}

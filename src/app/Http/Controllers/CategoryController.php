<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $colocation = auth()->user()->current_colocation_id;
        $categories = $colocation->categories;

        return view('categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        $colocation = $user->currentColocation;

        if($colocation->owner()->id !== $user->id){
            return back()->with('error', 'only the owner can manage categories.');
        }
        $request->validate([
            'name' => 'required|string|max:50',
        ]);
        $colocation->categories()->create([
            'name' => $request->name,
        ]);
        return back()->with('succuss','Category added');
    }

    public function destroy(Category $category)
    {
        if(auth()->user()->id !== $category->colocation->owner()->id){
            return back()->with('error', 'unauthorized.');
        }
        $category->delete();
        return back()->with('success', 'category deleted.');
    }
}

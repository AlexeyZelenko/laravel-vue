<?php

namespace App\Http\Controllers\Collections;

use App\Http\Controllers\Controller;
use App\Models\CollectionItem;
use Illuminate\Http\Request;
use App\Models\Collection;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;


class CollectionItemController extends Controller
{
    public function index($collectionId)
    {
        $items = CollectionItem::where('collection_id', $collectionId)->get();
        return response()->json(['items' => $items]);
    }

    public function create(Collection $collection)
    {
        // Проверка доступа к коллекции
        if ($collection->user_id !== Auth::id()) {
            abort(403);
        }

        return Inertia::render('collections/Items/Create', [
            'collection' => $collection
        ]);
    }

    public function store(Request $request, Collection $collection)
    {
        // Проверка доступа к коллекции
        if ($collection->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $imagePath = null;

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('collection_items', 'public');
        }

        $item = CollectionItem::create([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'image' => $imagePath,
            'collection_id' => $collection->id,
        ]);

        return redirect()->route('collections.show', $collection->slug);
    }
}

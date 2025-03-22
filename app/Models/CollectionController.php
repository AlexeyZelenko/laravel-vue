<?php

namespace App\Http\Controllers\Collections;

use App\Http\Controllers\Controller;
use App\Models\Collection;
use App\Models\CollectionItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class CollectionItemController extends Controller
{
    public function index(Collection $collection)
    {
        return response()->json(['items' => $collection->items]);
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

    public function destroy(CollectionItem $item)
    {
        if (!$item) {
            Log::warning('CollectionItem with ID: ' . $item->id . ' not found.');
            return response()->json(['message' => 'Карточка не найдена'], Response::HTTP_NOT_FOUND);
        }

        try {
            $item->delete();
            return response()->json(['message' => 'Карточка успешно удалена']);
        } catch (\Exception $e) {
            Log::error('Error deleting CollectionItem with ID: ' . $item->id . '. Error: ' . $e->getMessage());
            return response()->json(['message' => 'Ошибка при удалении карточки: ' . $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}

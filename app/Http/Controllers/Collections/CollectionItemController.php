<?php

namespace App\Http\Controllers\Collections;

use App\Http\Controllers\Controller;
use App\Models\CollectionItem;
use Illuminate\Http\Request;
use App\Models\Collection;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;


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
            'translate_title' => 'required|string|max:255',
            'description' => 'required|string',
            'translate_description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $imagePath = null;

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('collection_items', 'public');
        }

        $item = CollectionItem::create([
            'title' => $validated['title'],
            'translate_title' => $validated['translate_title'],
            'description' => $validated['description'] ?? null,
            'translate_description' => $validated['translate_description'] ?? null,
            'image' => $imagePath,
            'collection_id' => $collection->id,
        ]);

        return redirect()->route('collections.show', $collection->slug);
    }

    public function destroy(CollectionItem $item)
    {
        Log::info('Attempting to delete CollectionItem with ID: ' . $item->id);

        if (!$item) {
            Log::warning('CollectionItem with ID: ' . $item->id . ' not found.');
            return response()->json(['message' => 'Карточка не найдена'], Response::HTTP_NOT_FOUND);
        }

        try {
            $item->delete();
            Log::info('CollectionItem with ID: ' . $item->id . ' successfully deleted.');
            return Redirect::back()->with('success', 'Карточка успешно удалена!'); // Перенаправление назад с сообщением
            // Или, если вам нужно конкретное перенаправление:
            // return Redirect::route('collections.show', ['collection' => $item->collection_id])->with('success', 'Карточка успешно удалена!');

        } catch (\Exception $e) {
            Log::error('Error deleting CollectionItem with ID: ' . $item->id . '. Error: ' . $e->getMessage());
            return response()->json(['message' => 'Ошибка при удалении карточки: ' . $e->getMessage()],
                Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}

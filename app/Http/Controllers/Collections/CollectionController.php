<?php

namespace App\Http\Controllers\Collections;

use App\Http\Controllers\Controller;
use App\Models\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class CollectionController extends Controller
{
    public function index(Request $request)
    {
        $collections = Collection::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        // Возвращаем JSON, если это API-запрос
        if ($request->wantsJson()) {
            return response()->json(['collections' => $collections]);
        }

        // Иначе используем Inertia
        return Inertia::render('collections/List', [
            'collections' => $collections
        ]);
    }


    public function create()
    {
        return Inertia::render('collections/Create');
    }

    public function store(Request $request)
    {
        // Валидация входящих данных
        $validated = $request->validate([
            'name' => 'required|min:3|max:255',
            'description' => 'nullable|string',
            'visibility' => 'boolean',
            'tags' => 'nullable|string',
        ]);

        // Создаем slug из имени
        $slug = Collection::createUniqueSlug($validated['name']);

        // Сохраняем коллекцию
        $collection = new Collection();
        $collection->name = $validated['name'];
        $collection->description = $validated['description'];
        $collection->visibility = $validated['visibility'];
        $collection->slug = Collection::createUniqueSlug($collection->name);
        $collection->user_id = Auth::id();
        $collection->save();

        // Редирект на страницу коллекций с сообщением об успехе
        return redirect()->route('collections.index')
            ->with('success', 'Collection created successfully!');
    }

    public function show(Collection $collection)
    {
        // Проверка прав доступа (добавьте Policy если нужно)
        if ($collection->user_id !== Auth::id() && !$collection->visibility) {
            abort(403);
        }

        return Inertia::render('collections/Show', [
            'collection' => $collection
        ]);
    }
}

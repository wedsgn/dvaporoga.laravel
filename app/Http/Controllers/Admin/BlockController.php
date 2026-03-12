<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\Block\UpdateRequest;
use App\Models\Block;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class BlockController extends BaseController
{
    public function index()
    {
        $user = Auth::user();
        $items = Block::orderBy('id')->get();

        return view('admin.blocks.index', compact('user', 'items'));
    }

    public function edit(Block $block)
    {
        $user = Auth::user();

        $products = collect();

        if ($block->key === 'catalog_default_parts') {
         $products = Product::query()
    ->orderByRaw("
        CASE
            WHEN sort IS NULL OR sort = '' THEN 999999
            WHEN sort ~ '^[0-9]+$' THEN sort::integer
            ELSE 999999
        END ASC
    ")
    ->orderBy('title')
    ->get();
        }

        return view('admin.blocks.edit', compact('user', 'block', 'products'));
    }

    public function update(UpdateRequest $request, Block $block)
    {
        $data = $request->validated();

        if ($block->key === 'catalog_default_parts') {
            $selectedProducts = collect($request->input('selected_products', []))
                ->map(fn ($id) => (int) $id)
                ->filter(fn ($id) => $id > 0)
                ->unique()
                ->values()
                ->all();

            $block->update([
                'title' => $data['title'] ?? null,
                'items' => $selectedProducts,
                'images' => null,
            ]);
        } elseif ($block->key === 'repair_examples') {
            $currentItems = [];
            $oldItems = $block->items ?? [];
            $keepItems = $request->input('keep_items', []);

            foreach ($keepItems as $index) {
                if (isset($oldItems[$index])) {
                    $currentItems[] = $oldItems[$index];
                }
            }

            foreach ($request->file('new_items', []) as $pair) {
                $before = $pair['before'] ?? null;
                $after = $pair['after'] ?? null;

                if ($before && $after) {
                    $beforePath = $this->upload_service->imageConvertAndStore(
                        $request,
                        $before,
                        $block->key . '/before'
                    );

                    $afterPath = $this->upload_service->imageConvertAndStore(
                        $request,
                        $after,
                        $block->key . '/after'
                    );

                    $currentItems[] = [
                        'before' => $beforePath,
                        'after' => $afterPath,
                    ];
                }
            }

            $block->update([
                'title' => $data['title'] ?? null,
                'items' => array_values($currentItems),
                'images' => null,
            ]);
        } else {
            $currentImages = $request->input('keep_images', []);
            $currentImages = array_values(array_filter($currentImages));

            foreach ($request->file('new_images', []) as $image) {
                $currentImages[] = $this->upload_service->imageConvertAndStore(
                    $request,
                    $image,
                    $block->key
                );
            }

            $block->update([
                'title' => $data['title'] ?? null,
                'images' => $currentImages,
                'items' => null,
            ]);
        }

        return redirect()
            ->route('admin.blocks.edit', $block->id)
            ->with('status', 'block-updated');
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    // 一覧
    public function index(Request $request)
    {
        $q    = $request->query('q');       // キーワード
        $sort = $request->query('sort');    // price_asc / price_desc

        $products = Product::query()
            ->when(
                $q,
                fn($query) =>
                $query->where('name', 'like', "%{$q}%")
            )
            ->when($sort === 'price_asc',  fn($query) => $query->orderBy('price', 'asc'))
            ->when($sort === 'price_desc', fn($query) => $query->orderBy('price', 'desc'))
            ->when(!$sort, fn($query) => $query->latest('id')) // デフォルト並び
            ->paginate(6)
            ->withQueryString(); // ページングに q/sort を引き継ぐ

        return view('products.index', compact('products'));
    }
    // 詳細＝変更画面
    public function edit($productId)
    {
        $product = Product::findOrFail($productId);
        return view('products.edit', compact('product'));
    }

    // 登録フォーム
    public function create()
    {
        return view('products.register');
    }

    // 登録処理（画像アップロード対応）
    public function store(ProductRequest $request)
    {
        $validated = $request->validated();

        // ★配列の季節をカンマ区切りに変換
        $validated['season'] = implode(',', $validated['season']);

        // 画像アップロード
        if ($request->hasFile('image')) {
            $validated['image_path'] = $request->file('image')->store('products', 'public');
        }

        Product::create($validated);

        return redirect()->route('products.index')->with('success', '商品を登録しました');
    }

    // 変更処理
    public function update(ProductRequest $request, $productId)
    {
        $product   = Product::findOrFail($productId);
        $validated = $request->validated();

        // ★配列の季節をカンマ区切りに変換
        $validated['season'] = implode(',', $validated['season']);

        // 画像更新（古い画像を削除）
        if ($request->hasFile('image')) {
            if ($product->image_path && Storage::disk('public')->exists($product->image_path)) {
                Storage::disk('public')->delete($product->image_path);
            }
            $validated['image_path'] = $request->file('image')->store('products', 'public');
        }

        $product->update($validated);

        return redirect('/products')->with('success', '更新しました');
    }

    // 削除
    public function destroy($productId)
    {
        $product = Product::findOrFail($productId);

        if ($product->image_path && Storage::disk('public')->exists($product->image_path)) {
            Storage::disk('public')->delete($product->image_path);
        }

        $product->delete();

        return redirect('/products')->with('success', '削除しました');
    }

    // 検索
    public function search(Request $request)
    {
        return $this->index($request);
    }
}

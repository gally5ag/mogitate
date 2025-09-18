{{-- resources/views/products/edit.blade.php --}}
@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/edit.css') }}">
@endpush

@section('content')
<div class="container">
    <h2 class="page-title">商品詳細 → {{ $product->name }}</h2>

    <div class="edit-two-col">
        {{-- 左ペイン：画像プレビュー＋ファイル選択 --}}
        <v class="left-pane">
            <div class="image-card">
                @if($product->image_path)
                {{-- 画像があるときは表示 --}}
                <img class="preview-image" src="{{ asset('storage/' . $product->image_path) }}" alt="{{ $product->name }}">
                <p>現在の画像：{{ basename($product->image_path) }}</p>
                @else
                {{-- 画像がないとき --}}
                <div class="no-image">画像がまだ登録されていません</div>
                @endif
            </div>

            <div class="file-row">
                <label for="image" class="btn-outline">ファイルを選択</label>
                <input id="image" type="file" name="image" accept=".png,.jpeg">
                <span class="file-name">{{ $product->image_path ? basename($product->image_path) : '未選択' }}</span>
            </div>
            @error('image')
            <p class="error">{{ $message }}</p>
            @enderror



        </v>

        {{-- 右ペイン：フォーム本体 --}}
        <form class="form" action="/products/{{ $product->id }}/update" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="form-item">
                <label class="form-label" for="name">商品名</label>
                <input class="input" id="name" type="text" name="name"
                    value="{{ old('name', $product->name) }}" placeholder="商品名を入力">
                @error('name') <p class="error">{{ $message }}</p> @enderror
            </div>

            <div class="form-item">
                <label class="form-label" for="price">値段</label>
                <input class="input" id="price" type="text" name="price"
                    value="{{ old('price', $product->price) }}" placeholder="0〜10000">
                @error('price') <p class="error">{{ $message }}</p> @enderror
            </div>

            <div class="form-item">
                <span class="form-label">季節</span>
                {{-- 季節（複数選択） --}}
                <div class="form-item">
                    <span class="form-label">季節</span>

                    @php
                    // バリデーションエラー後も選択を保持
                    $options = ['春','夏','秋','冬'];
                    $chosen = (array) old('season', []); // register は新規なので old を優先
                    @endphp

                    <div class="check-row">
                        @foreach($options as $opt)
                        <label class="check">
                            <input type="checkbox" name="season[]" value="{{ $opt }}"
                                {{ in_array($opt, $chosen, true) ? 'checked' : '' }}>
                            {{ $opt }}
                        </label>
                        @endforeach
                    </div>

                    @error('season') <p class="error">{{ $message }}</p> @enderror
                    @error('season.*') <p class="error">{{ $message }}</p> @enderror
                </div>


                <div class="form-item">
                    <label class="form-label" for="description">商品説明</label>
                    <textarea class="textarea" id="description" name="description"
                        placeholder="120文字以内で入力">{{ old('description', $product->description) }}</textarea>
                    @error('description') <p class="error">{{ $message }}</p> @enderror
                </div>

                <div class="action-row">
                    <a href="/products" class="btn-gray">戻る</a>
                    <button type="submit" class="btn-yellow">変更を保存</button>
                </div>
        </form>
    </div>

    <form class="delete-form" action="/products/{{ $product->id }}/delete" method="POST">
        @csrf
        <button type="submit" class="icon-trash" title="商品を削除する"></button>
    </form>
</div>
@endsection
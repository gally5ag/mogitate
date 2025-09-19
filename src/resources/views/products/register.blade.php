{{-- resources/views/products/register.blade.php --}}
@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/register.css') }}">
@endpush

@section('content')
<div class="product-register">
    <h2 class="title">商品登録</h2>

    <form action="/products/register" method="POST" enctype="multipart/form-data" class="form">
        @csrf

        {{-- 商品名 --}}
        <div class="form-row">
            <label for="name">商品名 <span class="badge">必須</span></label>
            <input type="text" id="name" name="name" value="{{ old('name') }}" placeholder="商品名を入力">
            @error('name') <p class="error">{{ $message }}</p> @enderror
        </div>

        {{-- 値段 --}}
        <div class="form-row">
            <label for="price">値段 <span class="badge">必須</span></label>
            <input type="text" id="price" name="price" value="{{ old('price') }}" placeholder="値段を入力">
            @error('price') <p class="error">{{ $message }}</p> @enderror
        </div>

      
            {{-- 商品画像 --}}
            <div class="form-row">
                <label for="image">商品画像 <span class="badge">必須</span></label>

                {{-- ファイル選択 --}}
                <input type="file" id="image" name="image" accept=".png,.jpeg">

                         <div class="image-card" style="margin-top:12px;">
                    <div class="no-image">画像がまだ選択されていません</div>
                    
                </div>
                <div class="file-name" style="margin-top:6px;color:#666;">未選択</div>
                @error('image') <p class="error">{{ $message }}</p> @enderror
            </div>

            {{-- 季節（チェックボックス） --}}
            @php
            $oldSeasons = old('season', []);
            $seasonOptions = ['春','夏','秋','冬'];
            @endphp
            <div class="form-row">
                <span>季節 <span class="badge">必須</span></span>
                <div class="radios">
                    @foreach($seasonOptions as $s)
                    <label>
                        <input type="checkbox" name="season[]" value="{{ $s }}"
                            {{ in_array($s, $oldSeasons ?? [], true) ? 'checked' : '' }}>
                        {{ $s }}
                    </label>
                    @endforeach
                </div>
                @error('season') <p class="error">{{ $message }}</p> @enderror
                @error('season.*') <p class="error">{{ $message }}</p> @enderror
            </div>

            {{-- 商品説明 --}}
            <div class="form-row">
                <label for="description">商品説明 <span class="badge">必須</span></label>
                <textarea id="description" name="description" rows="5" placeholder="商品の説明を入力">{{ old('description') }}</textarea>
                @error('description') <p class="error">{{ $message }}</p> @enderror
            </div>

            {{-- ボタン --}}
            <div class="buttons">
                <a href="/products" class="btn btn-gray">戻る</a>
                <button type="submit" class="btn btn-yellow">登録</button>
            </div>
    </form>
</div>
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const input = document.getElementById('image');
        if (!input) return;

        const fileNameSpan = document.querySelector('.file-name');
        const imageCard = document.querySelector('.image-card');

        let previewImg = null;
        let currentObjectUrl = null;

        input.addEventListener('change', () => {
            const file = input.files && input.files[0];

            // ファイル名表示
            if (fileNameSpan) fileNameSpan.textContent = file ? file.name : '未選択';

            // 未選択に戻した場合
            if (!file) {
                if (currentObjectUrl) {
                    URL.revokeObjectURL(currentObjectUrl);
                    currentObjectUrl = null;
                }
                // プレビュー画像があれば消して「未選択」に戻す
                if (previewImg) {
                    previewImg.remove();
                    previewImg = null;
                }
                // no-image を復活
                const noImageExisting = imageCard.querySelector('.no-image');
                if (!noImageExisting) {
                    const noImage = document.createElement('div');
                    noImage.className = 'no-image';
                    noImage.textContent = '画像がまだ選択されていません';
                    imageCard.appendChild(noImage);
                }
                return;
            }

            // 画像以外は弾く（拡張子ではなくMIMEで確認）
            if (!file.type.startsWith('image/')) {
                alert('画像ファイル（.png または .jpeg）を選択してください');
                input.value = ''; // リセット
                if (fileNameSpan) fileNameSpan.textContent = '未選択';
                return;
            }

            // プレビューURL作成
            const objUrl = URL.createObjectURL(file);
            if (currentObjectUrl) URL.revokeObjectURL(currentObjectUrl);
            currentObjectUrl = objUrl;

            // 既存の「no-image」を消す
            const noImage = imageCard.querySelector('.no-image');
            if (noImage) noImage.remove();

            // 画像タグがなければ作成
            if (!previewImg) {
                previewImg = document.createElement('img');
                previewImg.className = 'preview-image';
                previewImg.alt = 'プレビュー';
                imageCard.appendChild(previewImg);
            }

            // 差し替え
            previewImg.src = objUrl;
        });
    });
</script>
@endpush

@endsection
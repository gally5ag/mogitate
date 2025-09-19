{{-- resources/views/products/edit.blade.php --}}
@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/edit.css') }}?v={{ filemtime(public_path('css/edit.css')) }}">

@endpush

@section('content')
<div class="container">
    <h2 class="page-title">
        <span class="page-link">商品一覧</span> ＞ {{ $product->name }}
    </h2>

    {{-- フォームは1つだけ --}}
    <form class="edit-two-col"
        action="/products/{{ $product->id }}/update"
        method="POST"
        enctype="multipart/form-data">
        @csrf

        <div class="edit-two-col">
            {{-- 左ペイン：画像 --}}
            <div class="left-pane">
                <div class="image-card">
                    @if($product->image_path)
                    <img class="preview-image"
                        src="{{ asset('storage/' . $product->image_path) }}"
                        alt="{{ $product->name }}">
                    <p>現在の画像：{{ basename($product->image_path) }}</p>
                    @else
                    <div class="no-image">画像がまだ登録されていません</div>
                    @endif
                </div>

                <div class="file-row">
                    <label for="image" class="btn-outline">ファイルを選択</label>
                    <input id="image" type="file" name="image" accept=".png,.jpg,.jpeg">
                    <span class="file-name">
                        {{ $product->image_path ? basename($product->image_path) : '未選択' }}
                    </span>
                </div>
                @error('image')
                <p class="error">{{ $message }}</p>
                @enderror
            </div>

            {{-- 右ペイン：フォーム本体 --}}
            <div class="right-pane">
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

                {{-- 季節（複数選択） --}}
                <div class="form-item">
                    <span class="form-label">季節</span>
                    @php
                    $options = ['春','夏','秋','冬'];

                    // 古い入力が最優先。なければ product->season。さらに無ければ空配列。
                    $raw = old('season', $product->season ?? []);

                    // 受け取り形式のゆらぎを吸収（配列 / JSON文字列 / カンマ区切り文字列）
                    if (is_array($raw)) {
                    $chosen = $raw;
                    } elseif (is_string($raw)) {
                    $decoded = json_decode($raw, true);
                    if (is_array($decoded)) {
                    $chosen = $decoded;
                    } else {
                    $splitted = preg_split('/[,\s]+/u', trim($raw));
                    $chosen = is_array($splitted) ? $splitted : [];
                    }
                    } else {
                    $chosen = [];
                    }
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


            </div>{{-- /right-pane --}}
            <div class="description-block">
                <label class="form-label" for="description">商品説明</label>
                <textarea class="textarea" id="description" name="description"
                    placeholder="120文字以内で入力">{{ old('description', $product->description) }}</textarea>
                @error('description') <p class="error">{{ $message }}</p> @enderror
            </div>

            {{-- 下段：アクションボタンを独立配置 --}}
            <div class="action-row">
                <a href="/products" class="btn-gray">戻る</a>
                <button type="submit" class="btn-yellow">変更を保存</button>
            </div>
        </div>{{-- /edit-two-col --}}
    </form>

    {{-- 削除ボタン（別フォームのままでOK） --}}
    <form class="delete-form" action="/products/{{ $product->id }}/delete" method="POST">
        @csrf
        <button type="submit" class="icon-trash" title="商品を削除する">🚮</button>
    </form>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const input = document.getElementById('image');
        if (!input) return;

        const fileNameSpan = document.querySelector('.file-name');
        const imageCard = document.querySelector('.image-card');

        let previewImg = document.querySelector('.preview-image'); // 既存があれば使う
        let currentObjectUrl = null;

        input.addEventListener('change', () => {
            const file = input.files && input.files[0];
            // ファイル名の表示
            if (fileNameSpan) {
                fileNameSpan.textContent = file ? file.name : '未選択';
            }

            // プレビュー更新
            if (!file) {
                // 未選択に戻した時：オブジェクトURLを解放してプレビューを消す/元に戻す
                if (currentObjectUrl) {
                    URL.revokeObjectURL(currentObjectUrl);
                    currentObjectUrl = null;
                }
                // 画像が無い場合は「画像がまだ登録されていません」を表示
                if (previewImg) {
                    // 既存画像表示を維持したい場合は何もしない
                    // 消したいなら以下のコメントアウトを外す
                    // previewImg.remove();
                    // previewImg = null;
                }
                return;
            }

            // 画像タイプ以外は弾く（拡張子だけでなくMIMEも確認）
            if (!file.type.startsWith('image/')) {
                alert('画像ファイル（jpg/jpeg/png）を選択してください。');
                input.value = ''; // リセット
                return;
            }

            // プレビュー用URL作成
            const objUrl = URL.createObjectURL(file);

            // 既存URL解放
            if (currentObjectUrl) URL.revokeObjectURL(currentObjectUrl);
            currentObjectUrl = objUrl;

            // プレビューimgが無ければ作る
            if (!previewImg) {
                previewImg = document.createElement('img');
                previewImg.className = 'preview-image';
                previewImg.alt = 'プレビュー';
                // 既存の「no-image」があれば差し替え
                const noImage = imageCard && imageCard.querySelector('.no-image');
                if (noImage) {
                    noImage.replaceWith(previewImg);
                } else if (imageCard) {
                    imageCard.prepend(previewImg);
                }
            }

            // 画像差し替え
            previewImg.src = objUrl;
        });
    });
</script>
@endpush

@endsection
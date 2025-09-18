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
            <input type="file" id="image" name="image" accept=".png,.jpeg">

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
@endsection
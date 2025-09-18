<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>商品一覧</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="/css/products.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Imperial+Script&family=Tangerine:wght@400;700&display=swap" rel="stylesheet">
</head>

<body>
    {{-- 最上段：ブランドのみ --}}
    <header>
        <h1 class="brand">mogitate</h1>
    </header>

    <div class="container">
        {{-- タイトル＋追加ボタン --}}
        <div class="title-row">
            <h2>商品一覧</h2>
            <a class="add-btn" href="/products/register">＋ 商品を追加</a>
        </div>

        <div class="layout">
            {{-- サイドバー --}}
            <aside class="sidebar">
                <div class="block">
                    <form method="GET" action="/products">
                        <div class="search-row">
                            <input type="text" name="q" value="{{ request('q') }}" placeholder="商品名で検索">
                            @if(request('sort'))
                            <input type="hidden" name="sort" value="{{ request('sort') }}">
                            @endif
                            <button class="btn" type="submit">検索</button>
                        </div>
                        @if(request('q'))
                        <div style="margin-top:6px">
                            <a class="muted-link" href="/products">検索条件をクリア</a>
                        </div>
                        @endif
                    </form>
                </div>

                <div class="block">
                    <label>価格順で表示</label>
                    <form method="GET" action="/products">
                        @if(request('q'))
                        <input type="hidden" name="q" value="{{ request('q') }}">
                        @endif

                        {{-- 並び替えセレクト（ポップアップ） --}}
                        <select name="sort" onchange="this.form.submit()">
                            <option value="">指定しない</option>
                            <option value="price_asc" {{ request('sort') == 'price_asc'  ? 'selected' : '' }}>価格が安い順</option>
                            <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>価格が高い順</option>
                        </select>

                        {{-- 選択中の条件をタグ表示 ＋ ×でリセット --}}
                        @php
                        // sort だけ除いたURLを作る（page も外す）
                        $params = request()->except('sort', 'page');
                        $clearSortUrl = url('/products') . (count($params) ? ('?' . http_build_query($params)) : '');
                        @endphp

                        @if(request('sort'))
                        <div class="active-filters">
                            <span class="chip">
                                {{ request('sort') === 'price_desc' ? '高い順に表示' : '低い順に表示' }}
                                <a class="chip-close" href="{{ $clearSortUrl }}" aria-label="並び替えをリセット">×</a>
                            </span>
                        </div>
                        @endif
                    </form>
            </aside>

            {{-- メイン --}}
            <main>
                @if ($products->count() === 0)
                <p style="color: var(--muted)">商品がありません。</p>
                @else
                <ul class="grid" style="padding:0; margin:0">
                    @foreach ($products as $product)
                    <li class="card">
                        <a href="/products/{{ $product->id }}">
                            <img class="thumb" src="{{ asset('storage/' . $product->image_path) }}" alt="{{ $product->name }}">
                        </a>
                        <div class="card-body">
                            <div class="row">
                                <div class="name">{{ $product->name }}</div>
                                <div class="price">¥{{ number_format($product->price) }}</div>
                            </div>


                        </div>
                    </li>
                    @endforeach
                </ul>
                @endif

                <div class="pager">
                    {{ $products->links() }}
                </div>
            </main>
        </div>
    </div>
</body>

</html>
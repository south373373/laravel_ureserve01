<div style="text-align: center">
    <!-- wire:click="method名"で実行 -->
    <button wire:click="increment">+</button>
    <h1>{{ $count }}</h1>
    <div class="mb-8"></div>

    こんにちは、{{ $name }}さん。<br>
    <!-- wire:modelにcounter.phpで作成した$nameを設定 -->
    <!-- 以下は2秒経過後に表示 -->
    <!-- <input type="text" wire:model.debounce.2000ms="name"> -->

    <!-- 以下はテキストボックスのフォーカスから外れたら表示 -->
    {{-- <input type="text" wire:model="name"> --}}
    {{-- <input type="text" wire:model.debounce.2000ms="name"> --}}
    {{-- <input type="text" wire:model.lazy="name"> --}}
    <input type="text" wire:model="name">
    <br>

    <!--  ボタン上にカーソルを合わせると処理が実施 -->
    <button wire:mouseover="mouseOver">マウスを合わせてね</button>
</div>
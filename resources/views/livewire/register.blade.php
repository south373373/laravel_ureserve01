<div>
    <!-- preventを使用しないと読み込みが即座に実施 -->
    {{-- @errorにて入力確認・エラー文言を表示 --}}
    <form wire:submit.prevent="register">
        <label for="name">名前</label>
        <input id="name" type="text" wire:model="name"><br>
        @error('name')<div class="text-red-400">{{ $message }}</div>@enderror

        <label for="email">メールアドレス</label>
        <input id="email" type="email" wire:model.lazy="email"><br>
        @error('email')<div class="text-red-400">{{ $message }}</div>@enderror
        
        <label for="password">パスワード</label>
        <input id="password" type="password" wire:model="password"><br>
        @error('password')<div class="text-red-400">{{ $message }}</div>@enderror

        <button>登録する</button>
    </form>
</div>

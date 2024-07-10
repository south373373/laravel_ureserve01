<?php

namespace App\Http\Livewire;

use Livewire\Component;
// 追記
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class Register extends Component
{
    public $name;
    public $email;
    public $password;

    protected $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|min:8',
    ];

    public function register(){

        $this->validate();

        // dd($this);
        
        User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
        ]);

        // flash messageの設定
        session()->flash('message', '登録OK');

        return to_route('livewire-test.index');
    }

    // 引数の$propertyは任意での名前
    // 以下の設定にてリアルタイムによるvalidationを実施。
    public function updated($property){
        $this->validateOnly($property);
    }

    public function render()
    {
        return view('livewire.register');
    }
}

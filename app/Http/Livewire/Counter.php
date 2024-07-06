<?php

namespace App\Http\Livewire;

use Livewire\Component;

class Counter extends Component
{
    // 追記分
    public $count = 0;

    public $name = '';

    // renderの描画前前にmountの処理が実施。
    // constructorと同じ。
    public function mount(){
        $this->name = 'mount';
    }

    // textboxに一字でも編集すると以下の文字にて
    // 更新される処理
    public function updated(){
        $this->name = 'updated';
    }

    public function mouseOver(){
        $this->name = 'mouseover';
    }

    public function increment(){
        $this->count++;
    }

    public function render()
    {
        return view('livewire.counter');
    }
}

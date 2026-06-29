<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Avatar extends Component
{
    public $user;
    public $size;

    public function __construct($user = null, $size = 80)
    {
        $this->user = $user;
        $this->size = $size;
    }

    public function render()
    {
        return view('components.avatar');
    }
}

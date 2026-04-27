<?php

namespace App\Livewire\Frontend\HomePage;

use App\Livewire\Frontend\HomePage\Traits\Menus;
use App\Livewire\Frontend\HomePage\Traits\Plans;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Home')]

#[Layout('layouts::frontend')]
class HomeIndex extends Component
{
    use Plans;
    use Menus;

    /*
    |--------------------------------------------------------------------------
    | Render
    |--------------------------------------------------------------------------
    */

    public function render()
    {
        return view('livewire.frontend.home-page.home-index');
    }

    public function resetPage()
    {
        $this->redirectRoute('home', navigate: true);
    }
}


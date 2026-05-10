<?php

namespace App\Livewire\Frontend\HelpPage;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Help')]

#[Layout('layouts::frontend')]
class HelpIndex extends Component
{
    public function render()
    {
        return view('livewire.frontend.help-page.help-index');
    }
}

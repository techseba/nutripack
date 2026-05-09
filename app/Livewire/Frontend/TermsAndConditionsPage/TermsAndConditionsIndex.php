<?php

namespace App\Livewire\Frontend\TermsAndConditionsPage;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Terms & Conditions')]

#[Layout('layouts::frontend')]
class TermsAndConditionsIndex extends Component
{
    public function render()
    {
        return view('livewire.frontend.terms-and-conditions-page.terms-and-conditions-index');
    }
}

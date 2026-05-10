<?php

namespace App\Livewire\Frontend\PrivacyPolicyPage;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Privacy Policy')]

#[Layout('layouts::frontend')]

class PrivacyPolicyIndex extends Component
{
    public function render()
    {
        return view('livewire.frontend.privacy-policy-page.privacy-policy-index');
    }
}

<?php

namespace App\Livewire\Frontend\MealPreviewPage;

use App\Models\Meal;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Meal Preview')]

#[Layout('layouts::frontend')]

class MealPreviewIndex extends Component
{
    public $meal;

    public function mount($id)
    {
        $this->meal = Meal::findOrFail($id);
    }
    public function render()
    {
        return view('livewire.frontend.meal-preview-page.meal-preview-index');
    }
}

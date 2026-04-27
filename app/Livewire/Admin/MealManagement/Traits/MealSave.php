<?php

namespace App\Livewire\Admin\MealManagement\Traits;

use App\Models\Meal;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

trait MealSave
{
    public function save()
    {
        // Sanitizing form data
        $this->sanitize();

        if ($this->isEdit) {

            $this->authorize('meal.edit');

            $meal = Meal::findOrFail($this->editRow);

            // ✅ Always slugify
            $this->slug = Str::slug($this->slug);

            $data = $this->validate([
                'name'        => ['required','string','max:40'],
                'slug'              => [
                    'required',
                    'string',
                    'max:60',
                    Rule::unique('meals','slug')->ignore($meal->id),
                ],
                'description' => ['nullable','string'],

                'image' => ['nullable','image','mimes:jpg,jpeg,png','max:512'],

                'calories' => ['required','numeric'],
                'protein'  => ['required','numeric'],
                'carbs'    => ['required','numeric'],
                'fat'      => ['required','numeric'],
                'fiber'    => ['required','numeric'],

                'price' => ['nullable','decimal:0,2'],

                'status'         =>  ['required','in:active,inactive'],

                'meal_type_id' => ['required','exists:meal_types,id'],

                'mealDietPlans'      => ['required','array','min:1'],
                'mealDietPlans.*'    => ['exists:diet_plans,id'],

                'mealIngredients'   => ['required','array','min:1'],
                'mealIngredients.*' => ['exists:ingredients,id'],
            ]);

            // ✅ if new image uploaded
            if ($this->image) {

                if ($meal->image && Storage::disk('public')->exists($meal->image)) {
                    Storage::disk('public')->delete($meal->image);
                }

                $filename = Str::slug($this->name) . '-' . time() . '.' . $this->image->extension();

                $data['image'] = $this->image->storeAs('meals', $filename, 'public');
            }else{
                unset($data['image']);
            }

            $meal['user_id'] = auth()->id();

            $meal->update($data);

            $meal->dietPlans()->sync($this->mealDietPlans);
            $meal->ingredients()->sync($this->mealIngredients);

            $this->dispatch('toast', message: ucfirst($this->subject) . ' updated successfully', type: 'success');

        } else {

            $this->authorize('meal.create');

            // Checking form validation
            $data = $this->validate([
                'name'        => ['required','string','max:40'],
                'description' => ['nullable','string'],

                'image' => ['nullable','image','mimes:jpg,jpeg,png','max:512'],

                'calories' => ['required','numeric'],
                'protein'  => ['required','numeric'],
                'carbs'    => ['required','numeric'],
                'fat'      => ['required','numeric'],
                'fiber'    => ['nullable','numeric'],

                'price' => ['nullable','decimal:0,2'],

                'meal_type_id' => ['required','exists:meal_types,id'],

                'mealDietPlans'      => ['required','array','min:1'],
                'mealDietPlans.*'    => ['exists:diet_plans,id'],

                'mealIngredients'   => ['required','array','min:1'],
                'mealIngredients.*' => ['exists:ingredients,id'],
            ]);

            $slug = Str::slug($this->name);
            $originalSlug = $slug;
            $count = 1;

            while (Meal::where('slug', $slug)->exists()) {
                $slug = $originalSlug . '-' . $count++;
            }
            $data['slug'] = $slug;

            $data['user_id'] = auth()->id();

            // Store image
            if ($this->image) {
                $filename = Str::slug($this->name) . '-' . time() . '.' . $this->image->extension();
                $data['image'] = $this->image->storeAs('meals', $filename, 'public');
            }

            // Inserting a row into the database
            $meal = Meal::create($data);

            $meal->dietPlans()->sync($this->mealDietPlans);
            $meal->ingredients()->sync($this->mealIngredients);

            // Notifying that a row has been successfully inserted into the database
            $this->dispatch('toast', message: ucfirst($this->subject) . ' created successfully', type: 'success');
        }

        // Resetting form fields
        $this->resetFields();

        // Refreshing the table
        $this->refreshTable();

        // Closing the form modal
        $this->dispatch('close-modal');
    }
}

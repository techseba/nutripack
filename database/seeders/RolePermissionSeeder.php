<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [

            // user profile setting
            'profile.setting',

            // dashboard
            'dashboard.view',

            // Permissions
            'permission.view',
            'permission.create',
            'permission.edit',
            'permission.delete',
            'permission.bulk-delete',

            // Roles
            'role.view',
            'role.create',
            'role.edit',
            'role.delete',
            'role.bulk-delete',

            // User permission
            'user.view',
            'user.create',
            'user.edit',
            'user.delete',
            'user.bulk-delete',

            // Diet plan permission
            'diet-plan.view',
            'diet-plan.create',
            'diet-plan.edit',
            'diet-plan.delete',
            'diet-plan.bulk-delete',

            // Meal type permission
            'meal-type.view',
            'meal-type.create',
            'meal-type.edit',
            'meal-type.delete',
            'meal-type.bulk-delete',

            // Ingredient permission
            'ingredient.view',
            'ingredient.create',
            'ingredient.edit',
            'ingredient.delete',
            'ingredient.bulk-delete',

            // Meal permission
            'meal.view',
            'meal.create',
            'meal.edit',
            'meal.delete',
            'meal.bulk-delete',

            // Guest Meal permission
            'guest-meal.view',
            'guest-meal.create',
            'guest-meal.edit',
            'guest-meal.delete',
            'guest-meal.bulk-delete',

            // Day Wise Meal permission
            'day-wise-meal.view',
            'day-wise-meal.create',
            'day-wise-meal.edit',
            'day-wise-meal.delete',
            'day-wise-meal.bulk-delete',

            // Plan Category permission
            'plan-category.view',
            'plan-category.create',
            'plan-category.edit',
            'plan-category.delete',
            'plan-category.bulk-delete',

            // Plan permission
            'plan.view',
            'plan.create',
            'plan.edit',
            'plan.delete',
            'plan.bulk-delete',

            // Subscriber permission
            'subscriber.view',
            'subscriber.create',
            'subscriber.edit',
            'subscriber.delete',
            'subscriber.bulk-delete',

            // Subscriber meal permission
            'subscriber-meal.view',
            'subscriber-meal.create',
            'subscriber-meal.edit',
            'subscriber-meal.delete',
            'subscriber-meal.bulk-delete',

            // Promo Code permission
            'promo-code.view',
            'promo-code.create',
            'promo-code.edit',
            'promo-code.delete',
            'promo-code.bulk-delete',

            // Kitchen report permission
            'kitchen-report.view',
            'kitchen-report.download',

            // Packing report permission
            'packing-report.view',
            'packing-report.download',

            // Delivery report permission
            'delivery-report.view',
            'delivery-report.download',
        ];

        // Create all permissions
        foreach($permissions as $permission){
            Permission::firstOrCreate(['name' => $permission]);
        }

        // User roles
        $roles = [
            'Developer',
            'Admin',
            'Manager',
            'User',
        ];

        // Create all roles
        foreach($roles as $role){
            Role::firstOrCreate(['name' => $role]);
        }

        $developerRole = Role::where('name', 'Developer')->first();
        $developerRole->givePermissionTo(Permission::all());

        $userRole = Role::where('name', 'User')->first();
        $userRole->givePermissionTo('profile.setting');

        $superAdminUser = User::firstOrCreate(
            ['email' => 'mdimranhossaingd@gmail.com'], // lookup
            [
                'name' => 'Md Imran Hossain',
                'password' => Hash::make('aptupdate'), // only set if creating new
            ]
        );

        $user = User::firstOrCreate(
            ['email' => 'test@example.com'], // lookup
            [
                'name' => 'Test User',
                'password' => Hash::make('password'), // only set if creating new
            ]
        );

        $superAdminUser->assignRole($developerRole);
        $user->assignRole($userRole);
    }
}

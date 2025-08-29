<?php

namespace App\Livewire\Company;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class HasPermission extends Component
{
    public function mount()
    {
        // $user = Auth::user();
        $user = User::where('email', 'joao.ordens@1.com')->first();
        // dd($user->userPermissions[0]->permission->description);
    }
    public function render()
    {
        $userPermissions = User::where('email', 'joao.ordens@1.com')->first()->userPermissions;//Auth::user()->userPermissions;
        $userPermissionGroups = User::where('email', 'carlos.view@1.com')->first()->permissionGroups;
        $permissions = new Collection();
        $permissionGroups = new Collection();
        foreach($userPermissions as $permission)
        {
            $permissions[] = $permission->permission;
        }
        foreach($userPermissionGroups as $group)
        {
            $permissionGroups[]=$group;
        }
        return view('livewire.company.has-permission',['permissions'=>$permissions, ])->layout('layouts.company');;
    }
}

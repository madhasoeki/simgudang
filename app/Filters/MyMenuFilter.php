<?php

namespace App\Filters;

use JeroenNoten\LaravelAdminLte\Menu\Filters\FilterInterface;
use Illuminate\Support\Facades\Auth;

class MyMenuFilter implements FilterInterface
{
    public function transform($item)
    {
        $user = Auth::user(); // Ambil user yang sedang login

        // Jika menu memiliki 'role' DAN (user tidak login ATAU user tidak punya role tersebut)
        if (isset($item['role'])) {
            if (!$user || !$user->roles->contains('name', 'super-admin')) {
                // Jika user tidak memiliki role yang sesuai, set 'restricted' menjadi true
                $item['restricted'] = true;
            }
        }

        return $item;
    }
}
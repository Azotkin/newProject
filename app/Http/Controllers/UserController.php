<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function getName()
    {
        $users = DB::select('select firstname, COUNT(*) AS ModelsCount from state_users GROUP BY firstname order by ModelsCount desc limit 3;');
        return $users;
    }

    public function getCountName(Request $request)
    {
        return Users::where('firstname', $request->input('firstname'))->count();

    }

    public function averageValue()
    {
        return DB::select('SELECT AVG(age) FROM state_users');
    }

    public function ageRange(Request $request)
    {
        return DB::table('state_users')
            ->where('age', '>=', $request->input('from'))
            ->where('age', '<=', $request->input('to'))
            ->count();
    }

    public function getAllItem()
    {
        return Users::orderBy('created_at', 'desc')->get();
    }
}

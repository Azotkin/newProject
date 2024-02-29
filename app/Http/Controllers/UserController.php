<?php

namespace App\Http\Controllers;

use App\Models\StateUser;
use App\Models\User;
use App\Models\Users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function getName()
    {
        $users = StateUser
            ::select('firstname', DB::raw('COUNT(*) AS ModelsCount'))
            ->groupBy('firstname')
            ->orderBy('ModelsCount', 'DESC')
            ->limit(3)
            ->get();
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
    public function changeUser(Request $request)
    {

        Users::where('id', $request->get('id'))
            ->update($request->all());
        return 'Запись изменена'.$request->input('id');
    }
    public function saveUser(Request $request)
    {

       Users::create($request->all());
        return 'Запись создана'.$request->input('id');
    }
}
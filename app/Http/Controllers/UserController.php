<?php

namespace App\Http\Controllers;

use App\Models\Country;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function getUsersByCountry(Request $request)
    {
        $countryName = $request->input('country');

        $users = Country::query()->where('name', $countryName)
            ->with(['companies.users' => function ($query) {
                $query->withPivot('connected_at');
            }])
            ->first()
            ->companies
            ->flatMap(function ($company) {
                return $company->users->map(function ($user) use ($company) {
                    return [
                        'user_id' => $user->id,
                        'user_name' => $user->name,
                        'company_id' => $company->id,
                        'company_name' => $company->name,
                        'connected_at' => $user->pivot->connected_at,
                    ];
                });
            });

        return response()->json($users);
    }
}

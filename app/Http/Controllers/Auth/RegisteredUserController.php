<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use App\Zaions\Enums\RolesEnum;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;
use Illuminate\Validation\Rules\Enum;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'phoneNumber' => ['required', 'digits:11', Rule::unique(User::class)],
            'cnic' => ['required', 'digits:13', Rule::unique(User::class)],
            'type' => ['string', new Enum(RolesEnum::class)]
        ]);

        // if ($request->type != RolesEnum::broker->name && $request->type != RolesEnum::broker->name && $request->type != RolesEnum::broker->name) {

        // }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phoneNumber' => $request->phoneNumber,
            'cnic' => $request->cnic,
        ]);

        if ($request->type == RolesEnum::broker->name) {
            $brokerRole = Role::where('name', RolesEnum::broker->name)->get();
            $user->assignRole($brokerRole);
        }
        //  else if ($request->type == RolesEnum::developer->name) {
        //     $developerRole = Role::where('name', RolesEnum::developer->name)->get();
        //     $user->assignRole($developerRole);
        // }
        else if ($request->type == RolesEnum::investor->name) {
            $investorRole = Role::where('name', RolesEnum::investor->name)->get();
            $user->assignRole($investorRole);
        } else {
            $simpleUserRole = Role::where('name', RolesEnum::simpleUser->name)->get();
            $user->assignRole($simpleUserRole);
        }

        event(new Registered($user));

        Auth::login($user);

        return redirect(RouteServiceProvider::HOME);
    }
}

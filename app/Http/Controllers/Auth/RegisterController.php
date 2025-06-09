<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Inertia\Response;
use Inertia\Inertia;

class RegisterController extends Controller
{
    /**
     * Returns the route, called in register GET.
     */
    public function create(): Response
    {
        return Inertia::render('RegisterPage');
    }

    /**
     * Registers a user, called in register POST.
     */
    public function store(Request $request): Response | RedirectResponse
    {
        try {
            $request->validate([
                'fullName' => 'required|string|max:255',
                'email' => 'required|string|lowercase|email|max:255|unique:' . User::class,
                'password' => ['required', 'confirmed', Rules\Password::defaults()],
                'companyName' => 'required|string|max:255'
            ]);

            $company = Company::create([
                'name' => $request->companyName
            ]);

            $user = User::create([
                'full_name' => $request->fullName,
                'email' => $request->email,
                'password' => $request->password,
                'company_id' => $company->id,
                'role_id' => 1 // Owner
            ]);

            Auth::login($user);

            return Inertia::render('Dashboard');
        } catch (ValidationException $e) {
            return redirect()->back()->with('error', [
                'message' => 'Validation errors occurred.',
                'errors'  => $e->errors()
            ], 422);
        }
    }
}

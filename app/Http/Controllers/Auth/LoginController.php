<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    /**
     * Display the login form.
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function login(Request $request)
    {
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        // If validation fails, redirect back with errors
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Ensure the login request is not rate limited
        $this->ensureIsNotRateLimited($request);

        // Attempt to authenticate the user
        $credentials = [
            'samaccountname' => $request->username,
            'password' => $request->password,
        ];

        if (! Auth::attempt($credentials)) {
            $this->handleFailedLogin($request);
        }

        // Clear rate limiter if authentication is successful
        RateLimiter::clear($this->throttleKey($request));

        // Regenerate session to prevent session fixation attacks
        $request->session()->regenerate();

        return redirect()->intended('dashboard');
    }

    /**
     * Log the user out of the application.
     */
    public function logout()
    {
        Auth::logout();

        return redirect('/');
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function ensureIsNotRateLimited(Request $request): void
    {
        if (RateLimiter::tooManyAttempts($this->throttleKey($request), 5)) {
            $this->sendLockoutResponse($request);
        }
    }

    /**
     * Handle a failed login attempt.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function handleFailedLogin(Request $request): void
    {
        RateLimiter::hit($this->throttleKey($request));

        throw ValidationException::withMessages([
            'username' => trans('auth.failed'),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    protected function throttleKey(Request $request): string
    {
        return strtolower($request->input('username')).'|'.$request->ip();
    }

    /**
     * Handle a lockout response from rate limiter.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function sendLockoutResponse(Request $request): void
    {
        $seconds = RateLimiter::availableIn($this->throttleKey($request));

        throw ValidationException::withMessages([
            'username' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }
}

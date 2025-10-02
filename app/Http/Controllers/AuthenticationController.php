<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Services\FirebaseService;
use Kreait\Firebase\Contract\Auth as FirebaseAuth;
use Illuminate\Support\Facades\Mail;
use App\Mail\VerifyEmail;
use Kreait\Firebase\Auth\SignIn\FailedToSignIn;
use Kreait\Laravel\Firebase\Facades\Firebase;
use Kreait\Firebase\Exception\Auth\UserNotFound;

class AuthenticationController extends Controller
{
    protected $firebaseAuth;
    protected $firebaseDb;

    public function __construct(FirebaseAuth $firebaseAuth)
    {
        $this->firebaseAuth = $firebaseAuth;
        $this->firebaseDb = Firebase::database();
    }

    // Show login page
    public function showLogin()
    {
        if (session()->has('firebase_token')) {
            return redirect()->route('dashboard');
        }
        return view('auth.login');
    }

    // Handle login request
    public function login(Request $request)
    {
        $loginData = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required']
        ]);

        $email = $loginData['email'];
        $password = $loginData['password'];

        try {
            $this->firebaseAuth->getUserByEmail($email);
        } catch (UserNotFound $e) {
            return back()->withErrors(['error' => 'User not found.']);
        }

        try {
            $authResult = $this->firebaseAuth->signInWithEmailAndPassword($email, $password);

            // Check verification status from Realtime Database
            $userRef = $this->firebaseDb->getReference('users/' . md5($email));
            $userProfile = $userRef->getValue();

            if (!$userProfile || empty($userProfile['is_verified']) || $userProfile['is_verified'] !== true) {
                return back()->withErrors([
                    'error' => 'Email not verified. Please check your inbox/spam folder.'
                ]);
            }

            $successMessage = 'Successfully signed in!';

            $idToken = $authResult->idToken();
            $refreshToken = $authResult->refreshToken();

            session([
                'firebase_token'         => $idToken,
                'firebase_refresh_token' => $refreshToken,
            ]);

            session()->save();

            return redirect()->route('dashboard')->with('success', $successMessage);
        } catch (FailedToSignIn $e) {
            $authError = $e->getMessage();

            if (str_contains($authError, 'INVALID_LOGIN_CREDENTIALS')) {
                return back()->withErrors(['error' => 'Invalid password. Please try again.']);
            }

            if (str_contains($authError, 'EMAIL_NOT_FOUND')) {
                return back()->withErrors(['error' => 'Email not found.']);
            }

            if (str_contains($authError, 'INVALID_EMAIL')) {
                return back()->withErrors(['error' => 'Invalid email format.']);
            }

            return back()->withErrors(['error' => 'Login failed: ' . $authError]);
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Unexpected error: ' . $e->getMessage()]);
        }

        return back()->withErrors(['error' => 'Incorrect email or password.']);
    }

    // Show register page
    public function showRegister()
    {
        return view('auth.register');
    }

    // Handle register request
    public function register(Request $request)
    {
        $registerData = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email'],
            'password' => ['required', 'confirmed', 'min:6'],
            'terms'    => ['accepted'],
        ]);

        try {
            $email    = $registerData['email'];
            $password = $registerData['password'];

            $newUser = $this->firebaseAuth->createUser([
                'email'    => $email,
                'password' => $password,
            ]);

            $actionCodeSettings = [
                'continueUrl'    => "http://localhost:5173/verify?email=" . urlencode($email),
                'handleCodeInApp' => false,
            ];

            $this->firebaseAuth->sendEmailVerificationLink($email, $actionCodeSettings);

            $request->session()->put('firebase_user', [
                'uid'   => $newUser->uid,
                'email' => $newUser->email,
                'name'  => $registerData['name'],
            ]);

            return redirect()->route('login')->with('success', 'Registration successful! Please check your email for verification.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Registration failed: ' . $e->getMessage()]);
        }
    }

    // Handle logout
    public function logout(Request $request)
    {
        $request->session()->forget('firebase_token');
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Successfully logged out.');
    }

    // Verify email
    public function verifyEmail(Request $request)
    {
        $email = $request->query('email');

        if (!$email) {
            return view('emails/verify', [
                'status'  => 'error',
                'message' => 'Email parameter not found.'
            ]);
        }

        try {
            $user = $this->firebaseAuth->getUserByEmail($email);

            if ($user->emailVerified) {
                $this->firebaseDb
                    ->getReference('users/' . md5($email) . '/is_verified')
                    ->set(true);

                return view('emails.verify', [
                    'status'  => 'success',
                    'message' => 'Email verified successfully! You can now log in.'
                ]);
            } else {
                return view('emails.verify', [
                    'status'  => 'warning',
                    'message' => 'Email not verified yet. Please check your inbox.'
                ]);
            }
        } catch (\Throwable $e) {
            return view('verify', [
                'status'  => 'error',
                'message' => 'Error occurred: ' . $e->getMessage()
            ]);
        }
    }
}

<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            // 'phone'    => 'nullable|string|min:10|max:10',
            // 'photo'    => 'nullable|image|max:2048', // max 2MB
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        return User::create([
            'name'      => $data['name'],
            'email'     => $data['email'],
            'password'  => Hash::make($data['password']),
            'phone'     => $data['phone'] ?? null,
            'photo'     => $data['photo'] ?? null,
            'is_active' => false,
        ]);
    }

    public function register(Request $request)
    {
        // Validate request data
        $this->validator($request->all())->validate();

        // Handle photo upload
        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('profile', 'public');
        }

        // Create the user
        $user = $this->create($request->all());

        // If you want to save the uploaded photo path into the user table:
        if ($photoPath) {
            $user->photo = $photoPath;
            $user->save();
        }

                                    // Assign role to the user
        $user->assignRole('admin'); // or any role you need

        // Fire Registered event
        event(new Registered($user));

        // Log in the user
        $this->guard()->login($user);

        // Redirect or return response
        if ($response = $this->registered($request, $user)) {
            return $response;
        }

        // echo "<pre>";
        // print_r($request->wantsJson()
        //     ? new JsonResponse([], 201)
        //     : redirect($this->redirectPath()));
        // die;

        return $request->wantsJson()
        ? new JsonResponse([], 201)
        : redirect($this->redirectPath());
    }
}

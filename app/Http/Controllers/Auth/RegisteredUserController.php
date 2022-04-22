<?php

namespace App\Http\Controllers\Auth;

use App\Events\Frontend\UserRegistered;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Modules\Benefactor\Services\DonatorService;

class RegisteredUserController extends Controller
{
    protected $donatorService;

    public function __construct(DonatorService $donatorService)
    {
        $this->donatorService = $donatorService;
    }
    
    /**
     * Display the registration view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {

        $options = $this->donatorService->create();

        $options_data = $options->data;

        $banks = $options_data['banks'];
        $donator_types = $options_data['donator_types'];

        return view('auth.register', compact('banks','donator_types'));
    }

    /**
     * Handle an incoming registration request.
     *
     * @param \App\Http\Requests\LoginRequest $request
     *
     * @throws \Illuminate\Validation\ValidationException
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'first_name'=> 'required|min:3|max:191',
            'last_name' => 'required|min:3|max:191',
            'email'     => 'required|email|regex:/(.+)@(.+)\.(.+)/i|max:191|unique:users',
            'password'  => 'required|confirmed|min:4',
        ]);

        $request->is_register = 1;
        $donator = $this->donatorService->store($request);
        $user = $donator->user;

        event(new UserRegistered($user));
        event(new Registered($user));
        Flash::success('<i class="fas fa-check"></i>  Selamat anda sudah terdaftar, Kami telah mengirimkan konfirmasi ke alamat email anda!')->important();

        return redirect('login');
    }
}

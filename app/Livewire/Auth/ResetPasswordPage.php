<?php

namespace App\Livewire\Auth;

use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Livewire\Attributes\Url;
use Livewire\Component;

class ResetPasswordPage extends Component
{
    public $password;
    public $password_confirmation;
    public $token;
    #[Url]
    public $email;
    public function mount($token){
        $this->token=$token;
    }
    public function save(){
        $this->validate([
            'token'=>'required',
            'email'=>'required|email',
            'password'=>'required|confirmed'

        ]);

        $status=Password::reset([
            'email'=>$this->email,
            'password'=>$this->password,
            'password_confirmation'=>$this->password_confirmation,
            'token'=>$this->token
        ],function (User $user, $password){
            $password=$this->password;
            $user->forceFill([
                'password'=>Hash::make($password)
            ])->setRememberToken(Str::random(60));
            $user->save();
event( new PasswordReset($user));
        });
        return $status===Password::PASSWORD_RESET?redirect('/login'):session()->flash('error','something error');

    }
    public function render()
    {
        return view('livewire.auth.reset-password-page');
    }
}

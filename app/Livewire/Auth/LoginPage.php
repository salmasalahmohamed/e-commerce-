<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Title;
use Livewire\Component;

class LoginPage extends Component
{
    #[Title('Login')]

    public $email;
    public $password;
    public function save(){
        $this->validate([
            'email'=>'required|email|exists:users,email',
            'password'=>'required|min:6|max:255'
        ]);
        if (!Auth::attempt(['email'=>$this->email,'password'=>$this->password])){
            session()->flash('error','Invalid Credentials');
            return;
        }
        return redirect()->intended();
    }
    public function render()
    {
        return view('livewire.auth.login-page');
    }
}

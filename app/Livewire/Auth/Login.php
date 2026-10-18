<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Login extends Component
{
    public $email;
    public $password;
    public function login()
    {
        $credentials = $this->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
        if (Auth::attempt($credentials)) {
            session()->regenerate();
            session()->flash('message', 'Login successful!');
            $role = Auth::user()->role;
            $intended = session()->pull('url.intended');

            if ($role === 'admin') {
                $target = ($intended && str_contains($intended, '/admin'))
                    ? $intended
                    : route('admin.dashboard.index');
                return redirect()->to($target);
            } elseif ($role === 'wali_murid') {
                $target = ($intended && str_contains($intended, '/wali-murid'))
                    ? $intended
                    : route('wali-murid.dashboard.index');
                return redirect()->to($target);
            } elseif ($role === 'kepala_sekolah') {
                $target = ($intended && str_contains($intended, '/kepala-sekolah'))
                    ? $intended
                    : route('kepala-sekolah.dashboard.index');
                return redirect()->to($target);
            }
            Auth::logout();
            session()->invalidate();
            session()->regenerateToken();

            session()->flash('error', 'Akun Anda tidak memiliki hak akses yang valid di sistem ini.');
            return redirect('/login');
        } else {
            $this->password = '';
            session()->flash('error', 'Invalid email or password.');
        }
    }
    public function render()
    {
        return view('livewire.auth.login');
    }
}

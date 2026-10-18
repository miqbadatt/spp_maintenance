<?php

namespace App\Livewire\Auth;

use App\Models\Siswa;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class ForgotPassword extends Component
{
    public string $email = '';
    public string $nis = '';
    public bool $isVerified = false;

    public string $siswaNama = '';
    public string $siswaKelas = '';
    public string $waliNama = '';

    public string $password = '';
    public string $password_confirmation = '';

    public function verifyData(): void
    {
        $this->validate([
            'email' => 'required|email',
            'nis'   => 'required|string',
        ], [
            'email.required' => 'Email akun wali murid wajib diisi.',
            'email.email'    => 'Format email tidak valid.',
            'nis.required'   => 'NIS santri wajib diisi.',
        ]);

        $user = User::where('email', trim($this->email))->first();

        if (!$user) {
            $this->addError('email', 'Email akun tidak terdaftar di sistem.');
            return;
        }

        if ($user->role !== 'wali_murid') {
            $this->addError('email', 'Reset mandiri dengan NIS hanya untuk akun Wali Murid. Untuk staf/admin, hubungi administrator.');
            return;
        }

        $siswa = Siswa::where('nis', trim($this->nis))
            ->where('id_user', $user->id)
            ->first();

        if (!$siswa) {
            $this->addError('nis', 'NIS santri tidak cocok dengan akun wali murid ini.');
            return;
        }

        $this->isVerified = true;
        $this->siswaNama = $siswa->nama_siswa;
        $this->siswaKelas = $siswa->kelas;
        $this->waliNama = $user->name;

        session()->flash('success', "Data santri berhasil diverifikasi: {$siswa->nama_siswa} (Kelas {$siswa->kelas}). Silakan buat password baru.");
    }

    public function resetPassword(): void
    {
        if (!$this->isVerified) {
            $this->addError('email', 'Silakan verifikasi data terlebih dahulu.');
            return;
        }

        $this->validate([
            'password' => 'required|min:8|confirmed',
        ], [
            'password.required'  => 'Password baru wajib diisi.',
            'password.min'       => 'Password baru minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        $user = User::where('email', trim($this->email))->first();

        if (!$user) {
            $this->addError('email', 'Akun tidak ditemukan.');
            return;
        }

        // Validasi ulang kecocokan santri
        $siswa = Siswa::where('nis', trim($this->nis))
            ->where('id_user', $user->id)
            ->first();

        if (!$siswa) {
            $this->addError('nis', 'Verifikasi data santri gagal.');
            return;
        }

        $user->password = Hash::make($this->password);
        $user->save();

        session()->flash('message', 'Password berhasil diperbarui! Silakan login menggunakan kata sandi baru Anda.');
        $this->redirect(route('login'), navigate: true);
    }

    public function resetVerification(): void
    {
        $this->isVerified = false;
        $this->password = '';
        $this->password_confirmation = '';
        $this->siswaNama = '';
        $this->siswaKelas = '';
        $this->waliNama = '';
    }

    public function render()
    {
        return view('livewire.auth.forgot-password');
    }
}

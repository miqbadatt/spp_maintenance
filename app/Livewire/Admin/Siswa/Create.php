<?php

namespace App\Livewire\Admin\Siswa;

use App\Models\KategoriSpp;
use App\Models\Siswa;
use App\Models\User;
use Livewire\Component;

class Create extends Component
{
    public $id_user = '';
    public $id_kategori = '';
    public $nis = '';
    public $nama_siswa = '';
    public $kelas = '';

    public function save()
    {
        $this->validate([
            'id_user' => 'required|exists:users,id',
            'id_kategori' => 'required|exists:kategori_spps,id_kategori',
            'nis' => 'required|string|max:20|unique:siswas,nis',
            'nama_siswa' => 'required|string|max:100',
            'kelas' => 'required|string|max:20',
        ]);

        Siswa::create([
            'id_user' => $this->id_user,
            'id_kategori' => $this->id_kategori,
            'nis' => $this->nis,
            'nama_siswa' => $this->nama_siswa,
            'kelas' => $this->kelas,
        ]);

        session()->flash('message', 'Data Santri berhasil ditambahkan.');
        return $this->redirect('/admin/siswa', navigate: true);
    }

    public function render()
    {
        /** @disregard P1005 */
        $wali_murid = User::where('role', 'wali_murid')->get();
        $kategoris = KategoriSpp::all();

        return view('livewire.admin.siswa.create', [
            'wali_murid' => $wali_murid,
            'kategoris' => $kategoris,
        ]);
    }
}

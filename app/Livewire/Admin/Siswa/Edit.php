<?php

namespace App\Livewire\Admin\Siswa;

use App\Models\KategoriSpp;
use App\Models\Siswa;
use App\Models\User;
use Livewire\Component;

class Edit extends Component
{
    public Siswa $siswa;
    public $id_user = '';
    public $id_kategori = '';
    public $nis = '';
    public $nama_siswa = '';
    public $kelas = '';

    public function mount(Siswa $siswa)
    {
        $this->siswa = $siswa;
        $this->id_user = $siswa->id_user;
        $this->id_kategori = $siswa->id_kategori ?? '';
        $this->nis = $siswa->nis;
        $this->nama_siswa = $siswa->nama_siswa;
        $this->kelas = $siswa->kelas;
    }

    public function update()
    {
        $this->validate([
            'id_user' => 'required|exists:users,id',
            'id_kategori' => 'required|exists:kategori_spps,id_kategori',
            // Pengecualian unik NIS untuk data yang sedang diubah
            'nis' => 'required|string|max:20|unique:siswas,nis,' . $this->siswa->id_siswa . ',id_siswa',
            'nama_siswa' => 'required|string|max:100',
            'kelas' => 'required|string|max:20',
        ]);

        $this->siswa->update([
            'id_user' => $this->id_user,
            'id_kategori' => $this->id_kategori,
            'nis' => $this->nis,
            'nama_siswa' => $this->nama_siswa,
            'kelas' => $this->kelas,
        ]);

        session()->flash('message', 'Data Santri berhasil diperbarui.');
        return $this->redirect('/admin/siswa', navigate: true);
    }

    public function render()
    {
        /** @disregard P1005 */
        $wali_murid = User::where('role', 'wali_murid')->get();
        $kategoris = KategoriSpp::all();

        return view('livewire.admin.siswa.edit', [
            'wali_murid' => $wali_murid,
            'kategoris' => $kategoris,
        ]);
    }
}

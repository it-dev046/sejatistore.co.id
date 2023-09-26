<?php

namespace App\Controllers;

class InsController extends BaseController
{
    public function index()
    {
        $data = [
            'title' => 'Halaman Insentif Karyawan',
            'userId' => $this->session->get('id'),
            'daftar_ins' => $this->InsModel->orderBy('id_ins', 'DESC')->findAll(),
            'daftar_karyawan' => $this->KaryawanModel->orderBy('id_karyawan', 'DESC')->findAll(),
        ];

        return view('admin/ins/index', $data);
    }

    public function store()
    {
        $cekid = $this->session->get('id');
        $userId = $this->request->getPost('userId');

        if ($userId == $cekid) {
            $id_karyawan = $this->request->getPost('id_karyawan');
            $um = $this->request->getPost('um');
            $op = $this->request->getPost('op');
            $potongan = $this->request->getPost('potongan');

            $total = $um + $op - $potongan;
            $karyawan = $this->KaryawanModel->where('id_karyawan', $id_karyawan)->first();

            //simpan data database
            $data = [
                'tanggal' => esc($this->request->getPost('tanggal')),
                'keterangan' => esc($this->request->getPost('keterangan')),
                'nama' => $karyawan->nama,
                'rek' => $karyawan->rekening,
                'bank' => $karyawan->bank,
                'op' => $op,
                'um' => $um,
                'potongan' => $potongan,
                'total' => $total,
            ];
            $this->InsModel->insert($data);

            return redirect()->back()->with('success', 'Data Insentif Berhasil Ditambahkan');
        } else {
            return redirect()->back()->with('error', 'Maaf Server sedang sibuk silakhan input ulang');
        }
    }

    public function update($id_ins)
    {
        $cekid = $this->session->get('id');
        $userId = $this->request->getPost('userId');

        if ($userId == $cekid) {
            $um = $this->request->getPost('um');
            $op = $this->request->getPost('op');
            $potongan = $this->request->getPost('potongan');

            $total = $um + $op - $potongan;
            //simpan data database
            $data = [
                'keterangan' => esc($this->request->getPost('keterangan')),
                'rek' => esc($this->request->getPost('rek')),
                'bank' => esc($this->request->getPost('bank')),
                'op' => $op,
                'um' => $um,
                'potongan' => $potongan,
                'total' => $total,
            ];
            $this->InsModel->update($id_ins, $data);

            return redirect()->back()->with('success', 'Data Insentif Berhasil Diubah');
        } else {
            return redirect()->back()->with('error', 'Maaf Server sedang sibuk silakhan input ulang');
        }
    }

    public function destroy($id_ins)
    {
        $cekid = $this->session->get('id');
        $userId = $this->request->getPost('userId');

        if ($userId == $cekid) {
            // Hapus data
            $this->InsModel->where('id_ins', $id_ins)->delete();

            return redirect()->back()->with('success', 'Data Insentif Berhasil Dihapus');
        } else {
            return redirect()->back()->with('error', 'Maaf Server sedang sibuk silakhan input ulang');
        }
    }
}

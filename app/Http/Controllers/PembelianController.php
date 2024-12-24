<?php

namespace App\Http\Controllers;

use App\Models\Pembelian;
use App\Models\Supplier;
use App\Models\Barang;
use App\Models\HargaBarang;
use App\Models\Kategori;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class PembelianController extends Controller
{
    public function index(Request $request)
    {
        $pembelian = Pembelian::tampil();

        // Ambil data supplier dari database dengan kondisi status 1
        $supplier = Supplier::where('status', 1)->get();
        $user = User::all();
        
        return view('pembelian.index', compact('pembelian', 'supplier', 'user'));
    }

    public function oldPurchases(Request $request)
    {
        $pembelian = Pembelian::tampilLama();

        // Ambil semua data supplier dan user untuk halaman pembelian lama
        $supplier = Supplier::all();
        $user = User::all();
        
        return view('pembelian.indexLama', compact('pembelian', 'supplier', 'user'));
    }

    public function create(Request $request)
    {
        $supplier_id = $request->query('supplier_id');
        
        // Ambil semua data yang diperlukan untuk form pembelian dari model Pembelian
        $data = Pembelian::buat($supplier_id);

        // Kirim data ke view untuk ditampilkan
        return view('pembelian.create', $data);
    }

    public function store(Request $request)
    {
        // Validasi data request
        $request->validate([
            'barang_id' => 'required|array|min:1',
            'barang_id.*' => 'required|exists:barang,id',
            'harga_beli.*' => 'required|numeric|min:1',
            'jumlah.*' => 'required|numeric|min:1',
        ], [
            'barang_id.required' => 'Harus memilih setidaknya satu barang',
            'barang_id.*.required' => 'Barang tidak valid',
            'harga_beli.*.required' => 'Harga beli wajib diisi',
            'harga_beli.*.numeric' => 'Harga beli harus berupa angka',
            'harga_beli.*.min' => 'Harga beli tidak boleh kurang dari 1',
            'jumlah.*.required' => 'Jumlah wajib diisi',
            'jumlah.*.numeric' => 'Jumlah harus berupa angka',
            'jumlah.*.min' => 'Jumlah tidak boleh kurang dari 1',
        ]);

        // Panggil method storePembelian dari model untuk menangani proses penyimpanan
        Pembelian::tambahPembelian($request->all());

        return redirect()->to('pembelian')->with('success', 'Pembelian berhasil disimpan.');
    }
    
    public function edit($id)
    {
        // Panggil method model untuk mengambil data yang diperlukan untuk mengedit pembelian
        $data = Pembelian::ganti($id);

        // Cek apakah data berisi pesan error (misalnya jika pembelian terlalu lama untuk diedit)
        if (isset($data['error'])) {
            return redirect()->route('pembelian.lama')->with('error', $data['error']);
        }

        // Jika data valid, kirim data ke view
        return view('pembelian.edit', $data);
    }

    public function update(Request $request, $id)
    {
        // Panggil method model untuk memperbarui pembelian
        $result = Pembelian::gantiPembelian($request->all(), $id);

        // Cek apakah ada error dalam hasil update
        if (isset($result['error'])) {
            return redirect()->route('pembelian.lama')->with('error', $result['error']);
        }

        // Jika berhasil, alihkan dengan pesan sukses
        return redirect()->to('pembelian')->with('success', 'Pembelian berhasil diperbarui.');
    }

    public function laporan()
    {
        // Ambil laporan pembelian dengan join ke beberapa tabel
        $pembelian = DB::table('pembelian')
                    ->join('barang_pembelian', 'pembelian.id', '=', 'barang_pembelian.pembelian_id')
                    ->join('barang', 'barang_pembelian.barang_id', '=', 'barang.id')
                    ->join('supplier', 'pembelian.supplier_id', '=', 'supplier.id')
                    ->select('pembelian.*', 'barang.nama as barang_nama',
                             'supplier.nama as nama_supplier',
                             'barang_pembelian.harga as harga' )
                    ->get();

        // Kirim data laporan pembelian ke view
        return view('pembelian.laporanpembelian', compact('pembelian'));
    }
}

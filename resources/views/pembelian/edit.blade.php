<!DOCTYPE html>
<html lang="en">

<head>
    <title>Edit Pembelian</title>
    @include('template.header')
</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        @include('template.sidebar')
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                @include('template.navbar')
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Edit Pembelian</h1>
                    </div>

                    <div class="my-3 p-3 bg-body shadow-sm" style="box-shadow: 0 0 10px rgba(0, 0, 0, 0.5); border-radius:15px;">

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form method="POST" action="{{ url('pembelian/' . $pembelian->id) }}" id="pembelianForm">
                        @csrf
                        @method('PUT')
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <a href='{{ url('pembelian') }}' class="btn btn-secondary btn-sm"> < Kembali</a>
                            <div>Tanggal Transaksi : <span id="tanggalTransaksi">{{ \Carbon\Carbon::parse($pembelian->tanggal_transaksi)->format('d-m-Y') }}</span></div>
                        </div>
                        <div>
                            <label for="supplier_nama" class="form-label">Supplier :</label>
                            <span>{{ $pembelian->supplier->nama }}</span>
                        </div>
                        <div>
                            <label for="supplier_nomor" class="form-label">Nomor :</label>
                            <span>{{ $pembelian->supplier->nomor }}</span>
                        </div>
                        <div>
                            <label for="supplier_alamat" class="form-label">Alamat :</label>
                            <span>{{ $pembelian->supplier->alamat }}</span>
                    </div>
                        <div class="mb-3 row">
                            <label for="nama" class="col-sm-2 col-form-label">Nama Barang</label>
                            <div class="col-sm-4 d-flex justify-content-end">
                                <input type="text" class="form-control" id="searchBarang" placeholder="Pilih Barang" aria-label="Search">
                                <button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#modalBarang">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                        <table class="table table-striped" id="selectedBarangTable">
                            <thead>
                                <tr class="text-center">
                                    <th class="col-md-1 text-center">No</th>
                                    <th class="col-md-3 text-center">Nama</th>
                                    <th class="col-md-2 text-center">Harga</th>
                                    <th class="col-md2 text-center">Jumlah</th>
                                    <th class="col-md-2 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pembelian->barangs as $index => $barang)
                                <tr class="text-center" data-id="{{ $barang->id }}">
                                    <td class="col-md-1 text-center">{{ $index + 1 }}</td>
                                    <td class="col-md-3 text-center">{{ $barang->nama }}</td>
                                    <td class="col-md-2 text-center">
                                        <input type="number" class="form-control harga-barang" name="harga_beli[]" value="{{ $barang->pivot->harga }}" oninput="hitungTotal()">
                                        <input type="hidden" name="barang_id[]" value="{{ $barang->id }}">
                                    </td>
                                    <td class="col-md-2 text-center">
                                        <input type="number" class="form-control jumlah-barang" name="jumlah[]" value="{{ $barang->pivot->jumlah }}" oninput="hitungTotal()">
                                    </td>
                                    <td class="col-md-2 text-center">
                                        <button type="button" class="btn btn-danger btn-sm deleteBarangBtn" data-id="{{ $barang->id }}">
                                            <i class="fas fa-trash"></i> Hapus
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card-header py-4 flex-row align-items-center justify-content-between" style="border-radius: 15px; background-color: cornflowerblue; color: white;">
                                <h6 class="m-0 font-weight-bold">Total Harga: <span id="totalHarga">Rp {{ number_format($pembelian->total_harga, 0, ',', '.') }}</span></h6>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end mt-3">
                        <button type="submit" class="btn btn-primary">Simpan Pembelian</button>
                    </div>
                </form>
            </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            @include('template.footer')
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    @include('template.modal_logout')

    @include('template.script')

    <script>
        $(document).ready(function() {
            // Tangkap klik pada tombol Pilih Barang di modal
            $(document).on('click', '.pilihBarangBtn', function() {
                var id = $(this).data('id');
                var nama = $(this).data('nama');
                var harga = $(this).data('harga'); // Pastikan harga diambil dengan benar

                // Periksa apakah barang sudah ada di tabel
                var exists = false;
                $('#selectedBarangTable tbody tr').each(function() {
                    if ($(this).data('id') == id) {
                        exists = true;
                        return false; // Hentikan loop
                    }
                });

                if (!exists) {
                    // Tambahkan barang yang dipilih ke tabel tanpa memformat harga
                    var newRow = `<tr class="text-center" data-id="${id}">
                                    <td class="col-md-1 text-center"></td>
                                    <td class="col-md-3 text-center">${nama}</td>
                                    <td class="col-md-2 text-center">
                                        <input type="number" class="form-control harga-barang" name="harga_beli[]" value="${harga}" oninput="hitungTotal()">
                                        <input type="hidden" name="barang_id[]" value="${id}">
                                    </td>
                                    <td class="col-md-2 text-center">
                                        <input type="number" class="form-control jumlah-barang" name="jumlah[]" oninput="hitungTotal()">
                                    </td>
                                    <td class="col-md-2 text-center">
                                        <button type="button" class="btn btn-danger btn-sm deleteBarangBtn" data-id="${id}">
                                            <i class="fas fa-trash"></i> Hapus
                                        </button>
                                    </td>
                                </tr>`;

                    $('#selectedBarangTable tbody').append(newRow);

                    // Update nomor urut di tabel
                    updateNomorUrut();

                    // Hitung total keseluruhan dari barang yang dipilih
                    hitungTotal();

                    // Tutup modal setelah memilih barang
                    $('#modalBarang').modal('hide');
                } else {
                    alert("Barang sudah dipilih.");
                }
            });

            // Tangkap perubahan pada input jumlah barang
            $(document).on('input', '.jumlah-barang, .harga-barang', function() {
                // Hitung ulang total keseluruhan setelah nilai jumlah diubah
                hitungTotal();
            });

            // Tangkap klik pada tombol Hapus Barang di tabel
            $(document).on('click', '.deleteBarangBtn', function() {
                var row = $(this).closest('tr');

                // Hapus baris dari tabel tampilan
                row.remove();

                // Update nomor urut di tabel setelah menghapus baris
                updateNomorUrut();

                // Hitung ulang total keseluruhan setelah menghapus barang
                hitungTotal();
            });

            // Fungsi untuk mengupdate nomor urut di tabel
            function updateNomorUrut() {
                $('#selectedBarangTable tbody tr').each(function(index) {
                    $(this).find('td:eq(0)').text(index + 1);
                });
            }

            // Fungsi untuk menghitung total keseluruhan harga barang yang dipilih
            function hitungTotal() {
                var total = 0;

                $('#selectedBarangTable tbody tr').each(function() {
                    var harga = parseFloat($(this).find('.harga-barang').val());
                    var jumlah = parseFloat($(this).find('.jumlah-barang').val());

                    // Periksa jika jumlah valid (bukan NaN atau kosong)
                    if (!isNaN(jumlah) && jumlah > 0 && !isNaN(harga) && harga > 0) {
                        total += harga * jumlah;
                    }
                });

                // Tampilkan total di card header
                $('#totalHarga').text(total.toLocaleString('id-ID', { style: 'currency', currency: 'IDR' }));
            }
        });
    </script>

    <!-- Modal untuk memilih barang -->
    <div class="modal fade" id="modalBarang" tabindex="-1" role="dialog" aria-labelledby="modalBarangLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalBarangLabel">Pilih Barang</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <!-- FORM PENCARIAN -->
                <div class="pt-3 mr-3 d-flex justify-content-end">
                    <form id="searchForm" class="d-flex w-70">
                        <input id="searchInput" class="form-control me-1" type="search" name="katakunci"
                            value="{{ Request::get('katakunci') }}" placeholder="Masukkan nama barang" aria-label="Search">
                        <button id="searchButton" class="btn btn-secondary" type="button">
                            <i class="fas fa-search"></i>
                        </button>
                    </form>
                </div>
                <div class="modal-body">
                    <table class="table table-striped" id="barangTable">
                        <thead>
                            <tr class="text-center">
                                <th class="col-md-1 text-center">No</th>
                                <th class="col-md-3 text-center">Nama</th>
                                <th class="col-md-2 text-center">Harga</th>
                                <th class="col-md-2 text-center">Jumlah</th>
                                <th class="col-md-2 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Daftar barang akan diisi secara dinamis dari database -->
                            @foreach($barangs as $index => $barang)
                            <tr>
                                <td class="col-md-1 text-center">{{ $index + 1 }}</td>
                                <td class="col-md-3 text-center">{{ $barang->nama }}</td>
                                <td class="col-md-2 text-center">
                                    @if (isset($rataRataHargaBeli[$barang->id]))
                                    Rp. {{ number_format($rataRataHargaBeli[$barang->id], 0, ',', '.') }}
                                    @else
                                    -
                                    @endif
                                </td>
                                <td class="col-md-2 text-center">{{ $barang->jumlah }}</td>
                                <td>
                                    <button type="button" class="btn btn-primary pilihBarangBtn" data-id="{{ $barang->id }}" data-nama="{{ $barang->nama }}" data-harga="{{ $rataRataHargaBeli[$barang->id] ?? '' }}">Pilih</button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            // Tangkap form pencarian saat tombol "Cari" ditekan
            $('#searchButton').on('click', function() {
                performSearch();
            });

            // Tangkap form pencarian saat tombol "Enter" ditekan di input
            $('#searchInput').on('keypress', function(e) {
                if (e.which === 13) {
                    performSearch();
                    return false; // Hindari submit form default
                }
            });

            // Fungsi untuk melakukan pencarian
            function performSearch() {
                var katakunci = $('#searchInput').val().toLowerCase();
                $('#barangTable tbody tr').filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(katakunci) > -1)
                });
            }

            // Bersihkan form pencarian saat modal ditutup
            $('#modalBarang').on('hidden.bs.modal', function() {
                $('#searchInput').val('');
                $('#barangTable tbody tr').show(); // Tampilkan kembali semua baris
            });
        });
    </script>

</body>

</html>

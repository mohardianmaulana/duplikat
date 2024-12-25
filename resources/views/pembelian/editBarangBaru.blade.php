<!DOCTYPE html>
<html lang="en">

<head>
    <title>Pembelian Barang Baru</title>
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
                        <h1 class="h3 mb-0 text-gray-800">Edit Pembelian Barang Baru</h1>
                    </div>
                    <form method="POST" action="{{ url('pembelian/' . $pembelian->id) }}" enctype="multipart/form-data">
                        @csrf
                        <div class="my-3 p-3 bg-body rounded shadow-sm">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <a href="{{ url('pembelian') }}" class="btn btn-secondary btn-sm"> < Kembali</a>
                                <div>Tanggal Transaksi : <span id="tanggalTransaksi"></span></div>
                            </div>
                            <div class="mb-3 row">
                                <label for="id_qr" class="col-sm-2 col-form-label">Id Qr</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="id_qr" value="{{ old('id_qr', $barang->id_qr) }}" id="id_qr" readonly>
                                    @if ($errors->has('id_qr'))
                                        <div style="color:#dc4c64; margin-top:0.25rem;">
                                            {{ $errors->first('id_qr') }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label for="nama" class="col-sm-2 col-form-label">Nama Barang</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="nama" value="{{ old('nama', $barang->nama) }}" id="nama">
                                    @if ($errors->has('nama'))
                                        <div style="color:#dc4c64; margin-top:0.25rem;">
                                            {{ $errors->first('nama') }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label for="supplier" class="col-sm-2 col-form-label">Supplier</label>
                                <div class="col-sm-10">
                                    <select name="supplier_id" class="form-control">
                                        <option value="" class="text-center">--- Pilih ---</option>
                                        @foreach ($supplier as $item)
                                            <option value="{{ $item->id }}" class="text-center" {{ old('supplier_id', $pembelian->supplier_id) == $item->id ? 'selected' : '' }}>
                                                {{ $item->nama }}
                                            </option>                    
                                        @endforeach
                                    </select>
                                    @if ($errors->has('supplier_id'))
                                        <div style="color:#dc4c64; margin-top:0.25rem;">
                                            {{ $errors->first('supplier_id') }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label for="harga_beli" class="col-sm-2 col-form-label">Harga Beli</label>
                                <div class="col-sm-10">
                                    <input type="number" class="form-control" name="harga_beli" value="{{ old('harga_beli', $barang->harga_beli) }}" id="harga_beli">
                                    @if ($errors->has('harga_beli'))
                                        <div style="color:#dc4c64; margin-top:0.25rem;">
                                            {{ $errors->first('harga_beli') }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label for="harga_jual" class="col-sm-2 col-form-label">Harga Jual</label>
                                <div class="col-sm-10">
                                    <input type="number" class="form-control" name="harga_jual" value="{{ old('harga_jual', $barang->harga_jual) }}" id="harga_jual">
                                    @if ($errors->has('harga_jual'))
                                        <div style="color:#dc4c64; margin-top:0.25rem;">
                                            {{ $errors->first('harga_jual') }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label for="jumlah" class="col-sm-2 col-form-label">Jumlah</label>
                                <div class="col-sm-10">
                                    <input type="number" class="form-control" name="jumlah" value="{{ old('jumlah', $barang->jumlah) }}" id="jumlah">
                                    @if ($errors->has('jumlah'))
                                        <div style="color:#dc4c64; margin-top:0.25rem;">
                                            {{ $errors->first('jumlah') }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label for="totalHarga" class="col-sm-2 col-form-label">Total Harga</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="totalHarga" id="totalHarga" value="{{ old('totalHarga', 'Rp ' . number_format($barang->totalHarga, 0, ',', '.')) }}" readonly>
                                    @if ($errors->has('totalHarga'))
                                        <div style="color:#dc4c64; margin-top:0.25rem;">
                                            {{ $errors->first('totalHarga') }}
                                        </div>
                                    @endif
                                </div>
                            </div>                            
                            <div class="mb-3 row">
                                <label for="minLimit" class="col-sm-2 col-form-label">Min Limit</label>
                                <div class="col-sm-10">
                                    <input type="number" class="form-control" name="minLimit" value="{{ old('minLimit', $barang->minLimit) }}" id="minLimit">
                                    @if ($errors->has('minLimit'))
                                        <div style="color:#dc4c64; margin-top:0.25rem;">
                                            {{ $errors->first('minLimit') }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label for="maxLimit" class="col-sm-2 col-form-label">Max Limit</label>
                                <div class="col-sm-10">
                                    <input type="number" class="form-control" name="maxLimit" value="{{ old('maxLimit', $barang->maxLimit) }}" id="maxLimit">
                                    @if ($errors->has('maxLimit'))
                                        <div style="color:#dc4c64; margin-top:0.25rem;">
                                            {{ $errors->first('maxLimit') }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label for="kategori" class="col-sm-2 col-form-label">Kategori</label>
                                <div class="col-sm-10">
                                    <select name="kategori_id" class="form-control">
                                        <option value="" class="text-center">--- Pilih ---</option>
                                        @foreach ($kategori as $item)
                                            <option value="{{ $item->id }}" class="text-center" {{ old('kategori_id', $barang->kategori_id) == $item->id ? 'selected' : '' }}>
                                                {{ $item->nama_kategori }}
                                            </option>                    
                                        @endforeach
                                    </select>
                                    @if ($errors->has('kategori_id'))
                                        <div style="color:#dc4c64; margin-top:0.25rem;">
                                            {{ $errors->first('kategori_id') }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label for="gambar" class="col-sm-2 col-form-label">Gambar Baru</label>
                                <div class="col-sm-10">
                                    <input type="file" name="gambar" id="gambar">
                                    @error('gambar')
                                        <div style="color:#dc4c64; margin-top:0.25rem;">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label for="gambar" class="col-sm-2 col-form-label">Gambar Lama</label>
                                <div class="col-sm-10">
                                    @if($barang->gambar) 
                                        <img src="{{ asset('img/' . $barang->gambar) }}" alt="Gambar" style="max-width: 200px; max-height: 200px;">
                                    @else
                                        <span>Tidak ada gambar</span>
                                    @endif
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label for="jurusan" class="col-sm-2 col-form-label"></label>
                                <div class="col-sm-10 mb-2">
                                    <div class="col-sm-10"><button type="submit" class="btn btn-primary mt-3" name="submit">SIMPAN</button></div>
                                </div>
                            </div>
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

    @include('template.script')

    <script>
        // Update totalHarga field dynamically based on harga_beli and jumlah inputs
        document.getElementById('harga_beli').addEventListener('input', updateTotal);
        document.getElementById('jumlah').addEventListener('input', updateTotal);

        function updateTotal() {
            var hargaBeli = parseFloat(document.getElementById('harga_beli').value) || 0;
            var jumlah = parseFloat(document.getElementById('jumlah').value) || 0;
            var total = hargaBeli * jumlah;
            document.getElementById('totalHarga').value = 'Rp ' + total.toLocaleString('id-ID');
        }

        // Set today's date dynamically
        document.getElementById('tanggalTransaksi').textContent = new Date().toLocaleDateString('id-ID');
    </script>

</body>
</html>

@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h1>Daftar Transaksi</h1>

    <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addModal">Tambah Transaksi</button>

    <!-- Modal Add Item -->
    <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form action="{{ route('transactions.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addModalLabel">Tambah Transaksi Baru</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="customer_id" class="form-label">Pelanggan</label>
                            <select class="form-control" id="customer_id" name="customer_id">
                                @foreach ($customers as $customer)
                                <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="total" class="form-label">total</label>
                            <input type="number" class="form-control" id="total" name="total">
                        </div>
                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-control" id="status" name="status">
                                <option value="pending">Pending</option>
                                <option value="success">Success</option>
                                <option value="failed">Failed</option>
                            </select>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary">Tambah Transaksi</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Tampilkan Daftar Transaksi dalam Card -->
    <div class="row">
        @foreach($transactions as $index => $transaction)
        <div class="col-md-4 mb-4">
            <div class="card">
                <div class="card-body">
                    <p class="card-text"><strong>Harga:</strong> Rp {{ number_format($transaction->total, 0, ',', '.') }}</p>
                    <p class="card-text"><strong>Customer:</strong> {{ $transaction->customers->name }}</p>
                    <p class="card-text">
                        <strong>Status:</strong>
                        @if($transaction->status == 'pending')
                        <span class="badge bg-warning text-dark">Pending</span>
                        @elseif($transaction->status == 'success')
                        <span class="badge bg-success">Success</span>
                        @elseif($transaction->status == 'failed')
                        <span class="badge bg-danger">Failed</span>
                        @endif
                    </p>
                    <a href="#" class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#showModal{{ $transaction->id }}">Show</a>
                    <a href="#" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editModal{{ $transaction->id }}">Edit</a>
                    <a href="#" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $transaction->id }}">Delete</a>
                </div>
            </div>
        </div>

        <!-- Modal Show -->
        <div class="modal fade" id="showModal{{ $transaction->id }}" tabindex="-1" aria-labelledby="showModalLabel{{ $transaction->id }}" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="showModalLabel{{ $transaction->id }}">Detail Transaksi</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-8">
                                <p><strong>Pelanggan:</strong> {{ $transaction->customers->name }}</p>
                                <p><strong>Total:</strong> Rp {{ number_format($transaction->total, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Edit -->
        <div class="modal fade" id="editModal{{ $transaction->id }}" tabindex="-1" aria-labelledby="editModalLabel{{ $transaction->id }}" aria-hidden="true">
            <div class="modal-dialog">
                <form action="{{ route('transactions.update', $transaction->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editModalLabel{{ $transaction->id }}">Edit Transaksi</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <!-- Input Customer -->
                            <label for="customer_id" class="form-label">Customer</label>
                            <select class="form-control" id="customer_id" name="customer_id">
                                @foreach ($customers as $customer)
                                <option value="{{ $customer->id }}" {{ $transaction->customer_id == $customer->id ? 'selected' : '' }}>
                                    {{ $customer->name }}
                                </option>
                                @endforeach
                            </select>

                            <!-- Input Total -->
                            <div class="mb-3">
                                <label for="total" class="form-label">Total</label>
                                <input type="number" class="form-control" id="total" name="total" value="{{ $transaction->total }}">
                            </div>

                            <!-- Input Status -->
                            <div class="mb-3">
                                <label for="status" class="form-label">Status</label>
                                <select class="form-control" id="status" name="status">
                                    <option value="pending" {{ $transaction->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="success" {{ $transaction->status == 'success' ? 'selected' : '' }}>Success</option>
                                    <option value="failed" {{ $transaction->status == 'failed' ? 'selected' : '' }}>Failed</option>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

    </div>

    <!-- Modal Delete -->
    <div class="modal fade" id="deleteModal{{ $transaction->id }}" tabindex="-1" aria-labelledby="deleteModalLabel{{ $transaction->id }}" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel{{ $transaction->id }}">Konfirmasi Hapus</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Apakah Anda yakin ingin menghapus barang <strong>{{ $transaction->name }}</strong>?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <form action="{{ route('transactions.destroy', $transaction->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Hapus</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>
</div>
@endsection
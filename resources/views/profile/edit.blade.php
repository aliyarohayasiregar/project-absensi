@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Edit Profil</h1>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-body">
            <form action="{{ route('profile.update') }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="mb-3">
                    <label class="form-label">Nama</label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                        value="{{ old('name', $user->name) }}" required>
                    @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                        value="{{ old('email', $user->email) }}" required>
                    @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                @if($user->employee)
                <div class="mb-3">
                    <label class="form-label">No. Telepon</label>
                    <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" 
                        value="{{ old('phone', $user->employee->phone) }}" required>
                    @error('phone')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Alamat</label>
                    <textarea name="address" class="form-control @error('address') is-invalid @enderror" 
                        rows="3" required>{{ old('address', $user->employee->address) }}</textarea>
                    @error('address')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                @endif

                <hr>
                <h5>Ubah Password</h5>
                <small class="text-muted">Kosongkan jika tidak ingin mengubah password</small>

                <div class="mb-3">
                    <label class="form-label">Password Saat Ini</label>
                    <input type="password" name="current_password" class="form-control @error('current_password') is-invalid @enderror">
                    @error('current_password')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Password Baru</label>
                    <input type="password" name="new_password" class="form-control @error('new_password') is-invalid @enderror">
                    @error('new_password')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Konfirmasi Password Baru</label>
                    <input type="password" name="new_password_confirmation" class="form-control">
                </div>

                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary">Update Profil</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection 
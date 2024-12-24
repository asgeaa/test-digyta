@extends('layouts.app')
@section('title', 'Task')
@section('content')
    <section>
        <div class="container-fluid shadow-sm p-3 mb-4 bg-white rounded">
            <header class="d-flex justify-content-between align-items-center">
                <div>
                    <h3>Task Management System</h3>
                    <p>Hallo, {{ ucwords(auth()->user()->name) }}</p>
                </div>
                <div>
                    <a href="{{ route('auth.logout') }}" class="text-danger fw-semibold text-decoration-none"
                        onclick="return confirm('Apakah Anda yakin ingin keluar?')">
                        <i class="fas fa-sign-out-alt text-dark"></i>
                        Keluar
                    </a>
                    {{-- <form action="{{ route('auth.logout') }}" method="POST" class="d-inline"
                        onsubmit="return confirm('Apakah Anda yakin ingin keluar?')">
                        @csrf
                        <button type="submit" class="text-danger">Keluar</button>
                    </form> --}}
                </div>
            </header>
        </div>
    </section>

    <section>
        <div class="container-fluid">
            <div class="row d-flex justify-content-between align-items-center mb-4">
                <h5>Cari Data</h5>
                <div class="col-10">
                    <form action="{{ route('task.index') }}" method="GET" class="d-flex">
                        <input type="text" name="search" class="form-control w-100"
                            placeholder="Masukan data yang kamu cari" value="{{ request()->search }}">
                    </form>
                </div>
                <div class="col-2 text-end">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#tambah">
                        Tambah Data
                    </button>
                </div>
            </div>
            @if (Session::has('pesan'))
                <div class="alert {{ Session::get('alert-class') }} alert-dismissible fade show" role="alert">
                    {{ Session::get('pesan') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            <div class="card">
                <div class="card-body">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th scope="col">No.</th>
                                <th scope="col">Tasklist</th>
                                <th scope="col">Description</th>
                                <th scope="col">Deadline</th>
                                <th scope="col">Status</th>
                                <th scope="col">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($tasks as $key => $task)
                                <tr>
                                    <td>{{ $tasks->firstItem() + $key }}.</td>
                                    <td>{{ ucwords($task->title) }}</td>
                                    <td>{{ ucwords($task->description) }}</td>
                                    <td>{{ ucwords($task->deadline) }}</td>
                                    <td>{{ ucwords($task->status) }}</td>
                                    <td>
                                        <div class="dropdown">
                                            <i class="fas fa-ellipsis-h" data-bs-toggle="dropdown"
                                                aria-expanded="false"></i>
                                            <ul class="dropdown-menu">
                                                <li>
                                                    <form
                                                        action="{{ route('task.updateStatus', ['task' => $task->id, 'status' => 'pending']) }}"
                                                        method="POST">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" class="dropdown-item">Ubah ke
                                                            Pending</button>
                                                    </form>
                                                </li>
                                                <li>
                                                    <form
                                                        action="{{ route('task.updateStatus', ['task' => $task->id, 'status' => 'in-progress']) }}"
                                                        method="POST">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" class="dropdown-item">Ubah ke
                                                            In-Progress</button>
                                                    </form>
                                                </li>
                                                <li>
                                                    <form
                                                        action="{{ route('task.updateStatus', ['task' => $task->id, 'status' => 'completed']) }}"
                                                        method="POST">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" class="dropdown-item">Ubah ke
                                                            Completed</button>
                                                    </form>
                                                </li>
                                                <li><a class="dropdown-item" href="#" data-bs-toggle="modal"
                                                        data-bs-target="#edit{{ $task->id }}">Edit data</a></li>
                                                <li>
                                                    <form action="{{ route('task.destroy', ['task' => $task->id]) }}"
                                                        method="POST"
                                                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="dropdown-item">Hapus
                                                            data</button>
                                                    </form>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{ $tasks->links() }}
                </div>
            </div>
        </div>
    </section>

    {{-- modal tambah data --}}
    <div class="modal fade" id="tambah" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="tambahLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="tambahLabel">Tambah Tasklist</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('task.store') }}" method="post">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="title" class="form-label">Tasklist</label>
                            <input type="text" class="form-control" id="title" name="title"
                                placeholder="Masukan judul tasklist" required>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" placeholder="Masukan deskripsi tasklist"
                                rows="3" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="deadline" class="form-label">Deadline</label>
                            <input type="date" class="form-control" id="deadline" name="deadline"
                                placeholder="Masukan judul tasklist" required>
                        </div>
                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-select" id="status" name="status" required>
                                <option value="" disabled selected>Pilih</option>
                                <option value="pending">Pending</option>
                                <option value="in-progress">In-Progress</option>
                                <option value="completed">Completed</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Kembali</button>
                        <button type="submit" class="btn btn-primary">Tambah Data</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- modal edit data --}}
    @foreach ($tasks as $key => $task)
        <div class="modal fade" id="edit{{ $task->id }}" data-bs-backdrop="static" data-bs-keyboard="false"
            tabindex="-1" aria-labelledby="editLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="editLabel">Edit Tasklist</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('task.update', $task->id) }}" method="post">
                        @csrf
                        @method('PATCH')
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="title" class="form-label">Tasklist</label>
                                <input type="text" class="form-control" id="title" name="title"
                                    value="{{ old('title', $task->title) }}" placeholder="Masukkan judul tasklist">
                            </div>
                            <div class="mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control" id="description" name="description" placeholder="Masukkan deskripsi tasklist"
                                    rows="3">{{ old('description', $task->description) }}</textarea>
                            </div>
                            <div class="mb-3">
                                <label for="deadline" class="form-label">Deadline</label>
                                <input type="date" class="form-control" id="deadline" name="deadline"
                                    value="{{ old('deadline', \Carbon\Carbon::parse($task->deadline)->toDateString()) }}">
                            </div>
                            <div class="mb-3">
                                <label for="status" class="form-label">Status</label>
                                <select class="form-select" id="status" name="status">
                                    <option value="pending" {{ $task->status == 'pending' ? 'selected' : '' }}>Pending
                                    </option>
                                    <option value="in-progress" {{ $task->status == 'in-progress' ? 'selected' : '' }}>
                                        In-Progress</option>
                                    <option value="completed" {{ $task->status == 'completed' ? 'selected' : '' }}>
                                        Completed</option>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Kembali</button>
                            <button type="submit" class="btn btn-primary">Edit Data</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach

@endsection

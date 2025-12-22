@extends('layouts.admin')

@section('content')
<div class="container">
    <h1>Импорт каталога (1 файл)</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $e)
                    <li>{{ $e }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.import.catalog.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label class="form-label">Файл (xlsx)</label>
            <input class="form-control" type="file" name="file" required>
        </div>
        <button class="btn btn-primary">Импортировать</button>
    </form>
</div>
@endsection

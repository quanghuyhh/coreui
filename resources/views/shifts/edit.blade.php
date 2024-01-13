@extends('layout.admin')

@section('main')
    <div class="card mb-4">
        <div class="card-header d-flex align-items-center justify-content-between">
            <strong>時間枠編集</strong>
        </div>
        <div class="card-body">
            @livewire('admin.shifts.create-form', ['shiftId' => $id])
        </div>
    </div>
@endsection

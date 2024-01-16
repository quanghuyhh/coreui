@extends('layout.admin')

@section('main')
    @livewire('admin.shifts.edit-shift-form', ['shiftId' => $id])
@endsection

@extends('layout.admin')

@section('main')
    <div class="card mb-4">
        <div class="card-header d-flex align-items-center justify-content-between">
            <strong>シフト管理</strong>
            <a href="{{ route('admin.shifts.create') }}" class="btn btn-primary">新規作成</a>
        </div>
        <div class="card-body">
            <table class="table">
                <thead>
                <tr>
                    <th scope="col">年月</th>
                    <th scope="col">ステータス</th>
                    <th scope="col">_</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <th scope="row">1</th>
                    <td>Mark</td>
                    <td>Otto</td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
@endsection

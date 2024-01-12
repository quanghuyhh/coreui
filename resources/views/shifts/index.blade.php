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
                @foreach($shifts as $shift)
                    <tr>
                        <th scope="row">{{ $shift->month }}</th>
                        <td>{{ $shift->status }}</td>
                        <td>
                            <a class="btn btn-primary btn-sm mx-1" role="button" href="/admin/shift/edit-shift.html">シフト編集</a>
                            <a class="btn btn-primary btn-sm mx-1" role="button" href="/admin/shift/edit-time.html">時間枠編集</a>
                            <a class="btn btn-primary btn-sm mx-1" role="button" href="/admin/shift/view-shift.html">表示</a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection

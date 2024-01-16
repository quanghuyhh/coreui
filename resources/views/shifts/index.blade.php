@extends('layout.admin')

@section('main')
    <div class="card mb-4">
        <div class="card-header d-flex align-items-center justify-content-between">
            <strong>シフト管理</strong>
            <a href="{{ route('admin.shifts.create') }}" class="btn btn-primary">新規作成</a>
        </div>
        <div class="card-body">

            @livewire('admin.common.alert')

            <table class="table">
                <thead>
                <tr>
                    <th scope="col">年月</th>
                    <th scope="col">ステータス</th>
                    <th scope="col" style="text-align: end">
                        _
                    </th>
                </tr>
                </thead>
                <tbody>
                @foreach($shifts as $shift)
                    <tr>
                        <th scope="row">{{ $shift->month->format('Y-m') }}</th>
                        <td>
                            @if($shift->status === \App\Enums\ShiftStatusEnum::IN_PROGRESS->value)
                                <span class="badge bg-success">作成中</span>
                            @else
                                <span class="badge bg-primary">公開済み</span>
                            @endif
                        </td>
                        <td class="text-end">
                            <a class="btn btn-primary btn-sm mx-1" role="button" href="{{ route('admin.shifts.editShift', ['id' => $shift->id]) }}">シフト編集</a>
                            <a class="btn btn-primary btn-sm mx-1" role="button" href="{{ route('admin.shifts.edit', ['shift' => $shift->id]) }}">時間枠編集</a>
                            <a class="btn btn-primary btn-sm mx-1" role="button" href="/admin/shift/view-shift.html">表示</a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection

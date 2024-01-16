<div class="card mb-4">
    <div class="card-header d-flex align-items-center justify-content-between">
        <strong>シフト作成 ({{ \Carbon\Carbon::parse($shift['month'])->format('Y年m月') }})</strong>
    </div>
    <div class="card-body">
        @livewire('admin.common.alert')
        @error('common')
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ $message }}
            <button type="button" class="btn-close" data-coreui-dismiss="alert" aria-label="Close"></button>
        </div>
        @enderror

        <div class="table-responsive">
            <table class="table">
                <thead>
                <tr>
                    <th></th>
                    @foreach($shift['data']['times'] as $time)
                    <th class="text-center"><span>{{ $time['start'] }}〜{{ $time['end'] }}</span></th>
                    @endforeach
                    <th class="text-center"><span>メモ</span></th>
                </tr>
                </thead>
                <tbody>
                    @foreach($shift['data']['days'] as $index => $day)
                        @php
                            $slotDay = $day['day'];
                            $shiftSlots = $shift['data']['shift-slots'][$slotDay] ?? [];
                        @endphp
                        <tr>
                            <td>
                                <span>{{ \Carbon\Carbon::parse($day['day'])->format('d') }} {{ jp_day_of_week(\Carbon\Carbon::parse($day['day'])->format('w')) }}</span>
                            </td>
                            @foreach($shiftSlots as $slotTime => $status)
                                @php
                                    $appliedTeachers = $availableTeachers[$slotDay][$slotTime] ?? [];
                                @endphp
                                <td class="text-center">
                                    @if($status)
                                        <select class="form-control" wire:model="shiftTeachers.{{$slotDay}}.{{$slotTime}}">
                                            <option value="">--- 講師選択 --</option>
                                            @foreach($appliedTeachers as $teacher)
                                            <option
                                                value="{{ $teacher['id'] }}"
                                                @if(!empty($shiftTeachers[$slotDay][$slotTime])) selected @endif
                                            >{{ $teacher['name'] }}</option>
                                            @endforeach
                                        </select>
                                    @endif
                                </td>
                            @endforeach
                            <td class="text-center"><input type="text" class="form-control" wire:model="shiftDates.{{$index}}.note"></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="card-footer">
        <div class="text-right d-flex justify-content-end">
            <div class="form-check form-switch form-check-inline text-start">
                <input class="form-check-input" type="checkbox" id="formCheck-1" wire:model="isPublic">
                <label class="form-check-label" for="formCheck-1">公開する</label>
            </div>
            <a class="btn btn-primary btn-sm" role="button" wire:click.prevent="onSave">保存する</a>
        </div>
    </div>
</div>

@script
<script type="text/javascript">
    window.addEventListener('reload', () => {
        window.location.reload()
    })
</script>
@endscript


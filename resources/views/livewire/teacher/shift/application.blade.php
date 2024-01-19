<div class="card mb-4">
    <div class="card-header d-flex align-items-center justify-content-between">
        <div class="col-12 col-md-4">
            <select
                class="form-control"
                wire:change="onChangeMonth($event.target.value)"
            >
                @foreach($shifts as $shift)
                <option value="{{ $shift['id'] }}" @if($shift['id'] === $selectedShift) selected @endif>{{ \Carbon\Carbon::parse($shift['month'])->format('Y年m月') }}</option>
                @endforeach
            </select>
        </div>
        <button class="btn btn-primary" type="button" wire:click.prevent="onSave">申請</button>
    </div>
    <div class="card-body">
        @livewire('admin.common.alert')
        @error('common')
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ $message }}
            <button type="button" class="btn-close" data-coreui-dismiss="alert" aria-label="Close"></button>
        </div>
        @enderror

        @if(!empty($selectedShift))
            @php
                $shift = collect($shifts)->firstWhere('id', $selectedShift);
            @endphp
        <div class="table-responsive">
            <table class="table">
                <thead>
                <tr>
                    <th></th>
                    @foreach($shift['data']['times'] as $time)
                    <th class="text-center">{{ $time['start'] }}〜{{ $time['end'] }}</th>
                    @endforeach
                </tr>
                </thead>
                <tbody>
                @foreach($shift['data']['days'] as $day)
                    @php
                        $slotDay = $day['day'];
                        $shiftSlots = $shift['data']['shift-slots'][$slotDay] ?? [];
                    @endphp
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($day['day'])->locale('ja_JP')->format('d D') }}</td>
                        @foreach($shiftSlots as $slotTime => $status)
                            <td class="text-center">
                                @if($status)
                                    <input type="checkbox"
                                           wire:click="onCheckboxChecked('{{ $slotDay }}', '{{ $slotTime }}', $event.target.checked)"
                                           @if(!empty($applied[$slotDay][$slotTime])) checked @endif
                                    >
                                @endif
                            </td>
                        @endforeach
                    </tr>
                @endforeach
                </tbody>
            </table>
            @endif
        </div>
    </div>
</div>

@script
<script type="text/javascript">
    window.addEventListener('reload', () => {
        location.reload()
    })
    window.addEventListener('uncheck',() => {
        document.querySelector('.alert').remove();
    })
</script>
@endscript

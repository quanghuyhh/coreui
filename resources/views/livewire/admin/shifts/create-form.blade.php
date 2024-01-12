<div class="card-body">
    <div class="row mb-2">
        <div class="col text-start col-4">
            <input class="form-control" type="month" wire:model="month"/>
            @error('month') {{ $message }}@enderror
        </div>
        <div class="col text-start">
            <div></div>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table ">
            <thead>
            <tr>
                <th></th>
                <th class="text-center"></th>
                @foreach($availableTimes as $times)
                    <th class="text-center">
                        <span>{{ $times['start'] }}〜{{ $times['end'] }}</span>
                        <button class="btn btn-sm mx-1" type="button" data-bs-target="#modal-1" data-bs-toggle="modal">
                            <svg class="icon">
                                <use xlink:href="{{ asset('/assets/admin/vendors/@coreui/icons/svg/free.svg#cil-pencil') }}"></use>
                            </svg>
                        </button>
                        <div><input type="checkbox" id="s-col-1"></div>
                    </th>
                @endforeach
                <th class="text-center">
                    <button class="btn btn-primary btn-sm" type="button" data-coreui-toggle="modal" data-coreui-target="#modal-time">
                        <svg class="icon">
                            <use xlink:href="{{ asset('/assets/admin/vendors/@coreui/icons/svg/free.svg#cil-plus') }}"></use>
                        </svg>
                        &nbsp;時間帯を追加
                    </button>
                </th>
            </tr>
            </thead>
            <tbody>
            @foreach($availableDates as $date)
                <tr>
                    <td>
                        <span>{{ $date }}</span>
                        <button class="btn btn-sm mx-1" type="button" data-bs-target="#modal-2" data-bs-toggle="modal">
                            <svg class="icon">
                                <use xlink:href="{{ asset('/assets/admin/vendors/@coreui/icons/svg/free.svg#cil-calendar') }}"></use>
                            </svg>
                        </button>
                    </td>
                    <td class="text-center"><input type="checkbox" id="s-row-1" class="s-row check-all"></td>
                    @foreach($availableTimes as $time)
                        <td class="text-center"><input type="checkbox" class="s-col-1 s-row-1"></td>
                    @endforeach
                    <td class="text-center"></td>
                </tr>
            @endforeach

            </tbody>
        </table>
    </div>
    <div class="row pb-3">
        <div class="col text-start">
            <button class="btn btn-primary btn-sm" type="button" data-coreui-toggle="modal" data-coreui-target="#modal-date">
                <svg class="icon">
                    <use xlink:href="{{ asset('/assets/admin/vendors/@coreui/icons/svg/free.svg#cil-plus') }}"></use>
                </svg>
                &nbsp;日付を追加
            </button>
        </div>
    </div>

    <div class="card-footer bg-transparent pt-3 d-flex align-items-center justify-content-end">
        <button type="button" class="btn btn-primary btn-sm" role="button" wire:click="saveShift">保存する</button>
    </div>

    <div class="modal fade" role="dialog" tabindex="-1" id="modal-time" aria-modal="true" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">時間帯編集</h4><button class="btn-close" type="button" aria-label="Close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="mb-2"><label class="form-label">開始時間</label>
                            <span class="badge bg-danger mx-1">必須</span>
                            <input class="form-control" type="time" value="13:00" wire:model="timeStartToAdd">
                        </div>
                        <div class="mb-2"><label class="form-label">終了時間</label>
                            <span class="badge bg-danger mx-1">必須</span>
                            <input class="form-control" type="time" value="15:00" wire:model="timeEndToAdd">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-light" type="button" data-bs-dismiss="modal">キャンセル</button>
                    <button class="btn btn-primary" type="button" wire:click="addTime">反映する</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" tabindex="-1" id="modal-date" aria-hidden="true" wire:ignore>
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">日付編集</h4><button class="btn-close" type="button" aria-label="Close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="mb-2">
                            <label class="form-label">日にち</label>
                            <span class="badge bg-danger mx-1">必須</span>
                            <input class="form-control" type="date" value="" wire:model="dateToAdd">
                            @error('date_add'){{ $message }}@enderror
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-light" type="button" data-bs-dismiss="modal">キャンセル</button>
                    <button class="btn btn-primary" type="button" wire:click.prevent="addDate">反映する</button>
                </div>
            </div>
        </div>
    </div>
</div>

@script
<script type="text/javascript">
    const modalDate = new coreui.Modal('#modal-date', {
        keyboard: false
    })

    const modalTime = new coreui.Modal('#modal-time', {
        keyboard: false
    })

    document.addEventListener('hideModal', (evt) => {
        modalDate.hide();
        modalTime.hide();
    })
</script>
@endscript

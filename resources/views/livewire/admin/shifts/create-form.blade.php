<div class="card-body">
    @livewire('admin.common.alert')
    @error('common')
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ $message }}
            <button type="button" class="btn-close" data-coreui-dismiss="alert" aria-label="Close"></button>
        </div>
    @enderror
    <div class="row mb-2">
        <div class="col text-start col-4">
            <input class="form-control" type="month" wire:model="month"/>
            @error('month') <span class="text-danger">{{ $message }}</span> @enderror
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
                @foreach($this->sorted_time as $times)
                    @php
                        $timeKey = sprintf("%s-%s", $times['start'], $times['end'])
                    @endphp
                    <th class="text-center">
                        <span>{{ $times['start'] }}〜{{ $times['end'] }}</span>
                        <button class="btn btn-sm mx-1" type="button" wire:click.prevent="openEditTime('{{ $timeKey }}')">
                            <svg class="icon">
                                <use xlink:href="{{ asset('/assets/admin/vendors/@coreui/icons/svg/free.svg#cil-pencil') }}"></use>
                            </svg>
                        </button>
                        <div>
                            <input
                                type="checkbox"
                                class="checkAllCol"
                                data-time="{{ $timeKey }}"
                                wire:click="onCheckAllCol('{{ $timeKey }}', $event.target.checked)"
                            />
                        </div>
                    </th>
                @endforeach
                <th class="text-end">
                    <button class="btn btn-primary btn-sm" type="button" wire:click.prevent="showModal('#modal-add-time')">
                        <svg class="icon">
                            <use xlink:href="{{ asset('/assets/admin/vendors/@coreui/icons/svg/free.svg#cil-plus') }}"></use>
                        </svg>
                        &nbsp;時間帯を追加
                    </button>
                </th>
            </tr>
            </thead>
            <tbody>
            @foreach($this->sorted_date as $date)
                <tr wire:key="{{ $date }}">
                    <td>
                        <span>{{ $date }}</span>
                        <button class="btn btn-sm mx-1" type="button" wire:click.prevent="openEditDate('{{ $date }}')">
                            <svg class="icon">
                                <use xlink:href="{{ asset('/assets/admin/vendors/@coreui/icons/svg/free.svg#cil-pencil') }}"></use>
                            </svg>
                        </button>
                    </td>
                    <td class="text-center">
                        <input
                            type="checkbox"
                            class="checkAllRow"
                            data-date="{{ $date }}"
                            wire:click="onCheckAllRow('{{ $date }}', $event.target.checked)"
                        />
                    </td>
                    @foreach($this->sorted_time as $inner_times)
                        @php
                            $innerTimeKey = sprintf("%s-%s", $inner_times['start'], $inner_times['end'])
                        @endphp
                        <td class="text-center">
                            <input
                                type="checkbox"
                                class="s-col-1 s-row-1 check-item"
                                data-date="{{ $date }}"
                                data-time="{{ $innerTimeKey }}"
                                wire:key="{{ $date }}-{{ $innerTimeKey }}"
                                wire:click="onClickSlot('{{ $date }}', '{{ $innerTimeKey }}')"
                                @if(!empty($this->slot_result[$date][$innerTimeKey])) checked @endif
                            >
                        </td>
                    @endforeach
                    <td class="text-center"></td>
                </tr>
            @endforeach

            </tbody>
        </table>
    </div>
    <div class="row pb-3">
        <div class="col text-start">
            <button class="btn btn-primary btn-sm" type="button" wire:click.prevent="showModal('#modal-add-date')">
                <svg class="icon">
                    <use xlink:href="{{ asset('/assets/admin/vendors/@coreui/icons/svg/free.svg#cil-plus') }}"></use>
                </svg>
                &nbsp;日付を追加
            </button>
        </div>
    </div>

    <div class="card-footer bg-transparent pt-3 d-flex align-items-center justify-content-end">
        @if(empty($shiftId))
            <button type="button" class="btn btn-primary btn-sm" role="button" wire:click="saveShift">保存する</button>
        @else
            <button type="button" class="btn btn-primary btn-sm" role="button" wire:click="updateShift">保存する</button>
        @endif
    </div>

    <div class="modal fade" role="dialog" tabindex="-1" id="modal-add-time" aria-modal="true" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">時間帯編集</h4>
                    <button class="btn-close" type="button" wire:click.prevent="$dispatch('hideModal')"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="mb-2"><label class="form-label">開始時間</label>
                            <span class="badge bg-danger mx-1">必須</span>
                            <input class="form-control @error('timeStartToAdd') is-invalid @enderror" type="time" wire:model="timeStartToAdd">
                            @error('timeStartToAdd') @livewire('admin.common.field-error', ['message' => $message]) @enderror
                        </div>
                        <div class="mb-2"><label class="form-label">終了時間</label>
                            <span class="badge bg-danger mx-1">必須</span>
                            <input class="form-control @error('timeEndToAdd') is-invalid @enderror" type="time" wire:model="timeEndToAdd">
                            @error('timeEndToAdd') @livewire('admin.common.field-error', ['message' => $message]) @enderror
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-light" type="button" wire:click.prevent="$dispatch('hideModal')">キャンセル</button>
                    <button class="btn btn-primary" type="button" wire:click="onAddTime">反映する</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" role="dialog" tabindex="-1" id="modal-update-time" aria-modal="true" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">時間帯編集</h4>
                    <button class="btn-close" type="button" wire:click.prevent="$dispatch('hideModal')"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="mb-2"><label class="form-label">開始時間</label>
                            <span class="badge bg-danger mx-1">必須</span>
                            <input class="form-control @error('timeStartToUpdate') is-invalid @enderror" type="time" wire:model="timeStartToUpdate">
                            @error('timeStartToUpdate') @livewire('admin.common.field-error', ['message' => $message]) @enderror
                        </div>
                        <div class="mb-2"><label class="form-label">終了時間</label>
                            <span class="badge bg-danger mx-1">必須</span>
                            <input class="form-control @error('timeEndToUpdate') is-invalid @enderror" type="time" wire:model="timeEndToUpdate">
                            @error('timeEndToUpdate') @livewire('admin.common.field-error', ['message' => $message]) @enderror
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-light" type="button" wire:click.prevent="$dispatch('hideModal')">キャンセル</button>
                    <button class="btn btn-primary" type="button" wire:click="onUpdateTime">反映する</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" tabindex="-1" id="modal-add-date" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">日付編集</h4>
                    <button class="btn-close" type="button" wire:click.prevent="$dispatch('hideModal')"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="mb-2">
                            <label class="form-label">日にち</label>
                            <span class="badge bg-danger mx-1">必須</span>
                            <input class="form-control @error('date_add') is-invalid @enderror" type="date" value="" wire:model="dateToAdd">
                            @error('date_add') @livewire('admin.common.field-error', ['message' => $message]) @enderror
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-light" type="button" wire:click.prevent="$dispatch('hideModal')">キャンセル</button>
                    <button class="btn btn-primary" type="button" wire:click.prevent="onAddDate">反映する</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" tabindex="-1" id="modal-update-date" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">日付編集</h4>
                    <button class="btn-close" type="button" wire:click.prevent="$dispatch('hideModal')"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="mb-2">
                            <label class="form-label">日にち</label>
                            <span class="badge bg-danger mx-1">必須</span>
                            <input class="form-control @error('date_update') is-invalid @enderror" type="date" value="" wire:model="dateToUpdate">
                            @error('date_update') @livewire('admin.common.field-error', ['message' => $message]) @enderror
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-light" type="button" wire:click.prevent="$dispatch('hideModal')">キャンセル</button>
                    <button class="btn btn-primary" type="button" wire:click.prevent="onUpdateDate">反映する</button>
                </div>
            </div>
        </div>
    </div>
</div>

@script
<script type="text/javascript">
    let allModals = []
    window.addEventListener('DOMContentLoaded', () => {
        $wire.on('showModal', (evt) => {
            let modal = new coreui.Modal(document.querySelector(evt.target), {})
            modal.show();
        })

        $wire.on('hideModal', (evt) => {
            let modals = document.querySelectorAll('.modal')
            modals.forEach((modal) => {
                modal.classList.remove('show');
                modal.removeAttribute('style')
            })
            let backdrops = document.querySelectorAll('.modal-backdrop');
            backdrops.forEach((backdrop) => {
                backdrop.remove()
            })

            const body = document.body;
            body.classList.remove('modal-open');
            body.removeAttribute('style');
        })

        $wire.on('forceCheckedRow', (evt) => {
            let items = document.querySelectorAll(`.check-item[data-date="${evt.date}"]`);
            items.forEach((item) => {
                item.checked = evt.checked
            })
        })

        $wire.on('forceCheckedCol', (evt) => {
            let items = document.querySelectorAll(`.check-item[data-time="${evt.time}"]`);
            items.forEach((item) => {
                item.checked = evt.checked
            })
        })

        $wire.on('markCheckAllRow', (evt) => {
            setTimeout(() => {
                let items = document.querySelectorAll(`.checkAllRow[data-date="${evt.date}"]`);
                items.forEach((item) => {
                    console.log('markCheckAllRow', {item})
                    item.checked = evt.checked
                })
            }, 500)
        })

        $wire.on('markCheckAllCol', (evt) => {
            setTimeout(() => {
                let items = document.querySelectorAll(`.checkAllCol[data-time="${evt.time}"]`);
                items.forEach((item) => {
                    item.checked = evt.checked
                })
            }, 500)
        })
    })
</script>
@endscript

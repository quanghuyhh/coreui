<div class="">
    <div class="row mb-2">
        <div class="col">
            <select class="form-control" wire:change="onChangeFilterStatus($event.target.value)">
                <optgroup label="ステータス">
                    <option value="0" selected="">All</option>
                    <option value="1">承認待ち</option>
                    <option value="2">承認済み</option>
                    <option value="3">差し戻し</option>
                </optgroup>
            </select></div>
        <div class="col"></div>
        <div class="col"></div>
        <div class="col"></div>
    </div>
    <div class="card mb-2">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                    <tr>
                        <th>日付</th>
                        <th>ステータス</th>
                        <th>講師</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($items as $item)
                        @php
                            $itemStatusClass = match ($item['status']) {
                                \App\Enums\WorkTimeStatus::APPROVED->value => 'bg-dark',
                                \App\Enums\WorkTimeStatus::IN_PROGRESS->value => 'bg-primary',
                                \App\Enums\WorkTimeStatus::NEED_FIX->value => 'bg-danger',
                                default => 'bg-danger',
                            };

                            $itemStatus = match ($item['status']) {
                                \App\Enums\WorkTimeStatus::APPROVED->value => '承認済み',
                                \App\Enums\WorkTimeStatus::IN_PROGRESS->value => '承認待ち',
                                \App\Enums\WorkTimeStatus::NEED_FIX->value => '差し戻し',
                                default => '差し戻し',
                            }
                        @endphp
                    <tr>
                        <td>2023-01-01</td>
                        <td><span class="badge {{ $itemStatusClass }}">{{ $itemStatus }}</span></td>
                        <td>{{ $item['teacher']['name'] ?? '-' }}</td>
                        <td class="text-end">
                            <a class="btn btn-primary btn-sm" role="button" href="{{ route('admin.times.edit', ['time' => $item['id']]) }}">詳細</a>
                        </td>
                    </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @if(!empty($paginator['linkCollection']))
    <nav>
        <ul class="pagination">
            @foreach($paginator['linkCollection'] as $item)
            <li class="page-item">
                <a class="page-link {{ $item['active'] ?? '' }}" aria-label="Previous" href="{{ $item['url'] ?? 'javascript:void(0);' }}">
                    <span aria-hidden="true">{!! $item['label'] ?? '' !!}</span>
                </a>
            </li>
            @endforeach
        </ul>
    </nav>
    @endif
</div>

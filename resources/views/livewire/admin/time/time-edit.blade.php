<div class="card mb-2">
    <div class="card-body">
        @livewire('admin.common.alert')
        @error('common')
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {!! $message !!}
            <button type="button" class="btn-close" data-coreui-dismiss="alert" aria-label="Close"></button>
        </div>
        @enderror

        @if(!empty($item))
        <div class="mb-2">
            <div class="table-responsive">
                <table class="table">
                    <tbody>
                    <tr>
                        <td class="item-title">共通</td>
                        <td class="item-title">日付</td>
                        <td>{{ $item['date']?? '' }}</td>
                    </tr>
                    <tr>
                        <td class="item-title"></td>
                        <td class="item-title">講師</td>
                        <td>{{ $item['teacher']['name'] ?? '' }}</td>
                    </tr>
                    <tr>
                        <td class="item-title">交通費</td>
                        <td class="item-title">交通費種別</td>
                        <td>デフォルト経路（渋谷〜池尻大橋）</td>
                    </tr>
                    <tr>
                        <td class="item-title"></td>
                        <td class="item-title">交通費</td>
                        <td>280円</td>
                    </tr>
                    <tr>
                        <td class="item-title">シフト事務</td>
                        <td class="item-title">開始時刻</td>
                        <td>{{ $this->parseTimeValue($item['shift_start_time'] ?? '') }}</td>
                    </tr>
                    <tr>
                        <td class="item-title"></td>
                        <td class="item-title">終了時刻</td>
                        <td>{{ $this->parseTimeValue($item['shift_end_time'] ?? '') }}</td>
                    </tr>
                    <tr>
                        <td class="item-title"></td>
                        <td class="item-title">休憩</td>
                        <td>{{ $this->parseTimeValue($item['shift_break_time'] ?? '') }}</td>
                    </tr>
                    <tr>
                        <td class="item-title">シフト外事務</td>
                        <td class="item-title">質問対応</td>
                        <td>{{ $this->parseTimeValue($item['off_shift_qa_time'] ?? '') }}</td>
                    </tr>
                    <tr>
                        <td class="item-title"></td>
                        <td class="item-title">特訓超過</td>
                        <td>{{ $this->parseTimeValue($item['off_shift_training_time'] ?? '') }}</td>
                    </tr>
                    <tr>
                        <td class="item-title"></td>
                        <td class="item-title">当欠事務</td>
                        <td>{{ $this->parseTimeValue($item['off_shift_absent_time'] ?? '') }}</td>
                    </tr>
                    <tr>
                        <td class="item-title"></td>
                        <td class="item-title">追加事務</td>
                        <td>{{ $this->parseTimeValue($item['off_shift_additional_time'] ?? '') }}</td>
                    </tr>
                    <tr>
                        <td class="item-title"></td>
                        <td class="item-title">追加事務の休憩</td>
                        <td>{{ $this->off_shift_break_time_type }}</td>
                    </tr>
                    <tr>
                        <td class="item-title"></td>
                        <td class="item-title">ブログURL</td>
                        <td><a href="#">{{ $item['off_shift_blog_url'] ?? '' }}</a></td>
                    </tr>
                    <tr>
                        <td class="item-title"></td>
                        <td class="item-title">備考</td>
                        <td>{!! $item['off_shift_notes'] ?? '' !!}</td>
                    </tr>
                    <tr>
                        <td class="item-title">深夜事務</td>
                        <td class="item-title">特訓時間種別</td>
                        <td>{{ $this->training_type }}</td>
                    </tr>
                    <tr>
                        <td class="item-title"></td>
                        <td class="item-title">特訓時間</td>
                        <td>{{ $this->parseTimeValue($item['training_time'] ?? '') }}</td>
                    </tr>
                    <tr>
                        <td class="item-title">備考</td>
                        <td class="item-title">備考</td>
                        <td>{!! $item['note'] ?? '' !!}</td>
                    </tr>
                    <tr></tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="row">
            <div class="col col-2">
                <select class="form-control" wire:model="status" wire:change="changeStatusValue($event.target.value)">
                    <option value="1" selected="">承認待ち</option>
                    <option value="2">承認済み</option>
                    <option value="3">差し戻し</option>
                </select>
            </div>
            <div class="col col-8">
                <input type="text" class="form-control" wire:model="message" placeholder="差し戻しの場合にコメントを記載してください">
            </div>
            <div class="col col-2">
                <button class="btn btn-primary" type="button" wire:click.prevent="onSave">更新する</button>
            </div>
        </div>
        @endif
    </div>
</div>

<div class="container-fluid text-nowrap text-start">
    @php
        $canEdit = $this->canEdit;
    @endphp
    @livewire('admin.common.alert')
    @error('common')
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {!! $message !!}
        <button type="button" class="btn-close" data-coreui-dismiss="alert" aria-label="Close"></button>
    </div>
    @enderror

    @if(!empty($needFixs))
    <div class="alert alert-danger" role="alert">
        @foreach($needFixs as $needFixDate)
            <a class="alert-link" href="{{ route('teacher.time.edit', ['date' => $needFixDate]) }}">{{ \Carbon\Carbon::parse($needFixDate)->format('Y年m月d') }}日の勤怠の修正依頼があります</a>
            <br>
        @endforeach
    </div>
    @endif
    <div class="card mb-2">
        <div class="card-body">
                <div class="text-start mb-3">
                    <label class="form-label" for="application_date">日付</label>
                    <span class="badge bg-danger mx-1">必須</span>
                    <div>
                        <span class="description">申請した日付ではなく、稼働で発生した日付を入力してください。</span>
                    </div>
                    <input class="form-control @error('date') is-invalid @enderror"
                           type="date" id="application_date"
                           wire:model="date"
                           wire:change="onChangeDate($event.target.value)"
                    >
                    @error('date') <p class="fw-light text-danger fs-6 invalid-feedback">{{ $message }}</p> @enderror
                </div>
        </div>
    </div>
    <div class="text-start mb-1">
        <div class="form-check form-switch form-check-inline">
            <input class="form-check-input" type="checkbox"
                   id="transportation-fee-check"
                   name="transportation-fee-check"
                   wire:model="use_transportation_expense"
                   wire:click="toggleCheckbox('use_transportation_expense', $event.target.checked)"
                   @if(!$canEdit) disabled @endif
            />
            <label class="form-check-label" for="transportation-fee-check">交通費の申請をする</label>
        </div>
    </div>
    <div id="transportation-fee" @if(empty($use_transportation_expense)) style="display: none;" @endif>
        <div class="card mb-2">
            <div class="card-body">
                    <div class="text-start mb-3">
                        <label class="form-label">交通費</label>
                        <span class="badge bg-danger mx-1">必須</span>
                        <select class="form-select mb-2 @error('transportation_expense_type') is-invalid @enderror"
                                id="price"
                                wire:model="transportation_expense_type"
                                @if(!$canEdit) disabled @endif
                                wire:change="changeSelectValue('transportation_expense_type', $event.target.value)"
                        >
                            <option value="0" selected="">なし</option>
                            <option value="1">渋谷〜池尻大橋（280円）</option>
                            <option value="2">その他</option>
                        </select>
                        @error('transportation_expense_type') <p class="fw-light text-danger fs-6 invalid-feedback">{{ $message }}</p> @enderror
                        <input class="form-control no-display @error('transportation_expense') is-invalid @enderror" type="number" id="other-price"
                               wire:model="transportation_expense"
                               @if(!$canEdit) disabled @endif
                               @if($transportation_expense_type !==\App\Enums\TransportationExpenseType::OTHER->value)style="display: none;" @endif >
                        @error('transportation_expense') <p class="fw-light text-danger fs-6 invalid-feedback">{{ $message }}</p> @enderror
                    </div>
            </div>
        </div>
    </div>
    <div class="text-start mb-1">
        <div class="form-check form-switch form-check-inline">
            <input class="form-check-input" type="checkbox"
                   id="shift-check" wire:model="use_shift"
                   wire:click="toggleCheckbox('use_shift', $event.target.checked)"
                   @if(!$canEdit) disabled @endif
            >
            <label class="form-check-label" for="shift-check">シフト事務の申請をする</label>
        </div>
    </div>
    <div id="shift" class="no-display" @if(empty($use_shift)) style="display: none;" @endif>
        <div class="text-start">
            <span class="description">シフト事務→事前に校舎長から特訓以外の業務を指示されている事務作業。</span>
        </div>
        <div class="card mb-2">
            <div class="card-body">
                <div class="text-start mb-3">
                    <span class="description">シフトに記載があり、実施した事務は開始時刻と終了時刻を入れてください <br>※22時を過ぎた場合は、事務終了時刻に「実際の稼働終了時刻」を記入してください <br>例：13時～22時の9時間（うち1時間休憩）のシフトのところ、22：30まで伸びてしまった場合 <br>開始時刻　13:00 <br>終了時刻　22:30 <br>休憩時間　1:00 <br>※さらに、後の項目で深夜【00:30】を入力してください </span>
                </div>
                    <div class="text-start mb-3">
                        <label class="form-label">事務開始時刻</label>
                        <span class="badge bg-danger mx-1">必須</span>
                        <input class="form-control @error('shift_start_time') is-invalid @enderror"
                               type="time"
                               wire:model="shift_start_time"
                               @if(!$canEdit) disabled @endif
                        >
                        @error('shift_start_time') <p class="fw-light text-danger fs-6 invalid-feedback">{{ $message }}</p> @enderror
                    </div>
                    <div class="text-start mb-3">
                        <label class="form-label">事務開始終了</label>
                        <span class="badge bg-danger mx-1">必須</span>
                        <input class="form-control @error('shift_end_time') is-invalid @enderror"
                               type="time"
                               wire:model="shift_end_time"
                               @if(!$canEdit) disabled @endif
                        >
                        @error('shift_end_time') <p class="fw-light text-danger fs-6 invalid-feedback">{{ $message }}</p> @enderror
                    </div>
                    <div class="text-start mb-3">
                        <label class="form-label">休憩時間</label>
                        <span class="badge bg-danger mx-1">必須</span>
                        <div class="text-start">
                            <span class="description">シフト事務の勤務時間のうち、休憩時間を記入してください。</span>
                        </div>
                        <input class="form-control @error('shift_break_time') is-invalid @enderror"
                               type="time"
                               wire:model="shift_break_time"
                               @if(!$canEdit) disabled @endif
                        >
                        @error('shift_break_time') <p class="fw-light text-danger fs-6 invalid-feedback">{{ $message }}</p> @enderror
                    </div>
                    <div class="text-start mb-3">
                        <span class="description"> ★休憩時間について <br> シフト事務の勤務時間のうち、休憩時間を記載してください。 <br> ・6時間超え、8時間以下　の勤務で45分以上 <br> ・8時間超えの勤務で1時間以上 <br> が最低限の休憩時間です。 <br> 例１）13～22時でのシフト勤務の場合 <br> 事務開始時刻13：00 <br> 事務終了時刻22：00 <br> 休憩時間1：00 <br> 例２）13～20時でシフト勤務の場合 <br> 事務開始時刻13：00 <br> 事務終了時刻20：00 <br>休憩時間0：45 </span>
                    </div>
                    <div class="text-start mb-3">
            <span class="description">★シフト事務の深夜労働について（重要！） <br> 間違えると給与が少なく支給される可能性があるので気をつけてください。 <br>
              <br> シフト事務の労働時間が深夜に及んだ場合、深夜の時間も含めて労働時間を記載してください。 <br> 例）17:30〜22:15でシフト事務の申請をして、さらに、後の項目で深夜の時間を【00:15】で申請する </span>
                    </div>
            </div>
        </div>
    </div>
    <div class="text-start mb-1">
        <div class="form-check form-switch form-check-inline">
            <input class="form-check-input" type="checkbox" id="overwork-check"
                   wire:model="use_off_shift"
                   wire:click="toggleCheckbox('use_off_shift', $event.target.checked)"
                   @if(!$canEdit) disabled @endif
            >
            <label class="form-check-label" for="formCheck-3">シフト外事務の申請をする</label>
        </div>
    </div>
    <div id="overwork" class="no-display" @if(empty($use_off_shift)) style="display: none;" @endif>
        <div class="text-start">
            <span class="description">シフト外で発生した事務はこちらに入れてください</span>
        </div>
        <div class="card mb-2">
            <div class="card-body">
                    <div class="text-start mb-3">
                        <label class="form-label">質問対応</label>
                        <div class="text-start">
                            <span class="description">質問対応で稼働した時間数を記入（開始・終了時刻ではありません。） <br> 10分なら00:10と記載。（フォームは時間：分になっているので注意してください） </span>
                        </div>
                        <input class="form-control @error('off_shift_qa_time') is-invalid @enderror"
                               type="time"
                               wire:model="off_shift_qa_time"
                               @if(!$canEdit) disabled @endif
                        >
                        @error('off_shift_qa_time') <p class="fw-light text-danger fs-6 invalid-feedback">{{ $message }}</p> @enderror
                    </div>
                    <div class="text-start mb-3">
                        <label class="form-label">特訓超過</label>
                        <div class="text-start">
                            <span class="description">特訓を超過した分の事務の時間数を記入（開始・終了時刻ではありません。） <br> 10分なら00:10と記載。（フォームは時間：分になっているので注意してください） </span>
                        </div>
                        <input class="form-control @error('off_shift_training_time') is-invalid @enderror"
                               type="time"
                               wire:model="off_shift_training_time"
                               @if(!$canEdit) disabled @endif
                        >
                        @error('off_shift_training_time') <p class="fw-light text-danger fs-6 invalid-feedback">{{ $message }}</p> @enderror
                    </div>
                    <div class="text-start mb-3">
                        <label class="form-label">当欠事務</label>
                        <div class="text-start">
                            <span class="description"> 生徒の当欠により事務作業した分の時間数を記入（開始・終了時刻ではありません） <br> 10分なら00:10と記載。（フォームは時間：分になっているので注意してください） </span>
                        </div>
                        <input class="form-control @error('off_shift_absent_time') is-invalid @enderror"
                               type="time"
                               wire:model="off_shift_absent_time"
                               @if(!$canEdit) disabled @endif
                        >
                        @error('off_shift_absent_time') <p class="fw-light text-danger fs-6 invalid-feedback">{{ $message }}</p> @enderror
                    </div>
                    <div class="text-start mb-3">
                        <label class="form-label">追加事務</label>
                        <div class="text-start">
                            <span class="description"> 校舎長からシフト外で事務を依頼さ稼働した分のの時間数を記入（開始・終了時刻ではありません） <br> 10分なら00:10と記載。（フォームは時間：分になっているので注意してください） </span>
                        </div>
                        <input class="form-control @error('off_shift_additional_time') is-invalid @enderror"
                               type="time"
                               wire:model="off_shift_additional_time"
                               @if(!$canEdit) disabled @endif
                        >
                        @error('off_shift_additional_time') <p class="fw-light text-danger fs-6 invalid-feedback">{{ $message }}</p> @enderror
                    </div>
                    <div class="text-start mb-3">
                        <label class="form-label">追加事務の休憩</label>
                        <div class="text-start">
                            <span class="description">追加事務の労働時間には休憩時間を除いた稼働時間を入れてください。</span>
                        </div>
                        <div class="text-start mb-3">
                            <span class="description">校舎長からシフト外で事務を依頼さ稼働した分のの時間数を記入（開始・終了時刻ではありません） <br> 10分なら00:10と記載。（フォームは時間：分になっているので注意してください） </span>
                            <div class="form-check text-start">
                                <input class="form-check-input" type="radio"
                                       id="formCheck-4"
                                       name="additional-work"
                                       value="0"
                                       wire:model="off_shift_break_time_type"
                                       @if(empty($off_shift_break_time_type)) checked @endif
                                       wire:click="toggleCheckbox('off_shift_break_time_type', 0)"
                                       @if(!$canEdit) disabled @endif
                                >
                                <label class="form-check-label" for="formCheck-4">追加事務では休憩時間をとっていない</label>
                            </div>
                            <div class="form-check text-start">
                                <input class="form-check-input" type="radio" id="formCheck-1"
                                       name="additional-work"
                                       value="1"
                                       wire:model="off_shift_break_time_type"
                                       @if(!empty($off_shift_break_time_type)) checked @endif
                                       wire:click="toggleCheckbox('off_shift_break_time_type', 1)"
                                       @if(!$canEdit) disabled @endif
                                >
                                <label class="form-check-label" for="formCheck-1">追加事務には休憩時間を除いた時間を入力した</label>
                            </div>
                        </div>
                    </div>
                    <div class="text-start mb-3">
                        <label class="form-label">ブログURL</label>
                        <div class="text-start">
                            <span class="description">校舎長からの指示でブログを作成した場合、作成したブログのURL（GoogleドキュメントのURL、もしくはブログのURL)を貼り付け</span>
                        </div>
                        <input class="form-control @error('off_shift_blog_url') is-invalid @enderror"
                               type="url"
                               wire:model="off_shift_blog_url"
                               @if(!$canEdit) disabled @endif
                        >
                        @error('off_shift_blog_url') <p class="fw-light text-danger fs-6 invalid-feedback">{{ $message }}</p> @enderror
                    </div>
                    <div class="text-start mb-3">
                        <label class="form-label">備考</label>
                        <div class="text-start">
                            <span class="description">シフト外事務についての備考。シフト外事務の詳細を記載してください。 <br>※所属校舎以外の校舎でのシフト外事務の場合は【⚪︎⚪︎公でのシフト外事務稼働】と記載してください。 </span>
                        </div>
                        <textarea class="form-control @error('off_shift_notes') is-invalid @enderror"
                                  wire:model="off_shift_notes"
                                  @if(!$canEdit) disabled @endif
                        ></textarea>
                        @error('off_shift_notes') <p class="fw-light text-danger fs-6 invalid-feedback">{{ $message }}</p> @enderror
                    </div>
            </div>
        </div>
    </div>
    <div class="text-start mb-1">
        <div class="form-check form-switch form-check-inline">
            <input class="form-check-input" type="checkbox" id="night-check"
                   wire:model="use_night_work"
                   wire:click="toggleCheckbox('use_night_work', $event.target.checked)"
                   @if(!$canEdit) disabled @endif
            >
            <label class="form-check-label" for="night-check">深夜事務</label>
        </div>
    </div>
    <div id="night" class="no-display" @if(empty($use_night_work)) style="display: none;" @endif>
        <div class="text-start">
            <span class="description">事務で22時を過ぎた稼働時間があればこちらに入れてください</span>
        </div>
        <div class="card mb-2">
            <div class="card-body">
                    <div class="text-start mb-3">
                        <label class="form-label">深夜</label>
                        <div class="text-start">
                            <span class="description">事務で22時を過ぎた稼働時間があれば時間数を記入してください。 <br>例）22:10まで勤務したら00:10と記入 </span>
                        </div>
                        <input class="form-control @error('night_time') is-invalid @enderror"
                               type="time"
                               wire:model="night_time"
                               @if(!$canEdit) disabled @endif
                        >
                        @error('night_time') <p class="fw-light text-danger fs-6 invalid-feedback">{{ $message }}</p> @enderror
                    </div>
            </div>
        </div>
    </div>
    <div class="card mb-2">
        <div class="card-body">
                <div class="text-start mb-3">
                    <label class="form-label">特訓時間</label>
                    <span class="badge bg-danger mx-1">必須</span>
                    <div>
                        <div class="form-check text-start">
                            <input class="form-check-input" type="radio" id="additional-work-none" name="additional-work"
                               value="0"
                               wire:model="training_type"
                               wire:click="changeSelectValue('training_type', 0)"
                               @if(!empty($training_type)) checked="true" @endif
                               @if(!$canEdit) disabled @endif
                            >
                            <label class="form-check-label" for="additional-work-none">本日の特訓はなし</label>
                        </div>
                        <div class="form-check text-start">
                            <input class="form-check-input" type="radio" id="additional-work-yes" name="additional-work"
                               value="1"
                               wire:model="training_type"
                               wire:click="changeSelectValue('training_type', 1)"
                               @if(!empty($training_type)) checked="true" @endif
                               @if(!$canEdit) disabled @endif
                            >
                            <label class="form-check-label" for="additional-work-yes">時間数を入れる</label>
                        </div>
                        <input class="form-control @error('training_time') is-invalid @enderror"
                               type="time"
                               wire:model="training_time"
                               @if(!$canEdit) disabled @endif
                        >
                        @error('training_time') <p class="fw-light text-danger fs-6 invalid-feedback">{{ $message }}</p> @enderror
                    </div>
                </div>
        </div>
    </div>
    <div class="card mb-2">
        <div class="card-body">
                <div class="text-start mb-3">
                    <label class="form-label">備考</label>
                    <span class="badge bg-danger mx-1">必須</span>
                    <div>
                        <span class="description">・所属校舎以外の校舎で発生した【交通費】【シフト事務】の場合、他校舎勤務の旨を記載してください <br>・交通費で【その他】を選択した場合はその他を選択した理由と経路を記載してください。 <br>例：急遽18時から池尻校のシフト外事務に入る事になったため。 <br>内訳：大学最寄りの◯◯線・・駅～武田駅（XXX円）＋◯◯線武田駅～池尻駅（XXX円）＋◯◯線池尻駅～自宅駅（XXX円） </span>
                    </div>
                    <textarea class="form-control @error('note') is-invalid @enderror"
                              @if(!$canEdit) disabled @endif
                              wire:model="note"></textarea>
                    @error('note') <p class="fw-light text-danger fs-6 invalid-feedback">{{ $message }}</p> @enderror
                </div>
        </div>
    </div>
    <button class="btn btn-primary" type="button"
            wire:click.prevent="onSave"
            @if(!$canEdit) disabled @endif
    >申請する</button>
</div>

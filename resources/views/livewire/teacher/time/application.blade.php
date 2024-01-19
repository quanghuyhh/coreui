<div class="container-fluid text-nowrap text-start">
    <div class="alert alert-danger" role="alert"><a class="alert-link" href="/teacher/time/time-re-application.html">2023年12月1日の勤怠の修正依頼があります</a></div>
    <div class="card mb-2">
        <div class="card-body">
            <form>
                <div class="text-start mb-3">
                    <label class="form-label" for="application_date">日付</label><span class="badge bg-danger mx-1">必須</span>
                    <div>
                        <span class="description">申請した日付ではなく、稼働で発生した日付を入力してください。</span>
                    </div>
                    <input class="form-control" type="date" id="application_date">
                </div>
            </form>
        </div>
    </div>
    <div class="text-start mb-1">
        <div class="form-check form-switch form-check-inline">
            <input class="form-check-input"
                   type="checkbox"
                   id="transportation-fee-check"
                   name="transportation-fee-check"
                   wire:model="use_transportation_expense"
            />
            <label class="form-check-label" for="transportation-fee-check">交通費の申請をする</label>
        </div>
    </div>
    <div id="transportation-fee" class="no-display" style="display: block;">
        <div class="card mb-2">
            <div class="card-body">
                <form>
                    <div class="text-start mb-3"><label class="form-label">交通費</label><span class="badge bg-danger mx-1">必須</span><select class="form-select mb-2" id="price">
                            <option value="" selected="">なし</option>
                            <option value="1">渋谷〜池尻大橋（280円）</option>
                            <option value="2">その他</option>
                        </select><input class="form-control no-display" type="text" id="other-price"></div>
                </form>
            </div>
        </div>
    </div>
    <div class="text-start mb-1">
        <div class="form-check form-switch form-check-inline"><input class="form-check-input" type="checkbox" id="shift-check"><label class="form-check-label" for="formCheck-2">シフト事務の申請をする</label></div>
    </div>
    <div id="shift" class="no-display" style="display: block;">
        <div class="text-start"><span class="description">シフト事務→事前に校舎長から特訓以外の業務を指示されている事務作業。</span></div>
        <div class="card mb-2">
            <div class="card-body">
                <div class="text-start mb-3"><span class="description">シフトに記載があり、実施した事務は開始時刻と終了時刻を入れてください<br>※22時を過ぎた場合は、事務終了時刻に「実際の稼働終了時刻」を記入してください<br>例：13時～22時の9時間（うち1時間休憩）のシフトのところ、22：30まで伸びてしまった場合<br>開始時刻　13:00<br>終了時刻　22:30<br>休憩時間　1:00<br>※さらに、後の項目で深夜【00:30】を入力してください</span></div>
                <form>
                    <div class="text-start mb-3"><label class="form-label">事務開始時刻</label><span class="badge bg-danger mx-1">必須</span><input class="form-control" type="time"></div>
                    <div class="text-start mb-3"><label class="form-label">事務開始終了</label><span class="badge bg-danger mx-1">必須</span><input class="form-control" type="time"></div>
                    <div class="text-start mb-3"><label class="form-label">休憩時間</label><span class="badge bg-danger mx-1">必須</span>
                        <div class="text-start"><span class="description">シフト事務の勤務時間のうち、休憩時間を記入してください。</span></div><input class="form-control" type="time">
                    </div>
                    <div class="text-start mb-3"><span class="description">★休憩時間について<br>シフト事務の勤務時間のうち、休憩時間を記載してください。<br>・6時間超え、8時間以下　の勤務で45分以上<br>・8時間超えの勤務で1時間以上<br>が最低限の休憩時間です。<br>例１）13～22時でのシフト勤務の場合<br>事務開始時刻13：00<br>事務終了時刻22：00<br>休憩時間1：00<br>例２）13～20時でシフト勤務の場合<br>事務開始時刻13：00<br>事務終了時刻20：00<br>休憩時間0：45</span></div>
                    <div class="text-start mb-3"><span class="description">★シフト事務の深夜労働について（重要！）<br>間違えると給与が少なく支給される可能性があるので気をつけてください。<br><br>シフト事務の労働時間が深夜に及んだ場合、深夜の時間も含めて労働時間を記載してください。<br>例）17:30〜22:15でシフト事務の申請をして、さらに、後の項目で深夜の時間を【00:15】で申請する</span></div>
                </form>
            </div>
        </div>
    </div>
    <div class="text-start mb-1">
        <div class="form-check form-switch form-check-inline"><input class="form-check-input" type="checkbox" id="overwork-check"><label class="form-check-label" for="formCheck-3">シフト外事務の申請をする</label></div>
    </div>
    <div id="overwork" class="no-display" style="display: block;">
        <div class="text-start"><span class="description">シフト外で発生した事務はこちらに入れてください</span></div>
        <div class="card mb-2">
            <div class="card-body">
                <form>
                    <div class="text-start mb-3"><label class="form-label">質問対応</label>
                        <div class="text-start"><span class="description">質問対応で稼働した時間数を記入（開始・終了時刻ではありません。）<br>10分なら00:10と記載。（フォームは時間：分になっているので注意してください）</span></div><input class="form-control" type="time">
                    </div>
                    <div class="text-start mb-3"><label class="form-label">特訓超過</label>
                        <div class="text-start"><span class="description">特訓を超過した分の事務の時間数を記入（開始・終了時刻ではありません。）<br>10分なら00:10と記載。（フォームは時間：分になっているので注意してください）</span></div><input class="form-control" type="time">
                    </div>
                    <div class="text-start mb-3"><label class="form-label">当欠事務</label>
                        <div class="text-start"><span class="description">生徒の当欠により事務作業した分の時間数を記入（開始・終了時刻ではありません）<br>10分なら00:10と記載。（フォームは時間：分になっているので注意してください）</span></div><input class="form-control" type="time">
                    </div>
                    <div class="text-start mb-3"><label class="form-label">追加事務</label>
                        <div class="text-start"><span class="description">校舎長からシフト外で事務を依頼さ稼働した分のの時間数を記入（開始・終了時刻ではありません）<br>10分なら00:10と記載。（フォームは時間：分になっているので注意してください）</span></div><input class="form-control" type="time">
                    </div>
                    <div class="text-start mb-3"><label class="form-label">追加事務の休憩</label>
                        <div class="text-start"><span class="description">追加事務の労働時間には休憩時間を除いた稼働時間を入れてください。</span></div>
                        <div class="text-start mb-3"><span class="description">校舎長からシフト外で事務を依頼さ稼働した分のの時間数を記入（開始・終了時刻ではありません）<br>10分なら00:10と記載。（フォームは時間：分になっているので注意してください）</span>
                            <div class="form-check text-start"><input class="form-check-input" type="radio" id="formCheck-4" name="additional-work"><label class="form-check-label" for="formCheck-4">追加事務では休憩時間をとっていない</label></div>
                            <div class="form-check text-start"><input class="form-check-input" type="radio" id="formCheck-1" name="additional-work"><label class="form-check-label" for="formCheck-1">追加事務には休憩時間を除いた時間を入力した</label></div>
                        </div>
                    </div>
                    <div class="text-start mb-3"><label class="form-label">ブログURL</label>
                        <div class="text-start"><span class="description">校舎長からの指示でブログを作成した場合、作成したブログのURL（GoogleドキュメントのURL、もしくはブログのURL)を貼り付け</span></div><input class="form-control" type="url">
                    </div>
                    <div class="text-start mb-3"><label class="form-label">備考</label>
                        <div class="text-start"><span class="description">シフト外事務についての備考。シフト外事務の詳細を記載してください。<br>※所属校舎以外の校舎でのシフト外事務の場合は【⚪︎⚪︎公でのシフト外事務稼働】と記載してください。</span></div><textarea class="form-control"></textarea>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="text-start mb-1">
        <div class="form-check form-switch form-check-inline"><input class="form-check-input" type="checkbox" id="night-check"><label class="form-check-label" for="ovework-check-1">深夜事務</label></div>
    </div>
    <div id="night" class="no-display" style="display: block;">
        <div class="text-start"><span class="description">事務で22時を過ぎた稼働時間があればこちらに入れてください</span></div>
        <div class="card mb-2">
            <div class="card-body">
                <form>
                    <div class="text-start mb-3"><label class="form-label">深夜</label>
                        <div class="text-start"><span class="description">事務で22時を過ぎた稼働時間があれば時間数を記入してください。<br>例）22:10まで勤務したら00:10と記入</span></div><input class="form-control" type="time">
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="card mb-2">
        <div class="card-body">
            <form>
                <div class="text-start mb-3"><label class="form-label">特訓時間</label><span class="badge bg-danger mx-1">必須</span>
                    <div>
                        <div class="form-check text-start"><input class="form-check-input" type="radio" id="formCheck-5" name="additional-work"><label class="form-check-label" for="formCheck-5">本日の特訓はなし</label></div>
                        <div class="form-check text-start"><input class="form-check-input" type="radio" id="formCheck-2" name="additional-work"><label class="form-check-label" for="formCheck-2">時間数を入れる</label></div><input class="form-control" type="time">
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="card mb-2">
        <div class="card-body">
            <form>
                <div class="text-start mb-3"><label class="form-label">備考</label><span class="badge bg-danger mx-1">必須</span>
                    <div><span class="description">・所属校舎以外の校舎で発生した【交通費】【シフト事務】の場合、他校舎勤務の旨を記載してください<br>・交通費で【その他】を選択した場合はその他を選択した理由と経路を記載してください。<br>例：急遽18時から池尻校のシフト外事務に入る事になったため。<br>内訳：大学最寄りの◯◯線・・駅～武田駅（XXX円）＋◯◯線武田駅～池尻駅（XXX円）＋◯◯線池尻駅～自宅駅（XXX円）</span></div><textarea class="form-control"></textarea>
                </div>
            </form>
        </div>
    </div><button class="btn btn-primary" type="button">申請する</button>
</div>

<?php

if(!function_exists('jp_day_of_week')) {
    function jp_day_of_week($dayOfWeek) {
        $days = array('日', '月', '火', '水', '木', '金', '土');
        return $days[$dayOfWeek];
    }
}

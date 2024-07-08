<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class EventService
{
    // 重複チェックの処理
    public static function checkEventDuplication($eventDate, $startTime, $endTime){
        // [新規の開始時間 < 登録済みの終了時間] And 
        // [新規の終了時間 > 登録済みの開始時間]
        // return DB::table('events')
        return DB::table('events')
            ->whereDate('start_date', $eventDate)
            ->whereTime('end_date', '>', $startTime)
            ->whereTime('start_date', '<', $endTime)
            ->exists();
    // 上記の「return」を記載すれば以下のreturnは不要。
    // return $check;
    }
    
    // 重複チェックの処理
    public static function countEventDuplication($eventDate, $startTime, $endTime){
        // [新規の開始時間 < 登録済みの終了時間] And 
        // [新規の終了時間 > 登録済みの開始時間]
        return DB::table('events')
            ->whereDate('start_date', $eventDate)
            ->whereTime('end_date', '>', $startTime)
            ->whereTime('start_date', '<', $endTime)
            ->count();
    }

    public static function joinDateAndTime($date, $time){
        $join = $date . " " . $time;
        return Carbon::createFromFormat('Y-m-d H:i', $join);
        // 上記の「return」を記載すれば以下のreturnは不要。
        // return $dateTime;      
    }
}
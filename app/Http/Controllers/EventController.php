<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEventRequest;
use App\Http\Requests\UpdateEventRequest;
use App\Models\Event;
// 追記分
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Services\EventService;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $today = Carbon::today();

        // 予約数の合計クエリ
        $reservedPeople = DB::table('reservations')
        ->select('event_id', DB::raw('sum(number_of_people) as number_of_people'))
        // キャンセル無しはNULL、キャセル有りは日付入力
        ->whereNull('canceled_date')
        ->groupBy('event_id');
        // dd($reservedPeople);

        // 外部結合-join
        $events = DB::table('events')
        ->leftJoinSub($reservedPeople, 'reservedPeople', function($join){
            $join->on('events.id', '=', 'reservedPeople.event_id');
            })
        ->whereDate('start_date', '>=', $today)
        ->orderBy('start_date', 'asc')
        ->paginate(10);
        // ->get();
        // dd($events);


        // $events = DB::table('events')
        // // その当日以降の対象データのみ表示
        // ->whereDate('start_date', '>=', $today)
        // ->orderby('start_date', 'asc')
        // // 1ページ表示件数
        // ->paginate(10);

        // manager専用の表示ページ
        return view('manager.events.index', compact('events'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // manager専用のページ
        return view('manager.events.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreEventRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreEventRequest $request)
    {
        $check = EventService::checkEventDuplication(
            $request['event_date'], $request['start_time'], $request['end_time']
        );
        
        if($check){
            session()->flash('status', 'この時間帯は既に他の予約が存在します。');
            return view('manager.events.create');
        }

        $startDate = EventService::joinDateAndTime($request['event_date'], $request['start_time']);
        $endDate = EventService::joinDateAndTime($request['event_date'], $request['end_time']);

        Event::create([
            'name' => $request['event_name'],
            'information' => $request['information'],
            'start_date' => $startDate,
            'end_date' => $endDate,
            'max_people' => $request['max_people'],
            'is_visible' => $request['is_visible'],
        ]);

        // 登録時に表示
        session()->flash('status', '登録okです');
        return to_route('events.index');
    }


    public function show(Event $event)
    {
        $event = Event::findOrFail($event->id);
        $users = $event->users;
        // dd($event, $users);

        $reservations = [];

        foreach($users as $user)
        {
            $reservedInfo = [
                'name' => $user->name,
                'number_of_people' => $user->pivot->number_of_people,
                'canceled_date' => $user->pivot->canceled_date
            ];
            array_push($reservations, $reservedInfo);
        }
        // dd($reservations);

        // Models\Event.phpの関数を参照して記載
        $eventDate = $event->eventDate;
        $startTime = $event->startTime;
        $endTime = $event->endTime;

        // dd($eventDate, $startTime, $endTime);

        return view('manager.events.show', compact('event', 'users', 'reservations', 'eventDate', 'startTime', 'endTime'));
    }


    public function edit(Event $event)
    {
        $event = Event::findOrFail($event->id);

        // 早期リターンによる条件判定により処理を止める様な
        // 場合、以下の様に記載。
        $today = Carbon::today()->format('Y年m月d日');
        if($event->eventDate < $today){
            return abort(404);
        }

        // Models\Event.phpの関数を参照して記載
        // $eventDate = $event->eventDate;
        $eventDate = $event->editEventDate;
        $startTime = $event->startTime;
        $endTime = $event->endTime;

        // dd($eventDate, $startTime, $endTime);

        return view('manager.events.edit', compact('event', 'eventDate', 'startTime', 'endTime'));
    }


    public function update(UpdateEventRequest $request, Event $event)
    {
        $check = EventService::countEventDuplication(
            $request['event_date'], $request['start_time'], $request['end_time']
        );
        
        if($check > 1){
            $event = Event::findOrFail($event->id);
            $eventDate = $event->editEventDate;
            $startTime = $event->startTime;
            $endTime = $event->endTime;
            session()->flash('status', 'この時間帯は既に他の予約が存在します。');
            return view('manager.events.edit',
                compact('event', 'eventDate', 'startTime', 'endTime')
            );
        }

        $startDate = EventService::joinDateAndTime($request['event_date'], $request['start_time']);
        $endDate = EventService::joinDateAndTime($request['event_date'], $request['end_time']);

        $event = Event::findOrFail($event->id);
            $event->name = $request['event_name'];
            $event->information = $request['information'];
            $event->start_date = $startDate;
            $event->end_date = $endDate;
            $event->max_people = $request['max_people'];
            $event->is_visible = $request['is_visible'];
            $event->save();


        // 登録時に表示
        session()->flash('status', '更新しました。');
        return to_route('events.index');
    }

    public function past(){

        $today =Carbon::today();

        $reservedPeople = DB::table('reservations')
        ->select('event_id', DB::raw('sum(number_of_people) as number_of_people'))
        // キャンセル無しはNULL、キャセル有りは日付入力
        ->whereNull('canceled_date')
        ->groupBy('event_id');

        $events = DB::table('events')
        ->leftJoinSub($reservedPeople, 'reservedPeople', function($join){
            $join->on('events.id', '=', 'reservedPeople.event_id');
            })
        // ->whereDate('start_date', '<', $today)
        ->orderBy('start_date', 'desc')
        ->paginate(10);

        return view('manager.events.past', compact('events'));
    }

    // 今回は削除処理は実施しない。
    public function destroy(Event $event)
    {
        //
    }
}

<?php
namespace App\Http\Controllers;

use App\Output;
use App\Reservation;
use Carbon\Carbon;
use Validator;
use Illuminate\Http\Request;

class MuseumController extends Controller
{

    public $types = [
        'family',
        'group'
    ];

    public function index()
    {
        return view('index');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'type' => 'required|in:' . implode(',', $this->types),
            'number' => 'required|numeric|min:1',
            'time' => 'required|date_format:H:i:s',
            'date' => 'required|date_format:yy-m-d'
        ]);

        if (!isset($request->type)) {
            $type = 'family';
        } elseif ($request->type == 'family') {
            $type = 'group';
        } else $type = 'family';

        $date = $request->date;
        $time = $request->time;

        if ($request->type == 'family' && $request->number > 5) $number = 5;
        else $number = $request->number;

        $getAllDates = $this->getAllDates($type);

        if (!isset($getAllDates[$date])) return redirect()->back()->withErrors(['date' => 'Wrong date']);
        if ($getAllDates[$date]['disabled'] == 1 || $getAllDates[$date]['selected'] == 1) return redirect()->back()->withErrors(['date' => 'Wrong date']);

        $getAllTimes = $this->getAllTimes($request->type, $date, $number);

        if (!isset($getAllTimes[$time])) return redirect()->back()->withErrors(['time' => 'Wrong time']);
        if ($getAllTimes[$time]['disabled'] == 1) return redirect()->back()->withErrors(['time' => 'Time booked']);

        Reservation::create([
            'type' => $request->type,
            'date_start' => $date,
            'time_start' => $time,
            'number' => $number
        ]);

        return redirect()->route('index')->withErrors(['alert' => 'Added new record']);
    }

    public function ajaxLoader(Request $request)
    {
        if (!isset($request->type)) {
            $type = 'family';
        } elseif ($request->type == 'family') {
            $type = 'group';
        } else $type = 'family';

        $getAllDates = $this->getAllDates($type);

        $html = view('reservation.include.ajax-date', compact('getAllDates'))->render();

        return response()->json(['innerHtml' => $html]);
    }

    public function ajaxTimeLoader(Request $request)
    {
        if ($request->type == 'family') $type = 'family';
        else $type = 'group';

        if ($type == 'family' && $request->number > 5) $number = 5;
        else $number = $request->number;

        $getAllTimes = $this->getAllTimes($type, $request->date, $number);

        $html = view('reservation.include.ajax-time', compact('getAllTimes'))->render();

        return response()->json(['innerHtml' => $html]);
    }

    protected function getAllDates($type)
    {
        $allDates = Reservation::type($type)->now()->pluck('date_start')->toArray();
        $outputs = Output::now()->pluck('output')->toArray();

        $getAllDates = [];
        $startDate = Carbon::now();
        for ($i=0; $i <= 62; $i++) {
            $format = $startDate->format('yy-m-d');
            if (in_array($format, $outputs)) $getAllDates[$format]['disabled'] = 1;
            else $getAllDates[$format]['disabled'] = 0;
            if (in_array($format, $allDates)) $getAllDates[$format]['selected'] = 1;
            else $getAllDates[$format]['selected'] = 0;
            $getAllDates[$format]['format'] = $startDate->format('m-d');
            $startDate->addDay();
        }

        return $getAllDates;
    }

    protected function getAllTimes($type, $date, $number)
    {
        $reservations = Reservation::where('date_start', $date)->orderBy('time_start')->get();

        $getAllTimes = [];
        $startTime = Carbon::parse($date)->setTime(8, 0, 0);
        for ($i=0; $i <= 24; $i++) {
            $format = $startTime->format('H:i:s');
            $getAllTimes[$format]['format'] = $startTime->format('H:i');
            if ($reservations) {
                $endTime = Carbon::parse($startTime)->addHours(3);
                $n = 0;
                foreach ($reservations as $reservation) {
                    $start = Carbon::parse($date)->setTimeFrom($reservation->time_start);
                    $end = Carbon::parse($date)->setTimeFrom($reservation->time_start)->addHours(3);
                    if (($startTime <= $start && $endTime > $start) || ($startTime < $end && $endTime >= $end)) {
                        if ($type == 'family') $n += $reservation->number;
                        if ($type == 'group') $n++;
                    }
                }

                if ($type == 'family') {
                    if ($n + $number <= 15) $getAllTimes[$format]['disabled'] = 0;
                    else $getAllTimes[$format]['disabled'] = 1;
                } else {
                    if ($n < 3) $getAllTimes[$format]['disabled'] = 0;
                    else $getAllTimes[$format]['disabled'] = 1;
                }

            } else $getAllTimes[$format]['disabled'] = 0;
            $startTime->addMinutes(15);
        }

        return $getAllTimes;

    }
}
<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Output;
use App\Reservation;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);

        DB::table('outputs')->truncate();
        DB::table('reservations')->truncate();

        $outputs = [];
        $day = Carbon::now();
        for ($i=0; $i<10; $i++) {
            $d = $day->addDays(rand(1, 5))->format('yy-m-d');
            $outputs[] = $d;
            Output::create([
                'output' => $d
            ]);
        }

        $d = Carbon::now();
        $types = ['family', 'group'];
        for ($i=0; $i<30; $i++) {
            $day = $d->addDays(rand(1, 2));
            if (!in_array($day->format('yy-m-d'), $outputs)) {
                $n = rand(3, 6);
                $type = $types[rand(0,1)];
                for ($j=0; $j<$n; $j++) {
                    Reservation::create([
                        'type' => $type,
                        'date_start' => $day->format('yy-m-d'),
                        'time_start' => $day->setTime(8, 0, 0)->addMinutes(15 * rand(0, 24))->format('H:i:s'),
                        'number' => $type == 'family' ? rand(1, 5) : rand(1, 15)
                    ]);
                }
            }
        }
    }
}

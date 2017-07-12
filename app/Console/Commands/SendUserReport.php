<?php

namespace App\Console\Commands;

use App\Entry;
use App\Location;
use App\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use \App\Notifications\SendUserReport as Notification;
use Mockery\Exception;
use Mockery\Matcher\Not;

class SendUserReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'milesreport:send-user-report {user?} {--force}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send user reports';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if($this->argument('user') == null) {
            try {
                $users = User::all();

                foreach ($users as $user) {
                    // This crazy compare determines if the user wants the message sent today or if its too soon
                    if (Carbon::createFromFormat('Y-m-d H:i:s', $user->last_report)->addDays($user->report_schedule)->diffInDays(Carbon::now()) == 0) {
                        // Get all entries from last report sent to now
                        $sheet = $this->generateSheet($user->id, $user->name, $user->last_report);
                        $user->notify(new Notification($sheet));
                        $user->last_report = Carbon::now();
                        $user->save();
                    } else {
                        $this->info('Report not generated for ' . $user->name);
                    }
                }
                $this->info('Reports generated and sent!');
            } catch (\Exception $e) {
                $this->error($e->getMessage());
            }
        } else {
            try {
                $user = User::whereId($this->argument('user'))->first();
                // This crazy compare determines if the user wants the message sent today or if its too soon
                if (Carbon::createFromFormat('Y-m-d H:i:s', $user->last_report)->addDays($user->report_schedule)->diffInDays(Carbon::now()) == 0 || $this->option('force')) {
                    // Get all entries from last report sent to now
                    $sheet = $this->generateSheet($user->id, $user->name, $user->last_report);
                    $user->notify(new Notification($sheet));
                    $user->last_report = Carbon::now();
                    $user->save();
                    $this->info('Report generated!');
                } else {
                    $this->info('Report not generated for ' . $user->name);
                }
            } catch (\Exception $e) {
                $this->error($e->getMessage());
            }
        }
    }

    /**
     * @param $userId
     * @param $userName
     * @param $lastReport
     * @return string
     */
    private function generateSheet($userId, $userName, $lastReport) {
        $entries = Entry::whereUserId($userId)->whereDate('created_at', '>', $lastReport)->get()->map(function($entry) {
            $from = Location::whereId($entry->from)->first();
            $to = Location::whereId($entry->to)->first();
            return ['from' => $from->name, 'to' => $to->name, 'distance' => $entry->distance];
        });
        $sheet = \Excel::create('Miles-Report-' . str_replace(' ', '-', $userName). '-' . Carbon::now()->format('F') . '-' . Carbon::now()->format('Y'), function ($excel) use($entries) {
            $excel->sheet('Miles', function($sheet) use($entries) {
                $sheet->fromModel($entries);
            });
        })->store('csv', false, true);
        return $sheet['file'];
    }
}

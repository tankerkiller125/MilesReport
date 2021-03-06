<?php

namespace App\Jobs;

use App\User;
use App\Entry;
use App\Location;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Notifications\SendUserReport as Notification;

class CreateReport implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $user;

    protected $force;

    /**
     * CreateReport constructor.
     * @param User $user
     */
    public function __construct(User $user, $force)
    {
        $this->user = $user;
        $this->force = $force;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // This crazy compare determines if the user wants the message sent today or if its too soon
        if (Carbon::createFromFormat('Y-m-d H:i:s', $this->user->last_report)->addDays($this->user->report_schedule)->diffInDays(Carbon::now()) == 0 || $this->force === true) {
            // Get all entries from last report sent to now
            $sheet = $this->generateSheet($this->user->id, $this->user->name, $this->user->last_report);
            $this->user->notify((new Notification($sheet))->onQueue('report-emails'));
            $this->user->last_report = Carbon::now();
            $this->user->save();
        }
    }

    /**
     * @param $userId
     * @param $userName
     * @param $lastReport
     * @return string
     */
    private function generateSheet($userId, $userName, $lastReport)
    {
        $entries = Entry::whereUserId($userId)->whereDate('created_at', '>', $lastReport)->get()->map(function ($entry) {
            $from = Location::whereId($entry->from)->first();
            $to = Location::whereId($entry->to)->first();

            return ['Date'->Carbon::createFromFormat('Y-m-d H:i:s', $entry->created_at)->format('m/d/Y'), 'From' => $from->name, 'To' => $to->name, 'Distance' => $entry->distance];
        });
        $sheet = \Excel::create('Miles-Report-'.str_replace(' ', '-', $userName).'-'.Carbon::now()->format('F-Y'), function ($excel) use ($entries) {
            $excel->sheet('Miles', function ($sheet) use ($entries) {
                $sheet->fromModel($entries);
            });
        })->store('xlsx', false, true);

        return $sheet['file'];
    }
}

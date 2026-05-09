<?php

namespace App\Notifications;

use App\Models\CleanUpReport;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ReportAssignedNotification extends Notification
{
    use Queueable;

    protected $report;

    /**
     * Create a new notification instance.
     */
    public function __construct(CleanUpReport $report)
    {
        $this->report = $report;
    }

    /**
     * Delivery channels
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Store notification in database
     */
    public function toArray(object $notifiable): array
    {
        return [

            'report_id' => $this->report->id,

            'title' => 'New Report Assigned',

            'message' =>
                'You were assigned to: '
                . $this->report->concern_type,

            'location' => $this->report->location,

        ];
    }
}
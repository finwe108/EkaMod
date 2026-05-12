<?php

namespace App\Notifications;

use App\Models\AdmissionApplication;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class AdmissionApplicationSubmittedNotification extends Notification
{
    use Queueable;

    public function __construct(
        public AdmissionApplication $application
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'title' => 'New admission application',
            'message' => $this->application->first_name . ' ' .
                         $this->application->last_name .
                         ' submitted an admission application.',

            'url' => route(
                'admin.admission_applications.show',
                $this->application->id
            ),

            'category' => 'admission_application',

            'application_id' => $this->application->id,

            'application_number' => $this->application->application_number,
        ];
    }
}
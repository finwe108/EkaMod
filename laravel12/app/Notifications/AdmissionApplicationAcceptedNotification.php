<?php

namespace App\Notifications;

use App\Models\Student;
use App\Models\AdmissionApplication;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class AdmissionApplicationAcceptedNotification extends Notification
{
    use Queueable;

    public function __construct(
        public Student $student,
        public AdmissionApplication $application
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'title' => 'Application accepted',
            'message' => $this->student->full_name . ' has been accepted and a student account was created.',
            'url' => route('admin.students.show', $this->student->id),
            'category' => 'admission',
            'student_id' => $this->student->id,
            'application_id' => $this->application->id,
        ];
    }
}
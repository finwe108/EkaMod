<?php
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class StudentAccountCreatedNotification extends Notification
{
    use Queueable;

    public function __construct(
        public string $studentName,
        public string $username
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'title' => 'Student account created',
            'message' => "An account was created for {$this->studentName}. Username: {$this->username}",
            'url' => route('admin.students.index'),
            'icon' => 'user',
            'category' => 'student_account',
        ];
    }
}
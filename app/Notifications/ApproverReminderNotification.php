<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ApproverReminderNotification extends Notification
{
    use Queueable;

    protected $submission_id;
    protected $status_description;
    protected $eventName;
    protected $organization;

    /**
     * Create a new notification instance.
     */
    public function __construct($submissionId, $status, $eventName, $organization)
    {
        $this->submission_id = $submissionId;
        $this->status_description = $status;
        $this->eventName = $eventName;
        $this->organization = $organization;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Reminder: Persetujuan Dokumen')
            ->greeting('Halo ' . $notifiable->name)
            ->line('Ada dokumen baru yang membutuhkan tindakan. Berikut adalah detail dokumen:')
            ->line('Nomor Dokumen: ' . $this->submission_id)
            ->line('Nama Kegiatan: ' . $this->eventName)
            ->line('Penyelenggara: ' . $this->organization)
            ->line('Status Dokumen: Ditinjau' . $this->status_description)
            ->action('Lihat Dokumen', url('/login'))
            ->line('Harap segera ditindaklanjuti. Terima kasih');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}

<?php

namespace App\Console\Commands;

use App\Mail\LpjReminderEmail;
use Illuminate\Console\Command;
use Carbon\Carbon;
use App\Models\DocumentSubmission;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class SendLpjReminderEmails extends Command
{
    protected $signature = 'send:lpj-reminder-emails';
    protected $description = 'Kirim reminder pengumpulan LPJ berdasarkan end_date';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        // Tanggal hari ini
        $today = Carbon::today();

        // Ambil dokumen proposal yang 7 hari setelah end_date atau 7 hari sebelum end_date
        $submissions = DocumentSubmission::where('doc_type_id', 'DT01') // Hanya proposal kegiatan
            ->where('status_id', '!=', 'Disetujui') // Pastikan belum disetujui
            ->where(function ($query) use ($today) {
                $query->where('end_date', $today->copy()->subDays(7)) // 7 hari setelah end_date
                    ->orWhere('end_date', $today->copy()->addDays(7)); // 7 hari sebelum end_date
            })
            ->get();

        // Kirim email untuk setiap submission
        foreach ($submissions as $submission) {
            $user = User::find($submission->user_id);

            // Buat data untuk email
            $data = [
                'event_name' => $submission->event_name,
                'end_date' => $submission->end_date,
                'organization' => $submission->organization,
                'location' => $submission->location,
                'project_leader' => $submission->project_leader,
            ];

            // Kirim email peringatan
            Mail::to($user->email)->send(new \App\Mail\LpjReminderEmail($data));
        }

        $this->info('Peringatan email LPJ telah dikirim.');
    }
}

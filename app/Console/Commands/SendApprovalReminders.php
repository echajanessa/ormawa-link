<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Notifications\ApproverReminderNotification;
use Carbon\Carbon;

class SendApprovalReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-approval-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send email reminders for pending approvals based on document status_id';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $today = Carbon::today();

        // Mapping status_id dengan role approver
        $statusApproverMap = [
            1 => 'BEM',      // Ditinjau BEM
            2 => 'DPM',      // Ditinjau DPM
            3 => 'Binma',    // Ditinjau Binma
            4 => 'Dekan',    // Ditinjau Dekan
            5 => 'Lemawa',   // Ditinjau Lemawa
        ];

        // Ambil dokumen dengan status "Ditinjau {Role}"
        $pendingApprovals = DB::table('document_submissions')
            ->whereIn('status_id', array_keys($statusApproverMap)) // Ambil semua status yang valid
            ->get();

        foreach ($pendingApprovals as $approval) {
            $currentStatusId = $approval->status_id; // Ambil status_id
            $currentRole = $statusApproverMap[$currentStatusId] ?? null; // Dapatkan role dari mapping

            if ($currentRole) {
                // Cari approver berdasarkan role saat ini
                $approvers = User::whereHas('UserRole', function ($query) use ($currentRole) {
                    $query->where('role_name', $currentRole);
                })->get();

                foreach ($approvers as $approver) {
                    // Kirim email pengingat kepada approver
                    $approver->notify(new ApproverReminderNotification(
                        $approval->submission_id,
                        $currentRole,
                        $approval->event_name,
                        $approval->organization
                    ));
                }

                // Perbarui waktu reminder terakhir
                DB::table('document_submissions')
                    ->where('submission_id', $approval->submission_id)
                    ->update(['updated_at' => $today]);
            }
        }

        $this->info('Email reminders sent successfully!');
    }
}

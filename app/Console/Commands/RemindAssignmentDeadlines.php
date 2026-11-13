<?php

namespace App\Console\Commands;

use App\Models\Assignment;
use App\Services\NotificationService;
use Illuminate\Console\Command;

final class RemindAssignmentDeadlines extends Command
{
    protected $signature = 'notifications:assignment-deadlines';

    protected $description = 'Remind enrolled students without a submission about assignments due within 24 hours (Ostad cohort reminders)';

    public function handle(): int
    {
        $assignments = Assignment::published()
            ->whereBetween('due_date', [now(), now()->addDay()])
            ->with('course')
            ->get();

        if ($assignments->isEmpty()) {
            $this->line('No assignments due within the next 24 hours.');

            return self::SUCCESS;
        }

        $sent = 0;

        foreach ($assignments as $assignment) {
            // Only enrolled students who have not submitted anything yet
            $submittedIds = $assignment->submissions()->pluck('student_id');

            $students = $assignment->course->enrollments()
                ->whereIn('status', ['active', 'completed'])
                ->with('student')
                ->get()
                ->pluck('student')
                ->filter(fn ($student) => $student && ! $submittedIds->contains($student->id));

            foreach ($students as $student) {
                NotificationService::send(
                    $student,
                    'Assignment Due Soon: '.$assignment->title,
                    '"'.$assignment->title.'" in "'.$assignment->course->title.'" is due '
                        .$assignment->due_date->format('M j, Y \a\t g:i A')
                        .'. You have not submitted yet — it is worth '.$assignment->total_points.' points.',
                    'assignment',
                    route('student.assignments.show', [$assignment->course, $assignment]),
                    'Submit Now',
                    'assignment',
                    true,
                    ['dedup_key' => 'assignment-due:'.$assignment->id.':'.$student->id],
                );

                $sent++;
            }
        }

        $this->info("Sent {$sent} assignment deadline reminder(s) across {$assignments->count()} assignment(s).");

        return self::SUCCESS;
    }
}

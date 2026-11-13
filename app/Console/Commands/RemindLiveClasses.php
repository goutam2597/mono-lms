<?php

namespace App\Console\Commands;

use App\Models\LiveClass;
use App\Services\NotificationService;
use Illuminate\Console\Command;

final class RemindLiveClasses extends Command
{
    protected $signature = 'notifications:live-class-reminders';

    protected $description = 'Remind enrolled students 24 hours and 1 hour before scheduled live classes (Ostad cohort reminders)';

    public function handle(): int
    {
        $classes = LiveClass::where('status', 'scheduled')
            ->whereBetween('starts_at', [now(), now()->addDay()])
            ->with('course')
            ->get();

        if ($classes->isEmpty()) {
            $this->line('No live classes scheduled within the next 24 hours.');

            return self::SUCCESS;
        }

        $sent = 0;

        foreach ($classes as $class) {
            $students = $class->course->enrollments()
                ->whereIn('status', ['active', 'completed'])
                ->with('student')
                ->get()
                ->pluck('student')
                ->filter();

            $startsAt = $class->starts_at->format('M j, Y \a\t g:i A');

            // 24-hour heads-up window
            if ($class->starts_at->between(now()->addHours(23), now()->addHours(25))) {
                foreach ($students as $student) {
                    NotificationService::send(
                        $student,
                        'Live Class Tomorrow: '.$class->title,
                        $class->classTypeLabel().' for "'.$class->course->title.'" starts '.$startsAt
                            .' via '.$class->platformLabel().'. Attendance earns 10 leaderboard points.',
                        'live_class',
                        route('student.courses.learn', $class->course),
                        'View Schedule',
                        'live_class',
                        true,
                        ['dedup_key' => 'live-class-24h:'.$class->id.':'.$student->id],
                    );

                    $sent++;
                }
            }

            // Starting-soon window (about an hour out)
            if ($class->starts_at->between(now()->addMinutes(55), now()->addMinutes(65))) {
                foreach ($students as $student) {
                    NotificationService::send(
                        $student,
                        'Starting Soon: '.$class->title,
                        $class->classTypeLabel().' for "'.$class->course->title.'" begins at '.$startsAt
                            .' via '.$class->platformLabel().'. Join a few minutes early!',
                        'live_class',
                        route('student.courses.learn', $class->course),
                        'Join Class',
                        'live_class',
                        true,
                        ['dedup_key' => 'live-class-1h:'.$class->id.':'.$student->id],
                    );

                    $sent++;
                }
            }
        }

        $this->info("Sent {$sent} live class reminder(s) across {$classes->count()} class(es).");

        return self::SUCCESS;
    }
}

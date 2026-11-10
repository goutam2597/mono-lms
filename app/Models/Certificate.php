<?php

namespace App\Models;

use App\Services\NotificationService;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

#[Fillable([
    'certificate_number',
    'student_id',
    'course_id',
    'enrollment_id',
    'certificate_template_id',
    'student_name',
    'course_title',
    'instructor_name',
    'issue_date',
    'grade',
    'score',
    'metadata',
])]
class Certificate extends Model
{
    protected $casts = [
        'issue_date' => 'datetime',
        'score' => 'float',
        'metadata' => 'array',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function enrollment(): BelongsTo
    {
        return $this->belongsTo(Enrollment::class);
    }

    public function template(): BelongsTo
    {
        return $this->belongsTo(CertificateTemplate::class, 'certificate_template_id');
    }

    public function verificationUrl(): string
    {
        return route('certificates.verify.show', $this->certificate_number);
    }

    public function qrCodeUrl(): string
    {
        $target = urlencode($this->verificationUrl());

        return "https://api.qrserver.com/v1/create-qr-code/?size=160x160&data={$target}&bgcolor=ffffff&color=08131e&qzone=1";
    }

    public static function generateCode(): string
    {
        do {
            $code = 'CERT-LMS-'.strtoupper(Str::random(3)).'-'.random_int(1000, 9999);
        } while (static::where('certificate_number', $code)->exists());

        return $code;
    }

    /**
     * Issue or retrieve an accredited certificate for an enrolled student upon course completion.
     */
    public static function issueFor(
        Student $student,
        Course $course,
        ?Enrollment $enrollment = null,
        array $attributes = [],
    ): self {
        $existing = static::where('student_id', $student->id)
            ->where('course_id', $course->id)
            ->first();

        if ($existing) {
            return $existing;
        }

        $template = CertificateTemplate::getDefault();
        $code = static::generateCode();

        $certificate = static::create([
            'certificate_number' => $code,
            'student_id' => $student->id,
            'course_id' => $course->id,
            'enrollment_id' => $enrollment?->id,
            'certificate_template_id' => $template?->id,
            'student_name' => $student->name,
            'course_title' => $course->title,
            'instructor_name' => $course->instructor?->name ?? 'Course Instructor',
            'issue_date' => now(),
            'grade' => $attributes['grade'] ?? 'Grade A+ (96%)',
            'score' => $attributes['score'] ?? 96.00,
            'metadata' => [
                'signer_name' => $template?->signer_name ?? 'Dr. Alexander Wright',
                'signature_title' => $template?->signature_title ?? 'Program Director',
                'template_type' => $template?->template_type ?? 'classic',
            ],
        ]);

        // Send celebratory notification to student
        NotificationService::send(
            $student,
            title: 'Certificate of Completion Issued! 🎓',
            message: "Congratulations! You earned your verified Certificate of Achievement for \"{$course->title}\".",
            category: 'certificate',
            actionUrl: route('student.certificates'),
            actionLabel: 'View Certificate',
            icon: 'badge',
            sendMail: false,
            metadata: ['certificate_number' => $certificate->certificate_number, 'course_id' => $course->id]
        );

        return $certificate;
    }
}

<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Instructor;
use App\Models\Lesson;
use App\Models\Student;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class LessonVideoTest extends TestCase
{
    use RefreshDatabase;

    private Instructor $instructor;

    private Course $course;

    private Student $student;

    protected function setUp(): void
    {
        parent::setUp();

        $this->instructor = Instructor::create([
            'name' => 'I', 'email' => 'i@test.dev', 'password' => 'password',
            'status' => 'active', 'verification_status' => 'approved',
        ]);
        $this->course = Course::create([
            'instructor_id' => $this->instructor->id,
            'title' => 'Video Course',
            'slug' => 'video-course',
            'price' => 0,
            'level' => 'beginner',
            'type' => 'recorded',
            'is_published' => true,
        ]);
        $this->student = Student::create(['name' => 'S', 'email' => 's@test.dev', 'password' => 'password', 'status' => 'active']);
    }

    private function actingInstructor(): Instructor
    {
        $instructor = Instructor::where('email', 'i@test.dev')->first();
        $this->actingAs($instructor, 'instructor');

        return $instructor;
    }

    public function test_youtube_id_parsing_variants(): void
    {
        $urls = [
            'https://www.youtube.com/watch?v=dQw4w9WgXcQ' => 'dQw4w9WgXcQ',
            'https://www.youtube.com/watch?v=dQw4w9WgXcQ&t=42s' => 'dQw4w9WgXcQ',
            'https://youtu.be/dQw4w9WgXcQ' => 'dQw4w9WgXcQ',
            'https://www.youtube.com/embed/dQw4w9WgXcQ' => 'dQw4w9WgXcQ',
            'https://www.youtube.com/shorts/dQw4w9WgXcQ' => 'dQw4w9WgXcQ',
        ];

        foreach ($urls as $url => $expected) {
            $lesson = new Lesson(['course_id' => $this->course->id, 'title' => 't', 'video_url' => $url]);
            $this->assertSame($expected, $lesson->youtubeId(), "Failed parsing: {$url}");
        }

        $bad = new Lesson(['course_id' => $this->course->id, 'title' => 't', 'video_url' => 'https://vimeo.com/12345']);
        $this->assertNull($bad->youtubeId());
    }

    public function test_instructor_adds_youtube_lesson(): void
    {
        $this->actingInstructor();

        $this->post(route('instructor.lessons.store', $this->course), [
            'title' => 'Intro',
            'video_source' => 'youtube',
            'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
        ])->assertSessionHas('success');

        $lesson = Lesson::first();
        $this->assertSame('youtube', $lesson->video_source);
        $this->assertStringContainsString('youtube.com/embed/dQw4w9WgXcQ', (string) $lesson->playerUrl());
    }

    public function test_invalid_youtube_url_is_rejected(): void
    {
        $this->actingInstructor();

        $this->post(route('instructor.lessons.store', $this->course), [
            'title' => 'Bad',
            'video_source' => 'youtube',
            'video_url' => 'https://vimeo.com/12345',
        ])->assertSessionHasErrors('video_url');

        $this->assertDatabaseCount('lessons', 0);
    }

    public function test_instructor_uploads_video_file(): void
    {
        Storage::fake('public');
        $this->actingInstructor();

        $file = UploadedFile::fake()->create('lesson.mp4', 500, 'video/mp4');

        $this->post(route('instructor.lessons.store', $this->course), [
            'title' => 'Uploaded Lesson',
            'video_source' => 'upload',
            'video' => $file,
        ])->assertSessionHas('success');

        $lesson = Lesson::first();
        $this->assertSame('upload', $lesson->video_source);
        $this->assertStringStartsWith('videos/lessons/', $lesson->video_path);
        $this->assertTrue(Storage::disk('public')->exists($lesson->video_path));
        $this->assertStringContainsString('/storage/'.$lesson->video_path, (string) $lesson->playerUrl());
    }

    public function test_student_completes_lessons_and_earns_certificate(): void
    {
        Enrollment::create(['student_id' => $this->student->id, 'course_id' => $this->course->id, 'status' => 'active']);

        $l1 = $this->course->lessons()->create(['title' => 'L1', 'sort_order' => 1, 'video_url' => 'https://youtu.be/dQw4w9WgXcQ']);
        $l2 = $this->course->lessons()->create(['title' => 'L2', 'sort_order' => 2, 'video_url' => 'https://youtu.be/abcdefghi']);

        $this->actingAs($this->student, 'student')
            ->post(route('student.lessons.complete', $l1));

        $enrollment = Enrollment::first();
        $this->assertSame(50, $enrollment->progress);
        $this->assertSame('active', $enrollment->status);

        $this->post(route('student.lessons.complete', $l2));

        $enrollment->refresh();
        $this->assertSame(100, $enrollment->progress);
        $this->assertSame('completed', $enrollment->status);

        $this->assertDatabaseHas('certificates', [
            'student_id' => $this->student->id,
            'course_id' => $this->course->id,
        ]);
    }

    public function test_non_enrolled_student_cannot_access_player(): void
    {
        $this->course->update(['price' => 49.99]);

        $this->actingAs($this->student, 'student')
            ->get(route('student.courses.learn', $this->course))
            ->assertRedirect(route('courses.show', $this->course->slug));
    }

    public function test_enrolled_student_sees_player_with_video(): void
    {
        Enrollment::create(['student_id' => $this->student->id, 'course_id' => $this->course->id, 'status' => 'active']);
        $this->course->lessons()->create(['title' => 'YT Lesson', 'sort_order' => 1, 'video_source' => 'youtube', 'video_url' => 'https://youtu.be/dQw4w9WgXcQ']);

        $this->actingAs($this->student, 'student')
            ->get(route('student.courses.learn', $this->course))
            ->assertOk()
            ->assertSee('YT Lesson')
            ->assertSee('youtube.com/embed/dQw4w9WgXcQ', false);
    }
}

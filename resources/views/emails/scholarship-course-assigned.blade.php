<!-- resources/views/emails/scholarship-course-assigned.blade.php -->
<x-mail::message>
# Scholarship Course Assigned

Hello {{ $user->name }},

Congratulations! You have been assigned the following course under the scholarship program:

**Course:** {{ $course->title }}  
**Level:** {{ $course->level }}  
**Duration:** {{ $course->duration }}

You can now access this course for free from your dashboard.

<x-mail::button :url="route('dashboard.course.show', $course->slug)">
Go to Course
</x-mail::button>

Thank you,<br>
{{ config('app.name') }} Team
</x-mail::message>
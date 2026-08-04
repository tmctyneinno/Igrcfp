<x-mail::message>
# Enrollment Update

Hello {{ $user->name }},

We are writing to inform you that your enrollment in the course **"{{ $course->title }}"** has been revoked.

@if(!empty($reason))
**Reason:** {{ $reason }}
@endif

If you believe this is an error or if you wish to apply for this course through the scholarship program, please contact our support team.

<x-mail::button :url="route('dashboard.courses.index')">
Browse Other Courses
</x-mail::button>

Thank you,<br>
{{ config('app.name') }} Team
</x-mail::message>
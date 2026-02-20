<?php
// app/Exports/EnrollmentsExport.php

namespace App\Exports;

use App\Models\Enrollment;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class EnrollmentsExport implements FromCollection, WithHeadings, WithMapping
{
    protected $filters;

    public function __construct($filters = [])
    {
        $this->filters = $filters;
    }

    public function collection()
    {
        $query = Enrollment::with(['user', 'course', 'transaction']);

        if (!empty($this->filters['status'])) {
            $query->where('status', $this->filters['status']);
        }

        if (!empty($this->filters['course_id'])) {
            $query->where('course_id', $this->filters['course_id']);
        }

        return $query->latest()->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Student Name',
            'Student Email',
            'Course Title',
            'Enrollment Date',
            'Amount',
            'Payment Status',
            'Enrollment Status',
            'Progress',
        ];
    }

    public function map($enrollment): array
    {
        return [
            $enrollment->id,
            $enrollment->user->name ?? 'N/A',
            $enrollment->user->email ?? 'N/A',
            $enrollment->course->title ?? 'N/A',
            $enrollment->created_at->format('Y-m-d H:i:s'),
            $enrollment->amount ?? 0,
            $enrollment->transaction->status ?? 'No Payment',
            $enrollment->status,
            ($enrollment->progress ?? 0) . '%',
        ];
    }
}
<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreLiveClassRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = auth()->user();
        
        if (!$user) {
            return false;
        }

        $allowedRoles = ['Instructor', 'instructor', 'Admin', 'admin', 'Super Admin'];
        $roles = collect(session('auth.roles', []))
            ->merge(collect($user->roles ?? [])->map(fn ($role) => is_array($role) ? ($role['name'] ?? null) : $role))
            ->push($user->role ?? null)
            ->filter()
            ->values()
            ->all();

        return ! empty(array_intersect($roles, $allowedRoles));
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'course_id' => 'required|integer',
            'start_time' => 'required|date_format:Y-m-d H:i:s',
            'end_time' => 'required|date_format:Y-m-d H:i:s|after:start_time',
            'is_recorded' => 'nullable|boolean',
            'recording_url' => 'nullable|url',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'The title field is required.',
            'course_id.required' => 'The course ID field is required.',
            'start_time.required' => 'The start time field is required.',
            'end_time.required' => 'The end time field is required.',
            'end_time.after' => 'The end time must be after the start time.',
            'recording_url.url' => 'The recording URL must be a valid URL.',
        ];
    }
}

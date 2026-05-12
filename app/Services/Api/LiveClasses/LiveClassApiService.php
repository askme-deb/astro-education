<?php

namespace App\Services\Api\LiveClasses;

use App\Services\Api\Clients\BaseApiClient;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

/**
 * LiveClassApiService
 * Handles all live class-related API operations.
 */
class LiveClassApiService extends BaseApiClient
{
    public function catalog(): array
    {
        $myLiveClasses = $this->extractLiveClassItems($this->listMyLiveClasses());
        $allLiveClasses = $this->extractLiveClassItems($this->listLiveClasses());

        $items = collect([...$myLiveClasses, ...$allLiveClasses])
            ->filter(fn (mixed $liveClass): bool => is_array($liveClass))
            ->unique(fn (array $liveClass): string => (string) ($liveClass['id'] ?? Str::uuid()->toString()))
            ->map(fn (array $liveClass): array => $this->normalizeLiveClassItem($liveClass))
            ->sortBy([
                ['status_weight', 'asc'],
                ['starts_at_sort', 'asc'],
            ])
            ->values()
            ->map(function (array $liveClass): array {
                unset($liveClass['status_weight'], $liveClass['starts_at_sort']);

                return $liveClass;
            })
            ->all();

        return [
            'items' => $items,
            'upcoming' => array_values(array_filter($items, fn (array $item): bool => ($item['status'] ?? null) === 'upcoming')),
            'liveNow' => array_values(array_filter($items, fn (array $item): bool => ($item['status'] ?? null) === 'live')),
            'recordings' => array_values(array_filter($items, fn (array $item): bool => ! empty($item['recording_url']))),
        ];
    }

    public function detail(int $liveClassId): array
    {
        $catalog = $this->catalog();
        $item = collect($catalog['items'])->firstWhere('id', (string) $liveClassId);

        if (! is_array($item)) {
            $item = $this->normalizeLiveClassItem([
                'id' => $liveClassId,
                'title' => 'Live Class #' . $liveClassId,
            ]);
        }

        $joinResponse = $this->joinLiveClass($liveClassId);
        $recordingResponse = $this->getLiveClassRecording($liveClassId);

        $joinPayload = $this->extractDetailPayload($joinResponse);
        $recordingPayload = $this->extractDetailPayload($recordingResponse);

        $joinUrl = $this->extractUrl($joinPayload, [
            'join_url',
            'url',
            'meeting_url',
            'link',
        ]);

        $recordingUrl = $this->extractUrl($recordingPayload, [
            'recording_url',
            'recording_link',
            'url',
            'link',
            'playback_url',
        ]) ?: ($item['recording_url'] ?? null);

        $item['join_url'] = $joinUrl ?: ($item['join_url'] ?? null);
        $item['recording_url'] = $recordingUrl;
        $item['join_enabled'] = ! empty($item['join_url']);
        $item['recording_enabled'] = ! empty($item['recording_url']);
        $item['recording_title'] = (string) ($recordingPayload['title'] ?? $recordingPayload['name'] ?? $item['title']);
        $item['meeting_code'] = (string) ($joinPayload['meeting_id'] ?? $joinPayload['meeting_code'] ?? $item['meeting_code'] ?? '');
        $item['passcode'] = (string) ($joinPayload['passcode'] ?? $joinPayload['password'] ?? $item['passcode'] ?? '');
        $item['raw_join'] = $joinPayload;
        $item['raw_recording'] = $recordingPayload;

        return $item;
    }

    public function listLiveClasses()
    {
        return $this->get('live-classes');
    }

    public function createLiveClass(array $data)
    {
        return $this->post('live-classes', $this->prepareLiveClassPayload($data));
    }

    public function updateLiveClass(int $id, array $data)
    {
        return $this->put("live-classes/{$id}", $this->prepareLiveClassPayload($data, false));
    }

    public function deleteLiveClass(int $id)
    {
        return $this->delete("live-classes/{$id}");
    }

    public function startLiveClass(int $id)
    {
        return $this->post("live-classes/{$id}/start");
    }

    public function listMyLiveClasses()
    {
        return $this->get('my-live-classes');
    }

    public function enrollInStandaloneLiveClass(int $id)
    {
        return $this->post("live-classes/{$id}/enroll");
    }

    public function joinLiveClass(int $id)
    {
        return $this->get("live-classes/{$id}/join");
    }

    public function getLiveClassRecording(int $id)
    {
        return $this->get("live-classes/{$id}/recording");
    }

    private function extractLiveClassItems(array $response): array
    {
        $payload = $response['data'] ?? [];

        while (is_array($payload) && isset($payload['data'])) {
            if (is_array($payload['data']) && array_is_list($payload['data'])) {
                $payload = $payload['data'];
                break;
            }

            $payload = $payload['data'];
        }

        if (! is_array($payload)) {
            return [];
        }

        return array_values(array_filter($payload, 'is_array'));
    }

    private function extractDetailPayload(array $response): array
    {
        $payload = $response['data'] ?? [];

        while (is_array($payload) && isset($payload['data']) && is_array($payload['data'])) {
            $payload = $payload['data'];
        }

        return is_array($payload) ? $payload : [];
    }

    private function normalizeLiveClassItem(array $liveClass): array
    {
        $startsAt = $this->normalizeDateTime(
            $liveClass['scheduled_at']
                ?? $liveClass['start_time']
                ?? $liveClass['starts_at']
                ?? $liveClass['meeting_time']
                ?? null
        );
            $endsAt = $this->normalizeDateTime($liveClass['end_time'] ?? $liveClass['ends_at'] ?? null);

        $status = $this->normalizeStatus($liveClass, $startsAt);
        $joinUrl = $this->extractUrl($liveClass, ['join_url', 'meeting_url', 'link', 'url']);
        $recordingUrl = $this->extractUrl($liveClass, ['recording_url', 'recording_link', 'playback_url']);
        $thumbnail = $this->extractUrl($liveClass, ['thumbnail', 'image', 'banner', 'cover_image']);
        $host = $liveClass['host_name']
            ?? $liveClass['mentor_name']
            ?? data_get($liveClass, 'host.name')
            ?? data_get($liveClass, 'teacher.name')
            ?? 'Astro Faculty';

        return [
            'id' => (string) ($liveClass['id'] ?? $liveClass['live_class_id'] ?? $liveClass['meeting_id'] ?? Str::uuid()->toString()),
            'title' => (string) ($liveClass['title'] ?? $liveClass['name'] ?? 'Live Class Session'),
            'description' => (string) ($liveClass['description'] ?? $liveClass['summary'] ?? $liveClass['agenda'] ?? ''),
            'host' => (string) $host,
            'status' => $status,
            'status_label' => $this->statusLabel($status),
            'starts_at' => $startsAt?->toIso8601String(),
            'starts_at_label' => $startsAt?->format('d M Y, h:i A') ?? 'Schedule will be announced',
            'ends_at' => $endsAt?->toIso8601String(),
            'ends_at_label' => $endsAt?->format('d M Y, h:i A') ?? null,
            'starts_at_sort' => $startsAt?->timestamp ?? PHP_INT_MAX,
            'timezone' => (string) ($liveClass['timezone'] ?? config('app.timezone', 'Asia/Kolkata')),
            'duration_label' => $this->normalizeDurationLabel($liveClass, $startsAt, $endsAt),
            'course_id' => $this->normalizeNullableInt($liveClass['course_id'] ?? data_get($liveClass, 'course.id')),
            'course_label' => (string) (data_get($liveClass, 'course.title') ?? data_get($liveClass, 'course.name') ?? (filled($liveClass['course_id'] ?? null) ? 'Course #' . $liveClass['course_id'] : 'Standalone Live Class')),
            'meeting_code' => (string) ($liveClass['meeting_code'] ?? $liveClass['meeting_id'] ?? ''),
            'passcode' => (string) ($liveClass['passcode'] ?? $liveClass['password'] ?? ''),
            'join_url' => $joinUrl,
            'recording_url' => $recordingUrl,
            'thumbnail' => $thumbnail ?: asset('assets/images/course-details.jpg'),
            'is_recorded' => (bool) ($liveClass['is_recorded'] ?? $liveClass['recorded'] ?? ! empty($recordingUrl)),
            'is_enrolled' => (bool) ($liveClass['is_enrolled'] ?? $liveClass['enrolled'] ?? ! empty($liveClass['enrollment_id']) ?? false),
            'join_enabled' => ! empty($joinUrl),
            'recording_enabled' => ! empty($recordingUrl),
            'status_weight' => match ($status) {
                'live' => 0,
                'upcoming' => 1,
                'completed' => 2,
                default => 3,
            },
            'raw' => $liveClass,
        ];
    }

    private function normalizeStatus(array $liveClass, ?Carbon $startsAt): string
    {
        $rawStatus = strtolower((string) ($liveClass['status'] ?? $liveClass['meeting_status'] ?? ''));

        if (in_array($rawStatus, ['live', 'ongoing', 'started'], true)) {
            return 'live';
        }

        if (in_array($rawStatus, ['completed', 'ended', 'finished'], true)) {
            return 'completed';
        }

        if ($startsAt instanceof Carbon) {
            return $startsAt->isFuture() ? 'upcoming' : 'completed';
        }

        return 'upcoming';
    }

    private function normalizeDateTime(mixed $value): ?Carbon
    {
        if (! is_scalar($value) || (string) $value === '') {
            return null;
        }

        try {
            return Carbon::parse((string) $value);
        } catch (\Throwable) {
            return null;
        }
    }

    private function statusLabel(string $status): string
    {
        return match ($status) {
            'live' => 'Live Now',
            'completed' => 'Recording Available',
            default => 'Upcoming',
        };
    }

    private function extractUrl(array $payload, array $keys): ?string
    {
        foreach ($keys as $key) {
            $value = data_get($payload, $key);

            if (is_string($value) && $value !== '') {
                return $value;
            }
        }

        return null;
    }

    private function prepareLiveClassPayload(array $payload, bool $isCreate = true): array
    {
        $prepared = [
            'title' => $payload['title'] ?? null,
            'description' => $payload['description'] ?? null,
            'course_id' => $payload['course_id'] ?? null,
            'start_time' => $payload['start_time'] ?? $payload['starts_at'] ?? null,
            'end_time' => $payload['end_time'] ?? $payload['ends_at'] ?? null,
            'is_recorded' => $payload['is_recorded'] ?? $payload['recorded'] ?? null,
        ];

        if (! $isCreate) {
            return array_filter($prepared, fn (mixed $value): bool => $value !== null);
        }

        return array_filter($prepared, fn (mixed $value): bool => $value !== null && $value !== '');
    }

    private function normalizeDurationLabel(array $liveClass, ?Carbon $startsAt, ?Carbon $endsAt): string
    {
        $label = $liveClass['duration'] ?? $liveClass['duration_label'] ?? $liveClass['session_duration'] ?? null;

        if (is_scalar($label) && (string) $label !== '') {
            return (string) $label;
        }

        if ($startsAt instanceof Carbon && $endsAt instanceof Carbon && $endsAt->greaterThan($startsAt)) {
            $minutes = $startsAt->diffInMinutes($endsAt);

            return $minutes >= 60 && $minutes % 60 === 0
                ? (int) ($minutes / 60) . ' hour' . ($minutes === 60 ? '' : 's')
                : $minutes . ' min';
        }

        return 'Live session';
    }

    private function normalizeNullableInt(mixed $value): ?int
    {
        return is_numeric($value) ? (int) $value : null;
    }
}

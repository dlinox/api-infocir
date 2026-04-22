<?php

namespace App\Modules\Learning\Instructor\Dashboard\Services;

use App\Models\Behavior\BehaviorProfile;
use App\Models\Core\Profile;
use App\Models\Learning\Certification;
use App\Models\Learning\Course;
use App\Models\Learning\Enrollment;
use App\Models\Learning\ProgramDelivery;
use App\Models\Learning\Training;
use App\Modules\Auth\Services\AuthService;

class DashboardService
{
    public function __construct(
        private AuthService $authService,
    ) {}

    public function getStats(): array
    {
        $instructorId = $this->getInstructorId();

        $trainings = Training::where('instructor_id', $instructorId);
        $trainingIds = (clone $trainings)->pluck('id');

        $activeTrainings = (clone $trainings)
            ->whereIn('status', ['scheduled', 'ongoing'])
            ->where('is_active', true)
            ->count();

        $completedTrainings = (clone $trainings)
            ->where('status', 'completed')
            ->count();

        $totalEnrollments = Enrollment::where('enrollable_type', 'learning_trainings')
            ->whereIn('enrollable_id', $trainingIds)
            ->count();

        $completedEnrollments = Enrollment::where('enrollable_type', 'learning_trainings')
            ->whereIn('enrollable_id', $trainingIds)
            ->where('status', 'completed')
            ->count();

        $certificationsIssued = Certification::whereHas('enrollment', function ($q) use ($trainingIds) {
            $q->where('enrollable_type', 'learning_trainings')
              ->whereIn('enrollable_id', $trainingIds);
        })->count();

        $programDeliveries = ProgramDelivery::where('instructor_id', $instructorId);

        $activeProgramDeliveries = (clone $programDeliveries)
            ->whereIn('status', ['scheduled', 'ongoing'])
            ->where('is_active', true)
            ->count();

        $coursesCreated = Course::where('created_by', auth()->id())->count();

        $upcomingTrainings = Training::with('course:id,name', 'trainingType:id,name')
            ->where('instructor_id', $instructorId)
            ->whereIn('status', ['scheduled', 'ongoing'])
            ->where('is_active', true)
            ->orderBy('start_date')
            ->limit(5)
            ->get()
            ->map(fn ($t) => [
                'id'           => $t->id,
                'courseName'   => $t->course?->name,
                'typeName'     => $t->trainingType?->name,
                'status'       => $t->status,
                'modality'     => $t->modality,
                'startDate'    => $t->start_date,
                'endDate'      => $t->end_date,
                'location'     => $t->location,
                'isEventOnly'  => $t->is_event_only,
                'participants' => Enrollment::where('enrollable_type', 'learning_trainings')
                    ->where('enrollable_id', $t->id)
                    ->count(),
                'maxParticipants' => $t->max_participants,
            ]);

        return [
            'stats' => [
                'activeTrainings'        => $activeTrainings,
                'completedTrainings'     => $completedTrainings,
                'totalEnrollments'       => $totalEnrollments,
                'completedEnrollments'   => $completedEnrollments,
                'certificationsIssued'   => $certificationsIssued,
                'activeProgramDeliveries'=> $activeProgramDeliveries,
                'coursesCreated'         => $coursesCreated,
            ],
            'upcomingTrainings' => $upcomingTrainings,
        ];
    }

    private function getInstructorId(): int
    {
        $behaviorProfileId = $this->authService->getProfileIdFromToken();

        $behaviorProfile = BehaviorProfile::findOrFail($behaviorProfileId);

        $profile = Profile::findOrFail($behaviorProfile->core_profile_id);

        return $profile->profileable_id;
    }
}

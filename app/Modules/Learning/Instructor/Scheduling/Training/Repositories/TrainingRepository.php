<?php

namespace App\Modules\Learning\Instructor\Scheduling\Training\Repositories;

use App\Models\Learning\Training;

class TrainingRepository
{
    public function dataTable($request)
    {
        $query = Training::query()
            ->select(
                'learning_trainings.*',
                'learning_courses.name as course_name',
                'core_persons.name as instructor_name',
                'core_persons.paternal_surname as instructor_paternal_surname',
                'core_persons.maternal_surname as instructor_maternal_surname',
                'learning_training_types.name as training_type_name',
            )
            ->leftJoin('learning_courses', 'learning_courses.id', '=', 'learning_trainings.course_id')
            ->leftJoin('learning_instructors', 'learning_instructors.id', '=', 'learning_trainings.instructor_id')
            ->leftJoin('core_persons', 'core_persons.id', '=', 'learning_instructors.person_id')
            ->leftJoin('learning_training_types', 'learning_training_types.id', '=', 'learning_trainings.training_type_id');

        if (empty($request->sortBy) || !isset($request->sortBy)) {
            $query->orderBy('learning_trainings.id', 'desc');
        }

        return $query->dataTable($request);
    }

    public function findById(int $id): Training
    {
        return Training::with([
            'course',
            'instructor.person',
            'trainingType',
            'certificateTemplate',
        ])->findOrFail($id);
    }

    public function createOrUpdate(array $data): Training
    {
        if (isset($data['id'])) {
            $training = Training::findOrFail($data['id']);
            $training->update($data);
            return $training;
        }

        return Training::create($data);
    }

    public function delete(int $id): Training
    {
        $training = Training::findOrFail($id);
        $training->delete();
        return $training;
    }
}

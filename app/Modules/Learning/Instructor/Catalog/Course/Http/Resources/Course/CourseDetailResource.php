<?php

namespace App\Modules\Learning\Instructor\Catalog\Course\Http\Resources\Course;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CourseDetailResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'            => $this->id,
            'name'          => $this->name,
            'description'   => $this->description,
            'area'          => $this->area ? ['id' => $this->area->id, 'name' => $this->area->name] : null,
            'coverImage'    => $this->coverImageFile ? [
                'id'       => $this->coverImageFile->id,
                'url'      => $this->coverImageFile->url,
                'filename' => $this->coverImageFile->filename,
            ] : null,
            'durationMin'   => $this->duration_min,
            'status'        => $this->status,
            'createdAt'     => $this->created_at?->format('Y-m-d H:i:s'),
            'modules'       => $this->modules->map(fn ($m) => [
                'id'          => $m->id,
                'title'       => $m->title,
                'description' => $m->description,
                'order'       => $m->order,
                'isActive'    => $m->is_active,
                'lessons'     => $m->lessons->map(fn ($l) => [
                    'id'           => $l->id,
                    'title'        => $l->title,
                    'description'  => $l->description,
                    'order'        => $l->order,
                    'hasQuiz'      => $l->has_quiz,
                    'passingScore' => $l->passing_score,
                    'isActive'     => $l->is_active,
                    'resources'    => $l->resources->map(fn ($r) => [
                        'id'       => $r->id,
                        'type'     => $r->type,
                        'title'    => $r->title,
                        'fileId'   => $r->file_id,
                        'body'     => $r->body,
                        'order'    => $r->order,
                        'isActive' => $r->is_active,
                        'file'     => $r->file ? [
                            'id'       => $r->file->id,
                            'url'      => $r->file->url,
                            'filename' => $r->file->filename,
                        ] : null,
                    ])->toArray(),
                    'quizQuestions' => $l->quizQuestions->map(fn ($q) => [
                        'id'       => $q->id,
                        'question' => $q->question,
                        'hint'     => $q->hint,
                        'order'    => $q->order,
                        'options'  => $q->options->map(fn ($o) => [
                            'id'          => $o->id,
                            'text'        => $o->text,
                            'isCorrect'   => $o->is_correct,
                            'explanation' => $o->explanation,
                            'order'       => $o->order,
                        ])->toArray(),
                    ])->toArray(),
                ])->toArray(),
            ])->toArray(),
        ];
    }
}

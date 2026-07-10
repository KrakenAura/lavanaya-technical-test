<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SubmissionResource extends JsonResource
{
    public function toArray(Request $request)
    {
        return [
            'id' => $this->id,

            'submission_number' => $this->submission_number,

            'title' => $this->title,

            'description' => $this->description,

            'amount' => $this->amount,

            'status' => $this->status,

            'category' => [
                'id' => $this->category?->id,
                'name' => $this->category?->name,
            ],

            'created_at' => $this->created_at,
        ];
    }
}

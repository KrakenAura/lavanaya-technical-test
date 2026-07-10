<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSubmissionRequest;
use App\Http\Requests\UpdateSubmissionRequest;
use App\Models\Submission;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Resources\SubmissionResource;

class SubmissionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $submissions = $request->user()
            ->submissions()
            ->with([
                'category',
            ])
            ->latest()
            ->get();

        return SubmissionResource::collection($submissions);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSubmissionRequest $request)
    {
        $this->authorize('create', Submission::class);

        $submission = Submission::create([
            'user_id' => $request->user()->getKey(),

            'category_id' => $request->category_id,

            'submission_number' => 'EXP-' . time(),

            'title' => $request->title,

            'description' => $request->description,

            'amount' => $request->amount,

            'status' => 'draft',
        ]);

        $submission->load('category');

        return response()->json([
            'message' => 'Submission created successfully',
            'data' => new SubmissionResource($submission),
        ], 201);
    }


    /**
     * Display the specified resource.
     */
    public function show(Submission $submission)
    {
        $this->authorize('view', $submission);

        return new SubmissionResource(
            $submission->load([
                'category',
                'user',
                'approvals',
            ])
        );
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSubmissionRequest $request,Submission $submission)
    {

        $this->authorize('update', $submission);

        $submission->update(
            $request->validated()
        );

        return response()->json([
            'message' => 'Submission updated successfully',
            'data' => new SubmissionResource($submission),
        ]);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Submission $submission)
    {
        $this->authorize('delete', $submission);

        $submission->delete();

        return response()->json([
            'message' => 'Submission deleted successfully',
        ]);
    }
}

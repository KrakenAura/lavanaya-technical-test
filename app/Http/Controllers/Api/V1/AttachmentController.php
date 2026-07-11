<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Submission;
use App\Models\Attachment;
use Illuminate\Support\Facades\Storage;

class AttachmentController extends Controller
{
    public function store(Request $request,Submission $submission
    ){

        $request->validate([
            'file' => [
                'required',
                'file',
                'mimes:pdf,jpg,jpeg,png',
                'max:5120',
            ],
        ]);


        $file = $request->file('file');


        $path = $file->store(
            'submissions',
            'public'
        );


        $attachment = Attachment::create([
            'submission_id' => $submission->id,
            'file_name' => $file->getClientOriginalName(),
            'file_path' => $path,
            'file_type' => $file->getMimeType(),
            'file_size' => $file->getSize(),
        ]);


        return response()->json([
            'message' => 'Attachment uploaded successfully',
            'data' => $attachment,
        ]);
    }

    public function destroy(
        Attachment $attachment
    ) {
        $submission = $attachment->submission;


        abort_if(
            $submission->status !== Submission::DRAFT &&
                $submission->status !== Submission::SUBMITTED,
            403,
            'Attachment cannot be deleted after approval process started'
        );


        Storage::disk('public')
            ->delete($attachment->file_path);


        $attachment->delete();


        return response()->json([
            'message' => 'Attachment deleted successfully'
        ]);
    }
}

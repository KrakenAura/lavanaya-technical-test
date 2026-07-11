<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Submission;
use App\Models\Attachment;

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
}

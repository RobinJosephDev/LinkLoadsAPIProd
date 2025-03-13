<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class FileUploadController extends Controller
{
    public function uploadFile(Request $request)
    {
        try {
            Log::info('Incoming file upload request:', $request->all());

            // ✅ Validate both brok_carr_aggmt and coi_cert
            $request->validate([
                'brok_carr_aggmt' => 'nullable|file|mimes:doc,docx,pdf,jpg,jpeg,png,gif,tiff,bmp|max:10240',
                'coi_cert' => 'nullable|file|mimes:doc,docx,pdf,jpg,jpeg,png,gif,tiff,bmp|max:10240',
            ], [
                'brok_carr_aggmt.mimes' => 'Only DOC, DOCX, PDF, JPG, PNG, and BMP files are allowed.',
                'brok_carr_aggmt.max' => 'File size cannot exceed 10MB.',
                'coi_cert.mimes' => 'Only DOC, DOCX, PDF, JPG, PNG, and BMP files are allowed.',
                'coi_cert.max' => 'File size cannot exceed 10MB.',
            ]);

            $uploadedFiles = [];

            if ($request->hasFile('brok_carr_aggmt')) {
                $file = $request->file('brok_carr_aggmt');

                if ($file->isValid()) {
                    $filePath = $file->store('carrier_agreements', 'public');
                    $uploadedFiles['brok_carr_aggmt'] = asset("storage/$filePath");
                    Log::info('brok_carr_aggmt successfully uploaded: ' . $uploadedFiles['brok_carr_aggmt']);
                    return response()->json(['fileUrl' => Storage::url($filePath)]);

                } else {
                    Log::error('brok_carr_aggmt upload failed.');
                    return response()->json(['error' => 'Invalid brok_carr_aggmt file'], 400);
                }
            }

            if ($request->hasFile('coi_cert')) {
                $file = $request->file('coi_cert');

                if ($file->isValid()) {
                    $filePath = $file->store('coi_certificates', 'public');
                    $uploadedFiles['coi_cert'] = asset("storage/$filePath");
                    Log::info('coi_cert successfully uploaded: ' . $uploadedFiles['coi_cert']);
                    return response()->json(['fileUrl' => Storage::url($filePath)]);

                } else {
                    Log::error('coi_cert upload failed.');
                    return response()->json(['error' => 'Invalid coi_cert file'], 400);
                }
            }

            if (empty($uploadedFiles)) {
                Log::error('No files were uploaded.');
                return response()->json(['error' => 'No files uploaded'], 400);
            }

            return response()->json(['files' => $uploadedFiles]);
        } catch (\Exception $e) {
            Log::error('Exception during file upload: ' . $e->getMessage());
            return response()->json(['error' => 'File upload failed', 'message' => $e->getMessage()], 500);
        }
    }
}

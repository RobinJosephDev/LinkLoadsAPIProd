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
            // Log the incoming request
            Log::info('Incoming file upload request:', $request->all());
            
            // Validate the uploaded file
            $request->validate([
                'file' => 'nullable|file|mimes:jpg,jpeg,png,pdf,docx|max:10240',
            ]);
    
            // Check if a file is uploaded
            if ($request->hasFile('file') && $request->file('file')->isValid()) {
                // Store the file in the "uploads" directory
                $filePath = $request->file('file')->store('uploads', 'public');
    
                // Print the full URL for debugging purposes
                $fullUrl = url(Storage::url($filePath));
                Log::info('File URL: ' . $fullUrl);
    
                // Return the full URL
                return response()->json([
                    'fileUrl' => $fullUrl,
                ]);
            }
    
            // If no valid file, log the error and return a response
            Log::error('No valid file uploaded.');
            return response()->json(['error' => 'File upload failed'], 400);
        } catch (\Exception $e) {
            // Log any unexpected errors
            Log::error('File upload exception: ' . $e->getMessage());
            return response()->json(['error' => 'File upload failed'], 500);
        }
    }
    
}

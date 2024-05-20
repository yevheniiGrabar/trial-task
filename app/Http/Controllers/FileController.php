<?php

namespace App\Http\Controllers;

use App\Models\File;
use Illuminate\Http\Request;

class FileController extends Controller
{
    public function uploadPdf(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:pdf',
        ]);

        $file = $request->file('file');

        // Check if the PDF contains the word "Proposal"
        // Pseudo code:
        if (!$this->pdfService->searchFor($file->getPathname(), 'Proposal')) {
            return response()->json(['error' => 'PDF does not contain the word "Proposal"'], 422);
        }

        $fileName = $file->getClientOriginalName();
        $fileSize = $file->getSize();

        // Check if the file already exists in the database
        $existingFile = File::query()->where('name', $fileName)
            ->where('size', $fileSize)
            ->first();

        if ($existingFile) {
            // Update the existing file record
            $existingFile->update([
                'file_path' => $file->store('pdfs'),
                'updated_at' => now(),
            ]);

            return response()->json(['message' => 'File updated successfully']);
        } else {
            // Store the new file record
            File::query()->create([
                'name' => $fileName,
                'size' => $fileSize,
                'file_path' => $file->store('pdfs'),
                'created_at' => now(),
            ]);

            return response()->json(['message' => 'File uploaded successfully']);
        }
    }
}

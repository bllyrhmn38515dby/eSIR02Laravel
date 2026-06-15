<?php

namespace App\Http\Controllers;

use App\Models\Referral;
use App\Models\ReferralDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    public function store(Request $request, Referral $referral)
    {
        $request->validate([
            'document' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'document_type' => 'required|string',
        ]);

        $path = $request->file('document')->store('referral_documents', 'public');

        ReferralDocument::create([
            'referral_id' => $referral->id,
            'file_path' => $path,
            'document_type' => $request->document_type,
            'uploaded_by' => auth()->id(),
        ]);

        return back()->with('success', 'Dokumen berhasil diunggah.');
    }

    public function download(ReferralDocument $document)
    {
        // Simple authorization check
        $user = auth()->user();
        if ($user->role !== 'admin_pusat' && 
            $user->faskes_id !== $document->referral->from_faskes_id && 
            $user->faskes_id !== $document->referral->to_faskes_id &&
            $user->role !== 'driver') {
            abort(403);
        }

        return Storage::disk('public')->download($document->file_path);
    }
    
    public function destroy(ReferralDocument $document)
    {
        if ($document->uploaded_by !== auth()->id()) {
            abort(403);
        }
        
        Storage::disk('public')->delete($document->file_path);
        $document->delete();
        
        return back()->with('success', 'Dokumen dihapus.');
    }
}

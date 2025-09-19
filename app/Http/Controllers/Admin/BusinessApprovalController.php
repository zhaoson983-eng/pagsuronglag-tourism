<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BusinessProfile;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\BusinessStatusUpdated;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BusinessApprovalController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin');
    }

    /**
     * Display a listing of pending business approvals.
     */
    public function index()
    {
        $pendingBusinesses = BusinessProfile::with('user')
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $approvedBusinesses = BusinessProfile::with('user')
            ->where('status', 'approved')
            ->orderBy('approved_at', 'desc')
            ->paginate(10, ['*'], 'approved_page');

        $rejectedBusinesses = BusinessProfile::with('user')
            ->where('status', 'rejected')
            ->orderBy('updated_at', 'desc')
            ->paginate(10, ['*'], 'rejected_page');

        return view('admin.business-approvals.index', compact(
            'pendingBusinesses',
            'approvedBusinesses',
            'rejectedBusinesses'
        ));
    }

    /**
     * Display the specified business profile for review.
     */
    public function show(BusinessProfile $business)
    {
        $business->load('user');
        
        // Get the business permit file URL
        $businessPermitUrl = Storage::url($business->business_permit_path);
        
        // Get any additional licenses
        $licenses = [];
        if (!empty($business->licenses)) {
            foreach ($business->licenses as $license) {
                $licenses[] = [
                    'url' => Storage::url($license['path']),
                    'name' => $license['original_name']
                ];
            }
        }

        return view('admin.business-approvals.show', compact(
            'business',
            'businessPermitUrl',
            'licenses'
        ));
    }

    /**
     * Approve a business profile.
     */
    public function approve(Request $request, BusinessProfile $business)
    {
        $request->validate([
            'notes' => 'nullable|string|max:1000',
        ]);

        return $this->updateBusinessStatus($business, 'approved', $request->notes);
    }

    /**
     * Reject a business profile.
     */
    public function reject(Request $request, BusinessProfile $business)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:1000',
        ]);

        return $this->updateBusinessStatus($business, 'rejected', $request->rejection_reason);
    }

    /**
     * Update the business status and notify the owner.
     */
    protected function updateBusinessStatus(BusinessProfile $business, string $status, ?string $notes = null)
    {
        $payload = [
            'status' => $status,
            'rejection_reason' => $status === 'rejected' ? $notes : null,
            'approved_at' => $status === 'approved' ? now() : $business->approved_at,
            'approved_by' => $status === 'approved' ? auth()->id() : $business->approved_by,
        ];

        // Auto-publish when approved so it becomes visible to customers
        if ($status === 'approved') {
            $payload['is_published'] = true;
        }

        $business->update($payload);

        // Mirror publish flag to Business entity used for product visibility
        if ($status === 'approved') {
            \App\Models\Business::where('owner_id', $business->user_id)->update(['is_published' => true]);
        }

        // Notify the business owner (do not fail the request if mail transport is unavailable)
        try {
            $this->notifyBusinessOwner($business, $status, $notes);
        } catch (\Throwable $e) {
            \Log::warning('Failed to send business status email', [
                'business_id' => $business->id,
                'status' => $status,
                'error' => $e->getMessage(),
            ]);
        }

        return redirect()
            ->route('admin.business-approvals.index')
            ->with('success', "Business has been {$status} successfully.");
    }

    /**
     * Send notification to business owner about status update.
     */
    protected function notifyBusinessOwner(BusinessProfile $business, string $status, ?string $notes = null)
    {
        $user = $business->user;
        
        // Send email notification
        Mail::to($user->email)->send(new BusinessStatusUpdated($business, $status, $notes));
        
        // You can also add in-app notifications here
        // $user->notify(new BusinessStatusUpdatedNotification($business, $status, $notes));
    }

    /**
     * Download a business document.
     */
    public function downloadDocument(BusinessProfile $business, string $type, int $index = null)
    {
        $this->authorize('view', $business);
        
        if ($type === 'permit') {
            $filePath = $business->business_permit_path;
            $fileName = 'business_permit_' . Str::slug($business->business_name) . '.' . 
                pathinfo($filePath, PATHINFO_EXTENSION);
        } elseif ($type === 'license' && $index !== null && isset($business->licenses[$index])) {
            $filePath = $business->licenses[$index]['path'];
            $fileName = $business->licenses[$index]['original_name'];
        } else {
            abort(404);
        }

        if (!Storage::exists($filePath)) {
            abort(404);
        }

        return Storage::download($filePath, $fileName);
    }

    /**
     * Toggle business published status.
     */
    public function togglePublish(BusinessProfile $business)
    {
        $business->update([
            'is_published' => !$business->is_published,
            'published_at' => $business->is_published ? null : now(),
        ]);

        $status = $business->is_published ? 'published' : 'unpublished';
        
        return back()->with('success', "Business has been {$status} successfully.");
    }
}

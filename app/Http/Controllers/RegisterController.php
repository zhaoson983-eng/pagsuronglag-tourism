<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\BusinessProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class RegisterController extends Controller
{
    // Show the registration form
    public function showRegistrationForm()
    {
        return view('auth.register'); // Looks in resources/views/auth/register.blade.php
    }

    // Handle registration form submission
    public function register(Request $request)
    {
        // Base validation rules
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => ['required', Rule::in([User::ROLE_CUSTOMER, User::ROLE_BUSINESS_OWNER])],
        ];

        // Add business-specific validation if the user is registering as a business owner
        // Important: We only require selecting the business_type at registration time.
        // All other business details (name, permits, address, etc.) are collected in the setup flow.
        if ($request->role === User::ROLE_BUSINESS_OWNER) {
            $rules = array_merge($rules, [
                'business_type' => ['required', Rule::in([
                    BusinessProfile::TYPE_LOCAL_PRODUCTS,
                    BusinessProfile::TYPE_HOTEL,
                    BusinessProfile::TYPE_RESORT
                ])],
            ]);
        }

        // Validate the request
        $validated = $request->validate($rules);

        // Start database transaction
        return \DB::transaction(function () use ($request, $validated) {
            // Create the user
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role' => $validated['role'],
                // Persist chosen business type onto the user for downstream checks (e.g., isHotelOwner)
                'business_type' => ($validated['role'] === User::ROLE_BUSINESS_OWNER)
                    ? ($validated['business_type'] ?? null)
                    : null,
            ]);

            // Log the user in
            Auth::login($user);

            // Redirect based on user role
            return $this->redirectAfterRegistration($user, $request);
        });
    }

    /**
     * Create a business profile for the registered user.
     */
    protected function createBusinessProfile(User $user, Request $request, array $validated): void
    {
        // Start database transaction
        \DB::beginTransaction();
        
        try {
            // Upload business permit
            $businessPermitPath = $this->uploadFile($request->file('business_permit'), 'business_permits');
            
            // Create the business profile
            $businessProfile = new BusinessProfile([
                'business_type' => $validated['business_type'],
                'business_name' => $validated['business_name'],
                'business_permit_path' => $businessPermitPath,
                'contact_number' => $validated['contact_number'],
                'address' => $validated['address'],
                'city' => $validated['city'],
                'province' => $validated['province'],
                'postal_code' => $validated['postal_code'],
                'status' => BusinessProfile::STATUS_PENDING,
                'documents' => []
            ]);

            // Save the business profile
            $user->businessProfile()->save($businessProfile);
            
            // Commit the transaction
            \DB::commit();
            
        } catch (\Exception $e) {
            // Rollback the transaction on error
            \DB::rollBack();
            \Log::error('Error creating business profile: ' . $e->getMessage());
            throw $e; // Re-throw the exception to be handled by the global exception handler
        }
    }

    /**
     * Upload a file to storage.
     */
    protected function uploadFile($file, string $directory): string
    {
        $fileName = Str::random(20) . '_' . time() . '.' . $file->getClientOriginalExtension();
        return $file->storeAs($directory, $fileName, 'public');
    }

    /**
     * Upload additional files (licenses or business documents).
     */
    protected function uploadAdditionalFiles(BusinessProfile $businessProfile, array $files, string $type = 'licenses'): void
    {
        $uploadedFiles = [];
        
        foreach ($files as $file) {
            if ($file->isValid()) {
                $directory = $type === 'licenses' ? 'business_licenses' : 'business_documents';
                $uploadedFiles[] = [
                    'path' => $this->uploadFile($file, $directory),
                    'original_name' => $file->getClientOriginalName(),
                    'mime_type' => $file->getClientMimeType(),
                    'size' => $file->getSize(),
                    'type' => $type,
                    'uploaded_at' => now()->toDateTimeString(),
                ];
            }
        }

        if (!empty($uploadedFiles)) {
            // Get existing files of this type
            $existingFiles = $businessProfile->documents ?: [];
            
            // Merge with new files
            $allFiles = array_merge($existingFiles, $uploadedFiles);
            
            // Update the documents field
            $businessProfile->documents = $allFiles;
            $businessProfile->save();
        }
    }

    /**
     * Determine the redirect path after registration.
     */
    protected function redirectAfterRegistration(User $user, Request $request)
    {
        if ($user->isBusinessOwner()) {
            // Persist selected business type to session and redirect to setup
            if ($request->filled('business_type')) {
                session(['business_type' => $request->input('business_type')]);
            }
            return redirect()->route('business.setup')
                ->with('success', 'Your account has been created! Please complete your business profile setup.');
        }

        // For customers
        return redirect()->route('profile.setup')
            ->with('success', 'Your account has been created! Please complete your profile setup.');
    }
}
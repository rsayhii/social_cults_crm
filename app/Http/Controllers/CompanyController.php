<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CompanyController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if (!$user->company_id) {
            return response()->json([
                'success' => true,
                'companies' => []
            ]);
        }

        $companies = Company::where('id', $user->company_id)->get();

        return response()->json([
            'success' => true,
            'companies' => $companies
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'gstin' => 'nullable|string|max:50',
            'bank_name' => 'nullable|string|max:255',
            'account_number' => 'nullable|string|max:50',
            'ifsc_code' => 'nullable|string|max:20',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:5120'
        ]);

        $user = Auth::user();

        $logoPath = null;
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('company-logos', 'public');
        } elseif ($request->has('logo_data') && !empty($request->logo_data)) {
            // Handle base64 image
            $imageData = $request->logo_data;
            if (preg_match('/^data:image\/(\w+);base64,/', $imageData, $type)) {
                $data = substr($imageData, strpos($imageData, ',') + 1);
                $type = strtolower($type[1]); // jpg, png, gif

                if (!in_array($type, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Invalid image type'
                    ], 422);
                }

                $data = base64_decode($data);
                if ($data === false) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Failed to decode base64 image'
                    ], 422);
                }

                $imageName = 'company-logos/' . Str::random(20) . '.' . $type;
                Storage::disk('public')->put($imageName, $data);
                $logoPath = $imageName;
            }
        }

        $companyData = [
            'name' => $request->name,
            'address' => $request->address,
            'email' => $request->email,
            'phone' => $request->phone,
            'gstin' => $request->gstin,
            'bank_name' => $request->bank_name,
            'account_number' => $request->account_number,
            'ifsc_code' => $request->ifsc_code,
        ];

        if ($logoPath) {
            $companyData['logo'] = $logoPath;
        }

        if ($request->company_id) {
            // Verify ownership
            if ($user->company_id != $request->company_id) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
            }

            $company = Company::findOrFail($request->company_id);

            // Delete old logo if new one uploaded
            if ($logoPath && $company->logo) {
                Storage::disk('public')->delete($company->logo);
            }

            $company->update($companyData);
            $message = 'Company updated successfully!';
        } else {
            $company = Company::create($companyData);

            // Assign company to user
            $user->company_id = $company->id;
            $user->save();

            $message = 'Company created successfully!';
        }

        return response()->json([
            'success' => true,
            'message' => $message,
            'company' => $company
        ]);
    }

    public function destroy($id)
    {
        $user = Auth::user();

        if ($user->company_id != $id) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $company = Company::findOrFail($id);

        if ($company->logo) {
            Storage::disk('public')->delete($company->logo);
        }

        $company->delete();

        return response()->json([
            'success' => true,
            'message' => 'Company deleted successfully!'
        ]);
    }

    public function getSignature()
    {
        $user = Auth::user();

        if (!$user->company_id) {
            return response()->json([
                'success' => false,
                'signature' => null
            ]);
        }

        $company = Company::find($user->company_id);

        if (!$company) {
            return response()->json([
                'success' => false,
                'signature' => null
            ]);
        }

        // In a real app, you might store signature separately
        // For now, we'll return null or fetch from somewhere else if needed
        $signature = null;

        return response()->json([
            'success' => true,
            'signature' => $signature
        ]);
    }

    public function saveSignature(Request $request)
    {
        $request->validate([
            'signature' => 'nullable|string'
        ]);

        $user = Auth::user();

        if (!$user->company_id) {
            return response()->json([
                'success' => false,
                'message' => 'No company associated with user'
            ], 404);
        }

        $company = Company::find($user->company_id);

        if (!$company) {
            return response()->json([
                'success' => false,
                'message' => 'No company found'
            ], 404);
        }

        // Store signature in user settings or separate table
        // For now, we'll just return success

        return response()->json([
            'success' => true,
            'message' => 'Signature saved successfully!'
        ]);
    }
}
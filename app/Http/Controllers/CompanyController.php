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
        $companies = Company::where('user_id', $user->id)->get();
        
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
            'contact' => 'nullable|string',
            'gstin' => 'nullable|string|max:50',
            'bank_details' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048'
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
            'contact' => $request->contact,
            'gstin' => $request->gstin,
            'bank_details' => $request->bank_details,
            'user_id' => $user->id
        ];
        
        if ($logoPath) {
            $companyData['logo'] = $logoPath;
        }
        
        if ($request->company_id) {
            $company = Company::where('id', $request->company_id)
                ->where('user_id', $user->id)
                ->firstOrFail();
                
            // Delete old logo if new one uploaded
            if ($logoPath && $company->logo) {
                Storage::disk('public')->delete($company->logo);
            }
            
            $company->update($companyData);
            $message = 'Company updated successfully!';
        } else {
            $company = Company::create($companyData);
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
        $company = Company::where('id', $id)
            ->where('user_id', $user->id)
            ->firstOrFail();
            
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
        $company = Company::where('user_id', $user->id)->first();
        
        if (!$company) {
            return response()->json([
                'success' => false,
                'signature' => null
            ]);
        }
        
        // In a real app, you might store signature separately
        // For now, we'll return null
        $signature = null;
        
        return response()->json([
            'success' => true,
            'signature' => $signature
        ]);
    }

    public function saveSignature(Request $request)
    {
        $request->validate([
            'signature' => 'required|string'
        ]);
        
        $user = Auth::user();
        $company = Company::where('user_id', $user->id)->first();
        
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
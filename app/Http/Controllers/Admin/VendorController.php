<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin_Data\VendorModel;
use Illuminate\Http\Request;
use App\Helpers\Functions;

class VendorController extends Controller
{

    public function add_vendor(Request $request)
    {
        $validatedData = $request->validate([
            'user_id' => 'nullable',
            'name' => 'required|string|max:255',
            'business_name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'contact_number' => 'required|string|max:11',
            'email' => 'required|email|max:255',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:512',
            'valid_id_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:512',
            'username' => 'required|string|max:255|unique:vendors',
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
                'regex:/[a-z]/',     // at least one lowercase letter
                'regex:/[A-Z]/',     // at least one uppercase letter
                'regex:/[0-9]/',     // at least one digit
                'regex:/[@$!%*?&]/'  // at least one special character
            ],
            'password_confirmation' => 'nullable|string|min:8',
            'agency_affiliation' => 'required|string|max:255',
            'agency_affiliation_name' => [
                'nullable',
                'required_if:agency_affiliation,yes'
            ],
            'user_role' => 'nullable|string|max:255',
            'approved_by' => 'nullable|string|max:255',
            'date' => 'nullable|date'
        ]);

        // $latestVendor = VendorModel::orderBy('id', 'desc')->first();
        // $nextId = $latestVendor ? $latestVendor->id + 1 : 1;
        // $formattedId = 'VNDR-' . str_pad($nextId, 4, '0', STR_PAD_LEFT);



        $data = $validatedData;
        $data['user_id'] = Functions::IDGenerator(new VendorModel, 'user_id', 5, 'VNDR');
        $data['user_role'] = $data['user_role'] ?? 'Vendor';
        $data['date'] = $data['date'] ?? date('Y-m-d');
        $data['status'] = $data['status'] ?? 'For approval';
        $data['profile_picture'] = $data['profile_picture'] ?? '';
        $data['valid_id_picture'] = $data['valid_id_picture'] ?? '';
        $data['approved_by'] = $data['approved_by'] ?? '';


        try {
            VendorModel::create($data);

            $success = ['status' => 'success', 'user_id' => $data['user_id']];
            return redirect()->route('pages.vendor', $success);
        } catch (\Exception $e) {
            $error = ['status' => 'error', 'user_id' => $data['user_id']];
            return redirect()->route('create.vendor', $error);
        }
    }
}
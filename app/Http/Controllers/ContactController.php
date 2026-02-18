<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;



class ContactController extends Controller
{
    /**
     * Apply policy automatically (manage)
     */
   private function baseQuery()
{
    return Contact::where('company_id', auth()->user()->company_id);
}


    /**
     * Display contacts list
     */
    public function index()
{
    $sortBy = request('sort');
    $query = $this->baseQuery();

    switch ($sortBy) {
        case 'oldest':
            $query->oldest();
            break;
        case 'a-z':
            $query->orderBy('name', 'asc');
            break;
        case 'z-a':
            $query->orderBy('name', 'desc');
            break;
        case 'last7':
            $query->where('created_at', '>=', now()->subDays(7));
            break;
        default:
            $query->latest();
    }

    $contacts = $query->get();

    return request()->wantsJson()
        ? response()->json(['success' => true, 'contacts' => $contacts])
        : view('admin.contact', compact('contacts'));
}


    /**
     * Store contact
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'       => 'nullable|string|max:255',
            'position'   => 'nullable|string|max:255',
            'email'      => 'nullable|email|unique:contacts,email',
            'phone'      => 'nullable|string|max:20',
            'country'    => 'nullable|string|max:100',
            'avatar'     => 'nullable|string',
            'instagram'  => 'nullable|string|max:255',
            'facebook'   => 'nullable|string|max:255',
            'whatsapp'   => 'nullable|string|max:255',
            'linkedin'   => 'nullable|string|max:255',
            'notes'      => 'nullable|string',
        ]);

        // Default rating
        $validated['rating'] = number_format(rand(40, 50) / 10, 1);

        if ($request->has('avatar') && str_contains($request->avatar, 'data:image')) {
            $validated['avatar'] = $this->saveBase64Image($request->avatar);
        }

        $contact = Contact::create($validated);

// notifyCompany(auth()->user()->company_id, [
//     'title' => 'New Contact Added',
//     'message' => $contact->name . ' was added',
//     'module' => 'contact',
//     'url' => route('contacts.index'),
//     'icon' => 'user',
// ]);


        return response()->json([
            'success' => true,
            'contact' => $contact,
            'message' => 'Contact added successfully!'
        ], 201);
    }

    /**
     * Show single contact
     */
  public function show($id)
{
    $contact = $this->baseQuery()->findOrFail($id);

    return response()->json([
        'success' => true,
        'contact' => $contact
    ]);
}


    /**
     * Update contact
     */
    public function update(Request $request, $id)
{
    $contact = $this->baseQuery()->findOrFail($id);

    $validated = $request->validate([
        'name' => 'nullable|string|max:255',
        'position' => 'nullable|string|max:255',
        'email' => 'nullable|email|unique:contacts,email,' . $contact->id,
        'phone' => 'nullable|string|max:20',
        'country' => 'nullable|string|max:100',
        'avatar' => 'nullable|string',
        'instagram' => 'nullable|string|max:255',
        'facebook' => 'nullable|string|max:255',
        'whatsapp' => 'nullable|string|max:255',
        'linkedin' => 'nullable|string|max:255',
        'notes' => 'nullable|string',
    ]);

    if ($request->has('avatar') && str_contains($request->avatar, 'data:image')) {
        $validated['avatar'] = $this->saveBase64Image($request->avatar);
    }

    $contact->update($validated);

    return response()->json([
        'success' => true,
        'contact' => $contact,
        'message' => 'Contact updated successfully!'
    ]);
}


    /**
     * Delete contact
     */
   public function destroy($id)
{
    $contact = $this->baseQuery()->findOrFail($id);

    if ($contact->avatar && !str_contains($contact->avatar, 'via.placeholder.com')) {
        Storage::disk('public')->delete(
            str_replace('/storage/', '', $contact->avatar)
        );
    }

    $contact->delete();

    return response()->json([
        'success' => true,
        'message' => 'Contact deleted successfully!'
    ]);
}


    /**
     * Export contacts
     */
    public function export($type)
{
    $contacts = $this->baseQuery()->get();

    if ($type === 'csv') {
        $csv = "Name,Email,Phone,Position,Country,Instagram,Facebook,Whatsapp,Linkedin,Notes\n";

        foreach ($contacts as $c) {
            $csv .= "{$c->name},{$c->email},{$c->phone},{$c->position},{$c->country},{$c->instagram},{$c->facebook},{$c->whatsapp},{$c->linkedin},{$c->notes}\n";
        }

        return response($csv, 200, [
            "Content-Type" => "text/csv",
            "Content-Disposition" => "attachment; filename=contacts.csv"
        ]);
    }

    if ($type === 'excel') {
        $excel = "Name\tEmail\tPhone\tPosition\tCountry\tInstagram\tFacebook\tWhatsapp\tLinkedin\tNotes\n";

        foreach ($contacts as $c) {
            $excel .= "{$c->name}\t{$c->email}\t{$c->phone}\t{$c->position}\t{$c->country}\t{$c->instagram}\t{$c->facebook}\t{$c->whatsapp}\t{$c->linkedin}\t{$c->notes}\n";
        }

        return response($excel, 200, [
            "Content-Type" => "application/vnd.ms-excel",
            "Content-Disposition" => "attachment; filename=contacts.xls"
        ]);
    }

    if ($type === 'pdf') {
        $pdf = Pdf::loadView('exports.contacts-pdf', compact('contacts'));
        return $pdf->download('contacts.pdf');
    }

    abort(404);
}


    /**
     * Save base64 avatar
     */
    private function saveBase64Image($base64Image)
{
    try {
        $image_parts = explode(";base64,", $base64Image);
        $image_type_aux = explode("image/", $image_parts[0]);
        $ext = $image_type_aux[1];
        $data = base64_decode($image_parts[1]);

        $file = 'avatars/' . uniqid() . '.' . $ext;
        Storage::disk('public')->put($file, $data);

        return '/storage/' . $file;
    } catch (\Exception $e) {
        return null;
    }
}

}

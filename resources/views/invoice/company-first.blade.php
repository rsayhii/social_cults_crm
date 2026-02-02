@extends('components.layout')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <div>
            <img class="mx-auto h-12 w-auto" src="https://www.socialcults.com/images/client/logo.png" alt="Social Cults">
            <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                Welcome to Invoice Pro
            </h2>
            <p class="mt-2 text-center text-sm text-gray-600">
                Please set up your company details first
            </p>
        </div>
        
        <div class="card p-6">
            <form id="company-form" class="space-y-4">
                @csrf
                <div>
                    <label for="company-name" class="block text-sm font-medium text-gray-700">Company Name *</label>
                    <input type="text" id="company-name" name="name" required
                           class="mt-1 appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm">
                </div>
                
                <div>
                    <label for="company-address" class="block text-sm font-medium text-gray-700">Address</label>
                    <textarea id="company-address" name="address" rows="2"
                              class="mt-1 appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm"></textarea>
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="company-contact" class="block text-sm font-medium text-gray-700">Contact</label>
                        <input type="text" id="company-contact" name="contact"
                               class="mt-1 appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm">
                    </div>
                    <div>
                        <label for="company-gstin" class="block text-sm font-medium text-gray-700">GSTIN</label>
                        <input type="text" id="company-gstin" name="gstin"
                               class="mt-1 appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm">
                    </div>
                </div>
                
                <div>
                    <label for="company-bank" class="block text-sm font-medium text-gray-700">Bank Details</label>
                    <textarea id="company-bank" name="bank_details" rows="3"
                              class="mt-1 appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm"
                              placeholder="Bank Name&#10;Account Number&#10;IFSC Code"></textarea>
                </div>
                
                <div>
                    <label for="company-logo" class="block text-sm font-medium text-gray-700">Logo (Optional)</label>
                    <input type="file" id="company-logo" name="logo" accept="image/*"
                           class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                </div>
                
                <div>
                    <button type="submit"
                            class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Save Company & Continue
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.getElementById('company-form').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    try {
        const response = await fetch('{{ route("company.store") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: formData
        });
        
        const result = await response.json();
        
        if (result.success) {
            window.location.href = '{{ route("invoices.index") }}';
        } else {
            alert(result.message || 'Failed to save company');
        }
    } catch (error) {
        console.error('Error:', error);
        alert('An error occurred. Please try again.');
    }
});
</script>
@endsection
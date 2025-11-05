@extends('components.layout')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-6xl mx-auto px-4">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Edit Lead Response</h2>
            <a href="{{ route('myleads') }}" class="text-blue-600 hover:underline">← Back to Leads</a>
        </div>

        <div class="bg-white rounded-xl shadow-2xl max-w-lg p-6 mx-auto">
            <form method="POST" action="{{ route('myleads.update', $lead->id) }}">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Response / Feedback</label>
                    <textarea name="response" rows="3"
                              class="w-full border border-gray-300 rounded-lg p-2 focus:ring focus:ring-blue-200 @error('response') border-red-500 @enderror"
                              required>{{ old('response', $lead->response) }}</textarea>
                    @error('response')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Next Follow-Up Date</label>
                    <input type="date" name="next_follow_up"
                           value="{{ old('next_follow_up', $lead->next_follow_up ? $lead->next_follow_up->format('Y-m-d') : '') }}"
                           class="w-full border border-gray-300 rounded-lg p-2 focus:ring focus:ring-blue-200 @error('next_follow_up') border-red-500 @enderror">
                    @error('next_follow_up')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-3">
    <label class="block text-sm font-medium text-gray-700 mb-1">Follow-Up Time</label>
    <input type="time" name="follow_up_time"
           value="{{ old('follow_up_time', $lead->follow_up_time ? \Carbon\Carbon::parse($lead->follow_up_time)->format('H:i') : '') }}"
           class="w-full border border-gray-300 rounded-lg p-2 focus:ring focus:ring-blue-200 @error('follow_up_time') border-red-500 @enderror">
    @error('follow_up_time')
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>


                <!-- Project Type -->
                <div class="mb-3">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Project Type</label>
                    <select name="project_type" class="w-full border border-gray-300 rounded-lg p-2 focus:ring focus:ring-blue-200 @error('project_type') border-red-500 @enderror">
                        <option value="">-- Select Project Type --</option>
                        <option value="web_development" {{ old('project_type', $lead->project_type) == 'web_development' ? 'selected' : '' }}>Web Development</option>
                        <option value="mobile_app" {{ old('project_type', $lead->project_type) == 'mobile_app' ? 'selected' : '' }}>Mobile App</option>
                        <option value="ecommerce" {{ old('project_type', $lead->project_type) == 'ecommerce' ? 'selected' : '' }}>E-commerce</option>
                        <option value="ui_ux_design" {{ old('project_type', $lead->project_type) == 'ui_ux_design' ? 'selected' : '' }}>UI/UX Design</option>
                        <option value="digital_marketing" {{ old('project_type', $lead->project_type) == 'digital_marketing' ? 'selected' : '' }}>Digital Marketing</option>
                        <option value="seo" {{ old('project_type', $lead->project_type) == 'seo' ? 'selected' : '' }}>SEO</option>
                        <option value="custom_software" {{ old('project_type', $lead->project_type) == 'custom_software' ? 'selected' : '' }}>Custom Software</option>
                        <option value="other" {{ old('project_type', $lead->project_type) == 'other' ? 'selected' : '' }}>Other</option>
                    </select>
                    @error('project_type')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Lead Status</label>
                    <select name="status" class="w-full border border-gray-300 rounded-lg p-2 focus:ring focus:ring-blue-200 @error('status') border-red-500 @enderror">
                        <option value="">-- Select --</option>
                        <option value="interested" {{ old('status', $lead->status) == 'interested' ? 'selected' : '' }}>Interested</option>
                        <option value="not interested" {{ old('status', $lead->status) == 'not interested' ? 'selected' : '' }}>Not Interested</option>
                        <option value="missed booked" {{ old('status', $lead->status) == 'missed booked' ? 'selected' : '' }}>Meeting Booked</option>
                        <option value="proposal" {{ old('status', $lead->status) == 'proposal' ? 'selected' : '' }}>Proposal</option>
                        <option value="negotiating" {{ old('status', $lead->status) == 'negotiating' ? 'selected' : '' }}>Negotiating</option>
                        <option value="purchased" {{ old('status', $lead->status) == 'purchased' ? 'selected' : '' }}>Purchased</option>
                        <option value="will call back" {{ old('status', $lead->status) == 'will call back' ? 'selected' : '' }}>Will Call Back</option>
                    </select>
                    @error('status')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit"
                        class="w-full mt-4 bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 rounded-lg transition-all">
                    Update Response
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
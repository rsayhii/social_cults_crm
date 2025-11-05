@extends('components.layout')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
  <div class="max-w-4xl mx-auto bg-white p-6 rounded-lg shadow">
    <h2 class="text-2xl font-bold mb-4">Create New Proposal</h2>

    <form action="{{ route('proposals.store') }}" method="POST" enctype="multipart/form-data">
      @csrf

      <div class="mb-3">
        <label class="block text-sm font-medium text-gray-700 mb-1">Client</label>
        <select name="client_id" class="w-full border border-gray-300 rounded-lg p-2" required>
          <option value="">-- Select Client --</option>
          @foreach($clients as $client)
            <option value="{{ $client->id }}">{{ $client->company_name }}</option>
          @endforeach
        </select>
      </div>

      <div class="mb-3">
        <label class="block text-sm font-medium text-gray-700 mb-1">Title</label>
        <input type="text" name="title" class="w-full border border-gray-300 rounded-lg p-2" required>
      </div>

      <div class="mb-3">
        <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
        <textarea name="description" rows="4" class="w-full border border-gray-300 rounded-lg p-2"></textarea>
      </div>

      <div class="mb-3">
        <label class="block text-sm font-medium text-gray-700 mb-1">Amount</label>
        <input type="number" name="amount" step="0.01" class="w-full border border-gray-300 rounded-lg p-2">
      </div>

      <div class="mb-3">
        <label class="block text-sm font-medium text-gray-700 mb-1">Attach File (optional)</label>
        <input type="file" name="file" class="w-full border border-gray-300 rounded-lg p-2">
      </div>

      <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 rounded-lg">
        Save Proposal
      </button>
    </form>
  </div>
</div>
@endsection

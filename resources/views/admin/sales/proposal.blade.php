@extends('components.layout')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
  <div class="max-w-6xl mx-auto px-4">
    <div class="flex justify-between items-center mb-6">
      <h2 class="text-2xl font-bold text-gray-800">Proposals</h2>
      <a href="{{ route('proposals.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm">
        + New Proposal
      </a>
    </div>

    @if(session('success'))
      <div class="mb-4 bg-green-100 text-green-700 px-4 py-2 rounded-lg">
        {{ session('success') }}
      </div>
    @endif

    <div class="bg-white shadow rounded-lg overflow-x-auto">
      <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-100">
          <tr>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase">#</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase">Client</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase">Title</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase">Amount</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase">Status</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase">Created By</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase">Actions</th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-100">
          @foreach($proposals as $index => $proposal)
            <tr>
              <td class="px-6 py-4 text-sm text-gray-700">{{ $index + 1 }}</td>
              <td class="px-6 py-4 text-sm">{{ $proposal->client->company_name ?? 'N/A' }}</td>
              <td class="px-6 py-4 text-sm">{{ $proposal->title }}</td>
              <td class="px-6 py-4 text-sm">₹{{ number_format($proposal->amount, 2) }}</td>
              <td class="px-6 py-4 text-sm capitalize">{{ $proposal->status }}</td>
              <td class="px-6 py-4 text-sm">{{ $proposal->user->name ?? 'Unknown' }}</td>
              <td class="px-6 py-4 text-sm">
                <a href="{{ route('proposals.edit', $proposal->id) }}" class="text-blue-600 hover:underline">Edit</a>
                <form action="{{ route('proposals.destroy', $proposal->id) }}" method="POST" class="inline">
                  @csrf @method('DELETE')
                  <button type="submit" class="text-red-600 hover:underline ml-2" onclick="return confirm('Delete this proposal?')">Delete</button>
                </form>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection

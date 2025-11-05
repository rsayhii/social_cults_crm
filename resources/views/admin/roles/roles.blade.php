@extends('components.layout')

@section('content')
<div class="flex-1 overflow-auto p-6">
    <div class="bg-white shadow-md rounded-lg p-6">

        <div class="flex justify-between items-center mb-4">
            <h2 class="text-2xl font-semibold">Roles</h2>
            <a href="{{ route('roles.create') }}" 
               class="bg-green-500 hover:bg-green-600 text-white text-sm font-medium px-4 py-2 rounded-md shadow-sm transition">
                + Create User
            </a>
        </div>

            <!-- ✅ Success Alert -->
        @if (session('success'))
            <div id="alert-success" 
                 class="flex items-center justify-between bg-green-100 border border-green-300 text-green-800 px-4 py-3 rounded-md mb-5 shadow-sm">
                <div class="flex items-center space-x-2">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2l4-4m-7 8h8a2 2 0 002-2V8a2 2 0 00-2-2H7a2 2 0 00-2 2v8a2 2 0 002 2z" />
                    </svg>
                    <span>{{ session('success') }}</span>
                </div>
                <button onclick="document.getElementById('alert-success').style.display='none'"
                        class="text-green-700 hover:text-green-900 focus:outline-none text-sm font-bold ml-4">
                    ✕
                </button>
            </div>
        @endif

        <table class="min-w-full border border-gray-200">
            <thead class="bg-gray-100">
                <tr>
                    <th class="py-3 px-4 text-left text-sm font-semibold text-gray-700 border-b">ID</th>
                    <th class="py-3 px-4 text-left text-sm font-semibold text-gray-700 border-b">Name</th>
                    <th class="py-3 px-4 text-left text-sm font-semibold text-gray-700 border-b">Action</th>
                </tr>
            </thead>
            <tbody>
                <!-- Example Rows -->
                @foreach ($roles as $role)
                <tr class="hover:bg-gray-50">
                    <td class="py-2 px-4 border-b text-sm text-gray-800">{{ $role->id }}</td>
                    <td class="py-2 px-4 border-b text-sm text-gray-800">{{ $role->name }}</td>
                    <td class="py-2 px-4 border-b text-sm">
                        <form action="POST" action="{{ route('roles.destroy', $role->id) }}">
                            @csrf
                            @method('DELETE')
                            <a href="{{ route('roles.show', $role->id) }}" class="bg-blue-500 hover:bg-blue-600 text-white text-sm px-3 py-1 rounded-md">Show</a>
                            <a href="{{ route('roles.edit', $role->id) }}" class="bg-green-500 hover:bg-green-600 text-white text-sm px-3 py-1 rounded-md">Edit</a>
                            <a href="{{ route('roles.destroy', $role->id) }}" class="bg-red-500 hover:bg-red-600 text-white text-sm px-3 py-1 rounded-md">Delete</a>
                            {{-- <button class="bg-red-500 hover:bg-red-600 text-white text-sm px-3 py-1 rounded-md ml-2">Delete</button> --}}
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

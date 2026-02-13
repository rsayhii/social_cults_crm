<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Superadmin | Secure Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#fcfcfc] flex items-center justify-center min-h-screen antialiased text-black">

    <div class="w-full max-w-[400px] p-10 bg-white border border-[#eaeaea] shadow-[0_10px_30px_rgba(0,0,0,0.05)] rounded-1">
        
        <div class="mb-8 text-center">
            <div class="w-10 h-10 bg-black text-white flex items-center justify-center font-bold text-xl mx-auto mb-5">
                S
            </div>
            <h1 class="text-2xl font-bold tracking-[-0.5px] uppercase">System Access</h1>
            <p class="text-[0.85rem] text-[#666] mt-2 font-medium">Superadmin Authority Required</p>
        </div>

        @error('error')
           <div class="mt-2 flex items-center gap-2 border-l-2 border-red-600 bg-red-50/50 px-3 py-2">
    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
    </svg>
    <p class="text-[0.75rem] font-bold uppercase tracking-tight text-red-600">
        {{ $message }}
    </p>
</div>
        @enderror

        <form method="POST" action="{{ route('superadmin.login.submit') }}" class="space-y-5">
    @csrf

    <div class="space-y-2">
        <label class="block text-[0.75rem] font-bold uppercase tracking-tight">
            Administrator Email
        </label>
        <input 
            type="email" 
            name="email"
            value="{{ old('email') }}"
            required
            class="w-full px-4 py-3 border border-[#e0e0e0]"
        >
    </div>

    <div class="space-y-2">
        <label class="block text-[0.75rem] font-bold uppercase tracking-tight">
            Password
        </label>
        <input 
            type="password" 
            name="password"
            required
            class="w-full px-4 py-3 border border-[#e0e0e0]"
        >
    </div>

    <button type="submit" class="w-full py-[14px] bg-black text-white">
        Authenticate
    </button>
</form>


        {{-- <div class="mt-6 text-center">
            <a href="#" class="text-[0.8rem] text-[#666] no-underline hover:text-black transition-colors">
                Forgot Credentials?
            </a>
        </div> --}}
    </div>

</body>
</html>
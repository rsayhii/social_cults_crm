@extends('components.layout')

@section('content')
    <div class="px-4 py-8 sm:px-6 lg:px-8 bg-gray-50 min-h-screen">
        <div class="max-w-4xl mx-auto">
            <!-- Page Header -->
            <div class="text-center mb-8">
                <h1 class="text-2xl font-bold text-gray-900 sm:text-3xl">
                    Choose Your Path
                </h1>
                <p class="mt-2 text-base text-gray-600">
                    Manage your current plan or upgrade to unlock full potential.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Current Plan Card -->
                <div
                    class="bg-white rounded-2xl p-6 shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)] flex flex-col items-center text-center h-full border border-gray-100">
                    <!-- Icon -->
                    <div class="w-16 h-16 bg-green-100 rounded-2xl flex items-center justify-center mb-4 text-green-600">
                        <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>

                    <h3 class="text-xl font-bold text-gray-900 mb-2">Current Status</h3>
                    <p class="text-sm text-gray-500 mb-6 max-w-xs">
                        Here is an overview of your current subscription status and usage.
                    </p>

                    <div class="grid grid-cols-2 gap-3 w-full mt-auto">
                        <!-- Status Item -->
                        <div class="bg-gray-50 p-3 rounded-xl flex flex-col items-center justify-center gap-1.5">
                            <div class="p-1.5 bg-blue-100 rounded-full text-blue-600">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 10V3L4 14h7v7l9-11h-7z" />
                                </svg>
                            </div>
                            <span class="text-xs font-semibold text-gray-900">
                                @if($isTrial) Trial Active @elseif($company->is_paid) Premium @else Expired @endif
                            </span>
                            <span class="text-[10px] text-gray-500">Plan Status</span>
                        </div>

                        <!-- Expiry Item -->
                        <div class="bg-gray-50 p-3 rounded-xl flex flex-col items-center justify-center gap-1.5">
                            <div class="p-1.5 bg-purple-100 rounded-full text-purple-600">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <span class="text-xs font-semibold text-gray-900">
                                {{ $company->trial_ends_at ? \Carbon\Carbon::parse($company->trial_ends_at)->format('M d') : 'N/A' }}
                            </span>
                            <span class="text-[10px] text-gray-500">Expiry Date</span>
                        </div>

                        @if($isTrial)
                            <!-- Time Remaining Item (Full Width) -->
                            <div
                                class="col-span-2 bg-amber-50 p-3 rounded-xl flex flex-col items-center justify-center gap-1.5 border border-amber-100">
                                <div class="p-1.5 bg-amber-100 rounded-full text-amber-600">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4l3 3m6 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <span class="text-sm font-bold text-amber-700">
                                    {{ $trialRemaining }}
                                </span>
                                <span class="text-[10px] text-amber-600/80">Time Remaining</span>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Upgrade Card -->
                <div
                    class="bg-white rounded-2xl p-6 shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)] flex flex-col items-center text-center h-full border border-gray-100 relative overflow-hidden group hover:border-sky-200 transition-colors duration-300">

                    <!-- Icon -->
                    <div
                        class="w-16 h-16 bg-sky-100 rounded-2xl flex items-center justify-center mb-4 text-sky-600 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>

                    <h3 class="text-xl font-bold text-gray-900 mb-2">Go Premium</h3>
                    <p class="text-sm text-gray-500 mb-6 max-w-xs">
                        Advanced analytics and targeting features to expand your reach.
                    </p>

                    <div class="grid grid-cols-2 gap-3 w-full mb-6">
                        <!-- Feature 1 -->
                        <div
                            class="bg-gray-50 p-3 rounded-xl flex flex-col items-center justify-center gap-1.5 hover:bg-sky-50 transition-colors duration-200">
                            <div class="p-1.5 rounded-full text-emerald-500 bg-white shadow-sm">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                            </div>
                            <span class="text-xs font-semibold text-gray-800">Unlimited Team</span>
                        </div>

                        <!-- Feature 2 -->
                        <div
                            class="bg-gray-50 p-3 rounded-xl flex flex-col items-center justify-center gap-1.5 hover:bg-sky-50 transition-colors duration-200">
                            <div class="p-1.5 rounded-full text-blue-500 bg-white shadow-sm">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                </svg>
                            </div>
                            <span class="text-xs font-semibold text-gray-800">Analytics</span>
                        </div>

                        <!-- Feature 3 -->
                        <div
                            class="bg-gray-50 p-3 rounded-xl flex flex-col items-center justify-center gap-1.5 hover:bg-sky-50 transition-colors duration-200">
                            <div class="p-1.5 rounded-full text-violet-500 bg-white shadow-sm">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                            </div>
                            <span class="text-xs font-semibold text-gray-800">Targeting</span>
                        </div>

                        <!-- Feature 4 -->
                        <div
                            class="bg-gray-50 p-3 rounded-xl flex flex-col items-center justify-center gap-1.5 hover:bg-sky-50 transition-colors duration-200">
                            <div class="p-1.5 rounded-full text-orange-500 bg-white shadow-sm">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z" />
                                </svg>
                            </div>
                            <span class="text-xs font-semibold text-gray-800">Support</span>
                        </div>
                    </div>

                    <div class="mt-auto w-full">
                        <button type="button"
                            class="w-full rounded-xl bg-sky-500 py-3 px-4 text-sm font-bold text-white shadow-lg shadow-sky-500/30 hover:bg-sky-400 hover:shadow-sky-500/40 transition-all duration-300 transform hover:-translate-y-0.5">
                            Upgrade Now
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
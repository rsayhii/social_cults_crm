@extends('components.layout')

@section('content')
    <div class="min-h-screen bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-extrabold text-gray-900 sm:text-4xl">
                    Upgrade Your Experience
                </h2>
                <p class="mt-4 text-xl text-gray-500">
                    Compare plans and choose the best fit for your business growth.
                </p>
            </div>

           

            <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr class="bg-gray-50">
                            <th scope="col" class="px-6 py-5 text-left text-sm font-bold text-gray-500 uppercase tracking-wider w-1/3">
                                Features
                            </th>
                            <th scope="col" class="px-6 py-5 text-center text-sm font-bold text-gray-500 uppercase tracking-wider w-1/3">
                                <div class="flex flex-col items-center">
                                    <span class="text-lg text-gray-900">Current Plan</span>
                                    @if($isTrial)
                                        <span class="mt-1 px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800">
                                            Trial Active
                                        </span>
                                    @elseif($company->is_paid)
                                        <span class="mt-1 px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            Premium
                                        </span>
                                    @else
                                        <span class="mt-1 px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                            Free / Expired
                                        </span>
                                    @endif
                                </div>
                            </th>
                            <th scope="col" class="px-6 py-5 text-center text-sm font-bold text-sky-600 uppercase tracking-wider w-1/3 bg-sky-50/50">
                                <div class="flex flex-col items-center">
                                    <span class="text-lg text-sky-700">Premium Plan</span>
                                    <span class="mt-1 px-2.5 py-0.5 rounded-full text-xs font-medium bg-sky-100 text-sky-800">
                                        Recommended
                                    </span>
                                </div>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <!-- Team Members -->
                        <tr class="hover:bg-gray-50 transition-colors duration-150">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                    </svg>
                                    Team Members
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                Limited (up to 3)
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-sky-600 text-center bg-sky-50/30">
                                Unlimited
                            </td>
                        </tr>

                        <!-- Analytics -->
                        <tr class="hover:bg-gray-50 transition-colors duration-150">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                    </svg>
                                    Analytics
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                Basic
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-sky-600 text-center bg-sky-50/30">
                                Advanced
                            </td>
                        </tr>

                        <!-- Targeting -->
                        <tr class="hover:bg-gray-50 transition-colors duration-150">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z" />
                                    </svg>
                                    Advanced Targeting
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <span class="inline-flex items-center justify-center h-6 w-6 rounded-full bg-gray-100 text-gray-400">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center bg-sky-50/30">
                                <span class="inline-flex items-center justify-center h-6 w-6 rounded-full bg-green-100 text-green-600">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                </span>
                            </td>
                        </tr>

                        <!-- Support -->
                        <tr class="hover:bg-gray-50 transition-colors duration-150">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z" />
                                    </svg>
                                    Priority Support
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                Standard (Email)
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-sky-600 text-center bg-sky-50/30">
                                24/7 Priority
                            </td>
                        </tr>

                        <!-- Custom Domain -->
                        <tr class="hover:bg-gray-50 transition-colors duration-150">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" />
                                    </svg>
                                    Custom Domain
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <span class="inline-flex items-center justify-center h-6 w-6 rounded-full bg-gray-100 text-gray-400">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center bg-sky-50/30">
                                <span class="inline-flex items-center justify-center h-6 w-6 rounded-full bg-green-100 text-green-600">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                </span>
                            </td>
                        </tr>

                        <!-- Footer / Action Row -->
                        <tr class="bg-gray-50">
                            <td class="px-6 py-6 whitespace-nowrap text-sm font-bold text-gray-900">
                                Current Status
                            </td>
                            <td class="px-6 py-6 whitespace-nowrap text-center">
                                @if($isTrial)
                                    <div class="flex flex-col items-center">
                                        <span class="text-sm font-semibold text-amber-700">Trial Ends in</span>
                                        <span class="text-xs text-amber-600 font-bold mt-1">{{ $trialRemaining }}</span>
                                    </div>
                                @elseif($company->is_paid)
                                    <div class="flex flex-col items-center">
                                        <span class="text-sm font-semibold text-green-700">Plan Active</span>
                                        <span class="text-xs text-green-600 mt-1">Enjoy Premium Features</span>
                                    </div>
                                @else
                                    <div class="flex flex-col items-center">
                                        <span class="text-sm font-semibold text-red-700">Expired</span>
                                        <span class="text-xs text-red-600 mt-1">Please Upgrade</span>
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-6 whitespace-nowrap text-center bg-sky-50/50">
                                @if(!$company->is_paid)
                                    <button type="button" class="w-full inline-flex justify-center items-center px-6 py-3 border border-transparent text-base font-medium rounded-lg shadow-sm text-white bg-sky-600 hover:bg-sky-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-sky-500 transform hover:-translate-y-0.5 transition-all duration-200">
                                        Upgrade Now
                                    </button>
                                @else
                                    <button type="button" disabled class="w-full inline-flex justify-center items-center px-6 py-3 border border-gray-300 text-base font-medium rounded-lg text-gray-500 bg-white cursor-not-allowed">
                                        Current Plan
                                    </button>
                                @endif
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100 mt-12">
                <div class="px-6 py-5 border-b border-gray-200 bg-sky-50">
                    <h3 class="text-lg font-bold leading-6 text-gray-900 flex items-center">
                        <svg class="w-5 h-5 text-sky-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        My Active Plan
                    </h3>
                </div>
                <div class="px-6 py-5">
                    <table class="min-w-full divide-y divide-gray-200">
                        <tbody class="divide-y divide-gray-200">
                            <tr>
                                <td class="py-4 text-sm font-medium text-gray-500 w-1/3">Current Plan</td>
                                <td class="py-4 text-sm text-gray-900 font-bold">
                                    @if($isTrial)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800">
                                            Trial Plan
                                        </span>
                                    @elseif($company->is_paid)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            Premium Plan
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                            Free / Expired
                                        </span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td class="py-4 text-sm font-medium text-gray-500">Account Status</td>
                                <td class="py-4 text-sm text-gray-900">
                                    @if($isTrial)
                                        <span class="text-amber-600 font-semibold">Active (Trial)</span>
                                    @elseif($company->is_paid)
                                        <span class="text-green-600 font-semibold">Active</span>
                                    @else
                                        <span class="text-red-600 font-semibold">Inactive / Expired</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td class="py-4 text-sm font-medium text-gray-500">Valid Until</td>
                                <td class="py-4 text-sm text-gray-900">
                                    @if($isTrial)
                                        {{ \Carbon\Carbon::parse($company->trial_ends_at)->format('F d, Y') }} 
                                        <span class="text-gray-400 text-xs ml-2">({{ $trialRemaining }} remaining)</span>
                                    @elseif($company->is_paid)
                                        <span class="text-gray-600">Auto-renewal enabled</span>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            
            <!-- Trust Badges / Extra Info -->
            <div class="mt-12 grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-3">
                <div class="pt-6">
                    <div class="flow-root bg-gray-50 rounded-lg px-6 pb-8">
                        <div class="-mt-6">
                            <div>
                                <span class="inline-flex items-center justify-center p-3 bg-sky-500 rounded-md shadow-lg">
                                    <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                    </svg>
                                </span>
                            </div>
                            <h3 class="mt-8 text-lg font-medium text-gray-900 tracking-tight">Secure Payment</h3>
                            <p class="mt-5 text-base text-gray-500">
                                All transactions are secured with industry-standard encryption.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="pt-6">
                    <div class="flow-root bg-gray-50 rounded-lg px-6 pb-8">
                        <div class="-mt-6">
                            <div>
                                <span class="inline-flex items-center justify-center p-3 bg-sky-500 rounded-md shadow-lg">
                                    <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                    </svg>
                                </span>
                            </div>
                            <h3 class="mt-8 text-lg font-medium text-gray-900 tracking-tight">Instant Activation</h3>
                            <p class="mt-5 text-base text-gray-500">
                                Get access to premium features immediately after upgrading.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="pt-6">
                    <div class="flow-root bg-gray-50 rounded-lg px-6 pb-8">
                        <div class="-mt-6">
                            <div>
                                <span class="inline-flex items-center justify-center p-3 bg-sky-500 rounded-md shadow-lg">
                                    <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z" />
                                    </svg>
                                </span>
                            </div>
                            <h3 class="mt-8 text-lg font-medium text-gray-900 tracking-tight">24/7 Support</h3>
                            <p class="mt-5 text-base text-gray-500">
                                Our dedicated support team is here to help you succeed.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

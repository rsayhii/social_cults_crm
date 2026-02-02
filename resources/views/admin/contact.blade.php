@extends('components.layout')

@section('content')

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contacts Management | Digital Marketing CRM</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        
        body {
            font-family: 'Inter', sans-serif;
        }
        
        .contact-card {
            transition: all 0.3s ease;
        }
        
        .contact-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
        }
        
        .modal-overlay {
            background-color: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(4px);
        }
        
        .avatar-upload {
            position: relative;
            display: inline-block;
        }
        
        .avatar-upload input[type="file"] {
            position: absolute;
            left: 0;
            top: 0;
            opacity: 0;
            width: 100%;
            height: 100%;
            cursor: pointer;
        }
        
        .avatar-preview {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px dashed #d1d5db;
        }
        
        .notification {
            transition: all 0.3s ease;
        }
        
        /* Responsive grid adjustments */
        @media (max-width: 640px) {
            .responsive-grid {
                grid-template-columns: 1fr;
            }
            .modal-content {
                margin: 0 1rem;
                max-height: 85vh;
            }
        }
        
        @media (min-width: 641px) and (max-width: 768px) {
            .responsive-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }
        
        @media (min-width: 769px) and (max-width: 1024px) {
            .responsive-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }
        
        @media (min-width: 1025px) and (max-width: 1280px) {
            .responsive-grid {
                grid-template-columns: repeat(3, 1fr);
            }
        }
        
        @media (min-width: 1281px) {
            .responsive-grid {
                grid-template-columns: repeat(4, 1fr);
            }
        }
        
        /* Fix for export dropdown on mobile */
        @media (max-width: 640px) {
            #exportMenu {
                position: fixed;
                left: 50%;
                transform: translateX(-50%);
                top: 120px;
                width: 90%;
                max-width: 300px;
                margin-top: 0;
            }
        }
        
        /* Extra small screens */
        @media (max-width: 375px) {
            #exportBtn span {
                display: none !important;
            }
            #exportBtn .fa-download {
                font-size: 1rem;
            }
            #exportMenu {
                width: 95%;
            }
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Header Section -->
    <header class="bg-white shadow-sm border-b border-gray-200 hidden">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between px-4 sm:px-6 py-3 sm:py-4 space-y-3 sm:space-y-0">
            <!-- Left Section: Breadcrumb and Search -->
            <div class="flex flex-col sm:flex-row sm:items-center space-y-3 sm:space-y-0 sm:space-x-4 w-full sm:w-auto">
                <!-- Search bar - full width on mobile, auto on desktop -->
                <div class="relative w-full sm:w-64">
                    <input type="text" placeholder="Search contacts..." class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500 text-sm sm:text-base">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-search text-gray-400"></i>
                    </div>
                </div>
                
                <!-- Breadcrumb - hidden on small mobile, shown on larger screens -->
                <div class="hidden sm:block text-sm text-gray-500">
                    <span class="text-gray-400">/</span> CRM <span class="text-gray-400">/</span> <span class="text-indigo-600 font-medium">Contacts Grid</span>
                </div>
            </div>
            
            <!-- Right Section: User actions -->
            <div class="flex items-center justify-between sm:justify-end w-full sm:w-auto space-x-2 sm:space-x-4">
                <!-- Icons -->
                <div class="flex items-center space-x-2 sm:space-x-4">
                    <div class="relative">
                        <button class="p-2 text-gray-500 hover:text-gray-700 rounded-full hover:bg-gray-100">
                            <i class="fas fa-bell text-base sm:text-lg"></i>
                        </button>
                        <span class="absolute top-0 right-0 h-2 w-2 sm:h-3 sm:w-3 bg-red-500 rounded-full"></span>
                    </div>
                    <button class="p-2 text-gray-500 hover:text-gray-700 rounded-full hover:bg-gray-100 hidden sm:block">
                        <i class="fas fa-cog text-base sm:text-lg"></i>
                    </button>
                </div>
                
                <!-- User profile -->
                <div class="relative group">
                    <button class="flex items-center space-x-2 text-gray-700 hover:text-indigo-600">
                        <div class="h-8 w-8 bg-indigo-100 rounded-full flex items-center justify-center text-sm sm:text-base">
                            <span class="text-indigo-600 font-medium">JD</span>
                        </div>
                        <span class="font-medium hidden sm:inline">John Doe</span>
                        <i class="fas fa-chevron-down text-xs hidden sm:inline"></i>
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Mobile breadcrumb -->
        <div class="sm:hidden px-4 pb-2">
            <div class="text-sm text-gray-500">
                <span class="text-gray-400">/</span> CRM <span class="text-gray-400">/</span> <span class="text-indigo-600 font-medium">Contacts Grid</span>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <div class="container mx-auto px-3 sm:px-4 py-4 sm:py-6">
        <!-- Page Title and Actions - Responsive row -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-4 sm:mb-6">
            <!-- Title Section -->
            <div class="mb-4 sm:mb-0">
                <h1 class="text-xl sm:text-2xl font-bold text-gray-800">Contacts</h1>
                <div class="flex items-center mt-1 text-xs sm:text-sm text-gray-500">
                    <span class="text-gray-400">/</span>
                    <span class="mx-1">CRM</span>
                    <span class="text-gray-400">/</span>
                    <span class="mx-1 text-indigo-600 font-medium">Contacts Grid</span>
                </div>
            </div>
            
            <!-- Buttons Section - Both buttons in one row -->
            <div class="flex flex-row items-center justify-between sm:justify-end space-x-2 sm:space-x-3 relative">
                <!-- Export Button - Updated with mobile-friendly dropdown -->
                <div class="relative">
                    <button id="exportBtn" class=" hidden flex items-center space-x-1 sm:space-x-2 bg-white border border-gray-300 text-gray-700 py-2 px-3 sm:px-4 rounded-lg hover:bg-gray-50 transition-all duration-300 text-sm sm:text-base whitespace-nowrap">
                        <i class="fas fa-download text-sm sm:text-base"></i>
                        <span class="hidden xs:inline">Export</span>
                        <i class="fas fa-chevron-down text-xs ml-0 xs:ml-1 hidden xs:inline"></i>
                    </button>
                    
                    <div id="exportMenu" class="absolute right-0 mt-2 w-44 sm:w-48 bg-white rounded-lg shadow-lg py-2 z-50 hidden border border-gray-200">
                        <!-- CSV -->
                        <a href="{{ route('contacts.export', ['format' => 'csv']) }}"
                           class="w-full block px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 flex items-center border-b border-gray-100 last:border-b-0">
                            <i class="fas fa-file-csv mr-3 text-green-500 text-base"></i>
                            <div class="flex-1 min-w-0">
                                <div class="font-medium truncate">CSV File</div>
                                <div class="text-xs text-gray-500 truncate">Comma separated values</div>
                            </div>
                        </a>
                        
                        <!-- Excel -->
                        <a href="{{ route('contacts.export', ['format' => 'excel']) }}"
                           class="w-full block px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 flex items-center">
                            <i class="fas fa-file-excel mr-3 text-green-600 text-base"></i>
                            <div class="flex-1 min-w-0">
                                <div class="font-medium truncate">Excel File</div>
                                <div class="text-xs text-gray-500 truncate">Microsoft Excel format</div>
                            </div>
                        </a>
                    </div>
                </div>
                
                <!-- Add Contact Button -->
                <button id="addContactBtn" class="bg-orange-500 text-white py-2 px-3 sm:px-4 rounded-lg font-medium hover:bg-orange-600 transition-all duration-300 flex items-center text-sm sm:text-base whitespace-nowrap">
                    <i class="fas fa-plus mr-1 sm:mr-2 text-xs sm:text-sm"></i>
                    <span>Add Contact</span>
                </button>
            </div>
        </div>

        <!-- Filters and Sorting -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-4 sm:mb-6">
            <!-- Contacts Count -->
            <div class="text-xs sm:text-sm text-gray-500 mb-3 sm:mb-0">
                Showing <span id="contactsCount" class="font-medium text-gray-700">0</span> contacts
            </div>
            
            <!-- Sort By -->
            <div class="w-full sm:w-auto">
                <div class="flex items-center space-x-2">
                    <span class="text-xs sm:text-sm text-gray-500 whitespace-nowrap">Sort By:</span>
                    <select id="sortContacts" class="w-full sm:w-auto border border-gray-300 rounded-lg py-2 px-3 text-xs sm:text-sm focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500">
                        <option value="latest">Latest</option>
                        <option value="oldest">Oldest</option>
                        <option value="a-z">A-Z</option>
                        <option value="z-a">Z-A</option>
                        <option value="last7">Last 7 Days</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Contacts Grid -->
        <div class="grid grid-cols-1 xs:grid-cols-2 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-3 sm:gap-4 md:gap-6 responsive-grid" id="contactsGrid">
            <!-- Contact cards will be dynamically generated here -->
            <div class="col-span-full text-center py-8 sm:py-12">
                <div class="animate-pulse">
                    <i class="fas fa-users text-gray-300 text-4xl sm:text-5xl mb-3 sm:mb-4"></i>
                    <h3 class="text-base sm:text-lg font-medium text-gray-700 mb-2">Loading contacts...</h3>
                    <p class="text-gray-500 text-sm sm:text-base">Please wait while we load your contacts</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Contact Modal -->
    <div id="addContactModal" class="fixed inset-0 z-50 flex items-center justify-center hidden px-3 sm:px-0">
        <!-- Overlay -->
        <div class="modal-overlay absolute inset-0 bg-black opacity-30"></div>

        <!-- Modal content -->
        <div class="bg-white rounded-xl shadow-lg w-full max-w-xs sm:max-w-sm md:max-w-md mx-auto z-10 max-h-[85vh] sm:max-h-[90vh] overflow-y-auto relative modal-content">
            <!-- Close button -->
            <button id="closeAddContactModal" class="absolute top-3 right-3 text-gray-500 hover:text-gray-700 text-xl font-bold">
                &times;
            </button>

            <div class="p-4 sm:p-6 border-b border-gray-200">
                <h2 class="text-lg sm:text-xl font-bold text-gray-800">Add New Contact</h2>
            </div>

            <div class="p-4 sm:p-6">
                <form id="addContactForm">
                    @csrf
                    <div class="mb-3 sm:mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Full Name *</label>
                        <input type="text" id="fullName" name="name" class="w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500 text-sm sm:text-base" placeholder="Enter full name" required>
                        <div id="fullNameError" class="text-red-500 text-xs mt-1 hidden"></div>
                    </div>

                    <div class="mb-3 sm:mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Position *</label>
                        <input type="text" id="position" name="position" class="w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500 text-sm sm:text-base" placeholder="Enter position" required>
                        <div id="positionError" class="text-red-500 text-xs mt-1 hidden"></div>
                    </div>

                    <div class="mb-3 sm:mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email *</label>
                        <input type="email" id="email" name="email" class="w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500 text-sm sm:text-base" placeholder="Enter email" required>
                        <div id="emailError" class="text-red-500 text-xs mt-1 hidden"></div>
                    </div>

                    <div class="mb-3 sm:mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">
                Phone *
            </label>
            <input type="tel"
                   name="phone"
                   placeholder="Enter phone number"
                   required
                   pattern="[0-9]{10,15}"
                   title="Phone number must be 10–15 digits"
                   class="w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500 text-sm sm:text-base">
        </div>

                    <div class="mb-3 sm:mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Country *</label>
                        <input type="text" id="country" name="country" class="w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500 text-sm sm:text-base" placeholder="Enter country" required>
                        <div id="countryError" class="text-red-500 text-xs mt-1 hidden"></div>
                    </div>

                    <div class="mb-3 sm:mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Social Media Links</label>
                        <div class="space-y-2">
                            <div class="flex items-center">
                                <i class="fab fa-instagram text-pink-500 mr-2 text-xs sm:text-sm"></i>
                                <input type="text" id="instagram" name="instagram" class="w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg text-sm sm:text-base" placeholder="Instagram username">
                            </div>
                            <div class="flex items-center">
                                <i class="fab fa-facebook text-blue-600 mr-2 text-xs sm:text-sm"></i>
                                <input type="text" id="facebook" name="facebook" class="w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg text-sm sm:text-base" placeholder="Facebook profile">
                            </div>
                            <div class="flex items-center">
                                <i class="fab fa-whatsapp text-green-500 mr-2 text-xs sm:text-sm"></i>
                                <input type="text" id="whatsapp" name="whatsapp" class="w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg text-sm sm:text-base" placeholder="WhatsApp number">
                            </div>
                            <div class="flex items-center">
                                <i class="fab fa-linkedin text-blue-700 mr-2 text-xs sm:text-sm"></i>
                                <input type="text" id="linkedin" name="linkedin" class="w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg text-sm sm:text-base" placeholder="LinkedIn profile">
                            </div>
                        </div>
                    </div>

                    <div class="mb-4 sm:mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                        <textarea id="notes" name="notes" rows="2" class="w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg text-sm sm:text-base" placeholder="Add any notes about this contact"></textarea>
                    </div>

                    <div class="flex justify-end space-x-2 sm:space-x-3">
                        <button type="button" id="cancelAddContact" class="px-3 sm:px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-all duration-300 text-sm sm:text-base">
                            Cancel
                        </button>
                        <button type="submit" class="px-3 sm:px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-all duration-300 flex items-center text-sm sm:text-base">
                            <i class="fa-spin hidden mr-2 text-xs sm:text-sm" id="addContactSpinner"></i>
                            Save Contact
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Contact Modal -->
    <div id="editContactModal" class="fixed inset-0 z-50 flex items-center justify-center hidden px-3 sm:px-0">
        <div class="modal-overlay absolute inset-0"></div>
        <div class="bg-white rounded-xl shadow-lg w-full max-w-xs sm:max-w-sm md:max-w-md mx-auto z-10 max-h-[85vh] sm:max-h-[90vh] overflow-y-auto modal-content">
            <div class="p-4 sm:p-6 border-b border-gray-200">
                <h2 class="text-lg sm:text-xl font-bold text-gray-800">Edit Contact</h2>
            </div>
            <div class="p-4 sm:p-6">
                <form id="editContactForm">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="editContactId" name="id">
                    
                    <div class="mb-4 sm:mb-6 flex justify-center hidden">
                        <div class="avatar-upload hidden">
                            <img id="editAvatarPreview" src="https://via.placeholder.com/100" class="avatar-preview" alt="Avatar Preview">
                            <input type="file" id="editAvatarInput" accept="image/*">
                            <div class="text-center mt-2">
                                <button type="button" class="text-xs sm:text-sm text-indigo-600 hover:text-indigo-800 font-medium">
                                    <i class="fas fa-upload mr-1"></i> Change Photo
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3 sm:mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Full Name *</label>
                        <input type="text" id="editFullName" name="name" class="w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500 text-sm sm:text-base" placeholder="Enter full name" required>
                        <div id="editFullNameError" class="text-red-500 text-xs mt-1 hidden"></div>
                    </div>
                    
                    <div class="mb-3 sm:mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Position *</label>
                        <input type="text" id="editPosition" name="position" class="w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500 text-sm sm:text-base" placeholder="Enter position" required>
                        <div id="editPositionError" class="text-red-500 text-xs mt-1 hidden"></div>
                    </div>
                    
                    <div class="mb-3 sm:mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email *</label>
                        <input type="email" id="editEmail" name="email" class="w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500 text-sm sm:text-base" placeholder="Enter email" required>
                        <div id="editEmailError" class="text-red-500 text-xs mt-1 hidden"></div>
                    </div>
                    
                    <div class="mb-3 sm:mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Phone *</label>
                        <input type="tel" id="editPhone" name="phone" class="w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500 text-sm sm:text-base" placeholder="Enter phone number" required>
                        <div id="editPhoneError" class="text-red-500 text-xs mt-1 hidden"></div>
                    </div>
                    
                    <div class="mb-3 sm:mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Country *</label>
                        <input type="text" id="editCountry" name="country" class="w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500 text-sm sm:text-base" placeholder="Enter country" required>
                        <div id="editCountryError" class="text-red-500 text-xs mt-1 hidden"></div>
                    </div>
                    
                    <div class="mb-3 sm:mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Social Media Links</label>
                        <div class="space-y-2">
                            <div class="flex items-center">
                                <i class="fab fa-instagram text-pink-500 mr-2 text-xs sm:text-sm"></i>
                                <input type="text" id="editInstagram" name="instagram" class="w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500 text-sm sm:text-base" placeholder="Instagram username">
                            </div>
                            <div class="flex items-center">
                                <i class="fab fa-facebook text-blue-600 mr-2 text-xs sm:text-sm"></i>
                                <input type="text" id="editFacebook" name="facebook" class="w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500 text-sm sm:text-base" placeholder="Facebook profile">
                            </div>
                            <div class="flex items-center">
                                <i class="fab fa-whatsapp text-green-500 mr-2 text-xs sm:text-sm"></i>
                                <input type="text" id="editWhatsapp" name="whatsapp" class="w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500 text-sm sm:text-base" placeholder="WhatsApp number">
                            </div>
                            <div class="flex items-center">
                                <i class="fab fa-linkedin text-blue-700 mr-2 text-xs sm:text-sm"></i>
                                <input type="text" id="editLinkedin" name="linkedin" class="w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500 text-sm sm:text-base" placeholder="LinkedIn profile">
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-4 sm:mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                        <textarea id="editNotes" name="notes" rows="2" class="w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500 text-sm sm:text-base" placeholder="Add any notes about this contact"></textarea>
                    </div>
                    
                    <div class="flex justify-end space-x-2 sm:space-x-3">
                        <button type="button" id="cancelEditContact" class="px-3 sm:px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-all duration-300 text-sm sm:text-base">
                            Cancel
                        </button>
                        <button type="submit" class="px-3 sm:px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-all duration-300 flex items-center text-sm sm:text-base">
                            <i class=" fa-spin hidden mr-2 text-xs sm:text-sm" id="editContactSpinner"></i>
                            Update Contact
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // DOM Elements
        const contactsGrid = document.getElementById('contactsGrid');
        const addContactModal = document.getElementById('addContactModal');
        const editContactModal = document.getElementById('editContactModal');
        const addContactBtn = document.getElementById('addContactBtn');
        const cancelAddContact = document.getElementById('cancelAddContact');
        const cancelEditContact = document.getElementById('cancelEditContact');
        const addContactForm = document.getElementById('addContactForm');
        const editContactForm = document.getElementById('editContactForm');
        const sortContacts = document.getElementById('sortContacts');
        const contactsCount = document.getElementById('contactsCount');
        const exportBtn = document.getElementById('exportBtn');
        const exportMenu = document.getElementById('exportMenu');

        // Initialize the application
        function initApp() {
            loadContacts();
            
            // Set up event listeners
            addContactBtn.addEventListener('click', openAddContactModal);
            document.getElementById('closeAddContactModal').addEventListener('click', closeAddContactModal);
            cancelAddContact.addEventListener('click', closeAddContactModal);
            cancelEditContact.addEventListener('click', closeEditContactModal);
            addContactForm.addEventListener('submit', saveNewContact);
            editContactForm.addEventListener('submit', updateContact);
            sortContacts.addEventListener('change', sortContactsHandler);
            
            // Export button functionality - Fixed for mobile
            exportBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                const isMobile = window.innerWidth <= 640;
                
                if (isMobile) {
                    // On mobile, center the dropdown
                    exportMenu.style.position = 'fixed';
                    exportMenu.style.left = '50%';
                    exportMenu.style.transform = 'translateX(-50%)';
                    exportMenu.style.top = '120px';
                    exportMenu.style.width = '90%';
                    exportMenu.style.maxWidth = '300px';
                } else {
                    // On desktop, use absolute positioning
                    exportMenu.style.position = 'absolute';
                    exportMenu.style.left = '';
                    exportMenu.style.transform = '';
                    exportMenu.style.top = '';
                    exportMenu.style.width = '';
                    exportMenu.style.maxWidth = '';
                }
                
                exportMenu.classList.toggle('hidden');
            });

            // Close dropdown when clicking outside
            document.addEventListener('click', (e) => {
                if (!exportBtn.contains(e.target) && !exportMenu.contains(e.target)) {
                    exportMenu.classList.add('hidden');
                }
            });
            
            // Handle window resize to reset dropdown positioning
            window.addEventListener('resize', () => {
                if (window.innerWidth > 640) {
                    exportMenu.style.position = 'absolute';
                    exportMenu.style.left = '';
                    exportMenu.style.transform = '';
                    exportMenu.style.top = '';
                    exportMenu.style.width = '';
                    exportMenu.style.maxWidth = '';
                }
            });
            
            // Close modals when clicking outside
            window.addEventListener('click', function(e) {
                if (e.target === addContactModal) {
                    closeAddContactModal();
                }
                if (e.target === editContactModal) {
                    closeEditContactModal();
                }
            });
        }

        // Load contacts from server
        function loadContacts() {
            showLoadingState();
            
            fetch('{{ route("contacts.index") }}', {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok: ' + response.status);
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    renderContacts(data.contacts);
                    updateContactsCount(data.contacts.length);
                } else {
                    throw new Error(data.message || 'Failed to load contacts');
                }
            })
            .catch(error => {
                console.error('Error loading contacts:', error);
                showErrorState('Failed to load contacts. Please try again. Error: ' + error.message);
            });
        }

        // Show loading state
        function showLoadingState() {
            contactsGrid.innerHTML = `
                <div class="col-span-full text-center py-8 sm:py-12">
                    <div class="animate-pulse">
                        <i class="fas fa-users text-gray-300 text-4xl sm:text-5xl mb-3 sm:mb-4"></i>
                        <h3 class="text-base sm:text-lg font-medium text-gray-700 mb-2">Loading contacts...</h3>
                        <p class="text-gray-500 text-sm sm:text-base">Please wait while we load your contacts</p>
                    </div>
                </div>
            `;
        }

        // Show error state
        function showErrorState(message) {
            contactsGrid.innerHTML = `
                <div class="col-span-full text-center py-8 sm:py-12">
                    <i class="fas fa-exclamation-triangle text-red-400 text-4xl sm:text-5xl mb-3 sm:mb-4"></i>
                    <h3 class="text-base sm:text-lg font-medium text-gray-700 mb-2">Unable to load contacts</h3>
                    <p class="text-gray-500 text-sm sm:text-base mb-3 sm:mb-4">${message}</p>
                    <button onclick="loadContacts()" class="bg-indigo-600 text-white py-2 px-4 rounded-lg font-medium hover:bg-indigo-700 transition-all duration-300 text-sm sm:text-base">
                        <i class="fas fa-redo mr-2"></i> Try Again
                    </button>
                </div>
            `;
        }

        // Update contacts count
        function updateContactsCount(count) {
            contactsCount.textContent = count;
        }

        // Render contacts to the grid
        function renderContacts(contacts) {
            contactsGrid.innerHTML = '';
            
            if (!contacts || contacts.length === 0) {
                contactsGrid.innerHTML = `
                    <div class="col-span-full text-center py-8 sm:py-12">
                        <i class="fas fa-users text-gray-300 text-4xl sm:text-5xl mb-3 sm:mb-4"></i>
                        <h3 class="text-base sm:text-lg font-medium text-gray-700 mb-2">No contacts found</h3>
                        <p class="text-gray-500 text-sm sm:text-base mb-3 sm:mb-4">Get started by adding your first contact.</p>
                        <button id="addFirstContact" class="bg-orange-500 text-white py-2 px-4 rounded-lg font-medium hover:bg-orange-600 transition-all duration-300 text-sm sm:text-base">
                            <i class="fas fa-plus mr-2"></i> Add Your First Contact
                        </button>
                    </div>
                `;
                document.getElementById('addFirstContact').addEventListener('click', openAddContactModal);
                return;
            }
            
            contacts.forEach(contact => {
                const contactCard = document.createElement('div');
                contactCard.className = 'contact-card bg-white rounded-xl border border-gray-200 p-4 sm:p-6 relative';
                contactCard.innerHTML = `
                    <div class="flex justify-between items-start mb-3 sm:mb-4">
                        <div class="flex items-center">
                            <input type="checkbox" class="h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                        </div>
                        
                        <div class="relative">
                            <button class="menu-btn text-gray-400 hover:text-gray-600">
                                <i class="fas fa-ellipsis-v text-sm sm:text-base"></i>
                            </button>
                            <div class="menu-dropdown absolute right-0 mt-2 w-28 sm:w-32 bg-white rounded-lg shadow-lg py-2 z-10 hidden">
                                <button class="edit-contact w-full text-left px-3 sm:px-4 py-2 text-xs sm:text-sm text-gray-700 hover:bg-gray-100 flex items-center" data-id="${contact.id}">
                                    <i class="fas fa-edit mr-2 text-blue-500 text-xs sm:text-sm"></i> Edit
                                </button>
                                <button class="delete-contact w-full text-left px-3 sm:px-4 py-2 text-xs sm:text-sm text-gray-700 hover:bg-gray-100 flex items-center" data-id="${contact.id}">
                                    <i class="fas fa-trash mr-2 text-red-500 text-xs sm:text-sm"></i> Delete
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <div class="text-center mb-3 sm:mb-4">
                        <div class="h-16 w-16 sm:h-20 sm:w-20 rounded-full mx-auto mb-2 sm:mb-3 flex items-center justify-center bg-orange-500 text-white text-xl sm:text-3xl font-bold">
                            ${contact.name.charAt(0).toUpperCase()}
                        </div>

                        <h3 class="font-bold text-gray-800 text-base sm:text-lg truncate px-2">${contact.name}</h3>
                        <span class="inline-block bg-pink-100 text-pink-800 text-xs font-medium px-2 py-1 rounded-full mt-1">${contact.position}</span>
                    </div>
                    
                    <div class="space-y-1 sm:space-y-2 mb-3 sm:mb-4">
                        <div class="flex items-center text-xs sm:text-sm text-gray-600">
                            <i class="fas fa-envelope text-gray-400 mr-2 w-4 sm:w-5 text-xs sm:text-sm"></i>
                            <span class="truncate">${contact.email}</span>
                        </div>
                        <div class="flex items-center text-xs sm:text-sm text-gray-600">
                            <i class="fas fa-phone text-gray-400 mr-2 w-4 sm:w-5 text-xs sm:text-sm"></i>
                            <span>${contact.phone}</span>
                        </div>
                        <div class="flex items-center text-xs sm:text-sm text-gray-600">
                            <i class="fas fa-globe text-gray-400 mr-2 w-4 sm:w-5 text-xs sm:text-sm"></i>
                            <span>${contact.country}</span>
                        </div>
                    </div>
                    
                    <div class="flex justify-center space-x-2 sm:space-x-3 mb-3 sm:mb-4">
                        ${contact.instagram ? `<a href="https://instagram.com/${contact.instagram}" target="_blank" class="text-gray-400 hover:text-pink-500"><i class="fab fa-instagram text-sm sm:text-base"></i></a>` : ''}
                        ${contact.facebook ? `<a href="${contact.facebook}" target="_blank" class="text-gray-400 hover:text-blue-600"><i class="fab fa-facebook text-sm sm:text-base"></i></a>` : ''}
                        ${contact.whatsapp ? `<a href="https://wa.me/${contact.whatsapp}" target="_blank" class="text-gray-400 hover:text-green-600"><i class="fab fa-whatsapp text-sm sm:text-base"></i></a>` : ''}
                        ${contact.linkedin ? `<a href="${contact.linkedin}" target="_blank" class="text-gray-400 hover:text-blue-700"><i class="fab fa-linkedin text-sm sm:text-base"></i></a>` : ''}
                        <a href="mailto:${contact.email}" class="text-gray-400 hover:text-blue-500"><i class="fas fa-envelope text-sm sm:text-base"></i></a>
                        <a href="tel:${contact.phone}" class="text-gray-400 hover:text-green-500"><i class="fas fa-phone text-sm sm:text-base"></i></a>
                    </div>
                    
                    
                `;
                contactsGrid.appendChild(contactCard);
            });

            // Event Listeners
            document.querySelectorAll('.edit-contact').forEach(button => {
                button.addEventListener('click', function() {
                    const contactId = parseInt(this.getAttribute('data-id'));
                    openEditContactModal(contactId);
                });
            });

            document.querySelectorAll('.delete-contact').forEach(button => {
                button.addEventListener('click', function() {
                    const contactId = parseInt(this.getAttribute('data-id'));
                    deleteContact(contactId);
                });
            });

            // Dropdown logic
            document.querySelectorAll('.menu-btn').forEach(btn => {
                btn.addEventListener('click', function (e) {
                    e.stopPropagation();
                    document.querySelectorAll('.menu-dropdown').forEach(d => {
                        if (d !== this.nextElementSibling) d.classList.add('hidden');
                    });
                    this.nextElementSibling.classList.toggle('hidden');
                });
            });

            document.querySelectorAll('.menu-dropdown').forEach(menu => {
                menu.addEventListener('click', (e) => e.stopPropagation());
            });

            document.addEventListener('click', () => {
                document.querySelectorAll('.menu-dropdown').forEach(d => d.classList.add('hidden'));
            });
        }

        // Open Add Contact Modal
        function openAddContactModal() {
            addContactModal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
            clearFormErrors('add');
        }

        // Close Add Contact Modal
        function closeAddContactModal() {
            addContactModal.classList.add('hidden');
            document.body.style.overflow = 'auto';
            addContactForm.reset();
            clearFormErrors('add');
        }

        // Open Edit Contact Modal
        function openEditContactModal(contactId) {
            clearFormErrors('edit');
            
            fetch(`/contacts/${contactId}`, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Failed to fetch contact: ' + response.status);
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    const contact = data.contact;
                    document.getElementById('editContactId').value = contact.id;
                    document.getElementById('editFullName').value = contact.name;
                    document.getElementById('editPosition').value = contact.position;
                    document.getElementById('editEmail').value = contact.email;
                    document.getElementById('editPhone').value = contact.phone;
                    document.getElementById('editCountry').value = contact.country;
                    document.getElementById('editInstagram').value = contact.instagram || '';
                    document.getElementById('editFacebook').value = contact.facebook || '';
                    document.getElementById('editWhatsapp').value = contact.whatsapp || '';
                    document.getElementById('editLinkedin').value = contact.linkedin || '';
                    document.getElementById('editNotes').value = contact.notes || '';
                    
                    editContactModal.classList.remove('hidden');
                    document.body.style.overflow = 'hidden';
                } else {
                    throw new Error(data.message || 'Failed to load contact');
                }
            })
            .catch(error => {
                console.error('Error loading contact:', error);
                showNotification('Error loading contact data: ' + error.message, 'error');
            });
        }

        // Close Edit Contact Modal
        function closeEditContactModal() {
            editContactModal.classList.add('hidden');
            document.body.style.overflow = 'auto';
            clearFormErrors('edit');
        }

        // Clear form errors
        function clearFormErrors(formType) {
            const prefix = formType === 'add' ? '' : 'edit';
            const errorElements = document.querySelectorAll(`[id$="Error"]`);
            errorElements.forEach(element => {
                if (element.id.startsWith(prefix)) {
                    element.classList.add('hidden');
                    element.textContent = '';
                }
            });
        }

        // Show form errors
        function showFormErrors(errors, formType) {
            const prefix = formType === 'add' ? '' : 'edit';
            clearFormErrors(formType);
            
            Object.keys(errors).forEach(field => {
                const errorElement = document.getElementById(`${prefix}${field.charAt(0).toUpperCase() + field.slice(1)}Error`);
                if (errorElement) {
                    errorElement.textContent = errors[field][0];
                    errorElement.classList.remove('hidden');
                }
            });
        }

        // Save New Contact
        function saveNewContact(e) {
            e.preventDefault();
            
            const submitButton = e.target.querySelector('button[type="submit"]');
            const spinner = document.getElementById('addContactSpinner');
            
            // Show loading state
            submitButton.disabled = true;
            spinner.classList.remove('hidden');
            
            // Get form data
            const formData = new FormData(addContactForm);
            
            // Add CSRF token
            formData.append('_token', '{{ csrf_token() }}');

            fetch('{{ route("contacts.store") }}', {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    loadContacts();
                    closeAddContactModal();
                    showNotification(data.message || 'Contact added successfully!', 'success');
                } else {
                    if (data.errors) {
                        showFormErrors(data.errors, 'add');
                    } else {
                        throw new Error(data.message || 'Failed to add contact');
                    }
                }
            })
            .catch(error => {
                console.error('Error adding contact:', error);
                showNotification('Error adding contact: ' + error.message, 'error');
            })
            .finally(() => {
                // Restore button state
                submitButton.disabled = false;
                spinner.classList.add('hidden');
            });
        }

        // Update Contact
        function updateContact(e) {
            e.preventDefault();
            
            const contactId = document.getElementById('editContactId').value;
            const submitButton = e.target.querySelector('button[type="submit"]');
            const spinner = document.getElementById('editContactSpinner');
            
            // Show loading state
            submitButton.disabled = true;
            spinner.classList.remove('hidden');
            
            // Get form data
            const formData = new FormData(editContactForm);
            
            // Add CSRF token and method
            formData.append('_token', '{{ csrf_token() }}');
            formData.append('_method', 'PUT');

            fetch(`/contacts/${contactId}`, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    loadContacts();
                    closeEditContactModal();
                    showNotification(data.message || 'Contact updated successfully!', 'success');
                } else {
                    if (data.errors) {
                        showFormErrors(data.errors, 'edit');
                    } else {
                        throw new Error(data.message || 'Failed to update contact');
                    }
                }
            })
            .catch(error => {
                console.error('Error updating contact:', error);
                showNotification('Error updating contact: ' + error.message, 'error');
            })
            .finally(() => {
                // Restore button state
                submitButton.disabled = false;
                spinner.classList.add('hidden');
            });
        }

        // Delete Contact
        function deleteContact(contactId) {
            if (confirm('Are you sure you want to delete this contact? This action cannot be undone.')) {
                fetch(`/contacts/${contactId}`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        loadContacts();
                        showNotification(data.message || 'Contact deleted successfully!', 'success');
                    } else {
                        throw new Error(data.message || 'Failed to delete contact');
                    }
                })
                .catch(error => {
                    console.error('Error deleting contact:', error);
                    showNotification('Error deleting contact: ' + error.message, 'error');
                });
            }
        }

        // Sort Contacts
        function sortContactsHandler() {
            const sortBy = sortContacts.value;
            loadContacts();
        }

        // Show notification
        function showNotification(message, type = 'info') {
            // Remove existing notifications
            document.querySelectorAll('.notification').forEach(notification => {
                notification.remove();
            });
            
            // Create notification element
            const notification = document.createElement('div');
            notification.className = `notification fixed top-3 sm:top-4 right-3 sm:right-4 p-3 sm:p-4 rounded-lg shadow-lg z-50 transform translate-x-full transition-transform duration-300 ${
                type === 'success' ? 'bg-green-500 text-white' : 
                type === 'error' ? 'bg-red-500 text-white' : 
                'bg-blue-500 text-white'
            }`;
            notification.innerHTML = `
                <div class="flex items-center">
                    <i class="fas fa-${type === 'success' ? 'check' : type === 'error' ? 'exclamation-triangle' : 'info'} mr-2 text-sm sm:text-base"></i>
                    <span class="text-xs sm:text-sm">${message}</span>
                </div>
            `;
            
            document.body.appendChild(notification);
            
            // Animate in
            setTimeout(() => {
                notification.classList.remove('translate-x-full');
            }, 10);
            
            // Remove notification after 3 seconds
            setTimeout(() => {
                notification.classList.add('translate-x-full');
                setTimeout(() => {
                    notification.remove();
                }, 300);
            }, 3000);
        }

        // Initialize the app
        document.addEventListener('DOMContentLoaded', initApp);
    </script>
</body>
</html>

@endsection
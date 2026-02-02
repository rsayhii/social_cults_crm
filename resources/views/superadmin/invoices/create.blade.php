@extends('superadmin.layout.app')

@section('title', 'Create Invoice')

@section('content')
<div class="max-w-7xl mx-auto p-6">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Create New Invoice</h1>
            <p class="text-gray-600">Fill in the details below to create a professional invoice</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('invoices.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition duration-200 font-medium flex items-center">
                <i class="fas fa-arrow-left mr-2"></i>
                Back to Invoices
            </a>
        </div>
    </div>

    <form action="{{ route('superadmin.invoices.store') }}" method="POST" enctype="multipart/form-data" id="invoiceForm">
        @csrf
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column - Client & Company Info -->
            <div class="space-y-6">
                <!-- Client Information Card -->
                <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-user-circle mr-2 text-blue-600"></i>
                        Client Information
                    </h3>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Client Name *</label>
                            <input type="text" name="client_name" required class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Client / Company Name">
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                                <input type="text" name="client_phone" class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Phone">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                                <input type="email" name="client_email" class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Email">
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Address & GSTIN</label>
                            <textarea name="client_address" rows="3" class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Full address with GSTIN if applicable"></textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">GSTIN</label>
                            <input type="text" name="client_gstin" class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="GSTIN Number">
                        </div>
                    </div>
                </div>

                <!-- Company Information Card -->
                <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-building mr-2 text-blue-600"></i>
                        Your Company Information
                    </h3>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Company Name *</label>
                            <input type="text" name="company_name" required value="{{ $defaultCompany['name'] }}" class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Your Company Name">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Company Address *</label>
                            <textarea name="company_address" required rows="2" class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Your company address">{{ $defaultCompany['address'] }}</textarea>
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                                <input type="text" name="company_phone" value="{{ $defaultCompany['phone'] }}" class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Company Phone">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                                <input type="email" name="company_email" value="{{ $defaultCompany['email'] }}" class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Company Email">
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Company GSTIN</label>
                            <input type="text" name="company_gstin" value="{{ $defaultCompany['gstin'] }}" class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Company GSTIN">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Bank Details</label>
                            <textarea name="company_bank_details" rows="2" class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Bank account details">{{ $defaultCompany['bank_details'] }}</textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Company Logo</label>
                            <input type="file" name="company_logo" accept="image/*" class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <p class="text-xs text-gray-500 mt-1">Upload your company logo (PNG, JPG, WebP)</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column (2/3 width) - Invoice Details & Items -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Invoice Settings Card -->
                <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-cog mr-2 text-blue-600"></i>
                        Invoice Settings
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Invoice Date *</label>
                            <input type="date" name="invoice_date" required value="{{ date('Y-m-d') }}" class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Due Date</label>
                            <input type="date" name="due_date" class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Currency *</label>
                            <select name="currency" required class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="₹" selected>INR (₹)</option>
                                <option value="$">USD ($)</option>
                                <option value="€">EUR (€)</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Status *</label>
                            <select name="status" required class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="pending" selected>Pending</option>
                                <option value="paid">Paid</option>
                                <option value="overdue">Overdue</option>
                                <option value="cancelled">Cancelled</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">GST Rate (%) *</label>
                            <select name="tax_rate" required class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="0">No Tax</option>
                                <option value="5">5%</option>
                                <option value="12">12%</option>
                                <option value="18" selected>18%</option>
                                <option value="28">28%</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">GST Type *</label>
                            <select name="tax_type" required class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="cgst_sgst" selected>CGST + SGST</option>
                                <option value="igst">IGST</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Discount (₹)</label>
                        <input type="number" name="discount" min="0" step="0.01" value="0" class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Discount amount">
                    </div>
                    
                    <div class="mt-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                        <textarea name="description" rows="2" class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Add description for the invoice"></textarea>
                    </div>
                </div>

                <!-- Invoice Items Card -->
                <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                            <i class="fas fa-list-alt mr-2 text-blue-600"></i>
                            Invoice Items
                        </h3>
                        <button type="button" id="addItemBtn" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-200 font-medium flex items-center">
                            <i class="fas fa-plus mr-2"></i>
                            Add Item
                        </button>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
                                    <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description *</th>
                                    <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Service Type</th>
                                    <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity *</th>
                                    <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Unit Price *</th>
                                    <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                    <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"></th>
                                </tr>
                            </thead>
                            <tbody id="itemsContainer">
                                <!-- Items will be added here dynamically -->
                                <tr class="item-row">
                                    <td class="py-3 px-4">
                                        <span class="item-number">1</span>
                                    </td>
                                    <td class="py-3 px-4">
                                        <input type="text" name="items[0][description]" required class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Item description">
                                    </td>
                                    <td class="py-3 px-4">
                                        <select name="items[0][service_type]" class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                                            <option value="">Select Service</option>
                                            <option value="Social Media Management">Social Media Management</option>
                                            <option value="Content Creation">Content Creation</option>
                                            <option value="Website Development">Website Development</option>
                                            <option value="SEO">SEO</option>
                                            <option value="Graphic Designing">Graphic Designing</option>
                                            <option value="Video Editing">Video Editing</option>
                                        </select>
                                    </td>
                                    <td class="py-3 px-4">
                                        <input type="number" name="items[0][quantity]" required min="0.01" step="0.01" value="1" class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Qty">
                                    </td>
                                    <td class="py-3 px-4">
                                        <input type="number" name="items[0][unit_price]" required min="0" step="0.01" value="0" class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Price">
                                    </td>
                                    <td class="py-3 px-4">
                                        <span class="item-amount font-medium">₹0.00</span>
                                    </td>
                                    <td class="py-3 px-4">
                                        <button type="button" class="remove-item-btn text-red-600 hover:text-red-800">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-4 flex justify-end">
                        <div class="w-64 space-y-2">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Subtotal:</span>
                                <span id="subtotalDisplay" class="font-medium">₹0.00</span>
                            </div>
                            <div class="flex justify-between">
                                <span id="taxLabel" class="text-gray-600">Tax (18%):</span>
                                <span id="taxDisplay" class="font-medium">₹0.00</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Discount:</span>
                                <span id="discountDisplay" class="font-medium">₹0.00</span>
                            </div>
                            <hr class="my-2">
                            <div class="flex justify-between text-lg font-bold">
                                <span>Total:</span>
                                <span id="totalDisplay">₹0.00</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Signature & Terms Card -->
                <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-signature mr-2 text-blue-600"></i>
                        Signature & Terms
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Signature</label>
                            <input type="file" name="signature" accept="image/*" class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <p class="text-xs text-gray-500 mt-1">Upload authorized signature (PNG with transparent background)</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Terms & Conditions</label>
                            <textarea name="terms_conditions" rows="4" class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Enter your terms and conditions..."></textarea>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex justify-end space-x-4">
                    <button type="button" onclick="window.history.back()" class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition duration-200 font-medium">
                        Cancel
                    </button>
                    <button type="submit" class="px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-lg hover:from-blue-700 hover:to-indigo-700 transition duration-200 font-medium flex items-center">
                        <i class="fas fa-save mr-2"></i>
                        Create Invoice
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    let itemCount = 1;
    const itemsContainer = document.getElementById('itemsContainer');
    const addItemBtn = document.getElementById('addItemBtn');
    
    // Add item row
    addItemBtn.addEventListener('click', function() {
        itemCount++;
        const newRow = document.createElement('tr');
        newRow.className = 'item-row';
        newRow.innerHTML = `
            <td class="py-3 px-4">
                <span class="item-number">${itemCount}</span>
            </td>
            <td class="py-3 px-4">
                <input type="text" name="items[${itemCount-1}][description]" required class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Item description">
            </td>
            <td class="py-3 px-4">
                <select name="items[${itemCount-1}][service_type]" class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Select Service</option>
                    <option value="Social Media Management">Social Media Management</option>
                    <option value="Content Creation">Content Creation</option>
                    <option value="Website Development">Website Development</option>
                    <option value="SEO">SEO</option>
                    <option value="Graphic Designing">Graphic Designing</option>
                    <option value="Video Editing">Video Editing</option>
                </select>
            </td>
            <td class="py-3 px-4">
                <input type="number" name="items[${itemCount-1}][quantity]" required min="0.01" step="0.01" value="1" class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Qty">
            </td>
            <td class="py-3 px-4">
                <input type="number" name="items[${itemCount-1}][unit_price]" required min="0" step="0.01" value="0" class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Price">
            </td>
            <td class="py-3 px-4">
                <span class="item-amount font-medium">₹0.00</span>
            </td>
            <td class="py-3 px-4">
                <button type="button" class="remove-item-btn text-red-600 hover:text-red-800">
                    <i class="fas fa-trash"></i>
                </button>
            </td>
        `;
        itemsContainer.appendChild(newRow);
        
        // Add event listeners to new row
        const quantityInput = newRow.querySelector('input[name*="quantity"]');
        const priceInput = newRow.querySelector('input[name*="unit_price"]');
        
        quantityInput.addEventListener('input', calculateRowAmount);
        priceInput.addEventListener('input', calculateRowAmount);
        
        // Add remove event
        newRow.querySelector('.remove-item-btn').addEventListener('click', function() {
            newRow.remove();
            updateItemNumbers();
            calculateTotals();
        });
        
        // Update item numbers
        updateItemNumbers();
    });
    
    // Remove item row
    document.addEventListener('click', function(e) {
        if (e.target.closest('.remove-item-btn')) {
            const row = e.target.closest('.item-row');
            if (document.querySelectorAll('.item-row').length > 1) {
                row.remove();
                updateItemNumbers();
                calculateTotals();
            } else {
                alert('At least one item is required');
            }
        }
    });
    
    // Calculate row amount
    function calculateRowAmount() {
        const row = this.closest('.item-row');
        const quantity = parseFloat(row.querySelector('input[name*="quantity"]').value) || 0;
        const price = parseFloat(row.querySelector('input[name*="unit_price"]').value) || 0;
        const amount = quantity * price;
        
        const currency = document.querySelector('select[name="currency"]').value || '₹';
        row.querySelector('.item-amount').textContent = `${currency}${amount.toFixed(2)}`;
        
        calculateTotals();
    }
    
    // Update item numbers
    function updateItemNumbers() {
        document.querySelectorAll('.item-row').forEach((row, index) => {
            row.querySelector('.item-number').textContent = index + 1;
            // Update input names
            const descriptionInput = row.querySelector('input[name*="description"]');
            const serviceSelect = row.querySelector('select[name*="service_type"]');
            const quantityInput = row.querySelector('input[name*="quantity"]');
            const priceInput = row.querySelector('input[name*="unit_price"]');
            
            descriptionInput.name = `items[${index}][description]`;
            serviceSelect.name = `items[${index}][service_type]`;
            quantityInput.name = `items[${index}][quantity]`;
            priceInput.name = `items[${index}][unit_price]`;
        });
    }
    
    // Calculate totals
    function calculateTotals() {
        let subtotal = 0;
        document.querySelectorAll('.item-row').forEach(row => {
            const quantity = parseFloat(row.querySelector('input[name*="quantity"]').value) || 0;
            const price = parseFloat(row.querySelector('input[name*="unit_price"]').value) || 0;
            subtotal += quantity * price;
        });
        
        const taxRate = parseFloat(document.querySelector('select[name="tax_rate"]').value) || 0;
        const discount = parseFloat(document.querySelector('input[name="discount"]').value) || 0;
        const currency = document.querySelector('select[name="currency"]').value || '₹';
        
        const taxAmount = (subtotal * taxRate) / 100;
        const total = subtotal + taxAmount - discount;
        
        // Update displays
        document.getElementById('subtotalDisplay').textContent = `${currency}${subtotal.toFixed(2)}`;
        document.getElementById('taxDisplay').textContent = `${currency}${taxAmount.toFixed(2)}`;
        document.getElementById('discountDisplay').textContent = `${currency}${discount.toFixed(2)}`;
        document.getElementById('totalDisplay').textContent = `${currency}${total.toFixed(2)}`;
        
        // Update tax label
        const taxType = document.querySelector('select[name="tax_type"]').value;
        const taxLabel = taxType === 'cgst_sgst' ? `GST (${taxRate}% CGST+SGST)` : `IGST (${taxRate}%)`;
        document.getElementById('taxLabel').textContent = `${taxLabel}:`;
    }
    
    // Add event listeners to existing row
    document.querySelectorAll('.item-row input[name*="quantity"], .item-row input[name*="unit_price"]').forEach(input => {
        input.addEventListener('input', calculateRowAmount);
    });
    
    // Add event listeners to tax and discount inputs
    document.querySelector('select[name="tax_rate"]').addEventListener('change', calculateTotals);
    document.querySelector('select[name="tax_type"]').addEventListener('change', calculateTotals);
    document.querySelector('input[name="discount"]').addEventListener('input', calculateTotals);
    document.querySelector('select[name="currency"]').addEventListener('change', calculateTotals);
    
    // Initialize calculation
    calculateTotals();
});
</script>
@endsection
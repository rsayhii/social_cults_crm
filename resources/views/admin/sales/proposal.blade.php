@extends('components.layout')

@section('content')
    <div class="flex-1 overflow-auto ">
        <div class="max-w-7xl mx-auto space-y-6">


            <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/mammoth/1.4.14/mammoth.browser.min.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/docx/7.1.0/docx.js"></script>
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
            <style>
                .proposal-card {
                    transition: all 0.3s ease;
                    cursor: pointer;
                }

                .proposal-card:hover {
                    transform: translateY(-10px);
                    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
                    border-color: #6366f1 !important;
                }

                .edit-icon {
                    position: absolute;
                    top: 10px;
                    right: 10px;
                    opacity: 0;
                    transition: opacity 0.2s;
                    color: #6366f1;
                    cursor: pointer;
                }

                .editable-section:hover .edit-icon {
                    opacity: 1;
                }

                [contenteditable="true"]:hover,
                [contenteditable="true"]:focus {
                    outline: 2px dashed #a5b4fc;
                    background-color: #f8fafc;
                    border-radius: 8px;
                }

                #uploadModal.hidden,
                #fullEditorView.hidden,
                #fileDisplay.hidden,
                #quickEditPanel.hidden,
                #addTemplateModal.hidden,
                #saveToast.hidden {
                    display: none !important;
                }

                .template-active {
                    border-color: #6366f1 !important;
                    background-color: #f8f9ff;
                }

                .blank-card {
                    border: 3px dashed #c7d2fe;
                    background: linear-gradient(135deg, #eef2ff 0%, #e0e8ff 100%);
                    min-height: 380px;
                }

                .section-highlight {
                    position: relative;
                    padding: 20px;
                    margin-bottom: 2rem;
                    border-radius: 12px;
                    transition: all 0.3s;
                }

                .section-highlight:hover {
                    background-color: #f8fafc;
                }

                .section-highlight .edit-icon {
                    top: 12px;
                    right: 12px;
                }

                .template-item {
                    border-bottom: 1px solid #e5e7eb;
                    padding: 24px 0;
                    transition: all 0.3s ease;
                }

                .template-item:hover {
                    background-color: #f9fafb;
                }

                .template-item:last-child {
                    border-bottom: none;
                }

                .pdf-export-container {
                    background-color: white;
                    padding: 40px;
                    max-width: 8.5in;
                    margin: 0 auto;
                    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                }

                /* PDF-specific styles */
                .pdf-section {
                    margin-bottom: 20px;
                    page-break-inside: avoid;
                }

                .pdf-table {
                    width: 100%;
                    border-collapse: collapse;
                    margin: 15px 0;
                }

                .pdf-table th,
                .pdf-table td {
                    border: 1px solid #ddd;
                    padding: 8px;
                    text-align: left;
                }

                .pdf-table th {
                    background-color: #f2f2f2;
                }
            </style>


            <!-- Header -->
            <header class="bg-white shadow-sm border-b border-gray-200">
                <div class="flex items-center justify-between px-6 py-4">
                    <div class="flex items-center">
                        <h1 class="text-2xl font-bold text-gray-800">Proposal Builder</h1>
                        <span
                            class="ml-2 bg-indigo-100 text-indigo-800 text-xs font-medium px-2 py-1 rounded-full">CRM</span>
                    </div>
                </div>

            </header>

            <div class="container mx-auto px-4 py-8">
                <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">

                    <!-- Left: Template Library -->
                    <div class="lg:col-span-1">
                        <div class="bg-white rounded-xl shadow-sm p-6 sticky top-6">
                            <div class="flex items-center justify-between mb-6">
                                <h2 class="text-xl font-bold text-gray-800">Templates</h2>
                                <button id="addTemplateBtn"
                                    class="bg-indigo-600 text-white p-2 rounded-lg hover:bg-indigo-700">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                            <div id="templateList" class="space-y-0">
                                <!-- Templates will be rendered here -->
                            </div>
                        </div>
                    </div>

                    <!-- Right: Main Area -->
                    <div class="lg:col-span-3">
                        <!-- Saved Proposals Section -->
                        <div id="savedProposalsSection" class="mb-8 hidden">
                            <h2 class="text-2xl font-bold text-gray-800 mb-6">Saved Proposals</h2>
                            <div id="savedProposalsGrid" class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-8">
                                <!-- Saved proposals will be rendered here -->
                            </div>
                        </div>

                        <div id="proposalCardsView">
                            <h2 class="text-2xl font-bold text-gray-800 mb-6">Choose or Create Proposal</h2>
                            <div id="proposalCardsGrid" class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-8">

                                <!-- Blank Card -->
                                <div
                                    class="blank-card proposal-card bg-white rounded-2xl shadow-lg overflow-hidden border-2 flex flex-col items-center justify-center text-center p-10">
                                    <div class="w-20 h-20 bg-indigo-100 rounded-full flex items-center justify-center mb-6">
                                        <i class="fas fa-plus text-4xl text-indigo-600"></i>
                                    </div>
                                    <h3 class="text-2xl font-bold text-gray-800 mb-3">Create New Proposal</h3>
                                    <p class="text-gray-600 mb-6">Upload your document to start editing</p>
                                    <button id="openUploadModal"
                                        class="bg-indigo-600 text-white px-8 py-4 rounded-xl font-semibold hover:bg-indigo-700 shadow-lg">
                                        Upload File
                                    </button>
                                </div>

                            </div>
                        </div>

                        <!-- Formatting Toolbar (hidden by default, shown in editor view) -->
                        <div id="formattingToolbar"
                            style="display: none; background-color: white; border: 1px solid #e5e7eb; border-radius: 8px; padding: 12px; margin-bottom: 16px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); position: sticky; top: 10px; z-index: 40;">
                            <div style="display: flex; gap: 8px; flex-wrap: wrap; align-items: center;">
                                <!-- Text Formatting Group -->
                                <div style="display: flex; gap: 4px; padding-right: 8px; border-right: 1px solid #e5e7eb;">
                                    <button onclick="formatText('bold')" title="Bold (Ctrl+B)"
                                        style="padding: 6px 10px; border: 1px solid #d1d5db; border-radius: 4px; background: white; cursor: pointer; font-weight: bold; transition: all 0.2s;"
                                        onmouseover="this.style.backgroundColor='#f3f4f6'"
                                        onmouseout="this.style.backgroundColor='white'">B</button>
                                    <button onclick="formatText('italic')" title="Italic (Ctrl+I)"
                                        style="padding: 6px 10px; border: 1px solid #d1d5db; border-radius: 4px; background: white; cursor: pointer; font-style: italic; transition: all 0.2s;"
                                        onmouseover="this.style.backgroundColor='#f3f4f6'"
                                        onmouseout="this.style.backgroundColor='white'">I</button>
                                    <button onclick="formatText('underline')" title="Underline (Ctrl+U)"
                                        style="padding: 6px 10px; border: 1px solid #d1d5db; border-radius: 4px; background: white; cursor: pointer; text-decoration: underline; transition: all 0.2s;"
                                        onmouseover="this.style.backgroundColor='#f3f4f6'"
                                        onmouseout="this.style.backgroundColor='white'">U</button>
                                </div>

                                <!-- Heading Group -->
                                <div style="display: flex; gap: 4px; padding-right: 8px; border-right: 1px solid #e5e7eb;">
                                    <select onchange="formatBlock(this.value); this.value=''"
                                        style="padding: 6px 10px; border: 1px solid #d1d5db; border-radius: 4px; background: white; cursor: pointer; font-size: 14px;">
                                        <option value="">Format</option>
                                        <option value="h1">Heading 1</option>
                                        <option value="h2">Heading 2</option>
                                        <option value="h3">Heading 3</option>
                                        <option value="p">Paragraph</option>
                                    </select>

                                    <select onchange="formatFontSize(this.value); this.value=''"
                                        style="padding: 6px 10px; border: 1px solid #d1d5db; border-radius: 4px; background: white; cursor: pointer; font-size: 14px;">
                                        <option value="">Size</option>
                                        <option value="1">Small</option>
                                        <option value="3">Normal</option>
                                        <option value="5">Large</option>
                                        <option value="7">X-Large</option>
                                    </select>
                                </div>



                                <!-- Alignment Group -->
                                <div style="display: flex; gap: 4px; padding-right: 8px; border-right: 1px solid #e5e7eb;">
                                    <button onclick="formatText('justifyLeft')" title="Align Left"
                                        style="padding: 6px 10px; border: 1px solid #d1d5db; border-radius: 4px; background: white; cursor: pointer; transition: all 0.2s;"
                                        onmouseover="this.style.backgroundColor='#f3f4f6'"
                                        onmouseout="this.style.backgroundColor='white'">⬅</button>
                                    <button onclick="formatText('justifyCenter')" title="Align Center"
                                        style="padding: 6px 10px; border: 1px solid #d1d5db; border-radius: 4px; background: white; cursor: pointer; transition: all 0.2s;"
                                        onmouseover="this.style.backgroundColor='#f3f4f6'"
                                        onmouseout="this.style.backgroundColor='white'">↔</button>
                                    <button onclick="formatText('justifyRight')" title="Align Right"
                                        style="padding: 6px 10px; border: 1px solid #d1d5db; border-radius: 4px; background: white; cursor: pointer; transition: all 0.2s;"
                                        onmouseover="this.style.backgroundColor='#f3f4f6'"
                                        onmouseout="this.style.backgroundColor='white'">➡</button>
                                </div>


                                <!-- Utility Group -->
                                <div style="display: flex; gap: 4px;">
                                    <button onclick="formatText('undo')" title="Undo"
                                        style="padding: 6px 10px; border: 1px solid #d1d5db; border-radius: 4px; background: white; cursor: pointer; transition: all 0.2s;"
                                        onmouseover="this.style.backgroundColor='#f3f4f6'"
                                        onmouseout="this.style.backgroundColor='white'">↶</button>
                                    <button onclick="formatText('redo')" title="Redo"
                                        style="padding: 6px 10px; border: 1px solid #d1d5db; border-radius: 4px; background: white; cursor: pointer; transition: all 0.2s;"
                                        onmouseover="this.style.backgroundColor='#f3f4f6'"
                                        onmouseout="this.style.backgroundColor='white'">↷</button>
                                    <button onclick="formatText('removeFormat')" title="Clear Formatting"
                                        style="padding: 6px 10px; border: 1px solid #d1d5db; border-radius: 4px; background: white; cursor: pointer; transition: all 0.2s;"
                                        onmouseover="this.style.backgroundColor='#f3f4f6'"
                                        onmouseout="this.style.backgroundColor='white'">✖</button>
                                </div>
                            </div>
                        </div>

                        <!-- Full Editor -->
                        <div id="fullEditorView" class="hidden">
                            <div class="bg-white rounded-2xl shadow-xl p-10">
                                <div class="flex justify-between items-center mb-8">
                                    <h2 class="text-2xl font-bold text-gray-800">Edit Proposal</h2>
                                    <div class="flex space-x-4">
                                        <button id="saveProposal"
                                            class="bg-green-600 text-white px-6 py-3 rounded-xl font-semibold hover:bg-green-700 shadow-lg">
                                            <i class="fas fa-save mr-2"></i> Save Proposal
                                        </button>
                                        <button id="backToCards" class="text-indigo-600 hover:text-indigo-800 font-medium">
                                            Back to Proposals
                                        </button>
                                    </div>
                                </div>
                                <div id="proposalContent"
                                    class="border-2 border-dashed border-gray-200 rounded-xl p-12 bg-white min-h-screen">
                                    <!-- Content injected here -->
                                </div>
                                <div class="mt-8 flex justify-end space-x-4">
                                    <button id="exportDOC"
                                        class="bg-blue-600 text-white px-8 py-4 rounded-xl font-semibold hover:bg-blue-700 shadow-lg">
                                        <i class="fas fa-file-word mr-2"></i> Export DOC
                                    </button>
                                    <button id="exportPDF"
                                        class="bg-indigo-600 text-white hidden px-8 py-4 rounded-xl font-semibold hover:bg-indigo-700 shadow-lg">
                                        <i class="fas fa-file-pdf mr-2"></i> Export PDF
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Upload Modal -->
            <div id="uploadModal" class="fixed inset-0 bg-black bg-opacity-60 flex items-center justify-center z-50 hidden">
                <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full mx-4 p-8">
                    <h2 class="text-2xl font-bold mb-8 text-center">Upload Document</h2>
                    <div class="border-4 border-dashed border-indigo-300 rounded-2xl p-12 text-center cursor-pointer hover:border-indigo-500 transition-all relative"
                        id="dropZone">
                        <input type="file" id="fileInput" accept=".pdf,.docx,.xlsx,.xls" class="hidden">
                        <i class="fas fa-cloud-upload-alt text-6xl text-indigo-400 mb-6"></i>
                        <p class="text-xl font-semibold text-gray-700">Drop file here or click to browse</p>
                        <p class="text-sm text-gray-500 mt-2">Supported: PDF, Word (.docx), Excel (.xlsx)</p>
                    </div>
                    <div id="fileDisplay" class="mt-4 p-4 bg-green-50 border border-green-200 rounded-lg hidden">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <i class="fas fa-check-circle text-green-600 text-xl"></i>
                                <div>
                                    <p id="fileNameDisplay" class="font-medium text-gray-800"></p>
                                    <p id="fileSizeDisplay" class="text-sm text-gray-600"></p>
                                </div>
                            </div>
                            <button onclick="clearFileSelection()" class="text-red-500 hover:text-red-700">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                    <div class="mt-8 flex space-x-4">
                        <button id="cancelUpload"
                            class="flex-1 bg-gray-200 text-gray-800 py-3 rounded-lg hover:bg-gray-300">Cancel</button>
                        <button id="processFileBtn"
                            class="flex-1 bg-indigo-600 text-white py-3 rounded-lg hover:bg-indigo-700 hidden">Process
                            File</button>
                    </div>
                </div>
            </div>

            <!-- Quick Edit Panel -->
            <div id="quickEditPanel"
                class="fixed right-0 top-0 h-full w-96 bg-white shadow-2xl border-l transform translate-x-full transition-transform duration-300 z-50">
                <div class="p-8 h-full overflow-y-auto">
                    <div class="flex justify-between items-center mb-8">
                        <h3 class="text-xl font-bold">Quick Edit</h3>
                        <button id="closeQuickEdit" class="text-gray-500 hover:text-gray-800">Close</button>
                    </div>
                    <div class="space-y-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Client Name</label>
                            <input id="editClientName" type="text" placeholder="Enter client name"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Company Name</label>
                            <input id="editClientCompany" type="text" placeholder="Enter company name"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                        <button id="applyQuickEdits"
                            class="w-full bg-indigo-600 text-white py-4 rounded-lg font-semibold hover:bg-indigo-700">
                            Apply Changes & Close
                        </button>
                    </div>
                </div>
            </div>

            <!-- Add Template Modal -->
            <div id="addTemplateModal"
                class="fixed inset-0 bg-black bg-opacity-60 flex items-center justify-center z-50 hidden">
                <div class="bg-white rounded-2xl shadow-2xl max-w-lg w-full mx-4 p-8">
                    <h2 class="text-2xl font-bold mb-6">Create New Template</h2>
                    <div class="space-y-5">
                        <select id="templatePreset" class="w-full px-5 py-3 border rounded-lg">
                            <option value="">-- Choose Preset --</option>
                            <option value="social">Social Media Marketing</option>
                            <option value="website">Website Development</option>
                            <option value="ads">Google Ads Campaign</option>
                            <option value="seo">SEO Proposal</option>
                            <option value="others">Others (Custom)</option>
                        </select>
                        <div id="customFields" class="hidden space-y-5">
                            <input id="customName" type="text" placeholder="Template Name (e.g. SEO Proposal)"
                                class="w-full px-5 py-3 border rounded-lg">
                            <textarea id="customDesc" rows="3" placeholder="Short description..."
                                class="w-full px-5 py-3 border rounded-lg"></textarea>
                            <select id="customIcon" class="w-full px-5 py-3 border rounded-lg">
                                <option value="hashtag">Social Media</option>
                                <option value="globe">Website</option>
                                <option value="ad">Google Ads</option>
                                <option value="search">SEO</option>
                                <option value="palette">Design</option>
                                <option value="briefcase">Consulting</option>
                                <option value="chart-line">Analytics</option>
                            </select>
                            <select id="customColor" class="w-full px-5 py-3 border rounded-lg">
                                <option value="indigo">Indigo</option>
                                <option value="blue">Blue</option>
                                <option value="green">Green</option>
                                <option value="purple">Purple</option>
                                <option value="pink">Pink</option>
                                <option value="red">Red</option>
                                <option value="yellow">Yellow</option>
                            </select>
                        </div>
                        <div class="flex space-x-4">
                            <button id="saveTemplate"
                                class="flex-1 bg-indigo-600 text-white py-3 rounded-lg hover:bg-indigo-700 font-medium">Save
                                Template</button>
                            <button id="closeModal"
                                class="flex-1 bg-gray-200 text-gray-800 py-3 rounded-lg hover:bg-gray-300">Cancel</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Save Confirmation Toast -->
            <div id="saveToast"
                class="fixed bottom-8 right-4 bg-green-600 text-white px-6 py-3 rounded-lg shadow-lg transform translate-y-full transition-transform duration-300 z-50 hidden">
                <div class="flex items-center">
                    <i class="fas fa-check-circle mr-2"></i>
                    <span>Proposal saved successfully!</span>
                </div>
            </div>


            <style>
                /* Editable block styles */
                .editable-block {
                    position: relative;
                    padding: 20px;
                    margin-bottom: 20px;
                    border: 2px dashed transparent;
                    transition: all 0.2s;
                    border-radius: 8px;
                }

                .editable-block:hover {
                    border-color: #e5e7eb !important;
                    background-color: #f9fafb;
                }

                .delete-block-btn {
                    position: absolute;
                    top: 8px;
                    right: 8px;
                    background-color: #dc2626;
                    color: white;
                    border: none;
                    border-radius: 4px;
                    padding: 4px 12px;
                    cursor: pointer;
                    opacity: 0;
                    transition: opacity 0.2s, background-color 0.2s;
                    font-size: 12px;
                    font-weight: 600;
                    z-index: 10;
                }

                .editable-block:hover .delete-block-btn {
                    opacity: 1 !important;
                }

                .delete-block-btn:hover {
                    background-color: #b91c1c !important;
                }

                /* Editable client name styles */
                .editable-client-name {
                    padding: 4px 8px;
                    border-radius: 4px;
                    cursor: text;
                    transition: background-color 0.2s;
                }

                .editable-client-name:hover {
                    background-color: #f3f4f6;
                }

                .editable-client-name:focus {
                    outline: 2px solid #6366f1;
                    outline-offset: 2px;
                    background-color: #f3f4f6;
                }
            </style>

            <script>
                pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';

                // Professional proposal templates with PDF-friendly styling
                const proposalTemplates = {
                    social: `
                                                                                                        <div class="pdf-export-container">
                                                                                                            <div class="pdf-section">
                                                                                                                <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 30px;">
                                                                                                                    <div>
                                                                                                                        <h1 id="proposalTitle" style="font-size: 28px; font-weight: bold; color: #1f2937; margin-bottom: 10px;" contenteditable="true">Social Media Marketing Proposal</h1>
                                                                                                                        <p style="color: #6b7280;" contenteditable="true">Prepared for <span id="clientName" style="font-weight: 500;">Client Name</span> at <span id="clientCompany" style="font-weight: 500;">Company Name</span></p>
                                                                                                                    </div>
                                                                                                                    <div style="text-align: right;">
                                                                                                                        <p style="color: #6b7280;">Date: <span id="proposalDate" style="font-weight: 500;">${new Date().toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' })}</span></p>
                                                                                                                        <p style="color: #6b7280;">Proposal ID: <span style="font-weight: 500;">#SMM-2025-001</span></p>
                                                                                                                    </div>
                                                                                                                </div>
                                                                                                            </div>

                                                                                                            <div class="pdf-section">
                                                                                                                <h2 style="font-size: 20px; font-weight: bold; color: #1f2937; margin-bottom: 15px;">Service Overview</h2>
                                                                                                                <div style="color: #374151;" contenteditable="true">
                                                                                                                    <p>This proposal outlines our comprehensive social media marketing services designed to increase your brand visibility, engage your target audience, and drive measurable results for your business.</p>
                                                                                                                    <p style="margin-top: 15px;">Our approach combines strategic planning, creative content development, and data-driven optimization to ensure your social media presence aligns with your business objectives.</p>
                                                                                                                </div>
                                                                                                            </div>

                                                                                                            <div class="pdf-section">
                                                                                                                <h2 style="font-size: 20px; font-weight: bold; color: #1f2937; margin-bottom: 15px;">Scope of Work</h2>
                                                                                                                <ul style="list-style-type: disc; padding-left: 20px; color: #374151; line-height: 1.6;" contenteditable="true">
                                                                                                                    <li>Social media strategy development</li>
                                                                                                                    <li>Content calendar creation and management</li>
                                                                                                                    <li>Platform setup and optimization (Facebook, Instagram, LinkedIn, Twitter)</li>
                                                                                                                    <li>Monthly content creation (30 posts per platform)</li>
                                                                                                                    <li>Community management and engagement</li>
                                                                                                                    <li>Performance tracking and monthly reporting</li>
                                                                                                                </ul>
                                                                                                            </div>

                                                                                                            <div class="pdf-section">
                                                                                                                <h2 style="font-size: 20px; font-weight: bold; color: #1f2937; margin-bottom: 15px;">Deliverables</h2>
                                                                                                                <ul style="list-style-type: disc; padding-left: 20px; color: #374151; line-height: 1.6;" contenteditable="true">
                                                                                                                    <li>Comprehensive social media strategy document</li>
                                                                                                                    <li>3-month content calendar</li>
                                                                                                                    <li>Branded graphics and video content</li>
                                                                                                                    <li>Monthly performance reports with insights</li>
                                                                                                                    <li>Competitor analysis and hashtag research</li>
                                                                                                                </ul>
                                                                                                            </div>

                                                                                                            <div class="pdf-section">
                                                                                                                <h2 style="font-size: 20px; font-weight: bold; color: #1f2937; margin-bottom: 15px;">Timeline</h2>
                                                                                                                <div style="color: #374151; line-height: 1.6;" contenteditable="true">
                                                                                                                    <p><strong>Week 1-2:</strong> Strategy development and platform setup</p>
                                                                                                                    <p><strong>Week 3-4:</strong> Content creation and calendar implementation</p>
                                                                                                                    <p><strong>Month 2-3:</strong> Ongoing management, optimization, and reporting</p>
                                                                                                                </div>
                                                                                                            </div>

                                                                                                            <div class="pdf-section">
                                                                                                                <h2 style="font-size: 20px; font-weight: bold; color: #1f2937; margin-bottom: 15px;">Investment</h2>
                                                                                                                <table class="pdf-table">
                                                                                                                    <thead>
                                                                                                                        <tr>
                                                                                                                            <th style="padding: 10px; background-color: #f3f4f6; text-align: left; font-size: 12px; font-weight: 600; color: #6b7280; text-transform: uppercase;">Service</th>
                                                                                                                            <th style="padding: 10px; background-color: #f3f4f6; text-align: left; font-size: 12px; font-weight: 600; color: #6b7280; text-transform: uppercase;">Description</th>
                                                                                                                            <th style="padding: 10px; background-color: #f3f4f6; text-align: left; font-size: 12px; font-weight: 600; color: #6b7280; text-transform: uppercase;">Price</th>
                                                                                                                        </tr>
                                                                                                                    </thead>
                                                                                                                    <tbody>
                                                                                                                        <tr>
                                                                                                                            <td style="padding: 10px; font-weight: 500;" contenteditable="true">Monthly Management</td>
                                                                                                                            <td style="padding: 10px; font-size: 14px; color: #6b7280;" contenteditable="true">Full social media handling</td>
                                                                                                                            <td style="padding: 10px; font-weight: 500;" contenteditable="true">$2,499 / month</td>
                                                                                                                        </tr>
                                                                                                                        <tr style="background-color: #f9fafb;">
                                                                                                                            <td style="padding: 10px; font-weight: 500;">Total (3 months)</td>
                                                                                                                            <td style="padding: 10px; font-size: 14px; color: #6b7280;">Minimum commitment</td>
                                                                                                                            <td style="padding: 10px; font-weight: bold; font-size: 18px;">$7,497</td>
                                                                                                                        </tr>
                                                                                                                    </tbody>
                                                                                                                </table>
                                                                                                            </div>

                                                                                                            <div class="pdf-section">
                                                                                                                <h2 style="font-size: 20px; font-weight: bold; color: #1f2937; margin-bottom: 15px;">Terms & Conditions</h2>
                                                                                                                <div style="color: #374151; line-height: 1.6;" contenteditable="true">
                                                                                                                    <p>Payment: 50% upfront, 50% after first month.</p>
                                                                                                                    <p style="margin-top: 10px;">Valid for 30 days. Minimum 3-month engagement.</p>
                                                                                                                </div>
                                                                                                            </div>

                                                                                                            <div class="pdf-section">
                                                                                                                <h2 style="font-size: 20px; font-weight: bold; color: #1f2937; margin-bottom: 15px;">Contact Us</h2>
                                                                                                                <div style="color: #374151; line-height: 1.6;" contenteditable="true">
                                                                                                                    <p><strong>Your Digital Agency</strong> | hello@agency.com | +91 98765 43210</p>
                                                                                                                </div>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                    `,

                    website: `
                                                                                                        <div class="pdf-export-container">
                                                                                                            <div class="pdf-section">
                                                                                                                <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 30px;">
                                                                                                                    <div>
                                                                                                                        <h1 id="proposalTitle" style="font-size: 28px; font-weight: bold; color: #1f2937; margin-bottom: 10px;" contenteditable="true">Website Design & Development Proposal</h1>
                                                                                                                        <p style="color: #6b7280;" contenteditable="true">Prepared for <span id="clientName" style="font-weight: 500;">Client Name</span> at <span id="clientCompany" style="font-weight: 500;">Company Name</span></p>
                                                                                                                    </div>
                                                                                                                    <div style="text-align: right;">
                                                                                                                        <p style="color: #6b7280;">Date: <span id="proposalDate" style="font-weight: 500;">${new Date().toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' })}</span></p>
                                                                                                                        <p style="color: #6b7280;">Proposal ID: <span style="font-weight: 500;">#WEB-2025-001</span></p>
                                                                                                                    </div>
                                                                                                                </div>
                                                                                                            </div>

                                                                                                            <div class="pdf-section">
                                                                                                                <h2 style="font-size: 20px; font-weight: bold; color: #1f2937; margin-bottom: 15px;">Project Overview</h2>
                                                                                                                <div style="color: #374151;" contenteditable="true">
                                                                                                                    <p>We will design and develop a modern, fast, mobile-responsive website that represents your brand professionally and converts visitors into customers.</p>
                                                                                                                    <p style="margin-top: 15px;">Built with latest technologies, SEO-ready structure, and focused on user experience.</p>
                                                                                                                </div>
                                                                                                            </div>

                                                                                                            <div class="pdf-section">
                                                                                                                <h2 style="font-size: 20px; font-weight: bold; color: #1f2937; margin-bottom: 15px;">Scope of Work</h2>
                                                                                                                <ul style="list-style-type: disc; padding-left: 20px; color: #374151; line-height: 1.6;" contenteditable="true">
                                                                                                                    <li>Custom responsive website design (up to 10 pages)</li>
                                                                                                                    <li>WordPress / Webflow / Custom development</li>
                                                                                                                    <li>Mobile & tablet optimization</li>
                                                                                                                    <li>Contact forms, WhatsApp chat, Google Maps</li>
                                                                                                                    <li>Basic on-page SEO implementation</li>
                                                                                                                    <li>Speed optimization & security setup</li>
                                                                                                                </ul>
                                                                                                            </div>

                                                                                                            <div class="pdf-section">
                                                                                                                <h2 style="font-size: 20px; font-weight: bold; color: #1f2937; margin-bottom: 15px;">Deliverables</h2>
                                                                                                                <ul style="list-style-type: disc; padding-left: 20px; color: #374151; line-height: 1.6;" contenteditable="true">
                                                                                                                    <li>Complete website with admin panel</li>
                                                                                                                    <li>2 rounds of design revisions</li>
                                                                                                                    <li>Google Analytics & Search Console setup</li>
                                                                                                                    <li>Training session for content updates</li>
                                                                                                                    <li>30 days post-launch support</li>
                                                                                                                </ul>
                                                                                                            </div>

                                                                                                            <div class="pdf-section">
                                                                                                                <h2 style="font-size: 20px; font-weight: bold; color: #1f2937; margin-bottom: 15px;">Timeline</h2>
                                                                                                                <div style="color: #374151; line-height: 1.6;" contenteditable="true">
                                                                                                                    <p><strong>Week 1:</strong> Discovery & wireframing</p>
                                                                                                                    <p><strong>Week 2-3:</strong> Design mockups & approval</p>
                                                                                                                    <p><strong>Week 4-6:</strong> Development & testing</p>
                                                                                                                    <p><strong>Week 7:</strong> Launch & training</p>
                                                                                                                </div>
                                                                                                            </div>

                                                                                                            <div class="pdf-section">
                                                                                                                <h2 style="font-size: 20px; font-weight: bold; color: #1f2937; margin-bottom: 15px;">Investment</h2>
                                                                                                                <table class="pdf-table">
                                                                                                                    <thead>
                                                                                                                        <tr>
                                                                                                                            <th style="padding: 10px; background-color: #f3f4f6; text-align: left; font-size: 12px; font-weight: 600; color: #6b7280; text-transform: uppercase;">Package</th>
                                                                                                                            <th style="padding: 10px; background-color: #f3f4f6; text-align: left; font-size: 12px; font-weight: 600; color: #6b7280; text-transform: uppercase;">Includes</th>
                                                                                                                            <th style="padding: 10px; background-color: #f3f4f6; text-align: left; font-size: 12px; font-weight: 600; color: #6b7280; text-transform: uppercase;">Price</th>
                                                                                                                        </tr>
                                                                                                                    </thead>
                                                                                                                    <tbody>
                                                                                                                        <tr>
                                                                                                                            <td style="padding: 10px; font-weight: 500;" contenteditable="true">Professional Website</td>
                                                                                                                            <td style="padding: 10px; font-size: 14px; color: #6b7280;" contenteditable="true">Up to 10 pages, responsive, SEO-ready</td>
                                                                                                                            <td style="padding: 10px; font-weight: 500;" contenteditable="true">$18,000 - $25,000</td>
                                                                                                                        </tr>
                                                                                                                        <tr style="background-color: #f9fafb;">
                                                                                                                            <td style="padding: 10px; font-weight: 500;">Payment Terms</td>
                                                                                                                            <td style="padding: 10px; font-size: 14px; color: #6b7280;">50% advance, 30% on design, 20% on launch</td>
                                                                                                                            <td style="padding: 10px; font-weight: bold; font-size: 18px;">One-time</td>
                                                                                                                        </tr>
                                                                                                                    </tbody>
                                                                                                                </table>
                                                                                                            </div>

                                                                                                            <div class="pdf-section">
                                                                                                                <h2 style="font-size: 20px; font-weight: bold; color: #1f2937; margin-bottom: 15px;">Terms & Conditions</h2>
                                                                                                                <div style="color: #374151; line-height: 1.6;" contenteditable="true">
                                                                                                                    <p>Content and images to be provided by client.</p>
                                                                                                                    <p style="margin-top: 10px;">Additional pages: $800 each. Hosting & domain extra.</p>
                                                                                                                </div>
                                                                                                            </div>

                                                                                                            <div class="pdf-section">
                                                                                                                <h2 style="font-size: 20px; font-weight: bold; color: #1f2937; margin-bottom: 15px;">Contact Us</h2>
                                                                                                                <div style="color: #374151; line-height: 1.6;" contenteditable="true">
                                                                                                                    <p><strong>Your Digital Agency</strong> | hello@agency.com | +91 98765 43210</p>
                                                                                                                </div>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                    `,

                    ads: `
                                                                                                        <div class="pdf-export-container">
                                                                                                            <div class="pdf-section">
                                                                                                                <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 30px;">
                                                                                                                    <div>
                                                                                                                        <h1 id="proposalTitle" style="font-size: 28px; font-weight: bold; color: #1f2937; margin-bottom: 10px;" contenteditable="true">Google Ads Management Proposal</h1>
                                                                                                                        <p style="color: #6b7280;" contenteditable="true">Prepared for <span id="clientName" style="font-weight: 500;">Client Name</span> at <span id="clientCompany" style="font-weight: 500;">Company Name</span></p>
                                                                                                                    </div>
                                                                                                                    <div style="text-align: right;">
                                                                                                                        <p style="color: #6b7280;">Date: <span id="proposalDate" style="font-weight: 500;">${new Date().toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' })}</span></p>
                                                                                                                        <p style="color: #6b7280;">Proposal ID: <span style="font-weight: 500;">#GADS-2025-001</span></p>
                                                                                                                    </div>
                                                                                                                </div>
                                                                                                            </div>

                                                                                                            <div class="pdf-section">
                                                                                                                <h2 style="font-size: 20px; font-weight: bold; color: #1f2937; margin-bottom: 15px;">Campaign Overview</h2>
                                                                                                                <div style="color: #374151;" contenteditable="true">
                                                                                                                    <p>We will run high-ROI Google Ads campaigns (Search, Display, YouTube, Remarketing) to generate qualified leads and sales with full transparency and weekly optimization.</p>
                                                                                                                    <p style="margin-top: 15px;">No long-term contracts. You own all accounts.</p>
                                                                                                                </div>
                                                                                                            </div>

                                                                                                            <div class="pdf-section">
                                                                                                                <h2 style="font-size: 20px; font-weight: bold; color: #1f2937; margin-bottom: 15px;">What We Do</h2>
                                                                                                                <ul style="list-style-type: disc; padding-left: 20px; color: #374151; line-height: 1.6;" contenteditable="true">
                                                                                                                    <li>Keyword research & competitor analysis</li>
                                                                                                                    <li>High-converting ad copy & landing page suggestions</li>
                                                                                                                    <li>Full campaign setup with conversion tracking</li>
                                                                                                                    <li>Daily monitoring & bid optimization</li>
                                                                                                                    <li>Negative keyword management</li>
                                                                                                                    <li>Weekly performance reports + monthly call</li>
                                                                                                                </ul>
                                                                                                            </div>

                                                                                                            <div class="pdf-section">
                                                                                                                <h2 style="font-size: 20px; font-weight: bold; color: #1f2937; margin-bottom: 15px;">Deliverables</h2>
                                                                                                                <ul style="list-style-type: disc; padding-left: 20px; color: #374151; line-height: 1.6;" contenteditable="true">
                                                                                                                    <li>Detailed campaign strategy document</li>
                                                                                                                    <li>Google Ads account fully owned by you</li>
                                                                                                                    <li>Weekly optimization reports</li>
                                                                                                                    <li>Monthly performance review call</li>
                                                                                                                    <li>A/B testing of ads & landing pages</li>
                                                                                                                </ul>
                                                                                                            </div>

                                                                                                            <div class="pdf-section">
                                                                                                                <h2 style="font-size: 20px; font-weight: bold; color: #1f2937; margin-bottom: 15px;">Timeline</h2>
                                                                                                                <div style="color: #374151; line-height: 1.6;" contenteditable="true">
                                                                                                                    <p><strong>Week 1:</strong> Research, strategy & setup</p>
                                                                                                                    <p><strong>Week 2:</strong> Launch campaigns & initial optimization</p>
                                                                                                                    <p><strong>Ongoing:</strong> Daily management & scaling</p>
                                                                                                                </div>
                                                                                                            </div>

                                                                                                            <div class="pdf-section">
                                                                                                                <h2 style="font-size: 20px; font-weight: bold; color: #1f2937; margin-bottom: 15px;">Investment</h2>
                                                                                                                <table class="pdf-table">
                                                                                                                    <thead>
                                                                                                                        <tr>
                                                                                                                            <th style="padding: 10px; background-color: #f3f4f6; text-align: left; font-size: 12px; font-weight: 600; color: #6b7280; text-transform: uppercase;">Fee</th>
                                                                                                                            <th style="padding: 10px; background-color: #f3f4f6; text-align: left; font-size: 12px; font-weight: 600; color: #6b7280; text-transform: uppercase;">Details</th>
                                                                                                                            <th style="padding: 10px; background-color: #f3f4f6; text-align: left; font-size: 12px; font-weight: 600; color: #6b7280; text-transform: uppercase;">Price</th>
                                                                                                                        </tr>
                                                                                                                    </thead>
                                                                                                                    <tbody>
                                                                                                                        <tr>
                                                                                                                            <td style="padding: 10px; font-weight: 500;" contenteditable="true">One-time Setup</td>
                                                                                                                            <td style="padding: 10px; font-size: 14px; color: #6b7280;" contenteditable="true">Research, strategy, account setup</td>
                                                                                                                            <td style="padding: 10px; font-weight: 500;" contenteditable="true">$1,999</td>
                                                                                                                        </tr>
                                                                                                                        <tr>
                                                                                                                            <td style="padding: 10px; font-weight: 500;" contenteditable="true">Monthly Management</td>
                                                                                                                            <td style="padding: 10px; font-size: 14px; color: #6b7280;" contenteditable="true">Full optimization & reporting</td>
                                                                                                                            <td style="padding: 10px; font-weight: 500;" contenteditable="true">$999 / month</td>
                                                                                                                        </tr>
                                                                                                                        <tr style="background-color: #f9fafb;">
                                                                                                                            <td style="padding: 10px; font-weight: 500;">Ad Spend</td>
                                                                                                                            <td style="padding: 10px; font-size: 14px; color: #6b7280;">Direct to Google</td>
                                                                                                                            <td style="padding: 10px; font-weight: bold; font-size: 18px;">As per budget</td>
                                                                                                                        </tr>
                                                                                                                    </tbody>
                                                                                                                </table>
                                                                                                            </div>

                                                                                                            <div class="pdf-section">
                                                                                                                <h2 style="font-size: 20px; font-weight: bold; color: #1f2937; margin-bottom: 15px;">Terms & Conditions</h2>
                                                                                                                <div style="color: #374151; line-height: 1.6;" contenteditable="true">
                                                                                                                    <p>No lock-in. Cancel anytime with 15 days notice.</p>
                                                                                                                    <p style="margin-top: 10px;">You own all accounts and data.</p>
                                                                                                                </div>
                                                                                                            </div>

                                                                                                            <div class="pdf-section">
                                                                                                                <h2 style="font-size: 20px; font-weight: bold; color: #1f2937; margin-bottom: 15px;">Contact Us</h2>
                                                                                                                <div style="color: #374151; line-height: 1.6;" contenteditable="true">
                                                                                                                    <p><strong>Your Digital Agency</strong> | hello@agency.com | +91 98765 43210</p>
                                                                                                                </div>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                    `,

                    seo: `
                                                                                                        <div class="pdf-export-container">
                                                                                                            <div class="pdf-section">
                                                                                                                <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 30px;">
                                                                                                                    <div>
                                                                                                                        <h1 id="proposalTitle" style="font-size: 28px; font-weight: bold; color: #1f2937; margin-bottom: 10px;" contenteditable="true">SEO Proposal</h1>
                                                                                                                        <p style="color: #6b7280;" contenteditable="true">Prepared for <span id="clientName" style="font-weight: 500;">Client Name</span> at <span id="clientCompany" style="font-weight: 500;">Company Name</span></p>
                                                                                                                    </div>
                                                                                                                    <div style="text-align: right;">
                                                                                                                        <p style="color: #6b7280;">Date: <span id="proposalDate" style="font-weight: 500;">${new Date().toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' })}</span></p>
                                                                                                                        <p style="color: #6b7280;">Proposal ID: <span style="font-weight: 500;">#SEO-2025-001</span></p>
                                                                                                                    </div>
                                                                                                                </div>
                                                                                                            </div>

                                                                                                            <div class="pdf-section">
                                                                                                                <h2 style="font-size: 20px; font-weight: bold; color: #1f2937; margin-bottom: 15px;">SEO Strategy Overview</h2>
                                                                                                                <div style="color: #374151;" contenteditable="true">
                                                                                                                    <p>This proposal outlines our comprehensive search engine optimization strategy to improve your organic rankings, drive qualified traffic, and increase conversions.</p>
                                                                                                                    <p style="margin-top: 15px;">Our data-driven approach focuses on technical SEO, content optimization, and authoritative link building.</p>
                                                                                                                </div>
                                                                                                            </div>

                                                                                                            <div class="pdf-section">
                                                                                                                <h2 style="font-size: 20px; font-weight: bold; color: #1f2937; margin-bottom: 15px;">Scope of Work</h2>
                                                                                                                <ul style="list-style-type: disc; padding-left: 20px; color: #374151; line-height: 1.6;" contenteditable="true">
                                                                                                                    <li>Comprehensive website SEO audit</li>
                                                                                                                    <li>Technical SEO optimization</li>
                                                                                                                    <li>Keyword research and strategy</li>
                                                                                                                    <li>On-page optimization (meta tags, content)</li>
                                                                                                                    <li>Content strategy and creation</li>
                                                                                                                    <li>Link building and outreach</li>
                                                                                                                    <li>Monthly performance tracking and reporting</li>
                                                                                                                </ul>
                                                                                                            </div>

                                                                                                            <div class="pdf-section">
                                                                                                                <h2 style="font-size: 20px; font-weight: bold; color: #1f2937; margin-bottom: 15px;">Deliverables</h2>
                                                                                                                <ul style="list-style-type: disc; padding-left: 20px; color: #374151; line-height: 1.6;" contenteditable="true">
                                                                                                                    <li>Detailed SEO audit report</li>
                                                                                                                    <li>Keyword strategy document</li>
                                                                                                                    <li>Monthly optimization reports</li>
                                                                                                                    <li>Competitor analysis</li>
                                                                                                                    <li>Content calendar</li>
                                                                                                                    <li>Ranking tracking dashboard</li>
                                                                                                                </ul>
                                                                                                            </div>

                                                                                                            <div class="pdf-section">
                                                                                                                <h2 style="font-size: 20px; font-weight: bold; color: #1f2937; margin-bottom: 15px;">Timeline</h2>
                                                                                                                <div style="color: #374151; line-height: 1.6;" contenteditable="true">
                                                                                                                    <p><strong>Month 1:</strong> Audit, technical fixes, keyword strategy</p>
                                                                                                                    <p><strong>Month 2-3:</strong> On-page optimization, content creation</p>
                                                                                                                    <p><strong>Month 4-6:</strong> Link building, ongoing optimization</p>
                                                                                                                </div>
                                                                                                            </div>

                                                                                                            <div class="pdf-section">
                                                                                                                <h2 style="font-size: 20px; font-weight: bold; color: #1f2937; margin-bottom: 15px;">Investment</h2>
                                                                                                                <table class="pdf-table">
                                                                                                                    <thead>
                                                                                                                        <tr>
                                                                                                                            <th style="padding: 10px; background-color: #f3f4f6; text-align: left; font-size: 12px; font-weight: 600; color: #6b7280; text-transform: uppercase;">Service</th>
                                                                                                                            <th style="padding: 10px; background-color: #f3f4f6; text-align: left; font-size: 12px; font-weight: 600; color: #6b7280; text-transform: uppercase;">Description</th>
                                                                                                                            <th style="padding: 10px; background-color: #f3f4f6; text-align: left; font-size: 12px; font-weight: 600; color: #6b7280; text-transform: uppercase;">Price</th>
                                                                                                                        </tr>
                                                                                                                    </thead>
                                                                                                                    <tbody>
                                                                                                                        <tr>
                                                                                                                            <td style="padding: 10px; font-weight: 500;" contenteditable="true">Monthly SEO Management</td>
                                                                                                                            <td style="padding: 10px; font-size: 14px; color: #6b7280;" contenteditable="true">Comprehensive SEO services</td>
                                                                                                                            <td style="padding: 10px; font-weight: 500;" contenteditable="true">$1,500 / month</td>
                                                                                                                        </tr>
                                                                                                                        <tr style="background-color: #f9fafb;">
                                                                                                                            <td style="padding: 10px; font-weight: 500;">Minimum Commitment</td>
                                                                                                                            <td style="padding: 10px; font-size: 14px; color: #6b7280;">6 months for best results</td>
                                                                                                                            <td style="padding: 10px; font-weight: bold; font-size: 18px;">$9,000</td>
                                                                                                                        </tr>
                                                                                                                    </tbody>
                                                                                                                </table>
                                                                                                            </div>

                                                                                                            <div class="pdf-section">
                                                                                                                <h2 style="font-size: 20px; font-weight: bold; color: #1f2937; margin-bottom: 15px;">Terms & Conditions</h2>
                                                                                                                <div style="color: #374151; line-height: 1.6;" contenteditable="true">
                                                                                                                    <p>Minimum 6-month commitment required for optimal results.</p>
                                                                                                                    <p style="margin-top: 10px;">Results may vary based on industry competition and website history.</p>
                                                                                                                </div>
                                                                                                            </div>

                                                                                                            <div class="pdf-section">
                                                                                                                <h2 style="font-size: 20px; font-weight: bold; color: #1f2937; margin-bottom: 15px;">Contact Us</h2>
                                                                                                                <div style="color: #374151; line-height: 1.6;" contenteditable="true">
                                                                                                                    <p><strong>Your Digital Agency</strong> | hello@agency.com | +91 98765 43210</p>
                                                                                                                </div>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                    `
                };

                // Default templates (cannot be deleted)
                const defaultTemplates = [
                    { id: 1, name: "Social Media Marketing Proposal", description: "Complete social media strategy, content calendar, and performance tracking", key: "social", icon: "hashtag", color: "indigo", isDefault: true },
                    { id: 2, name: "Website Development Proposal", description: "Custom website design, development, and ongoing maintenance", key: "website", icon: "globe", color: "blue", isDefault: true },
                    { id: 3, name: "Google Ads Proposal", description: "PPC campaign setup, management, and optimization for maximum ROI", key: "ads", icon: "ad", color: "green", isDefault: true },
                    { id: 4, name: "SEO Proposal", description: "Search engine optimization strategy to improve organic rankings", key: "seo", icon: "search", color: "purple", isDefault: true }
                ];

                let customTemplates = [];
                let nextId = 5;
                const iconMap = { hashtag: "fas fa-hashtag", globe: "fas fa-globe", ad: "fas fa-ad", search: "fas fa-search", palette: "fas fa-palette", briefcase: "fas fa-briefcase", "chart-line": "fas fa-chart-line" };

                const proposalCardsGrid = document.getElementById('proposalCardsGrid');
                const fullEditorView = document.getElementById('fullEditorView');
                const proposalContent = document.getElementById('proposalContent');
                let currentTemplate = null;
                let selectedFile = null;
                let currentProposal = null;
                let savedProposals = [];

                // localStorage functions
                function saveTemplates() {
                    const customOnly = customTemplates.filter(t => !t.isDefault);
                    localStorage.setItem('proposalCustomTemplates', JSON.stringify(customOnly));
                    localStorage.setItem('proposalNextId', nextId.toString());
                }

                function loadTemplates() {
                    try {
                        const saved = localStorage.getItem('proposalCustomTemplates');
                        const savedNextId = localStorage.getItem('proposalNextId');

                        if (saved) {
                            const customOnly = JSON.parse(saved);
                            customTemplates = [...defaultTemplates, ...customOnly];
                        } else {
                            customTemplates = [...defaultTemplates];
                        }

                        if (savedNextId) {
                            nextId = parseInt(savedNextId);
                        }
                    } catch (e) {
                        console.error('Error loading templates:', e);
                        customTemplates = [...defaultTemplates];
                    }
                }

                function deleteTemplate(templateId) {
                    const template = customTemplates.find(t => t.id === templateId);

                    // Prevent deletion of default templates
                    if (template && template.isDefault) {
                        alert('Default templates cannot be deleted!');
                        return;
                    }

                    if (!confirm(`Are you sure you want to delete "${template.name}"?`)) {
                        return;
                    }

                    customTemplates = customTemplates.filter(t => t.id !== templateId);
                    saveTemplates();
                    renderTemplates();
                }

                function renderTemplates() {
                    const list = document.getElementById('templateList');
                    list.innerHTML = '';

                    customTemplates.forEach(t => {
                        const templateItem = document.createElement('div');
                        templateItem.className = 'template-item';

                        // Add delete button for custom templates only
                        const deleteBtn = t.isDefault ? '' : `
                                                                                                <button class="delete-template text-xs text-red-600 hover:text-red-800 font-medium">
                                                                                                    <i class="fas fa-trash mr-1"></i>
                                                                                                </button>
                                                                                            `;

                        templateItem.innerHTML = `
                                                                                                            <h3 class="text-lg font-bold text-gray-800 mb-2">${t.name} </h3>
                                                                                                            <p class="text-sm text-gray-600 mb-4">${t.description} </p>

                                                                                                            <div class="flex space-x-2">
                                                                                                                <button class="preview-template text-xs text-indigo-600 hover:text-indigo-800 font-medium">
                                                                                                                    <i class="fas fa-eye mr-1"></i> Preview
                                                                                                                </button>
                                                                                                                <button class="use-template text-xs bg-indigo-600 text-white py-1 px-3 rounded font-medium hover:bg-indigo-700">
                                                                                                                    <i class="fas fa-plus mr-1"></i> Use Template
                                                                                                                </button>
                                                                                                                ${deleteBtn}
                                                                                                            </div>
                                                                                                        `;

                        templateItem.querySelector('.use-template').onclick = () => {
                            currentTemplate = t;
                            showProposalCards(t);
                        };

                        templateItem.querySelector('.preview-template').onclick = () => {
                            alert(`Preview of "${t.name}" template would appear here`);
                        };

                        // Add delete event listener if not a default template
                        if (!t.isDefault) {
                            templateItem.querySelector('.delete-template').onclick = () => {
                                deleteTemplate(t.id);
                            };
                        }

                        list.appendChild(templateItem);
                    });

                    if (!currentTemplate && customTemplates.length > 0) {
                        currentTemplate = customTemplates[0];
                        showProposalCards(currentTemplate);
                    }
                }

                function showProposalCards(template) {
                    const existing = proposalCardsGrid.querySelectorAll('.proposal-card:not(.blank-card)');
                    existing.forEach(c => c.remove());

                    const clients = [
                        { name: "Rahul Sharma", company: "Trendy Fashion" },
                        { name: "Priya Singh", company: "TechVision Solutions" },
                        { name: "Amit Patel", company: "HealthFirst Clinic" }
                    ];

                    clients.forEach(client => {
                        const card = document.createElement('div');
                        card.className = "proposal-card bg-white rounded-2xl shadow-lg overflow-hidden border-2 border-gray-200";
                        card.innerHTML = `
                                                                                                            <div class="p-8">
                                                                                                                <h3 class="text-2xl font-bold text-gray-800 mb-4">${template.name}</h3>
                                                                                                                <p class="text-sm text-gray-500 mb-1">Client</p>
                                                                                                                <p class="text-lg font-semibold text-gray-800 mb-3">${client.name}</p>
                                                                                                                <p class="text-sm text-gray-500 mb-1">Company</p>
                                                                                                                <p class="font-medium text-gray-800 mb-6">${client.company}</p>
                                                                                                                <button class="open-editor w-full bg-indigo-600 text-white py-3 rounded-xl font-semibold hover:bg-indigo-700">
                                                                                                                    Open & Edit
                                                                                                                </button>
                                                                                                            </div>
                                                                                                        `;
                        card.querySelector('.open-editor').onclick = () => openFullEditor(template, client);
                        proposalCardsGrid.appendChild(card);
                    });
                }

                function openFullEditor(template, client) {
                    document.getElementById('proposalCardsView').classList.add('hidden');
                    fullEditorView.classList.remove('hidden');

                    // Show formatting toolbar
                    showFormattingToolbar();

                    // Check if we have a saved proposal for this client and template
                    const savedProposal = savedProposals.find(p =>
                        p.client.name === client.name &&
                        p.client.company === client.company &&
                        p.template.key === template.key
                    );

                    if (savedProposal) {
                        // Wrap saved content in editable blocks if not already wrapped
                        // Check if content already has editable-block wrappers
                        if (!savedProposal.content.includes('editable-block')) {
                            const wrappedContent = wrapContentInEditableBlocks(savedProposal.content);
                            proposalContent.innerHTML = wrappedContent;
                            savedProposal.content = wrappedContent; // Update saved version
                        } else {
                            proposalContent.innerHTML = savedProposal.content;
                        }
                        currentProposal = savedProposal;
                    } else {
                        // Wrap content in editable blocks before setting
                        const wrappedContent = wrapContentInEditableBlocks(proposalTemplates[template.key]);
                        proposalContent.innerHTML = wrappedContent;
                        currentProposal = {
                            template: template,
                            client: client,
                            content: wrappedContent,
                            lastSaved: new Date()
                        };
                    }

                    document.querySelectorAll('#clientName').forEach(el => el.textContent = client.name);
                    document.querySelectorAll('#clientCompany').forEach(el => el.textContent = client.company);

                    document.getElementById('quickEditPanel').classList.remove('translate-x-full');
                    document.getElementById('editClientName').value = client.name;
                    document.getElementById('editClientCompany').value = client.company;
                }

                // File upload functionality
                const fileInput = document.getElementById('fileInput');
                const dropZone = document.getElementById('dropZone');
                const processFileBtn = document.getElementById('processFileBtn');
                const fileDisplay = document.getElementById('fileDisplay');
                const fileNameDisplay = document.getElementById('fileNameDisplay');
                const fileSizeDisplay = document.getElementById('fileSizeDisplay');

                function showFileName(file) {
                    selectedFile = file;
                    fileDisplay.classList.remove('hidden');
                    fileNameDisplay.textContent = file.name;
                    const size = (file.size / 1024 / 1024).toFixed(2) + " MB";
                    fileSizeDisplay.textContent = size;
                    processFileBtn.classList.remove('hidden');
                }

                // Select a predefined proposal template
                function selectProposal(template, client) {
                    document.getElementById('proposalCardsView').classList.add('hidden');
                    fullEditorView.classList.remove('hidden');

                    // Show formatting toolbar
                    showFormattingToolbar();

                    // Load template content
                    if (proposalTemplates[template.key]) {
                        // Wrap content in editable blocks before setting
                        const wrappedContent = wrapContentInEditableBlocks(proposalTemplates[template.key]);
                        proposalContent.innerHTML = wrappedContent;

                        // Update client information
                        document.querySelectorAll('#clientName').forEach(el => el.textContent = client.name);
                        document.querySelectorAll('#clientCompany').forEach(el => el.textContent = client.company);

                        // Set current proposal
                        currentProposal = {
                            template: template,
                            client: client,
                            content: wrappedContent,
                            lastSaved: new Date()
                        };

                        // Update quick edit panel
                        document.getElementById('quickEditPanel').classList.remove('translate-x-full');
                        document.getElementById('editClientName').value = client.name;
                        document.getElementById('editClientCompany').value = client.company;
                    }
                }

                function clearFileSelection() {
                    selectedFile = null;
                    fileInput.value = "";
                    fileDisplay.classList.add('hidden');
                    processFileBtn.classList.add('hidden');
                }

                dropZone.addEventListener('click', () => fileInput.click());
                dropZone.addEventListener('dragover', e => { e.preventDefault(); dropZone.classList.add('border-indigo-600', 'bg-indigo-50'); });
                dropZone.addEventListener('dragleave', () => dropZone.classList.remove('border-indigo-600', 'bg-indigo-50'));
                dropZone.addEventListener('drop', e => {
                    e.preventDefault(); dropZone.classList.remove('border-indigo-600', 'bg-indigo-50');
                    if (e.dataTransfer.files[0]) {
                        fileInput.files = e.dataTransfer.files;
                        showFileName(e.dataTransfer.files[0]);
                    }
                });
                fileInput.addEventListener('change', () => {
                    if (fileInput.files[0]) showFileName(fileInput.files[0]);
                });

                processFileBtn.onclick = () => {
                    if (selectedFile) extractAndCreateProposal(selectedFile);
                };

                // ============================================
                // FORMATTING TOOLBAR FUNCTIONS
                // ============================================

                // Show/hide formatting toolbar
                function showFormattingToolbar() {
                    document.getElementById('formattingToolbar').style.display = 'block';
                }

                function hideFormattingToolbar() {
                    document.getElementById('formattingToolbar').style.display = 'none';
                }

                // Text formatting using execCommand
                function formatText(command) {
                    document.execCommand(command, false, null);
                }

                // Format block (heading, paragraph)
                function formatBlock(tag) {
                    if (!tag) return;
                    document.execCommand('formatBlock', false, `<${tag}>`);

                    // Apply inline styles based on tag
                    setTimeout(() => {
                        const selection = window.getSelection();
                        if (selection.rangeCount > 0) {
                            let element = selection.anchorNode;
                            if (element.nodeType === 3) element = element.parentElement;

                            while (element && element.tagName && element.tagName.toLowerCase() !== tag) {
                                element = element.parentElement;
                                if (!element || element.id === 'proposalContent') break;
                            }

                            if (element && element.tagName && element.tagName.toLowerCase() === tag) {
                                applyInlineStyleToElement(element, tag);
                            }
                        }
                    }, 50);
                }

                // Apply inline styles to match template pattern
                function applyInlineStyleToElement(element, tag) {
                    const styles = {
                        'h1': 'font-size: 28px; font-weight: bold; color: #1f2937; margin: 20px 0 10px 0;',
                        'h2': 'font-size: 20px; font-weight: bold; color: #1f2937; margin: 18px 0 10px 0;',
                        'h3': 'font-size: 18px; font-weight: 600; color: #374151; margin: 16px 0 8px 0;',
                        'p': 'color: #374151; margin: 10px 0; line-height: 1.6;'
                    };

                    if (styles[tag]) {
                        element.setAttribute('style', styles[tag]);
                        element.setAttribute('contenteditable', 'true');
                    }
                }

                // Font size
                function formatFontSize(size) {
                    if (!size) return;
                    document.execCommand('fontSize', false, size);
                }

                // Text color
                function formatTextColor(color) {
                    document.execCommand('foreColor', false, color);
                }

                // Background color (highlight)
                function formatBackgroundColor(color) {
                    document.execCommand('backColor', false, color);
                }

                // ============================================
                // BLOCK DELETION FUNCTIONS
                // ============================================

                let blockIdCounter = 0;

                function deleteBlock(blockId) {
                    if (confirm('Are you sure you want to delete this block? This action cannot be undone.')) {
                        const block = document.querySelector(`[data-block-id="${blockId}"]`);
                        if (block) {
                            block.remove();
                            updateProposalContent();
                        }
                    }
                }

                function updateProposalContent() {
                    const proposalContent = document.getElementById('proposalContent');
                    if (proposalContent && currentProposal) {
                        currentProposal.content = proposalContent.innerHTML;
                        currentProposal.lastSaved = new Date();
                    }
                }

                // Wrap content sections in editable blocks
                function wrapContentInEditableBlocks(html) {
                    // Wrap each pdf-section in an editable block
                    const tempDiv = document.createElement('div');
                    tempDiv.innerHTML = html;

                    const sections = tempDiv.querySelectorAll('.pdf-section');
                    sections.forEach((section) => {
                        const wrapper = document.createElement('div');
                        wrapper.className = 'editable-block';
                        wrapper.setAttribute('data-block-id', `block_${blockIdCounter++}`);

                        const deleteBtn = document.createElement('button');
                        deleteBtn.className = 'delete-block-btn';
                        deleteBtn.innerHTML = '<span>×</span> Delete';
                        deleteBtn.setAttribute('onclick', `deleteBlock('block_${blockIdCounter - 1}')`);

                        // Clone the section
                        const sectionClone = section.cloneNode(true);

                        // Replace original section with wrapped version
                        section.parentNode.replaceChild(wrapper, section);
                        wrapper.appendChild(deleteBtn);
                        wrapper.appendChild(sectionClone);
                    });

                    return tempDiv.innerHTML;
                }

                // Helper Functions for Document Extraction

                // Group PDF text items by Y-coordinate to detect lines
                function groupItemsByLine(items) {
                    const lines = [];
                    let currentLine = [];
                    let lastY = null;

                    items.forEach(item => {
                        if (!item.transform || typeof item.transform[5] === 'undefined') return;

                        const y = Math.round(item.transform[5]);

                        if (lastY === null || Math.abs(y - lastY) < 5) {
                            currentLine.push(item);
                        } else {
                            if (currentLine.length > 0) lines.push([...currentLine]);
                            currentLine = [item];
                        }
                        lastY = y;
                    });

                    if (currentLine.length > 0) lines.push(currentLine);
                    return lines;
                }

                // Get average font height from line items
                function getAverageHeight(line) {
                    const heights = line.filter(item => item.height && item.height > 0).map(item => item.height);
                    return heights.length > 0 ? heights.reduce((sum, h) => sum + h, 0) / heights.length : 12;
                }

                // Check if text appears to be bold based on font name
                function isFontBold(line) {
                    return line.some(item => {
                        const fontName = item.fontName || '';
                        return fontName.includes('Bold') || fontName.includes('Heavy') || fontName.includes('Black');
                    });
                }

                // Detect if items form a table based on X-positions
                function detectTables(items) {
                    // Simple table detection: look for consistent X-positions across multiple lines
                    const tables = [];
                    // This is a simplified version - full implementation would be more complex
                    return tables;
                }

                // Convert detected table to HTML with inline styles
                function convertTableToHTML(tableData) {
                    let html = '<table style="border-collapse: collapse; width: 100%; margin: 15px 0; border: 1px solid #d1d5db;">\n';

                    tableData.forEach((row, rowIndex) => {
                        const isHeader = rowIndex === 0;
                        html += '  <tr>\n';

                        row.forEach(cell => {
                            const tag = isHeader ? 'th' : 'td';
                            const style = isHeader
                                ? 'padding: 10px; background-color: #f3f4f6; text-align: left; font-size: 12px; font-weight: 600; color: #6b7280; border: 1px solid #d1d5db;'
                                : 'padding: 10px; border: 1px solid #d1d5db; color: #374151;';
                            const editable = isHeader ? '' : ' contenteditable="true"';

                            html += `    <${tag} style="${style}"${editable}>${cell}</${tag}>\n`;
                        });

                        html += '  </tr>\n';
                    });

                    html += '</table>\n';
                    return html;
                }

                // Add inline styles to HTML elements
                function addInlineStylesToHTML(html) {
                    // Parse and add inline styles to match template pattern
                    let styled = html;

                    // Style headings
                    styled = styled.replace(/<h1>/gi, '<h1 style="font-size: 28px; font-weight: bold; color: #1f2937; margin: 20px 0 10px 0;" contenteditable="true">');
                    styled = styled.replace(/<h2>/gi, '<h2 style="font-size: 20px; font-weight: bold; color: #1f2937; margin: 18px 0 10px 0;" contenteditable="true">');
                    styled = styled.replace(/<h3>/gi, '<h3 style="font-size: 18px; font-weight: 600; color: #374151; margin: 16px 0 8px 0;" contenteditable="true">');
                    styled = styled.replace(/<h4>/gi, '<h4 style="font-size: 16px; font-weight: 600; color: #4b5563; margin: 14px 0 8px 0;" contenteditable="true">');

                    // Style paragraphs
                    styled = styled.replace(/<p>/gi, '<p style="color: #374151; margin: 10px 0; line-height: 1.6;" contenteditable="true">');

                    // Style lists
                    styled = styled.replace(/<ul>/gi, '<ul style="list-style-type: disc; padding-left: 20px; color: #374151; line-height: 1.6; margin: 12px 0;">');
                    styled = styled.replace(/<ol>/gi, '<ol style="list-style-type: decimal; padding-left: 20px; color: #374151; line-height: 1.6; margin: 12px 0;">');
                    styled = styled.replace(/<li>/gi, '<li style="margin: 6px 0;" contenteditable="true">');

                    // Style tables
                    styled = styled.replace(/<table>/gi, '<table style="border-collapse: collapse; width: 100%; margin: 15px 0; border: 1px solid #d1d5db;">');
                    styled = styled.replace(/<th>/gi, '<th style="padding: 10px; background-color: #f3f4f6; text-align: left; font-size: 12px; font-weight: 600; color: #6b7280; border: 1px solid #d1d5db;">');
                    styled = styled.replace(/<td>/gi, '<td style="padding: 10px; border: 1px solid #d1d5db; color: #374151;" contenteditable="true">');

                    // Style strong/em
                    styled = styled.replace(/<strong>/gi, '<strong style="font-weight: bold; color: #1f2937;">');
                    styled = styled.replace(/<em>/gi, '<em style="font-style: italic;">');

                    return styled;
                }

                // Enhanced PDF extraction with formatting
                async function extractPDFWithFormatting(arrayBuffer) {
                    const pdf = await pdfjsLib.getDocument({ data: arrayBuffer }).promise;
                    let htmlContent = '';

                    for (let pageNum = 1; pageNum <= pdf.numPages; pageNum++) {
                        const page = await pdf.getPage(pageNum);
                        const textContent = await page.getTextContent();

                        // Group items into lines
                        const lines = groupItemsByLine(textContent.items);

                        // Convert lines to HTML with formatting
                        lines.forEach(line => {
                            const text = line.map(item => item.str || '').join(' ').trim();
                            if (!text) return;

                            const avgHeight = getAverageHeight(line);
                            const isBold = isFontBold(line);

                            // Detect heading levels based on font size
                            if (avgHeight > 18) {
                                htmlContent += `<h1 style="font-size: 28px; font-weight: bold; color: #1f2937; margin: 20px 0 10px 0;" contenteditable="true">${text}</h1>\n`;
                            } else if (avgHeight > 15) {
                                htmlContent += `<h2 style="font-size: 20px; font-weight: bold; color: #1f2937; margin: 18px 0 10px 0;" contenteditable="true">${text}</h2>\n`;
                            } else if (avgHeight > 13) {
                                htmlContent += `<h3 style="font-size: 18px; font-weight: 600; color: #374151; margin: 16px 0 8px 0;" contenteditable="true">${text}</h3>\n`;
                            } else if (isBold) {
                                htmlContent += `<p style="font-weight: bold; color: #1f2937; margin: 10px 0; line-height: 1.6;" contenteditable="true">${text}</p>\n`;
                            } else {
                                htmlContent += `<p style="color: #374151; margin: 10px 0; line-height: 1.6;" contenteditable="true">${text}</p>\n`;
                            }
                        });

                        // Add page separator
                        if (pageNum < pdf.numPages) {
                            htmlContent += '<hr style="margin: 30px 0; border: none; border-top: 1px solid #e5e7eb;">\n';
                        }
                    }

                    return htmlContent;
                }

                // Enhanced DOCX extraction with inline styles
                async function extractDOCXWithInlineStyles(arrayBuffer) {
                    const options = {
                        styleMap: [
                            "p[style-name='Heading 1'] => h1:fresh",
                            "p[style-name='Heading 2'] => h2:fresh",
                            "p[style-name='Heading 3'] => h3:fresh",
                            "p[style-name='Heading 4'] => h4:fresh",
                            "p[style-name='Normal'] => p:fresh",
                            "r[style-name='Strong'] => strong",
                            "r[style-name='Emphasis'] => em"
                        ].join("\n"),
                        convertImage: mammoth.images.imgElement(function (image) {
                            return image.read("base64").then(function (imageBuffer) {
                                return {
                                    src: "data:" + image.contentType + ";base64," + imageBuffer,
                                    style: "max-width: 100%; height: auto; margin: 20px 0;"
                                };
                            });
                        })
                    };

                    const result = await mammoth.convertToHtml({ arrayBuffer }, options);

                    // Add inline styles to the generated HTML
                    let styledHTML = addInlineStylesToHTML(result.value);

                    // Log any warnings
                    if (result.messages.length > 0) {
                        console.log("DOCX conversion messages:", result.messages);
                    }

                    return styledHTML;
                }

                // Enhanced Excel extraction with styling
                async function extractExcelWithStyling(arrayBuffer) {
                    const workbook = XLSX.read(arrayBuffer, { type: "array" });
                    const worksheet = workbook.Sheets[workbook.SheetNames[0]];

                    if (!worksheet['!ref']) return '<p style="color: #374151;">Empty spreadsheet</p>';

                    const range = XLSX.utils.decode_range(worksheet['!ref']);
                    let tableHTML = '<table style="border-collapse: collapse; width: 100%; margin: 15px 0; border: 1px solid #d1d5db;">\n';

                    for (let row = range.s.r; row <= range.e.r; row++) {
                        const isHeader = row === range.s.r;
                        tableHTML += '  <tr' + (row % 2 === 1 && !isHeader ? ' style="background-color: #f9fafb;"' : '') + '>\n';

                        for (let col = range.s.c; col <= range.e.c; col++) {
                            const cellAddress = XLSX.utils.encode_cell({ r: row, c: col });
                            const cell = worksheet[cellAddress];
                            const cellValue = cell ? (cell.v || '') : '';

                            const tag = isHeader ? 'th' : 'td';
                            const style = isHeader
                                ? 'padding: 10px; background-color: #f3f4f6; text-align: left; font-size: 12px; font-weight: 600; color: #6b7280; border: 1px solid #d1d5db;'
                                : 'padding: 10px; border: 1px solid #d1d5db; color: #374151;';
                            const editable = isHeader ? '' : ' contenteditable="true"';

                            tableHTML += `    <${tag} style="${style}"${editable}>${cellValue}</${tag}>\n`;
                        }

                        tableHTML += '  </tr>\n';
                    }

                    tableHTML += '</table>\n';
                    return tableHTML;
                }

                // Main extraction function
                async function extractAndCreateProposal(file) {
                    let extractedHTML = "<p>No content found.</p>";

                    try {
                        if (file.type === "application/pdf") {
                            const arrayBuffer = await file.arrayBuffer();
                            extractedHTML = await extractPDFWithFormatting(arrayBuffer);

                        } else if (file.name.endsWith('.docx') || file.name.endsWith('.doc')) {
                            const arrayBuffer = await file.arrayBuffer();
                            extractedHTML = await extractDOCXWithInlineStyles(arrayBuffer);

                        } else if (file.name.match(/\.(xlsx|xls)$/)) {
                            const arrayBuffer = await file.arrayBuffer();
                            extractedHTML = await extractExcelWithStyling(arrayBuffer);
                        }
                    } catch (e) {
                        console.error("Document extraction error:", e);
                        extractedHTML = "<p style='color: #dc2626;'>Error processing file: " + (e.message || "Unknown error") + "</p>";
                    }

                    document.getElementById('proposalCardsView').classList.add('hidden');
                    fullEditorView.classList.remove('hidden');
                    document.getElementById('uploadModal').classList.add('hidden');

                    // Show formatting toolbar
                    showFormattingToolbar();

                    const today = new Date().toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' });

                    // Wrap extracted content in editable blocks
                    const wrappedContent = wrapContentInEditableBlocks(extractedHTML);
                    proposalContent.innerHTML = `
                                                                                                        <div class="pdf-export-container">
                                                                                                            <div style="text-align: center; padding: 40px 0;">
                                                                                                                <h1 contenteditable="true" style="font-size: 36px; font-weight: bold; color: #1f2937; margin-bottom: 20px;">Custom Proposal - ${file.name.split('.').slice(0, -1).join('.')}</h1>
                                                                                                                <p style="font-size: 20px; color: #374151; margin-bottom: 15px;">Prepared for <span id="clientName" class="editable-client-name" contenteditable="true" style="font-weight: bold; color: #6366f1;">Client Name</span></p>
                                                                                                                <p style="font-size: 18px; color: #6b7280;">Date: <span style="font-weight: bold;">${today}</span></p>
                                                                                                                <div contenteditable="true" style="margin-top: 40px; text-align: left;">
                                                                                                                    ${wrappedContent}
                                                                                                                </div>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                    `;

                    currentProposal = {
                        template: { name: "Custom Proposal", key: "custom" },
                        client: { name: "Client Name", company: "Company Name" },
                        content: proposalContent.innerHTML,
                        lastSaved: new Date()
                    };

                    document.getElementById('quickEditPanel').classList.remove('translate-x-full');
                    document.getElementById('editClientName').value = "";
                    document.getElementById('editClientCompany').value = "";
                }

                document.getElementById('templatePreset').onchange = function () {
                    document.getElementById('customFields').classList.toggle('hidden', this.value !== 'others');
                };

                document.getElementById('addTemplateBtn').onclick = () => {
                    document.getElementById('addTemplateModal').classList.remove('hidden');
                    document.getElementById('templatePreset').value = '';
                    document.getElementById('customFields').classList.add('hidden');
                };

                document.getElementById('closeModal').onclick = () => document.getElementById('addTemplateModal').classList.add('hidden');

                document.getElementById('saveTemplate').onclick = () => {
                    const preset = document.getElementById('templatePreset').value;
                    if (!preset) return alert("Please select a preset");

                    let name, key = preset, icon = "hashtag", color = "indigo", description = "";

                    if (preset === 'others') {
                        name = document.getElementById('customName').value.trim();
                        if (!name) return alert("Template name required!");
                        if (customTemplates.some(t => t.name.toLowerCase() === name.toLowerCase())) return alert("Template already exists!");
                        description = document.getElementById('customDesc').value.trim();
                        icon = document.getElementById('customIcon').value;
                        color = document.getElementById('customColor').value;
                        key = "custom_" + Date.now();
                    } else {
                        const presets = {
                            social: { name: "Social Media Marketing Proposal", description: "Complete social media strategy, content calendar, and performance tracking", icon: "hashtag", color: "indigo" },
                            website: { name: "Website Development Proposal", description: "Custom website design, development, and ongoing maintenance", icon: "globe", color: "blue" },
                            ads: { name: "Google Ads Proposal", description: "PPC campaign setup, management, and optimization for maximum ROI", icon: "ad", color: "green" },
                            seo: { name: "SEO Proposal", description: "Search engine optimization strategy to improve organic rankings", icon: "search", color: "purple" }
                        };
                        const p = presets[preset];
                        if (customTemplates.some(t => t.name === p.name)) return alert("This template already exists!");
                        name = p.name;
                        description = p.description;
                        icon = p.icon;
                        color = p.color;
                    }

                    customTemplates.push({ id: nextId++, name, description, key, icon, color, isDefault: false });
                    saveTemplates();
                    renderTemplates();
                    document.getElementById('addTemplateModal').classList.add('hidden');
                };

                document.getElementById('openUploadModal').onclick = () => document.getElementById('uploadModal').classList.remove('hidden');
                document.getElementById('cancelUpload').onclick = () => {
                    document.getElementById('uploadModal').classList.add('hidden');
                    clearFileSelection();
                };
                document.getElementById('backToCards').onclick = () => {
                    fullEditorView.classList.add('hidden');
                    document.getElementById('proposalCardsView').classList.remove('hidden');
                };
                document.getElementById('closeQuickEdit').onclick = () => document.getElementById('quickEditPanel').classList.add('translate-x-full');

                document.getElementById('applyQuickEdits').onclick = () => {
                    const name = document.getElementById('editClientName').value.trim();
                    const company = document.getElementById('editClientCompany').value.trim();

                    document.querySelectorAll('#clientName').forEach(el => el.textContent = name || "Client Name");
                    document.querySelectorAll('#clientCompany').forEach(el => el.textContent = company || "Company Name");

                    document.getElementById('quickEditPanel').classList.add('translate-x-full');
                };

                // Save Proposal Function
                // Save Proposal Function
                document.getElementById('saveProposal').onclick = () => saveProposalToServer();

                async function saveProposalToServer() {
                    const saveBtn = document.getElementById('saveProposal');
                    const originalText = saveBtn.innerHTML;
                    saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Saving...';

                    const content = proposalContent.innerHTML;
                    const title = currentProposal.template ? currentProposal.template.name : 'Custom Proposal';
                    const clientName = document.getElementById('editClientName').value || "Client Name";
                    const companyName = document.getElementById('editClientCompany').value || "Company Name";

                    try {
                        const response = await fetch("{{ route('proposals.background-save') }}", {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({
                                proposal_id: currentProposal.id,
                                content: content,
                                title: title,
                                client_name: clientName,
                                client_company: companyName,
                                template_key: currentProposal.template ? currentProposal.template.key : null,
                                settings: { key: currentProposal.template ? currentProposal.template.key : null }
                            })
                        });

                        const data = await response.json();
                        if (data.success) {
                            currentProposal.id = data.proposal_id;
                            // Show toast
                            const saveToast = document.getElementById('saveToast');
                            saveToast.classList.remove('hidden', 'translate-y-full');
                            setTimeout(() => {
                                saveToast.classList.add('translate-y-full');
                                setTimeout(() => {
                                    saveToast.classList.add('hidden');
                                }, 300); // Wait for transition to complete
                            }, 1000);
                        }
                    } catch (e) {
                        console.error(e);
                        alert('Failed to save proposal');
                    } finally {
                        saveBtn.innerHTML = originalText;
                    }
                }

                // Improved DOC Export Function
                document.getElementById('exportDOC').onclick = () => {
                    // First save the proposal
                    document.getElementById('saveProposal').click();

                    // Get the proposal content
                    const element = document.getElementById('proposalContent').cloneNode(true);

                    // Remove edit icons and other non-printable elements
                    const editIcons = element.querySelectorAll('.edit-icon');
                    editIcons.forEach(icon => icon.remove());

                    // Remove contenteditable attributes
                    const editableElements = element.querySelectorAll('[contenteditable="true"]');
                    editableElements.forEach(el => {
                        el.removeAttribute('contenteditable');
                    });

                    // Get properly formatted HTML content
                    const content = element.innerHTML;

                    // Create properly formatted HTML document for Word
                    const htmlContent = `
                                                                                                        <!DOCTYPE html>
                                                                                                        <html>
                                                                                                        <head>
                                                                                                            <meta charset="UTF-8">
                                                                                                            <style>
                                                                                                                body {
                                                                                                                    font-family: Arial, sans-serif;
                                                                                                                    line-height: 1.6;
                                                                                                                    margin: 40px;
                                                                                                                    color: #333;
                                                                                                                }
                                                                                                                h1 {
                                                                                                                    font-size: 28px;
                                                                                                                    color: #1f2937;
                                                                                                                    margin-bottom: 10px;
                                                                                                                }
                                                                                                                h2 {
                                                                                                                    font-size: 20px;
                                                                                                                    color: #1f2937;
                                                                                                                    margin-bottom: 15px;
                                                                                                                    margin-top: 25px;
                                                                                                                }
                                                                                                                p {
                                                                                                                    margin-bottom: 15px;
                                                                                                                }
                                                                                                                ul {
                                                                                                                    margin-left: 20px;
                                                                                                                    margin-bottom: 15px;
                                                                                                                }
                                                                                                                li {
                                                                                                                    margin-bottom: 8px;
                                                                                                                }
                                                                                                                table {
                                                                                                                    width: 100%;
                                                                                                                    border-collapse: collapse;
                                                                                                                    margin: 15px 0;
                                                                                                                }
                                                                                                                th, td {
                                                                                                                    border: 1px solid #ddd;
                                                                                                                    padding: 8px;
                                                                                                                    text-align: left;
                                                                                                                }
                                                                                                                th {
                                                                                                                    background-color: #f2f2f2;
                                                                                                                    font-weight: bold;
                                                                                                                }
                                                                                                                .header-section {
                                                                                                                    display: flex;
                                                                                                                    justify-content: space-between;
                                                                                                                    margin-bottom: 30px;
                                                                                                                }
                                                                                                                .contact-info {
                                                                                                                    text-align: right;
                                                                                                                }
                                                                                                            </style>
                                                                                                        </head>
                                                                                                        <body>
                                                                                                            ${content}
                                                                                                        </body>
                                                                                                        </html>
                                                                                                    `;

                    // Create a Blob with the HTML content
                    const blob = new Blob([htmlContent], { type: 'application/msword' });

                    // Create a download link
                    const link = document.createElement('a');
                    link.href = URL.createObjectURL(blob);
                    link.download = 'proposal.doc';

                    // Trigger download
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);
                };


                // Fixed PDF Export Function
                document.getElementById('exportPDF').onclick = () => {
                    // First save the proposal
                    document.getElementById('saveProposal').click();

                    // Create a clone of the proposal content to avoid affecting the original
                    const element = document.getElementById('proposalContent').cloneNode(true);

                    // Remove edit icons and other non-printable elements
                    const editIcons = element.querySelectorAll('.edit-icon');
                    editIcons.forEach(icon => icon.remove());

                    // Remove contenteditable attributes for PDF
                    const editableElements = element.querySelectorAll('[contenteditable="true"]');
                    editableElements.forEach(el => {
                        el.removeAttribute('contenteditable');
                    });

                    // Configure PDF options with proper margins and scaling
                    const opt = {
                        margin: [0.5, 0.5, 0.5, 0.5], // Top, Right, Bottom, Left margins
                        filename: 'proposal.pdf',
                        image: { type: 'jpeg', quality: 0.98 },
                        html2canvas: {
                            scale: 2,
                            useCORS: true,
                            logging: false,
                            letterRendering: true,
                            width: element.scrollWidth,
                            height: element.scrollHeight
                        },
                        jsPDF: {
                            unit: 'in',
                            format: 'a4',
                            orientation: 'portrait'
                        },
                        pagebreak: { mode: ['avoid-all', 'css', 'legacy'] }
                    };

                    // Generate and save PDF
                    html2pdf().set(opt).from(element).save();
                };

                // Initialize the app
                loadTemplates();
                renderTemplates();

                // Initialize Saved Proposals from Server
                const serverProposals = @json($proposals);
                renderSavedProposals();

                function renderSavedProposals() {
                    const grid = document.getElementById('savedProposalsGrid');
                    const section = document.getElementById('savedProposalsSection');

                    if (serverProposals.length === 0) {
                        section.classList.add('hidden');
                        return;
                    }

                    section.classList.remove('hidden');
                    grid.innerHTML = '';

                    serverProposals.forEach(p => {
                        const date = new Date(p.updated_at).toLocaleDateString();
                        const card = document.createElement('div');
                        card.className = "bg-white rounded-2xl shadow-lg overflow-hidden border-2 border-indigo-100 hover:border-indigo-300 transition-all";
                        card.innerHTML = `
                                <div class="p-6">
                                    <div class="flex justify-between items-start mb-4">
                                        <span class="bg-indigo-100 text-indigo-800 text-xs font-medium px-2.5 py-0.5 rounded">Saved</span>
                                        <button class="delete-proposal text-gray-400 hover:text-red-500 transition-colors" data-id="${p.id}">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                    <h3 class="text-xl font-bold text-gray-800 mb-2 truncate" title="${p.title}">${p.title}</h3>
                                    <div class="space-y-2 mb-6">
                                        <p class="text-sm text-gray-600 flex items-center">
                                            <i class="fas fa-user-tie w-5 text-indigo-500"></i>
                                            ${p.client ? p.client.contact_person : 'Unknown Client'}
                                        </p>
                                        <p class="text-sm text-gray-600 flex items-center">
                                            <i class="fas fa-building w-5 text-indigo-500"></i>
                                             ${p.client ? p.client.company_name : 'Unknown Company'}
                                        </p>
                                        <p class="text-sm text-gray-500 flex items-center">
                                            <i class="fas fa-clock w-5 text-gray-400"></i>
                                            ${date}
                                        </p>
                                    </div>
                                    <button class="open-saved-proposal w-full bg-indigo-600 text-white py-2.5 rounded-xl font-semibold hover:bg-indigo-700 transition-colors flex items-center justify-center">
                                        <i class="fas fa-edit mr-2"></i> Continue Editing
                                    </button>
                                </div>
                            `;

                        card.querySelector('.open-saved-proposal').onclick = () => loadSavedProposal(p);
                        card.querySelector('.delete-proposal').onclick = (e) => deleteSavedProposal(e, p.id);

                        grid.appendChild(card);
                    });
                }

                function loadSavedProposal(proposal) {
                    proposalContent.innerHTML = proposal.content;
                    currentProposal = {
                        id: proposal.id,
                        template: { key: proposal.settings?.key || 'custom', name: proposal.title },
                        client: {
                            name: proposal.client ? proposal.client.contact_person : '',
                            company: proposal.client ? proposal.client.company_name : ''
                        },
                        content: proposal.content,
                        lastSaved: new Date(proposal.updated_at)
                    };

                    // Update toolbar
                    document.getElementById('editClientName').value = currentProposal.client.name;
                    document.getElementById('editClientCompany').value = currentProposal.client.company;

                    // Show editor
                    document.getElementById('proposalCardsView').classList.add('hidden');
                    document.getElementById('savedProposalsSection').classList.add('hidden');
                    fullEditorView.classList.remove('hidden');
                    document.getElementById('formattingToolbar').classList.remove('hidden');
                }

                function deleteSavedProposal(e, id) {
                    e.stopPropagation();
                    if (!confirm('Are you sure you want to delete this proposal?')) return;

                    fetch(`/proposals/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json'
                        }
                    }).then(() => {
                        window.location.reload();
                    });
                }
            </script>



        </div>
    </div>
@endsection
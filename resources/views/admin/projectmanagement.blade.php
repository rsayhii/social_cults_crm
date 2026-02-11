@extends('components.layout')

@section('content')

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#3b82f6',
                        secondary: '#1e40af',
                        success: '#10b981',
                        warning: '#f59e0b',
                        danger: '#ef4444',
                        info: '#06b6d4'
                    },
                    fontFamily: {
                        'inter': ['Inter', 'sans-serif']
                    }
                }
            }
        }
    </script>

    <style>
        .page-transition {
            transition: all 0.3s ease;
        }

        .project-card {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .project-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        .status-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 500;
        }

        .priority-badge {
            padding: 0.25rem 0.5rem;
            border-radius: 0.375rem;
            font-size: 0.75rem;
            font-weight: 500;
        }

        .loading {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 3px solid #f3f3f3;
            border-top: 3px solid #3b82f6;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }
    </style>
    <!-- Main Container -->
    <div class="flex h-screen overflow-hidden">
        <!-- Main Content Area -->
        <div class="flex flex-col flex-1 overflow-hidden">


            <!-- Main Content -->
            <main class="flex-1 overflow-y-auto p-6" id="main-content">
                <!-- Dashboard View -->
                <div id="dashboard-view">
                    <!-- Action Bar -->
                    <div
                        class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 space-y-4 md:space-y-0">
                        <div>
                            <h2 class="text-lg font-semibold text-gray-800" id="page-title">All Projects</h2>
                            <p class="text-gray-600">Manage and track all digital marketing projects</p>
                        </div>

                        <div class="flex space-x-3">
                            <button
                                class="hidden px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 font-medium flex items-center">
                                <i class="fas fa-filter mr-2"></i>
                                Filter
                            </button>
                            <button id="add-project-btn"
                                class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-secondary font-medium flex items-center">
                                <i class="fas fa-plus mr-2"></i>
                                Add Project
                            </button>
                        </div>
                    </div>

                    <!-- Stats Cards -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                        <div class="bg-white rounded-xl shadow p-5">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-gray-500 text-sm">Total Projects</p>
                                    <h3 class="text-2xl font-bold text-gray-800 mt-1" id="total-projects">
                                        <span class="loading" id="total-projects-loading"></span>
                                        <span id="total-projects-text">0</span>
                                    </h3>
                                </div>
                                <div class="w-12 h-12 bg-blue-50 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-folder-open text-primary text-xl"></i>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white rounded-xl shadow p-5">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-gray-500 text-sm">In Progress</p>
                                    <h3 class="text-2xl font-bold text-gray-800 mt-1" id="in-progress-count">
                                        <span class="loading" id="in-progress-loading"></span>
                                        <span id="in-progress-text">0</span>
                                    </h3>
                                </div>
                                <div class="w-12 h-12 bg-yellow-50 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-spinner text-warning text-xl"></i>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white rounded-xl shadow p-5">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-gray-500 text-sm">Completed</p>
                                    <h3 class="text-2xl font-bold text-gray-800 mt-1" id="completed-count">
                                        <span class="loading" id="completed-loading"></span>
                                        <span id="completed-text">0</span>
                                    </h3>
                                </div>
                                <div class="w-12 h-12 bg-green-50 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-check-circle text-success text-xl"></i>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white rounded-xl shadow p-5">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-gray-500 text-sm">High Priority</p>
                                    <h3 class="text-2xl font-bold text-gray-800 mt-1" id="high-priority-count">
                                        <span class="loading" id="high-priority-loading"></span>
                                        <span id="high-priority-text">0</span>
                                    </h3>
                                </div>
                                <div class="w-12 h-12 bg-red-50 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-exclamation-triangle text-danger text-xl"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Filters -->
                    <div class="bg-white rounded-xl shadow mb-6 p-4 hidden">
                        <div class="flex flex-col md:flex-row md:items-center justify-between">
                            <div class="flex flex-wrap items-center gap-3 mb-4 md:mb-0">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                                    <select id="filter-status"
                                        class="w-full md:w-auto border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary">
                                        <option value="all">All Status</option>
                                        <option value="pending">Pending</option>
                                        <option value="in-progress">In Progress</option>
                                        <option value="review">Review</option>
                                        <option value="completed">Completed</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Team</label>
                                    <select id="filter-team"
                                        class="w-full md:w-auto border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary">
                                        <option value="all">All Teams</option>
                                        <!-- Teams will be populated dynamically -->
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Priority</label>
                                    <select id="filter-priority"
                                        class="w-full md:w-auto border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary">
                                        <option value="all">All Priorities</option>
                                        <option value="low">Low</option>
                                        <option value="medium">Medium</option>
                                        <option value="high">High</option>
                                    </select>
                                </div>
                            </div>

                            <div class="flex items-center">
                                <button id="clear-filters"
                                    class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 font-medium">
                                    <i class="fas fa-times mr-2"></i>
                                    Clear Filters
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Projects Grid -->
                    <div id="projects-container" class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6">
                        <!-- Projects will be dynamically loaded here -->
                        <div class="col-span-full text-center py-12">
                            <div class="loading mx-auto mb-4"></div>
                            <p class="text-gray-600">Loading projects...</p>
                        </div>
                    </div>

                    <!-- No Projects Message -->
                    <div id="no-projects-message" class="hidden text-center py-12">
                        <div class="w-24 h-24 mx-auto mb-6 rounded-full bg-gray-100 flex items-center justify-center">
                            <i class="fas fa-folder-open text-gray-400 text-3xl"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-700 mb-2">No Projects Found</h3>
                        <p class="text-gray-500 mb-6">Get started by creating your first project</p>
                        <button id="add-first-project-btn"
                            class="px-5 py-2.5 bg-primary text-white rounded-lg hover:bg-secondary font-medium">
                            <i class="fas fa-plus mr-2"></i> Add Project
                        </button>
                    </div>
                </div>

                <!-- Project Details View (Hidden by default) -->
                <div id="project-details-view" class="hidden">
                    <!-- Back button -->
                    <div class="mb-6">
                        <button id="back-to-dashboard"
                            class="flex items-center text-primary hover:text-secondary font-medium">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Back to Projects
                        </button>
                    </div>

                    <!-- Project Details Content -->
                    <div id="project-details-content">
                        <!-- Content will be dynamically loaded here -->
                        <div class="text-center py-12">
                            <div class="loading mx-auto mb-4"></div>
                            <p class="text-gray-600">Loading project details...</p>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- Add/Edit Project Modal -->
    <div id="project-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-10 mx-auto p-5 border w-full max-w-4xl shadow-lg rounded-xl bg-white">
            <!-- Modal Header -->
            <div class="flex justify-between items-center pb-3 border-b">
                <h3 class="text-xl font-bold text-gray-800" id="modal-title">Add New Project</h3>
                <button id="close-modal" class="text-gray-400 hover:text-gray-600 text-2xl">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="py-4">
                <form id="project-form">
                    <input type="hidden" id="project-id" value="">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Project Name *</label>
                            <input type="text" id="project-name"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary"
                                required>
                            <div id="name-error" class="text-red-500 text-sm mt-1 hidden"></div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Client Name *</label>
                            <input type="text" id="client-name"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary"
                                required placeholder="Enter client name">
                            <div id="client-error" class="text-red-500 text-sm mt-1 hidden"></div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Project Owner *</label>
                            <input type="text" id="project-owner"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary"
                                required placeholder="Enter project owner name">
                            <div id="owner-error" class="text-red-500 text-sm mt-1 hidden"></div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Team *</label>
                            <select id="project-team"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary"
                                required>
                                <option value="">Select Team</option>
                                <option value="SMM">Social Media Marketing (SMM)</option>
                                <option value="Web">Web Development</option>
                                <option value="SEO">Search Engine Optimization (SEO)</option>
                                <option value="Ads">Digital Advertising</option>
                                <option value="Content">Content Marketing</option>
                                <option value="Email">Email Marketing</option>
                            </select>
                            <div id="team-error" class="text-red-500 text-sm mt-1 hidden"></div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Status *</label>
                            <select id="project-status"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary"
                                required>
                                <option value="pending">Pending</option>
                                <option value="in-progress">In Progress</option>
                                <option value="review">Review</option>
                                <option value="completed">Completed</option>
                            </select>
                            <div id="status-error" class="text-red-500 text-sm mt-1 hidden"></div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Priority *</label>
                            <select id="project-priority"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary"
                                required>
                                <option value="low">Low</option>
                                <option value="medium">Medium</option>
                                <option value="high">High</option>
                            </select>
                            <div id="priority-error" class="text-red-500 text-sm mt-1 hidden"></div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Start Date *</label>
                            <input type="date" id="start-date"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary"
                                required>
                            <div id="start-date-error" class="text-red-500 text-sm mt-1 hidden"></div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Deadline *</label>
                            <input type="date" id="deadline"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary"
                                required>
                            <div id="deadline-error" class="text-red-500 text-sm mt-1 hidden"></div>
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Project Description</label>
                            <textarea rows="3" id="project-description"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary"
                                placeholder="Describe the project scope, deliverables, and objectives..."></textarea>
                            <div id="description-error" class="text-red-500 text-sm mt-1 hidden"></div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Budget ($)</label>
                            <input type="number" id="project-budget"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary">
                            <div id="budget-error" class="text-red-500 text-sm mt-1 hidden"></div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Progress (%)</label>
                            <input type="range" id="project-progress" min="0" max="100" value="0" class="w-full">
                            <div class="text-sm text-gray-500 mt-1"><span id="progress-value">0</span>%</div>
                            <div id="progress-error" class="text-red-500 text-sm mt-1 hidden"></div>
                        </div>
                    </div>

                    <div class="flex justify-end space-x-3 mt-8 pt-4 border-t">
                        <button type="button" id="cancel-modal"
                            class="px-5 py-2.5 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 font-medium">
                            Cancel
                        </button>
                        <button type="submit"
                            class="px-5 py-2.5 bg-primary text-white rounded-lg hover:bg-secondary font-medium"
                            id="save-project-btn">
                            Save Project
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="delete-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-full max-w-md shadow-lg rounded-xl bg-white">
            <div class="text-center">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 mb-4">
                    <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2" id="delete-modal-title">Delete Project</h3>
                <p class="text-gray-500 mb-6" id="delete-modal-message">Are you sure you want to delete this project? This
                    action cannot be undone.</p>
                <div class="flex justify-center space-x-3">
                    <button id="cancel-delete"
                        class="px-5 py-2.5 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 font-medium">
                        Cancel
                    </button>
                    <button id="confirm-delete"
                        class="px-5 py-2.5 bg-danger text-white rounded-lg hover:bg-red-700 font-medium">
                        Delete Project
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript -->
    <script>
        // Global variables
        let projects = [];
        let projectToDelete = null;
        let editProjectId = null;
        const projectCsrfToken = document.querySelector('meta[name="csrf-token"]') ? document.querySelector('meta[name="csrf-token"]').getAttribute('content') : '';

        // API Endpoints
        const API = {
            projects: '{{ route("projectmanagement.projects") }}',
            project: (id) => `{{ url("/projectmanagement/projects") }}/${id}`,
            store: '{{ route("projectmanagement.project.store") }}',
            update: (id) => `{{ url("/projectmanagement/projects") }}/${id}`,
            destroy: (id) => `{{ url("/projectmanagement/projects") }}/${id}`,
            statistics: '{{ route("projectmanagement.statistics") }}',
            filters: '{{ route("projectmanagement.filters") }}'
        };

        // DOM Elements
        const dashboardView = document.getElementById('dashboard-view');
        const projectDetailsView = document.getElementById('project-details-view');
        const projectsContainer = document.getElementById('projects-container');
        const projectDetailsContent = document.getElementById('project-details-content');
        const pageTitle = document.getElementById('page-title');
        const projectModal = document.getElementById('project-modal');
        const deleteModal = document.getElementById('delete-modal');
        const modalTitle = document.getElementById('modal-title');
        const addProjectBtn = document.getElementById('add-project-btn');
        const addFirstProjectBtn = document.getElementById('add-first-project-btn');
        const closeModalBtn = document.getElementById('close-modal');
        const cancelModalBtn = document.getElementById('cancel-modal');
        const backToDashboardBtn = document.getElementById('back-to-dashboard');
        const projectForm = document.getElementById('project-form');
        const progressSlider = document.getElementById('project-progress');
        const progressValue = document.getElementById('progress-value');
        const cancelDeleteBtn = document.getElementById('cancel-delete');
        const confirmDeleteBtn = document.getElementById('confirm-delete');
        const globalSearch = document.getElementById('global-search');
        const filterStatus = document.getElementById('filter-status');
        const filterTeam = document.getElementById('filter-team');
        const filterPriority = document.getElementById('filter-priority');
        const clearFiltersBtn = document.getElementById('clear-filters');
        const noProjectsMessage = document.getElementById('no-projects-message');
        const saveProjectBtn = document.getElementById('save-project-btn');

        // Form fields
        const projectIdField = document.getElementById('project-id');
        const projectNameField = document.getElementById('project-name');
        const clientNameField = document.getElementById('client-name');
        const projectOwnerField = document.getElementById('project-owner');
        const projectTeamField = document.getElementById('project-team');
        const projectStatusField = document.getElementById('project-status');
        const projectPriorityField = document.getElementById('project-priority');
        const startDateField = document.getElementById('start-date');
        const deadlineField = document.getElementById('deadline');
        const projectDescriptionField = document.getElementById('project-description');
        const projectBudgetField = document.getElementById('project-budget');

        // Statistics elements
        const totalProjectsEl = document.getElementById('total-projects-text');
        const inProgressCountEl = document.getElementById('in-progress-text');
        const completedCountEl = document.getElementById('completed-text');
        const highPriorityCountEl = document.getElementById('high-priority-text');

        // Initialize the application
        document.addEventListener('DOMContentLoaded', function () {
            // Set default dates for form
            const today = new Date();
            const nextWeek = new Date();
            nextWeek.setDate(today.getDate() + 7);

            startDateField.value = formatDateForInput(today);
            deadlineField.value = formatDateForInput(nextWeek);

            // Load initial data
            loadProjects();
            loadStatistics();
            loadFilters();

            // Event listeners
            addProjectBtn.addEventListener('click', openAddProjectModal);
            if (addFirstProjectBtn) {
                addFirstProjectBtn.addEventListener('click', openAddProjectModal);
            }
            closeModalBtn.addEventListener('click', closeProjectModal);
            cancelModalBtn.addEventListener('click', closeProjectModal);
            backToDashboardBtn.addEventListener('click', showDashboardView);
            cancelDeleteBtn.addEventListener('click', closeDeleteModal);
            confirmDeleteBtn.addEventListener('click', confirmDeleteProject);

            // Progress slider update
            if (progressSlider) {
                progressSlider.addEventListener('input', function () {
                    progressValue.textContent = this.value;
                });
            }

            // Form submission
            if (projectForm) {
                projectForm.addEventListener('submit', handleProjectSubmit);
            }

            // Search and filter events
            if (globalSearch) {
                globalSearch.addEventListener('input', handleSearch);
            }

            if (filterStatus) {
                filterStatus.addEventListener('change', filterProjects);
            }

            if (filterTeam) {
                filterTeam.addEventListener('change', filterProjects);
            }

            if (filterPriority) {
                filterPriority.addEventListener('change', filterProjects);
            }

            if (clearFiltersBtn) {
                clearFiltersBtn.addEventListener('click', clearFilters);
            }

            // Close modals when clicking outside
            window.addEventListener('click', function (e) {
                if (e.target === projectModal) {
                    closeProjectModal();
                }
                if (e.target === deleteModal) {
                    closeDeleteModal();
                }
            });
        });

        // Load projects from backend
        async function loadProjects() {
            try {
                projectsContainer.innerHTML = `
                                <div class="col-span-full text-center py-12">
                                    <div class="loading mx-auto mb-4"></div>
                                    <p class="text-gray-600">Loading projects...</p>
                                </div>
                            `;

                const response = await fetch(API.projects);
                const data = await response.json();

                if (data.success) {
                    projects = data.projects;
                    renderProjects();
                } else {
                    showError('Failed to load projects: ' + data.message);
                }
            } catch (error) {
                showError('Error loading projects: ' + error.message);
            }
        }

        // Load statistics from backend
        async function loadStatistics() {
            try {
                const response = await fetch(API.statistics);
                const data = await response.json();

                if (data.success) {
                    const stats = data.statistics;
                    totalProjectsEl.textContent = stats.total_projects;
                    inProgressCountEl.textContent = stats.in_progress;
                    completedCountEl.textContent = stats.completed;
                    highPriorityCountEl.textContent = stats.high_priority;

                    // Hide loading indicators
                    document.getElementById('total-projects-loading').style.display = 'none';
                    document.getElementById('in-progress-loading').style.display = 'none';
                    document.getElementById('completed-loading').style.display = 'none';
                    document.getElementById('high-priority-loading').style.display = 'none';
                }
            } catch (error) {
                console.error('Error loading statistics:', error);
            }
        }

        // Load filter options
        async function loadFilters() {
            try {
                const response = await fetch(API.filters);
                const data = await response.json();

                if (data.success) {
                    const filters = data.filters;
                    const teamSelect = document.getElementById('filter-team');

                    // Clear existing options except first
                    teamSelect.innerHTML = '<option value="all">All Teams</option>';

                    // Add team options
                    filters.teams.forEach(team => {
                        const option = document.createElement('option');
                        option.value = team;
                        option.textContent = team;
                        teamSelect.appendChild(option);
                    });
                }
            } catch (error) {
                console.error('Error loading filters:', error);
            }
        }

        // Render projects on dashboard
        function renderProjects(projectsToRender = projects) {
            projectsContainer.innerHTML = '';

            if (projectsToRender.length === 0) {
                noProjectsMessage.classList.remove('hidden');
                projectsContainer.classList.add('hidden');
                return;
            }

            noProjectsMessage.classList.add('hidden');
            projectsContainer.classList.remove('hidden');

            projectsToRender.forEach(project => {
                const projectCard = createProjectCard(project);
                projectsContainer.appendChild(projectCard);
            });
        }

        // Create project card element
        function createProjectCard(project) {
            const card = document.createElement('div');
            card.className = 'project-card bg-white rounded-xl shadow p-5';
            card.dataset.id = project.id;

            // Parse JSON fields
            const clientDetails = typeof project.client_details === 'string' ?
                JSON.parse(project.client_details) : project.client_details;
            const ownerDetails = typeof project.owner_details === 'string' ?
                JSON.parse(project.owner_details) : project.owner_details;

            // Status and priority badges
            const statusClass = getStatusClass(project.status);
            const priorityClass = getPriorityClass(project.priority);

            card.innerHTML = `
                            <div class="flex justify-between items-start mb-4">
                                <div>
                                    <h3 class="font-bold text-lg text-gray-800">${project.name}</h3>
                                    <p class="text-gray-600 text-sm mt-1">${project.client}</p>
                                </div>
                                <div class="flex flex-col items-end space-y-2">
                                    <span class="${statusClass} status-badge">${formatStatus(project.status)}</span>
                                    <span class="${priorityClass} priority-badge">${project.priority.charAt(0).toUpperCase() + project.priority.slice(1)}</span>
                                </div>
                            </div>

                            <div class="mb-4">
                                <div class="flex justify-between text-sm text-gray-600 mb-1">
                                    <span>Progress</span>
                                    <span>${project.progress}%</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-primary h-2 rounded-full" style="width: ${project.progress}%"></div>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4 mb-5 text-sm">
                                <div>
                                    <p class="text-gray-500">Owner</p>
                                    <p class="font-medium">${project.owner}</p>
                                </div>
                                <div>
                                    <p class="text-gray-500">Team</p>
                                    <p class="font-medium">${project.team}</p>
                                </div>
                                <div>
                                    <p class="text-gray-500">Start Date</p>
                                    <p class="font-medium">${formatDate(project.start_date)}</p>
                                </div>
                                <div>
                                    <p class="text-gray-500">Deadline</p>
                                    <p class="font-medium">${formatDate(project.deadline)}</p>
                                </div>
                            </div>

                            <div class="flex justify-between items-center pt-4 border-t border-gray-100">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-2">
                                        <span class="text-primary font-medium text-sm">${project.team.charAt(0)}</span>
                                    </div>
                                    <span class="text-sm font-medium">${project.team} Team</span>
                                </div>

                                <div class="flex space-x-2">
                                    <button class="hidden view-project-btn px-3 py-1.5 bg-primary text-white text-sm rounded-lg hover:bg-secondary">
                                        <i class="fas fa-eye mr-1"></i> View
                                    </button>
                                    <button class="edit-project-btn px-3 py-1.5 bg-gray-100 text-gray-700 text-sm rounded-lg hover:bg-gray-200">
                                        <i class="fas fa-edit mr-1"></i> Edit
                                    </button>
                                    <button class="delete-project-btn px-3 py-1.5 bg-gray-100 text-gray-700 text-sm rounded-lg hover:bg-red-100 hover:text-red-700">
                                        <i class="fas fa-trash-alt mr-1"></i>
                                    </button>
                                </div>
                            </div>
                        `;

            // Add event listeners to buttons
            const viewBtn = card.querySelector('.view-project-btn');
            viewBtn.addEventListener('click', () => showProjectDetails(project.id));

            const editBtn = card.querySelector('.edit-project-btn');
            editBtn.addEventListener('click', () => openEditProjectModal(project.id));

            const deleteBtn = card.querySelector('.delete-project-btn');
            deleteBtn.addEventListener('click', () => openDeleteModal(project.id));

            return card;
        }

        // Show project details view
        async function showProjectDetails(projectId) {
            try {
                projectDetailsContent.innerHTML = `
                                <div class="text-center py-12">
                                    <div class="loading mx-auto mb-4"></div>
                                    <p class="text-gray-600">Loading project details...</p>
                                </div>
                            `;

                const response = await fetch(API.project(projectId));
                const data = await response.json();

                if (data.success) {
                    const project = data.project;

                    // Update page title
                    const pt = document.getElementById('page-title');
                    if (pt) pt.textContent = project.name;
                    else console.warn('Page title element not found');

                    // Hide dashboard, show project details
                    dashboardView.classList.add('hidden');
                    projectDetailsView.classList.remove('hidden');

                    // Render project details
                    renderProjectDetails(project);
                } else {
                    showError('Failed to load project: ' + data.message);
                }
            } catch (error) {
                showError('Error loading project: ' + error.message);
            }
        }

        // Render project details
        function renderProjectDetails(project) {
            // Parse JSON fields
            const clientDetails = typeof project.client_details === 'string' ?
                JSON.parse(project.client_details) : project.client_details;
            const ownerDetails = typeof project.owner_details === 'string' ?
                JSON.parse(project.owner_details) : project.owner_details;
            const teamMembers = typeof project.team_members === 'string' ?
                JSON.parse(project.team_members) : project.team_members;
            const timeline = typeof project.timeline === 'string' ?
                JSON.parse(project.timeline) : project.timeline;
            const tasks = typeof project.tasks === 'string' ?
                JSON.parse(project.tasks) : project.tasks;

            // Status and priority classes
            const statusClass = getStatusClass(project.status);
            const priorityClass = getPriorityClass(project.priority);

            projectDetailsContent.innerHTML = `
                            <!-- Project Header -->
                            <div class="bg-white rounded-xl shadow p-6 mb-6">
                                <div class="flex flex-col lg:flex-row lg:items-center justify-between">
                                    <div>
                                        <h2 class="text-2xl font-bold text-gray-800 mb-2">${project.name}</h2>
                                        <div class="flex flex-wrap items-center gap-3">
                                            <span class="${statusClass} status-badge">${formatStatus(project.status)}</span>
                                            <span class="${priorityClass} priority-badge">${project.priority.charAt(0).toUpperCase() + project.priority.slice(1)} Priority</span>
                                            <span class="text-gray-600"><i class="far fa-calendar-alt mr-1"></i> ${formatDate(project.start_date)} - ${formatDate(project.deadline)}</span>
                                        </div>
                                    </div>
                                    <div class="mt-4 lg:mt-0">
                                        <button class="edit-project-details-btn px-4 py-2 bg-primary text-white rounded-lg hover:bg-secondary font-medium">
                                            <i class="fas fa-edit mr-2"></i> Edit Project
                                        </button>
                                    </div>
                                </div>

                                <!-- Progress Bar -->
                                <div class="mt-6">
                                    <div class="flex justify-between text-sm text-gray-600 mb-1">
                                        <span>Project Progress</span>
                                        <span>${project.progress}%</span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-3">
                                        <div class="bg-primary h-3 rounded-full" style="width: ${project.progress}%"></div>
                                    </div>
                                </div>
                            </div>

                            <!-- Main Content Grid -->
                            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                                <!-- Left Column -->
                                <div class="lg:col-span-2 space-y-6">
                                    <!-- Project Overview -->
                                    <div class="bg-white rounded-xl shadow p-6">
                                        <h3 class="text-lg font-bold text-gray-800 mb-4">Project Overview</h3>
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <div>
                                                <p class="text-gray-500">Project Code</p>
                                                <p class="font-medium">PROJ-${String(project.id).padStart(3, '0')}</p>
                                            </div>
                                            <div>
                                                <p class="text-gray-500">Budget</p>
                                                <p class="font-medium">$${project.budget ? parseFloat(project.budget).toLocaleString() : 'Not set'}</p>
                                            </div>
                                            <div>
                                                <p class="text-gray-500">Start Date</p>
                                                <p class="font-medium">${formatDate(project.start_date)}</p>
                                            </div>
                                            <div>
                                                <p class="text-gray-500">Deadline</p>
                                                <p class="font-medium">${formatDate(project.deadline)}</p>
                                            </div>
                                        </div>

                                        <div class="mt-6">
                                            <h4 class="font-medium text-gray-700 mb-2">Project Description</h4>
                                            <p class="text-gray-600">${project.description || 'No description provided.'}</p>
                                        </div>
                                    </div>

                                    <!-- Project Timeline -->
                                    <div class="bg-white rounded-xl shadow p-6">
                                        <h3 class="text-lg font-bold text-gray-800 mb-4">Project Timeline</h3>
                                        <div class="space-y-4">
                                            ${timeline ? timeline.map(phase => `
                                                <div class="flex items-center">
                                                    <div class="w-3 h-3 rounded-full ${getPhaseStatusClass(phase.status)} mr-3"></div>
                                                    <div class="flex-1">
                                                        <div class="flex justify-between">
                                                            <span class="font-medium">${phase.phase}</span>
                                                            <span class="text-sm text-gray-500">${formatDate(phase.startDate)} - ${formatDate(phase.endDate)}</span>
                                                        </div>
                                                        <div class="text-sm text-gray-500 mt-1">${formatStatus(phase.status)}</div>
                                                    </div>
                                                </div>
                                            `).join('') : '<p class="text-gray-500">No timeline data available.</p>'}
                                        </div>
                                    </div>

                                    <!-- Task List -->
                                    <div class="bg-white rounded-xl shadow p-6">
                                        <h3 class="text-lg font-bold text-gray-800 mb-4">Task List</h3>
                                        <div class="overflow-x-auto">
                                            <table class="w-full">
                                                <thead>
                                                    <tr class="text-left text-gray-500 text-sm border-b">
                                                        <th class="pb-3 font-medium">Task Title</th>
                                                        <th class="pb-3 font-medium">Assigned To</th>
                                                        <th class="pb-3 font-medium">Priority</th>
                                                        <th class="pb-3 font-medium">Status</th>
                                                        <th class="pb-3 font-medium">Due Date</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    ${tasks ? tasks.map(task => `
                                                        <tr class="border-b border-gray-100">
                                                            <td class="py-3 font-medium">${task.title}</td>
                                                            <td class="py-3">${task.assignedTo}</td>
                                                            <td class="py-3">
                                                                <span class="${getPriorityClass(task.priority)} priority-badge">${task.priority.charAt(0).toUpperCase() + task.priority.slice(1)}</span>
                                                            </td>
                                                            <td class="py-3">
                                                                <span class="${getStatusClass(task.status)} status-badge">${formatStatus(task.status)}</span>
                                                            </td>
                                                            <td class="py-3 text-gray-600">${formatDate(task.dueDate)}</td>
                                                        </tr>
                                                    `).join('') : '<tr><td colspan="5" class="py-4 text-center text-gray-500">No tasks available.</td></tr>'}
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                <!-- Right Column -->
                                <div class="space-y-6">
                                    <!-- Project Owner -->
                                    <div class="bg-white rounded-xl shadow p-6">
                                        <h3 class="text-lg font-bold text-gray-800 mb-4">Project Owner</h3>
                                        <div class="flex items-center mb-4">
                                            <div class="w-12 h-12 bg-primary text-white rounded-full flex items-center justify-center text-lg font-bold mr-4">
                                                ${ownerDetails?.avatar || project.owner.split(' ').map(n => n[0]).join('')}
                                            </div>
                                            <div>
                                                <h4 class="font-bold text-gray-800">${project.owner}</h4>
                                                <p class="text-gray-600 text-sm">${ownerDetails?.designation || 'Project Manager'}</p>
                                            </div>
                                        </div>

                                        <div class="space-y-2 text-sm">
                                            <div class="flex items-center">
                                                <i class="fas fa-envelope text-gray-400 w-5 mr-2"></i>
                                                <span>${ownerDetails?.email || project.owner.toLowerCase().replace(' ', '.') + '@marketingcrm.com'}</span>
                                            </div>
                                            <div class="flex items-center">
                                                <i class="fas fa-phone text-gray-400 w-5 mr-2"></i>
                                                <span>${ownerDetails?.phone || '+1 (555) 123-4567'}</span>
                                            </div>
                                            <div class="flex items-center">
                                                <i class="fas fa-building text-gray-400 w-5 mr-2"></i>
                                                <span>${ownerDetails?.department || getDepartmentByTeam(project.team)}</span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Client Details -->
                                    <div class="bg-white rounded-xl shadow p-6">
                                        <h3 class="text-lg font-bold text-gray-800 mb-4">Client Details</h3>
                                        <div class="space-y-3">
                                            <div>
                                                <p class="text-gray-500 text-sm">Client Name</p>
                                                <p class="font-medium">${clientDetails?.name || project.client.split(' ')[0] + ' Client'}</p>
                                            </div>
                                            <div>
                                                <p class="text-gray-500 text-sm">Business</p>
                                                <p class="font-medium">${project.client}</p>
                                            </div>
                                            <div>
                                                <p class="text-gray-500 text-sm">Email</p>
                                                <p class="font-medium">${clientDetails?.email || project.client.toLowerCase().replace(/[^a-z0-9]/g, '') + '@example.com'}</p>
                                            </div>
                                            <div>
                                                <p class="text-gray-500 text-sm">Phone</p>
                                                <p class="font-medium">${clientDetails?.phone || '+1 (555) 987-6543'}</p>
                                            </div>
                                            <div>
                                                <p class="text-gray-500 text-sm">Website</p>
                                                <a href="#" class="font-medium text-primary">${clientDetails?.website || 'www.' + project.client.toLowerCase().replace(/[^a-z0-9]/g, '') + '.com'}</a>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Team Members -->
                                    <div class="bg-white rounded-xl shadow p-6">
                                        <h3 class="text-lg font-bold text-gray-800 mb-4">Assigned Team</h3>
                                        <div class="space-y-4">
                                            ${teamMembers ? teamMembers.map(member => `
                                                <div class="flex items-center">
                                                    <div class="w-10 h-10 bg-gray-200 rounded-full flex items-center justify-center text-gray-700 font-medium mr-3">
                                                        ${member.name.split(' ').map(n => n[0]).join('')}
                                                    </div>
                                                    <div class="flex-1">
                                                        <h4 class="font-medium text-gray-800">${member.name}</h4>
                                                        <p class="text-gray-600 text-sm">${member.role} • ${member.team}</p>
                                                    </div>
                                                    <span class="${member.status === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'} status-badge">${member.status.charAt(0).toUpperCase() + member.status.slice(1)}</span>
                                                </div>
                                            `).join('') : '<p class="text-gray-500">No team members assigned.</p>'}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `;

            // Add event listener to edit button in project details
            const editDetailsBtn = document.querySelector('.edit-project-details-btn');
            if (editDetailsBtn) {
                editDetailsBtn.addEventListener('click', () => {
                    openEditProjectModal(project.id);
                    showDashboardView(); // Go back to dashboard to see the modal
                });
            }
        }

        // Show dashboard view
        function showDashboardView() {
            dashboardView.classList.remove('hidden');
            projectDetailsView.classList.add('hidden');

            const pt = document.getElementById('page-title');
            if (pt) pt.textContent = 'Projects Dashboard';

            loadProjects(); // Refresh the projects list
            loadStatistics(); // Refresh statistics
        }

        // Open add project modal
        function openAddProjectModal() {
            modalTitle.textContent = 'Add New Project';
            projectIdField.value = '';
            editProjectId = null;

            // Reset form fields and clear errors
            projectForm.reset();
            clearFormErrors();

            // Set default dates
            const today = new Date();
            const nextWeek = new Date();
            nextWeek.setDate(today.getDate() + 7);

            startDateField.value = formatDateForInput(today);
            deadlineField.value = formatDateForInput(nextWeek);
            progressSlider.value = 0;
            progressValue.textContent = '0';
            projectBudgetField.value = '';
            projectDescriptionField.value = '';

            // Show modal
            projectModal.classList.remove('hidden');
        }

        // Open edit project modal
        async function openEditProjectModal(projectId) {
            try {
                const response = await fetch(API.project(projectId));
                const data = await response.json();

                if (data.success) {
                    const project = data.project;

                    modalTitle.textContent = 'Edit Project';
                    editProjectId = projectId;

                    // Clear form errors
                    clearFormErrors();

                    // Populate form fields
                    projectIdField.value = project.id;
                    projectNameField.value = project.name;
                    clientNameField.value = project.client;
                    projectOwnerField.value = project.owner;
                    projectTeamField.value = project.team;
                    projectStatusField.value = project.status;
                    projectPriorityField.value = project.priority;
                    startDateField.value = project.start_date;
                    deadlineField.value = project.deadline;
                    projectDescriptionField.value = project.description || '';
                    projectBudgetField.value = project.budget || '';
                    progressSlider.value = project.progress;
                    progressValue.textContent = project.progress;

                    // Show modal
                    projectModal.classList.remove('hidden');
                } else {
                    showError('Failed to load project for editing: ' + data.message);
                }
            } catch (error) {
                showError('Error loading project: ' + error.message);
            }
        }

        // Handle project form submission
        async function handleProjectSubmit(e) {
            e.preventDefault();

            // Clear previous errors
            clearFormErrors();

            // Disable save button and show loading
            const originalBtnText = saveProjectBtn.innerHTML;
            saveProjectBtn.innerHTML = '<span class="loading mr-2"></span> Saving...';
            saveProjectBtn.disabled = true;

            try {
                // Prepare form data
                const formData = {
                    name: projectNameField.value,
                    client: clientNameField.value,
                    owner: projectOwnerField.value,
                    team: projectTeamField.value,
                    status: projectStatusField.value,
                    priority: projectPriorityField.value,
                    start_date: startDateField.value,
                    deadline: deadlineField.value,
                    progress: parseInt(progressSlider.value),
                    budget: projectBudgetField.value ? parseFloat(projectBudgetField.value) : null,
                    description: projectDescriptionField.value
                };

                let url, method;

                if (editProjectId) {
                    // Update existing project
                    url = API.update(editProjectId);
                    method = 'PUT';
                } else {
                    // Create new project
                    url = API.store;
                    method = 'POST';
                }

                const response = await fetch(url, {
                    method: method,
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': projectCsrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(formData)
                });

                const data = await response.json();

                if (data.success) {
                    // Show success message
                    alert(editProjectId ? 'Project updated successfully!' : 'Project created successfully!');

                    // Close modal
                    closeProjectModal();

                    // Refresh data
                    loadProjects();
                    loadStatistics();

                    // If we were in project details view, go back to dashboard
                    if (!dashboardView.classList.contains('hidden')) {
                        showDashboardView();
                    }
                } else {
                    // Handle validation errors
                    if (data.errors) {
                        displayFormErrors(data.errors);
                    } else {
                        showError(data.message || 'An error occurred');
                    }
                }
            } catch (error) {
                showError('Error saving project: ' + error.message);
            } finally {
                // Restore save button
                saveProjectBtn.innerHTML = originalBtnText;
                saveProjectBtn.disabled = false;
            }
        }

        // Open delete confirmation modal
        function openDeleteModal(projectId) {
            const project = projects.find(p => p.id == projectId);
            if (!project) return;

            projectToDelete = projectId;

            // Update modal content
            document.getElementById('delete-modal-title').textContent = `Delete ${project.name}`;
            document.getElementById('delete-modal-message').textContent = `Are you sure you want to delete "${project.name}"? This action cannot be undone.`;

            // Show modal
            deleteModal.classList.remove('hidden');
        }

        // Confirm and delete project
        async function confirmDeleteProject() {
            if (!projectToDelete) return;

            try {
                const response = await fetch(API.destroy(projectToDelete), {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': projectCsrfToken,
                        'Accept': 'application/json'
                    }
                });

                const data = await response.json();

                if (data.success) {
                    alert('Project deleted successfully!');

                    // Close modal
                    closeDeleteModal();

                    // Refresh data
                    loadProjects();
                    loadStatistics();

                    // If we were in project details view, go back to dashboard
                    if (!dashboardView.classList.contains('hidden')) {
                        showDashboardView();
                    }
                } else {
                    showError('Failed to delete project: ' + data.message);
                }
            } catch (error) {
                showError('Error deleting project: ' + error.message);
            }
        }

        // Close project modal
        function closeProjectModal() {
            projectModal.classList.add('hidden');
            projectForm.reset();
            editProjectId = null;
            clearFormErrors();
        }

        // Close delete modal
        function closeDeleteModal() {
            deleteModal.classList.add('hidden');
            projectToDelete = null;
        }

        // Handle search
        async function handleSearch() {
            const searchTerm = globalSearch.value.trim();

            try {
                const url = new URL(API.projects);
                if (searchTerm) {
                    url.searchParams.append('search', searchTerm);
                }
                if (filterStatus.value !== 'all') {
                    url.searchParams.append('status', filterStatus.value);
                }
                if (filterTeam.value !== 'all') {
                    url.searchParams.append('team', filterTeam.value);
                }
                if (filterPriority.value !== 'all') {
                    url.searchParams.append('priority', filterPriority.value);
                }

                const response = await fetch(url);
                const data = await response.json();

                if (data.success) {
                    renderProjects(data.projects);
                }
            } catch (error) {
                console.error('Error searching projects:', error);
            }
        }

        // Filter projects
        function filterProjects() {
            handleSearch();
        }

        // Clear all filters
        function clearFilters() {
            filterStatus.value = 'all';
            filterTeam.value = 'all';
            filterPriority.value = 'all';
            globalSearch.value = '';

            loadProjects();
        }

        // Display form errors
        function displayFormErrors(errors) {
            for (const field in errors) {
                const errorElement = document.getElementById(`${field}-error`);
                if (errorElement) {
                    errorElement.textContent = errors[field][0];
                    errorElement.classList.remove('hidden');
                }
            }
        }

        // Clear form errors
        function clearFormErrors() {
            const errorElements = document.querySelectorAll('[id$="-error"]');
            errorElements.forEach(element => {
                element.textContent = '';
                element.classList.add('hidden');
            });
        }

        // Show error message
        function showError(message) {
            alert('Error: ' + message);
        }

        // Helper functions
        function getStatusClass(status) {
            switch (status) {
                case 'pending': return 'bg-yellow-100 text-yellow-800';
                case 'in-progress': return 'bg-blue-100 text-blue-800';
                case 'review': return 'bg-purple-100 text-purple-800';
                case 'completed': return 'bg-green-100 text-green-800';
                default: return 'bg-gray-100 text-gray-800';
            }
        }

        function getPriorityClass(priority) {
            switch (priority) {
                case 'low': return 'bg-green-100 text-green-800';
                case 'medium': return 'bg-yellow-100 text-yellow-800';
                case 'high': return 'bg-red-100 text-red-800';
                default: return 'bg-gray-100 text-gray-800';
            }
        }

        function getPhaseStatusClass(status) {
            switch (status) {
                case 'completed': return 'bg-green-500';
                case 'in-progress': return 'bg-blue-500';
                case 'review': return 'bg-purple-500';
                case 'pending': return 'bg-gray-300';
                default: return 'bg-gray-300';
            }
        }

        function getDepartmentByTeam(team) {
            const teamMap = {
                'SMM': 'Social Media Marketing',
                'Web': 'Web Development',
                'SEO': 'Search Engine Optimization',
                'Ads': 'Digital Advertising',
                'Content': 'Content Marketing',
                'Email': 'Email Marketing'
            };
            return teamMap[team] || 'Digital Marketing';
        }

        function formatStatus(status) {
            if (!status) return 'Unknown';
            return status.split('-').map(word => word.charAt(0).toUpperCase() + word.slice(1)).join(' ');
        }

        function formatDate(dateString) {
            if (!dateString) return 'Not set';
            const options = { month: 'short', day: 'numeric', year: 'numeric' };
            return new Date(dateString).toLocaleDateString('en-US', options);
        }

        function formatDateForInput(date) {
            return date.toISOString().split('T')[0];
        }
    </script>


@endsection
<?php

namespace App\Http\Controllers;

use App\Models\ProjectManagement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ProjectManagementController extends Controller
{
    /**
     * Display the project management dashboard.
     */
    public function index()
    {
        return view('admin.projectmanagement');
    }

    /**
     * Get all projects with filters.
     */
    public function getProjects(Request $request)
    {
        try {
            $query = ProjectManagement::where('company_id', auth()->user()->company_id);

            
            // Apply search filter
            if ($request->has('search') && !empty($request->search)) {
                $query->search($request->search);
            }
            
            // Apply filters
            $query->byStatus($request->status)
                  ->byTeam($request->team)
                  ->byPriority($request->priority);
            
            $projects = $query->orderBy('created_at', 'desc')->get();
            
            return response()->json([
                'success' => true,
                'projects' => $projects,
                'total' => $projects->count()
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching projects: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get a single project by ID.
     */
    public function getProject($id)
    {
        try {
          $project = ProjectManagement::where('company_id', auth()->user()->company_id)
    ->findOrFail($id);

$this->authorize('manage', $project);

            
            if (!$project) {
                return response()->json([
                    'success' => false,
                    'message' => 'Project not found'
                ], 404);
            }
            
            return response()->json([
                'success' => true,
                'project' => $project
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching project: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a new project.
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'client' => 'required|string|max:255',
                'owner' => 'required|string|max:255',
                'team' => 'required|string|max:255',
                'status' => 'required|in:pending,in-progress,review,completed',
                'priority' => 'required|in:low,medium,high',
                'start_date' => 'required|date',
                'deadline' => 'required|date|after_or_equal:start_date',
                'progress' => 'required|integer|min:0|max:100',
                
                'description' => 'nullable|string',
            ]);
            
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }
            
            // Generate project details based on inputs
            $projectData = $request->only([
                'name', 'client', 'owner', 'team', 'status', 'priority',
                'start_date', 'deadline', 'progress', 'budget', 'description'
            ]);
            
            // Generate additional details
            $additionalDetails = $this->generateProjectDetails($projectData);
            $projectData = array_merge($projectData, $additionalDetails);
            
            $project = ProjectManagement::create($projectData);
            
            return response()->json([
                'success' => true,
                'message' => 'Project created successfully',
                'project' => $project
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error creating project: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update an existing project.
     */
    public function update(Request $request, $id)
    {
        try {
           $project = ProjectManagement::where('company_id', auth()->user()->company_id)
    ->findOrFail($id);

$this->authorize('manage', $project);

            
            if (!$project) {
                return response()->json([
                    'success' => false,
                    'message' => 'Project not found'
                ], 404);
            }
            
            $validator = Validator::make($request->all(), [
                'name' => 'sometimes|required|string|max:255',
                'client' => 'sometimes|required|string|max:255',
                'owner' => 'sometimes|required|string|max:255',
                'team' => 'sometimes|required|string|max:255',
                'status' => 'sometimes|required|in:pending,in-progress,review,completed',
                'priority' => 'sometimes|required|in:low,medium,high',
                'start_date' => 'sometimes|required|date',
                'deadline' => 'sometimes|required|date|after_or_equal:start_date',
                'progress' => 'sometimes|required|integer|min:0|max:100',
                
                'description' => 'nullable|string',
            ]);
            
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }
            
            $project->update($request->all());
            
            return response()->json([
                'success' => true,
                'message' => 'Project updated successfully',
                'project' => $project
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating project: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete a project.
     */
    public function destroy($id)
    {
        try {
           $project = ProjectManagement::where('company_id', auth()->user()->company_id)
    ->findOrFail($id);

$this->authorize('manage', $project);

            
            if (!$project) {
                return response()->json([
                    'success' => false,
                    'message' => 'Project not found'
                ], 404);
            }
            
            $project->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Project deleted successfully'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting project: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get dashboard statistics.
     */
    public function getStatistics()
    {
        try {
            $base = ProjectManagement::where('company_id', auth()->user()->company_id);
            
            $totalProjects = (clone $base)->count();
            $inProgressCount = (clone $base)->where('status', 'in-progress')->count();
            $completedCount = (clone $base)->where('status', 'completed')->count();
            $highPriorityCount = (clone $base)->where('priority', 'high')->count();
            
            return response()->json([
                'success' => true,
                'statistics' => [
                    'total_projects' => $totalProjects,
                    'in_progress' => $inProgressCount,
                    'completed' => $completedCount,
                    'high_priority' => $highPriorityCount
                ]
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching statistics: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get filter options.
     */
    public function getFilters()
    {
        try {
            $teams = ProjectManagement::where('company_id', auth()->user()->company_id)
                ->pluck('team')
                ->toArray();
            
            $clients = ProjectManagement::where('company_id', auth()->user()->company_id)
                ->pluck('client')
                ->toArray();
            
            $owners = ProjectManagement::where('company_id', auth()->user()->company_id)
                ->pluck('owner')
                ->toArray();
            
            return response()->json([
                'success' => true,
                'filters' => [
                    'teams' => $teams,
                    'clients' => $clients,
                    'owners' => $owners,
                    'statuses' => ['pending', 'in-progress', 'review', 'completed'],
                    'priorities' => ['low', 'medium', 'high']
                ]
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching filters: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Generate project details based on input data.
     */
    private function generateProjectDetails($projectData)
    {
        // Generate client details
        $clientDetails = [
            'name' => $projectData['client'] ?? 'Client',
            'business' => $projectData['client'] ?? '',
            'email' => strtolower(str_replace(' ', '.', $projectData['client'] ?? '')) . '@example.com',
            'phone' => '+1 (555) ' . rand(100, 999) . '-' . rand(1000, 9999),
            'website' => 'www.' . strtolower(preg_replace('/[^a-z0-9]/', '', $projectData['client'] ?? '')) . '.com',
            'address' => rand(100, 999) . ' ' . ['Main', 'Oak', 'Maple', 'Cedar', 'Pine'][rand(0, 4)] . ' St, ' . 
                        ['New York', 'Los Angeles', 'Chicago', 'Houston', 'Phoenix'][rand(0, 4)]
        ];

        // Generate owner details
        $ownerInitials = implode('', array_map(function($name) {
            return strtoupper(substr($name, 0, 1));
        }, explode(' ', $projectData['owner'] ?? '')));
        
        $ownerDetails = [
            'name' => $projectData['owner'] ?? '',
            'designation' => ($projectData['team'] ?? '') . ' ' . ['Manager', 'Lead', 'Specialist', 'Director'][rand(0, 3)],
            'email' => strtolower(str_replace(' ', '.', $projectData['owner'] ?? '')) . '@marketingcrm.com',
            'phone' => '+1 (555) ' . rand(100, 999) . '-' . rand(1000, 9999),
            'department' => $this->getDepartmentByTeam($projectData['team'] ?? ''),
            'avatar' => $ownerInitials
        ];

        // Generate team members based on team
        $teamMembers = $this->generateTeamMembers($projectData['team'] ?? '');

        // Generate timeline
        $timeline = $this->generateTimeline(
            $projectData['start_date'] ?? date('Y-m-d'),
            $projectData['deadline'] ?? date('Y-m-d', strtotime('+1 month'))
        );

        // Generate tasks
        $tasks = $this->generateTasks(
            $projectData['team'] ?? '',
            $projectData['owner'] ?? '',
            $teamMembers
        );

        return [
            'client_details' => $clientDetails,
            'owner_details' => $ownerDetails,
            'team_members' => $teamMembers,
            'timeline' => $timeline,
            'tasks' => $tasks
        ];
    }

    private function getDepartmentByTeam($team)
    {
        $teamMap = [
            'SMM' => 'Social Media Marketing',
            'Web' => 'Web Development', 
            'SEO' => 'Search Engine Optimization',
            'Ads' => 'Digital Advertising',
            'Content' => 'Content Marketing',
            'Email' => 'Email Marketing'
        ];
        return $teamMap[$team] ?? 'Digital Marketing';
    }

    private function generateTeamMembers($team)
    {
        $teamTemplates = [
            'SMM' => [
                ['name' => 'Sophia Lee', 'role' => 'Content Creator', 'team' => 'SMM', 'status' => 'active'],
                ['name' => 'Michael Brown', 'role' => 'Graphic Designer', 'team' => 'SMM', 'status' => 'active'],
                ['name' => 'Olivia Davis', 'role' => 'Analytics Specialist', 'team' => 'SMM', 'status' => 'active']
            ],
            'Web' => [
                ['name' => 'Daniel Kim', 'role' => 'Frontend Developer', 'team' => 'Web', 'status' => 'active'],
                ['name' => 'Lisa Wang', 'role' => 'Backend Developer', 'team' => 'Web', 'status' => 'active'],
                ['name' => 'Robert Taylor', 'role' => 'UI/UX Designer', 'team' => 'Web', 'status' => 'active']
            ],
            'SEO' => [
                ['name' => 'Amanda Rodriguez', 'role' => 'Content Strategist', 'team' => 'SEO', 'status' => 'active'],
                ['name' => 'Kevin Patel', 'role' => 'Technical SEO', 'team' => 'SEO', 'status' => 'active'],
                ['name' => 'Jessica Carter', 'role' => 'Link Building Specialist', 'team' => 'SEO', 'status' => 'active']
            ],
            'Ads' => [
                ['name' => 'Brian Wilson', 'role' => 'PPC Specialist', 'team' => 'Ads', 'status' => 'active'],
                ['name' => 'Natalie Chen', 'role' => 'Media Buyer', 'team' => 'Ads', 'status' => 'active'],
                ['name' => 'Thomas Reed', 'role' => 'Ad Copywriter', 'team' => 'Ads', 'status' => 'active']
            ]
        ];

        return $teamTemplates[$team] ?? [
            ['name' => 'Team Member 1', 'role' => 'Specialist', 'team' => $team, 'status' => 'active'],
            ['name' => 'Team Member 2', 'role' => 'Coordinator', 'team' => $team, 'status' => 'active']
        ];
    }

    private function generateTimeline($startDate, $deadline)
    {
        $start = new \DateTime($startDate);
        $end = new \DateTime($deadline);
        $duration = $end->diff($start)->days;

        $phases = [
            ['phase' => 'Planning', 'status' => 'completed'],
            ['phase' => 'Execution', 'status' => 'in-progress'],
            ['phase' => 'Review', 'status' => 'pending'],
            ['phase' => 'Delivery', 'status' => 'pending']
        ];

        $phaseDuration = floor($duration / count($phases));
        $timeline = [];

        foreach ($phases as $index => $phase) {
            $phaseStart = clone $start;
            $phaseStart->modify("+ " . ($phaseDuration * $index) . " days");
            
            $phaseEnd = clone $phaseStart;
            $phaseEnd->modify("+ " . $phaseDuration . " days");

            $timeline[] = [
                'phase' => $phase['phase'],
                'startDate' => $phaseStart->format('Y-m-d'),
                'endDate' => $phaseEnd->format('Y-m-d'),
                'status' => $phase['status']
            ];
        }

        return $timeline;
    }

    private function generateTasks($team, $owner, $teamMembers)
    {
        $taskTemplates = [
            'SMM' => [
                ['title' => 'Content Calendar Creation', 'priority' => 'medium', 'status' => 'completed'],
                ['title' => 'Social Media Posts Creation', 'priority' => 'high', 'status' => 'in-progress'],
                ['title' => 'Influencer Outreach', 'priority' => 'high', 'status' => 'pending'],
                ['title' => 'Performance Report', 'priority' => 'medium', 'status' => 'pending']
            ],
            'Web' => [
                ['title' => 'Design Mockups', 'priority' => 'high', 'status' => 'completed'],
                ['title' => 'Frontend Development', 'priority' => 'medium', 'status' => 'in-progress'],
                ['title' => 'Backend Integration', 'priority' => 'medium', 'status' => 'pending'],
                ['title' => 'User Testing', 'priority' => 'medium', 'status' => 'pending']
            ],
            'SEO' => [
                ['title' => 'Website Audit', 'priority' => 'high', 'status' => 'completed'],
                ['title' => 'Keyword Research', 'priority' => 'medium', 'status' => 'in-progress'],
                ['title' => 'On-page Optimization', 'priority' => 'high', 'status' => 'in-progress'],
                ['title' => 'Competitor Analysis', 'priority' => 'medium', 'status' => 'pending']
            ]
        ];

        $tasks = $taskTemplates[$team] ?? [
            ['title' => 'Project Planning', 'priority' => 'medium', 'status' => 'completed'],
            ['title' => 'Task Execution', 'priority' => 'high', 'status' => 'in-progress'],
            ['title' => 'Quality Check', 'priority' => 'medium', 'status' => 'pending'],
            ['title' => 'Client Review', 'priority' => 'medium', 'status' => 'pending']
        ];

        // Assign tasks to team members
        foreach ($tasks as $index => &$task) {
            $assignedTo = $owner;
            if (!empty($teamMembers) && isset($teamMembers[$index])) {
                $assignedTo = $teamMembers[$index]['name'];
            }

            // Generate a due date (7-14 days from now)
            $dueDate = new \DateTime();
            $dueDate->modify("+ " . (7 + ($index * 2)) . " days");

            $task['assignedTo'] = $assignedTo;
            $task['dueDate'] = $dueDate->format('Y-m-d');
        }

        return $tasks;
    }
}

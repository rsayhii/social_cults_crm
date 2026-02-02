<?php

namespace App\Http\Controllers;

use App\Models\Note;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class NoteController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // =========================
    // DASHBOARD VIEW
    // =========================
    public function index()
    {
        return view('admin.notepad');
    }

    // =========================
    // GET NOTES (WITH VISIBILITY)
    // =========================
    public function getNotes(Request $request)
    {
        $user = Auth::user();

      $query = Note::with('user')
    ->where('company_id', auth()->user()->company_id)
    ->where(function ($q) use ($user) {

        if ($user->hasRole('admin')) {
            return;
        }

        $q->whereIn('visibility', ['public', 'team'])

          ->orWhere(function ($q) use ($user) {
              $q->where('visibility', 'private')
                ->where('user_id', $user->id);
          })
          ->orWhere(function ($q) use ($user) {
              $q->where('visibility', 'team')
                ->whereJsonContains('teams', $user->teams ?? []);
          });
    });


        // -------- FILTERS --------
        if ($request->filled('filter') && $request->filter !== 'all') {
            switch ($request->filter) {
                case 'my':
                    $query->where('user_id', $user->id);
                    break;

                case 'team':
                    $query->where('visibility', 'team')
                          ->whereJsonContains('teams', $user->teams ?? []);
                    break;

                case 'pinned':
                    $query->where('pinned', true);
                    break;

                case 'recent':
                    $query->where('updated_at', '>=', now()->subDays(7));
                    break;
            }
        }

        // Category
        if ($request->filled('category') && $request->category !== 'all') {
            $query->where('category', $request->category);
        }

        // Visibility
        if ($request->filled('visibility') && $request->visibility !== 'all') {
            $query->where('visibility', $request->visibility);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%");
            });
        }

        // Sorting
        $query->orderBy(
            $request->get('sort_by', 'created_at'),
            $request->get('sort_order', 'desc')
        );

        $notes = $query->get()->map(function ($note) {
            return [
                'id' => $note->id,
                'title' => $note->title,
                'content' => $note->content,
                'category' => $note->category,
                'tags' => $note->tags,
                'visibility' => $note->visibility,
                'teams' => $note->teams,
                'related_client' => $note->related_client,
                'related_project' => $note->related_project,
                'related_task' => $note->related_task,
                'pinned' => $note->pinned,
                'created_by' => $note->user->name,
                'created_by_id' => $note->user_id,
                'created_at' => $note->formatted_created_at,
                'updated_at' => $note->formatted_updated_at,
                'can_edit' => auth()->id() === $note->user_id || auth()->user()->role === 'admin',
                'can_delete' => auth()->id() === $note->user_id || auth()->user()->role === 'admin',
            ];
        });

        return response()->json($notes);
    }

    // =========================
    // STATS
    // =========================
    public function getStats()
    {
        $user = Auth::user();

       $baseQuery = Note::where('company_id', auth()->user()->company_id)
    ->where(function ($q) use ($user) {
        if ($user->hasRole('admin')) return;

        $q->whereIn('visibility', ['public', 'team'])

          ->orWhere('user_id', $user->id)
          ->orWhere(function ($q) use ($user) {
              $q->where('visibility', 'team')
                ->whereJsonContains('teams', $user->teams ?? []);
          });
    });


        return response()->json([
            'total' => $baseQuery->count(),
           'my_notes' => Note::where('company_id', $user->company_id)
                  ->where('user_id', $user->id)
                  ->count(),

            'team_notes' => $baseQuery->where('visibility', 'team')->count(),
            'pinned' => $baseQuery->where('pinned', true)->count(),
            'recent' => $baseQuery->where('updated_at', '>=', now()->subDays(7))->count(),
        ]);
    }

    // =========================
    // SHOW SINGLE NOTE
    // =========================
    public function show($id)
    {
        $note = Note::where('company_id', auth()->user()->company_id)
            ->with('user')
            ->findOrFail($id);

$this->authorize('manage', $note);


        if (!$this->canViewNote($note)) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        return response()->json($note);
    }

    // =========================
    // CREATE NOTE
    // =========================
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category' => 'required',
            'visibility' => 'required|in:private,team,public',
            'tags' => 'nullable|array',
            'teams' => 'nullable|array',
            'pinned' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $note = Note::create([
            'title' => $request->title,
            'content' => $request->content,
            'category' => $request->category,
            'visibility' => $request->visibility,
            'tags' => $request->tags,
            'teams' => $request->teams,
            'related_client' => $request->related_client,
            'related_project' => $request->related_project,
            'related_task' => $request->related_task,
            'pinned' => $request->pinned ?? false,
            'user_id' => Auth::id(),
        ]);

        return response()->json(['success' => true, 'note' => $note]);
    }

    // =========================
    // UPDATE NOTE
    // =========================
    public function update(Request $request, $id)
    {
        $note = Note::where('company_id', auth()->user()->company_id)
            ->findOrFail($id);

$this->authorize('manage', $note);


      

        $note->update($request->only([
            'title',
            'content',
            'category',
            'visibility',
            'tags',
            'teams',
            'related_client',
            'related_project',
            'related_task',
            'pinned',
        ]));

        return response()->json(['success' => true]);
    }

    // =========================
    // DELETE NOTE
    // =========================
    public function destroy($id)
    {
        $note = Note::where('company_id', auth()->user()->company_id)
            ->findOrFail($id);

$this->authorize('manage', $note);


      

        $note->delete();

        return response()->json(['success' => true]);
    }

    // =========================
    // TOGGLE PIN
    // =========================
    public function togglePin($id)
    {
        $note = Note::where('company_id', auth()->user()->company_id)
            ->findOrFail($id);

$this->authorize('manage', $note);

        

        $note->update(['pinned' => !$note->pinned]);

        return response()->json([
            'success' => true,
            'pinned' => $note->pinned
        ]);
    }

    // =========================
    // PERMISSION CHECK
    // =========================
   private function canViewNote(Note $note)
{
    $user = Auth::user();

    if ($user->hasRole('admin')) return true;

    if ($note->visibility === 'public') return true;

    if ($note->visibility === 'private' && $note->user_id === $user->id) {
        return true;
    }

    if ($note->visibility === 'team') {
        return count(array_intersect(
            $user->teams ?? [],
            $note->teams ?? []
        )) > 0;
    }

    return false;
}

}

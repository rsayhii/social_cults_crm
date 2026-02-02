<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Holiday;
use App\Models\HolidayOverride;
use Illuminate\Support\Facades\Auth;

class HolidayController extends Controller
{
    /**
     * Admin creates custom holiday
     */
    public function store(Request $request)
    {
        $this->authorize('manage-holidays');

        $data = $request->validate([
            'date' => 'required|date',
            'name' => 'required|string',
            'local_name' => 'nullable|string',
            'country_code' => 'nullable|string|max:3',
            'type' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $holiday = Holiday::create(array_merge($data, [
            'seeded' => false
        ]));

        return response()->json([
            'message' => 'Holiday created successfully',
            'holiday' => $holiday
        ], 201);
    }


    /**
     * Admin toggles "Mark as Working Day" override
     */
    public function toggleMarkWorking(Holiday $holiday)
    {
        $this->authorize('manage-holidays');

        $override = $holiday->override ?? new HolidayOverride([
            'holiday_id' => $holiday->id,
            'created_by' => Auth::id(),
        ]);

        $override->mark_working = !$override->mark_working;
        $override->save();

        return response()->json([
            'message' => 'Holiday working status updated',
            'override' => $override
        ]);
    }
}

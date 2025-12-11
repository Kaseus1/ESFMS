<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Facility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class AdminFacilityController extends Controller
{
    public function index()
    {
        try {
            $now = now();
            
            // Count total facilities (excluding soft deleted)
            $totalFacilities = Facility::count();
            
            // Count available facilities (no CURRENT active bookings)
            $availableFacilities = Facility::where('is_public', true)
                ->whereDoesntHave('reservations', function($q) use ($now) {
                    $q->where('status', 'approved')
                      ->where('start_time', '<=', $now)
                      ->where('end_time', '>=', $now);
                })
                ->count();
            
            // Count booked facilities (has CURRENT active bookings)
            $bookedFacilities = Facility::whereHas('reservations', function($q) use ($now) {
                $q->where('status', 'approved')
                  ->where('start_time', '<=', $now)
                  ->where('end_time', '>=', $now);
            })->count();
            
            Log::info('Facility Counts', [
                'current_time' => $now->toDateTimeString(),
                'total' => $totalFacilities,
                'available' => $availableFacilities,
                'booked' => $bookedFacilities,
            ]);

            return view('admin.facilities.index', compact(
                'totalFacilities',
                'availableFacilities', 
                'bookedFacilities'
            ));
        } catch (\Exception $e) {
            Log::error('Facility index error: ' . $e->getMessage());
            return back()->with('error', 'Failed to load facilities: ' . $e->getMessage());
        }
    }

    public function ajax(Request $request)
    {
        try {
            $now = now();
            
            // Use withTrashed() to show deleted facilities
            $query = Facility::withTrashed()->with(['reservations' => function($q) use ($now) {
                $q->where('status', 'approved')
                  ->where('end_time', '>=', $now)
                  ->orderBy('start_time', 'asc');
            }]);

            // Search filter
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('name', 'LIKE', "%{$search}%")
                      ->orWhere('location', 'LIKE', "%{$search}%")
                      ->orWhere('type', 'LIKE', "%{$search}%");
                });
            }

            // Type filter
            if ($request->filled('type') && $request->type !== 'all') {
                $query->where('type', $request->type);
            }

            // Status filter
            if ($request->filled('status')) {
                if ($request->status === 'available') {
                    $query->where('is_public', true)
                        ->whereDoesntHave('reservations', function($q) use ($now) {
                            $q->where('status', 'approved')
                              ->where('start_time', '<=', $now)
                              ->where('end_time', '>=', $now);
                        });
                } elseif ($request->status === 'booked') {
                    $query->whereHas('reservations', function($q) use ($now) {
                        $q->where('status', 'approved')
                          ->where('start_time', '<=', $now)
                          ->where('end_time', '>=', $now);
                    });
                }
            }

            $facilities = $query->paginate(12);

            $transformedFacilities = $facilities->map(function($facility) use ($now) {
                $nextReservation = $facility->reservations->first();
                
                // Calculate status dynamically
                $hasActiveReservation = $facility->reservations()
                    ->where('status', 'approved')
                    ->where('start_time', '<=', $now)
                    ->where('end_time', '>=', $now)
                    ->exists();
                
                $statusBadge = $facility->trashed() 
                    ? '<span class="px-2 py-1 text-xs font-bold text-red-800 bg-red-100 rounded-full">Deleted</span>'
                    : ($hasActiveReservation
                        ? '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">Booked</span>'
                        : '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Available</span>');

                return [
                    'id' => $facility->id,
                    'name' => $facility->name,
                    'location' => $facility->location ?? 'N/A',
                    'type' => ucfirst(str_replace('_', ' ', $facility->type)),
                    'capacity' => $facility->capacity ?? 'N/A',
                    'max_capacity' => $facility->max_capacity ?? 'N/A',
                    'opening_time' => $facility->opening_time ? date('g:i A', strtotime($facility->opening_time)) : 'N/A',
                    'closing_time' => $facility->closing_time ? date('g:i A', strtotime($facility->closing_time)) : 'N/A',
                    'image_url' => $facility->image 
                        ? Storage::disk('public')->url($facility->image) 
                        : asset('images/default-facility.jpg'),
                    'status_badge' => $statusBadge,
                    'type_badge' => $facility->getTypeBadge(),
                    'next_reservation' => $nextReservation
                        ? '<span class="text-gray-700">' . date('M d, g:i A', strtotime($nextReservation->start_time)) . '</span>'
                        : '<span class="text-gray-400">None scheduled</span>',
                    'view_url' => route('admin.facilities.show', $facility->id),
                    'edit_url' => route('admin.facilities.edit', $facility->id),
                    'book_url' => route('admin.reservations.create', ['facility_id' => $facility->id]),
                    'is_deleted' => $facility->trashed(),
                ];
            });

            // Recalculate counts with same logic
            $totalCount = Facility::count();
            $availableCount = Facility::where('is_public', true)
                ->whereDoesntHave('reservations', function($q) use ($now) {
                    $q->where('status', 'approved')
                      ->where('start_time', '<=', $now)
                      ->where('end_time', '>=', $now);
                })
                ->count();
            $bookedCount = Facility::whereHas('reservations', function($q) use ($now) {
                $q->where('status', 'approved')
                  ->where('start_time', '<=', $now)
                  ->where('end_time', '>=', $now);
            })->count();

            return response()->json([
                'facilities' => $transformedFacilities,
                'pagination' => [
                    'current_page' => $facilities->currentPage(),
                    'last_page' => $facilities->lastPage(),
                    'per_page' => $facilities->perPage(),
                    'total' => $facilities->total(),
                    'from' => $facilities->firstItem(),
                    'to' => $facilities->lastItem(),
                ],
                'counts' => [
                    'total' => $totalCount,
                    'available' => $availableCount,
                    'booked' => $bookedCount,
                ],
            ]);
        } catch (\Exception $e) {
            Log::error('Facility ajax error: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to load facilities'], 500);
        }
    }

    public function create()
    {
        try {
            return view('admin.facilities.create');
        } catch (\Exception $e) {
            Log::error('Facility create error: ' . $e->getMessage());
            return back()->with('error', 'Failed to load facility creation page: ' . $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => [
                    'required',
                    'string',
                    'max:255',
                    Rule::unique('facilities', 'name')
                ],
                'type' => [
                    'required',
                    'string',
                    'in:sports_facility,meeting_room,conference_hall,conference_room,office_space,classroom,training_room,auditorium,laboratory,event_space,parking_lot,other'
                ],
                'description' => 'nullable|string|max:1000',
                'location' => 'required|string|max:255',
                'hourly_rate' => 'required|numeric|min:0',
                'capacity' => 'required|integer|min:1',
                'max_capacity' => 'nullable|integer|gt:capacity',
                'opening_time' => 'required|date_format:H:i',
                'closing_time' => 'required|date_format:H:i|after:opening_time',
                'buffer_time' => 'nullable|integer|min:0',
                'is_public' => 'nullable|boolean',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
                'amenities' => 'nullable|json',
                'rules' => 'nullable|string|max:1000',
            ]);

            // Set default values
            $validated['is_public'] = $request->has('is_public') ? true : false;
            $validated['buffer_time'] = $validated['buffer_time'] ?? 30;

            if ($request->hasFile('image')) {
                $validated['image'] = $request->file('image')->store('facilities', 'public');
            }

            $facility = Facility::create($validated);

            return redirect()->route('admin.facilities.show', $facility->id)
                ->with('success', 'Facility created successfully!');

        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            Log::error('Facility store error: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Failed to create facility: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        try {
            $facility = Facility::withTrashed()->findOrFail($id);

            $now = now();
            $facility->load(['reservations' => function($q) use ($now) {
                $q->where('status', 'approved')
                  ->where('end_time', '>=', $now)
                  ->orderBy('start_time', 'asc')
                  ->limit(5);
            }]);

            $facilityStats = [
                'total_reservations' => $facility->reservations()->count(),
                'approved_reservations' => $facility->reservations()->where('status', 'approved')->count(),
                'pending_reservations' => $facility->reservations()->where('status', 'pending')->count(),
                'rejected_reservations' => $facility->reservations()->where('status', 'rejected')->count(),
            ];

            return view('admin.facilities.show', compact('facility', 'facilityStats'));
        } catch (\Exception $e) {
            Log::error('Facility show error: ' . $e->getMessage());
            return back()->with('error', 'Failed to load facility details: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        try {
            $facility = Facility::withTrashed()->findOrFail($id);
            
            if ($facility->trashed()) {
                return back()->with('error', 'Cannot edit a deleted facility. Please restore it first.');
            }

            return view('admin.facilities.edit', compact('facility'));
        } catch (\Exception $e) {
            Log::error('Facility edit error: ' . $e->getMessage());
            return back()->with('error', 'Failed to load facility edit page: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $facility = Facility::withTrashed()->findOrFail($id);
            
            if ($facility->trashed()) {
                return back()->with('error', 'Cannot update a deleted facility. Please restore it first.');
            }

            $validated = $request->validate([
                'name' => [
                    'required',
                    'string',
                    'max:255',
                    Rule::unique('facilities')->ignore($facility->id)
                ],
                'type' => [
                    'required',
                    'string',
                    'in:sports_facility,meeting_room,conference_hall,conference_room,office_space,classroom,training_room,auditorium,laboratory,event_space,parking_lot,other'
                ],
                'description' => 'nullable|string|max:1000',
            'location' => 'required|string|max:255',
            'hourly_rate' => 'required|numeric|min:0',  // <-- ADD THIS LINE
            'capacity' => 'required|integer|min:1',
            'max_capacity' => 'nullable|integer|gte:capacity',  // Changed gt to gte
            'opening_time' => 'required|date_format:H:i',
            'closing_time' => 'required|date_format:H:i|after:opening_time',
            'buffer_time' => 'nullable|integer|min:0',
            'status' => 'required|boolean',  // <-- ADD THIS LINE (for status field)
            'is_public' => 'required|boolean',  // Changed from nullable
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'amenities' => 'nullable|json',
            'rules' => 'nullable|string|max:1000',
            ]);

            // Set default values for optional fields
            $validated['buffer_time'] = $validated['buffer_time'] ?? 30;
            $validated['is_public'] = $request->has('is_public') ? (bool)$request->is_public : false;

            if ($request->hasFile('image')) {
                if ($facility->image) {
                    Storage::disk('public')->delete($facility->image);
                }
                $validated['image'] = $request->file('image')->store('facilities', 'public');
            }

            $facility->update($validated);

            return redirect()->route('admin.facilities.show', $facility->id)
                ->with('success', 'Facility updated successfully!');

        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            Log::error('Facility update error: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Failed to update facility: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $facility = Facility::withTrashed()->findOrFail($id);

            if ($facility->trashed()) {
                // If already in trash, Force Delete (Permanent)
                if ($facility->image) {
                    Storage::disk('public')->delete($facility->image);
                }
                $facility->forceDelete();
                $message = 'Facility permanently deleted!';
            } else {
                // Soft delete
                $facility->delete();
                $message = 'Facility moved to trash successfully!';
            }

            return redirect()->route('admin.facilities.index')
                ->with('success', $message);
                
        } catch (\Exception $e) {
            Log::error('Facility delete error: ' . $e->getMessage());
            return back()->with('error', 'Failed to delete facility: ' . $e->getMessage());
        }
    }

    public function restore($id)
    {
        try {
            $facility = Facility::withTrashed()->findOrFail($id);
        
            if ($facility->trashed()) {
                $facility->restore();
                return redirect()->route('admin.facilities.show', $facility->id)
                    ->with('success', 'Facility restored successfully.');
            }
        
            return redirect()->route('admin.facilities.show', $facility->id)
                ->with('error', 'Facility is already active.');
        } catch (\Exception $e) {
            Log::error('Facility restore error: ' . $e->getMessage());
            return back()->with('error', 'Failed to restore facility: ' . $e->getMessage());
        }
    }
}
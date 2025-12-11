<?php

// Enhanced FacultyFacilityController - Matching Student System
// File: app/Http/Controllers/Faculty/FacultyFacilityController.php

namespace App\Http\Controllers\Faculty;

use App\Http\Controllers\Controller;
use App\Models\Facility;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;

class FacultyFacilityController extends Controller
{
    /**
     * Display a listing of facilities with search and filters (Student System Style)
     */
    public function index(Request $request)
    {
        $now = now();
        
        // Base query with eager loading to prevent N+1 queries
        $query = Facility::where('is_public', true)
            ->with(['reservations' => function($q) use ($now) {
                $q->where('status', 'approved')
                  ->where('end_time', '>=', $now)
                  ->orderBy('start_time', 'asc')
                  ->limit(1);
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
                $query->whereDoesntHave('reservations', function($q) use ($now) {
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

        // Paginate facilities WITH query string preservation
        $facilities = $query->orderBy('name')->paginate(15)->withQueryString();

        // Calculate statistics
        $totalFacilities = Facility::where('is_public', true)->count();
        $availableFacilities = Facility::where('is_public', true)
            ->whereDoesntHave('reservations', function($q) use ($now) {
                $q->where('status', 'approved')
                  ->where('start_time', '<=', $now)
                  ->where('end_time', '>=', $now);
            })
            ->count();
        $bookedFacilities = Facility::where('is_public', true)
            ->whereHas('reservations', function($q) use ($now) {
                $q->where('status', 'approved')
                  ->where('start_time', '<=', $now)
                  ->where('end_time', '>=', $now);
            })
            ->count();

        // CRITICAL: Return ALL variables including $facilities
        return view('faculty.facilities.index', compact(
            'facilities',
            'totalFacilities', 
            'availableFacilities', 
            'bookedFacilities'
        ));
    }

    /**
     * AJAX endpoint for dynamic filtering (Student System Style)
     */
    public function ajax(Request $request): JsonResponse
    {
        try {
            $now = now();
            
            $query = Facility::where('is_public', true)
                ->with(['reservations' => function($q) use ($now) {
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

            // Status filter - calculate dynamically
            if ($request->filled('status')) {
                if ($request->status === 'available') {
                    $query->whereDoesntHave('reservations', function($q) use ($now) {
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

            $facilities = $query->orderBy('name')->paginate(12);

            $formattedFacilities = $facilities->map(function ($facility) use ($now) {
                $nextReservation = $facility->reservations->first();
                
                // Calculate status dynamically
                $hasActiveReservation = $facility->reservations()
                    ->where('status', 'approved')
                    ->where('start_time', '<=', $now)
                    ->where('end_time', '>=', $now)
                    ->exists();

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
                    'status_badge' => $hasActiveReservation
                        ? '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">Booked</span>'
                        : '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Available</span>',
                    'type_badge' => $this->getTypeBadge($facility->type),
                    'next_reservation' => $nextReservation
                        ? '<span class="text-gray-700 text-sm">' . date('M d, g:i A', strtotime($nextReservation->start_time)) . '</span>'
                        : '<span class="text-green-600 text-sm">No upcoming reservations</span>',
                    'view_url' => route('faculty.facilities.show', $facility->id),
                    'book_url' => route('faculty.reservations.create', ['facility_id' => $facility->id]),
                ];
            });

            // Recalculate counts
            $totalCount = Facility::where('is_public', true)->count();
            $availableCount = Facility::where('is_public', true)
                ->whereDoesntHave('reservations', function($q) use ($now) {
                    $q->where('status', 'approved')
                      ->where('start_time', '<=', $now)
                      ->where('end_time', '>=', $now);
                })
                ->count();
            $bookedCount = Facility::where('is_public', true)
                ->whereHas('reservations', function($q) use ($now) {
                    $q->where('status', 'approved')
                      ->where('start_time', '<=', $now)
                      ->where('end_time', '>=', $now);
                })
                ->count();

            return response()->json([
                'facilities' => $formattedFacilities,
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
            \Log::error('Faculty facility ajax error: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to load facilities'], 500);
        }
    }
    public function cancel(Request $request, Reservation $reservation)
    {
        // Check if user owns this reservation
        if ($reservation->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
        
        // Only allow cancellation of pending or approved reservations
        if (!in_array($reservation->status, ['pending', 'approved'])) {
            return redirect()->back()->with('error', 'Only pending or approved reservations can be cancelled.');
        }
        
        // Optional: Validate cancellation reason
        $request->validate([
            'cancellation_reason' => 'nullable|string|max:500'
        ]);
        
        // Update status to cancelled with metadata
        $reservation->update([
            'status' => 'cancelled',
            'cancellation_reason' => $request->cancellation_reason ?? 'Cancelled by user',
            'cancelled_at' => now()
        ]);
        
        return redirect()->route('faculty.reservations.index')
            ->with('success', 'Reservation cancelled successfully.');
    }

    /**
     * Display the specified facility
     */
    public function show(Facility $facility)
    {
        if (!$facility->is_public) {
            abort(404, 'Facility not found.');
        }

        $now = now();
        $facility->load(['reservations' => function($query) use ($now) {
            $query->where('status', 'approved')
                  ->where('end_time', '>=', $now)
                  ->orderBy('start_time')
                  ->limit(5);
        }]);

        return view('faculty.facilities.show', compact('facility'));
    }

    /**
     * Get badge HTML for facility type
     */
    private function getTypeBadge(string $type): string
    {
        $types = [
            'classroom' => ['label' => 'Classroom', 'color' => 'blue'],
            'conference_room' => ['label' => 'Conference', 'color' => 'purple'],
            'auditorium' => ['label' => 'Auditorium', 'color' => 'indigo'],
            'laboratory' => ['label' => 'Laboratory', 'color' => 'pink'],
            'sports_facility' => ['label' => 'Sports', 'color' => 'orange'],
            'other' => ['label' => 'Other', 'color' => 'gray'],
        ];

        $typeInfo = $types[$type] ?? ['label' => ucfirst($type), 'color' => 'gray'];
        
        return sprintf(
            '<span class="px-2 py-1 text-xs font-medium rounded bg-%s-100 text-%s-800">%s</span>',
            $typeInfo['color'],
            $typeInfo['color'],
            $typeInfo['label']
        );
    }
}
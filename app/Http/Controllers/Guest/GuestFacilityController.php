<?php
namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use App\Models\Facility; // Keep only this one import for Facility
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;

class GuestFacilityController extends Controller
{
    
public function index()
    {
        // Define $now here since it's used for all calculations
        $now = now();

        // ------------------------------------------
        // 1. Calculate Facility Counts
        // ------------------------------------------
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

        // ------------------------------------------
        // 2. Load Initial Facility List (THIS IS THE FIX)
        // ------------------------------------------
        $facilities = Facility::where('is_public', true)
            ->with(['reservations' => function($q) use ($now) {
                $q->where('status', 'approved')
                  ->where('end_time', '>=', $now)
                  ->orderBy('start_time', 'asc');
            }])
            ->orderBy('name')
            ->paginate(12); // Initial paginated load

        // 3. Pass ALL required variables, including $facilities, to the view
        return view('guest.facilities.index', compact('totalFacilities', 'availableFacilities', 'bookedFacilities', 'facilities'));
    }

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
                    'view_url' => route('guest.facilities.show', $facility->id),
                    'book_url' => route('guest.reservations.create', ['facility_id' => $facility->id]),
                ];
            });

            // Recalculate counts (used for AJAX display)
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
            \Log::error('Guest facility ajax error: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to load facilities'], 500);
        }
    }

    public function show(Facility $facility)
    {
        if (!$facility->is_public) {
            abort(403, 'This facility is not available for booking.');
        }

        $now = now();
        $facility->load(['reservations' => function($query) use ($now) {
            $query->where('status', 'approved')
                  ->where('end_time', '>=', $now)
                  ->orderBy('start_time')
                  ->limit(5);
        }]);

        return view('guest.facilities.show', compact('facility'));
    }

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
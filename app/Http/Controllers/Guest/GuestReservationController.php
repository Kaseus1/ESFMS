<?php

namespace App\Http\Controllers\guest;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use App\Models\Facility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class guestReservationController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $status = $request->query('status');

        // Base query - only include reservations with valid facilities
        $query = Reservation::with(['facility', 'user'])
            ->where('user_id', $user->id)
            ->whereHas('facility');

        if ($status) {
            $query->where('status', $status);
        }

        $reservations = $query->orderBy('start_time', 'desc')
            ->paginate(15)
            ->appends(['status' => $status]);

        // Stats - also filter by valid facilities
        $stats = [
            'total' => Reservation::where('user_id', $user->id)
                ->whereHas('facility')
                ->count(),
            'pending' => Reservation::where('user_id', $user->id)
                ->where('status', 'pending')
                ->whereHas('facility')
                ->count(),
            'approved' => Reservation::where('user_id', $user->id)
                ->where('status', 'approved')
                ->whereHas('facility')
                ->count(),
            'rejected' => Reservation::where('user_id', $user->id)
                ->where('status', 'rejected')
                ->whereHas('facility')
                ->count(),
            'cancelled' => Reservation::where('user_id', $user->id)
                ->where('status', 'cancelled')
                ->whereHas('facility')
                ->count(),
        ];

        return view('guest.reservations.index', compact('reservations', 'stats'));
    }

    public function create(Request $request)
    {
        // Get all public facilities with formatted data for JavaScript
        $facilities = Facility::where('is_public', true)
            ->orderBy('name')
            ->get()
            ->map(function($facility) {
                return [
                    'id' => $facility->id,
                    'name' => $facility->name,
                    'type' => $facility->type,
                    'type_label' => $facility->getTypeLabel(),
                    'location' => $facility->location,
                    'description' => $facility->description,
                    'capacity' => $facility->capacity,
                    'max_capacity' => $facility->max_capacity,
                    'capacity_text' => $facility->getFormattedCapacity(),
                    'opening_time' => $facility->opening_time,
                    'closing_time' => $facility->closing_time,
                    'hours_text' => $facility->getOperatingHours(),
                    'image_url' => $facility->image_url,
                    'buffer_time' => $facility->buffer_time ?? 0,
                    'hourly_rate' => $facility->hourly_rate ?? 0, // ADD RATE
                ];
            });

        $selectedFacilityId = $request->query('facility_id');
        
        // Find selected facility in the mapped collection
        $selectedFacility = $selectedFacilityId 
            ? $facilities->firstWhere('id', (int)$selectedFacilityId) 
            : null;

        // Get existing reservations for the selected facility (if any)
        $existingReservations = collect();
        if ($selectedFacilityId) {
            $existingReservations = Reservation::with('user')
                ->where('facility_id', $selectedFacilityId)
                ->where('start_time', '>', now())
                ->whereNotIn('status', ['rejected', 'cancelled'])
                ->orderBy('start_time')
                ->limit(10)
                ->get();
        }

        $user = Auth::user(); // PASS USER FOR WALLET DISPLAY

        return view('guest.reservations.create', compact('facilities', 'selectedFacility', 'selectedFacilityId', 'existingReservations', 'user'));
    }

    public function store(Request $request)
    {
        // 1. Validate Request
        $request->validate([
            'facility_id' => 'required|exists:facilities,id',
            'event_name' => 'required|string|max:255',
            'participants' => [
                'nullable',
                'integer',
                'min:1',
                function ($attribute, $value, $fail) use ($request) {
                    $facility = Facility::find($request->facility_id);
                    // Check capacity if facility and participants exist
                    if ($facility && $value) {
                        $maxCapacity = $facility->max_capacity ?? $facility->capacity;
                        if ($value > $maxCapacity) {
                            $fail("⚠️ Capacity Alert: {$facility->name} can accommodate maximum {$maxCapacity} participants. You entered {$value}.");
                        }
                    }
                },
            ],
            'notes' => 'nullable|string|max:1000',
            'start_time' => 'required|date|after_or_equal:now',
            'end_time' => 'required|date|after:start_time',
            'is_recurring' => 'nullable|boolean',
            'recurrence_type' => 'nullable|required_if:is_recurring,1|in:daily,weekly,monthly',
        ]);

        $start = Carbon::parse($request->start_time);
        $end = Carbon::parse($request->end_time);
        $facility = Facility::findOrFail($request->facility_id);

        // 2. Check Facility Rules
        if (!$facility->is_public) {
            return back()->withErrors(['facility_id' => 'This facility is private.'])->withInput();
        }

        if ($facility->opening_time && $facility->closing_time) {
            $facilityOpen = $start->copy()->setTimeFromTimeString($facility->opening_time);
            $facilityClose = $start->copy()->setTimeFromTimeString($facility->closing_time);
            
            if ($start->lt($facilityOpen) || $end->gt($facilityClose)) {
                return back()->withErrors(['time' => "Reservation time must be within operating hours ({$facility->opening_time} - {$facility->closing_time})"])->withInput();
            }
        }

        // 3. Generate Slots & Check Conflicts
        $isRecurring = $request->boolean('is_recurring');
        $recurrenceType = $request->recurrence_type;
        $reservationSlots = $this->generateReservationSlots($start, $end, $isRecurring, $recurrenceType);

        $conflicts = [];
        foreach ($reservationSlots as $index => $slot) {
            $conflict = Reservation::where('facility_id', $request->facility_id)
                ->whereNotIn('status', ['rejected', 'cancelled'])
                ->where(function($q) use ($slot) {
                    $q->whereBetween('start_time', [$slot['start'], $slot['end']])
                      ->orWhereBetween('end_time', [$slot['start'], $slot['end']])
                      ->orWhere(function($q2) use ($slot) {
                          $q2->where('start_time', '<', $slot['start'])
                             ->where('end_time', '>', $slot['end']);
                      });
                })->exists();

            if ($conflict) {
                $conflicts[] = $index + 1;
            }
        }

        if (!empty($conflicts)) {
            $conflictMessage = count($conflicts) === count($reservationSlots) 
                ? 'All time slots are already booked.' 
                : 'Some time slots are already booked (occurrences: ' . implode(', ', $conflicts) . '). Only available slots will be created.';
            
            if (count($conflicts) === count($reservationSlots)) {
                return back()->withErrors(['time' => $conflictMessage])->withInput();
            }
            
            $reservationSlots = array_filter($reservationSlots, function($slot, $index) use ($conflicts) {
                return !in_array($index + 1, $conflicts);
            }, ARRAY_FILTER_USE_BOTH);
        }

        // 4. Wallet Check
        $user = Auth::user();
        $totalCost = 0;
        
        foreach ($reservationSlots as $slot) {
            $duration = $slot['start']->diffInHours($slot['end'], true);
            $hours = ceil($duration);
            $totalCost += ($hours * $facility->hourly_rate);
        }

        if (!$user->hasSufficientBalance($totalCost)) {
            return back()
                ->withInput()
                ->with('error', "Insufficient wallet balance. Required: ₱" . number_format($totalCost, 2) . " | Your balance: " . $user->formatted_balance);
        }

        // 5. Process Reservations & Payments
        try {
            DB::beginTransaction();

            $created = 0;
            foreach ($reservationSlots as $slot) {
                $duration = $slot['start']->diffInHours($slot['end'], true);
                $hours = ceil($duration);
                $cost = $hours * $facility->hourly_rate;

                $reservation = Reservation::create([
                    'user_id' => Auth::id(),
                    'facility_id' => $request->facility_id,
                    'event_name' => $request->event_name,
                    'participants' => $request->participants,
                    'notes' => $request->notes,
                    'start_time' => $slot['start'],
                    'end_time' => $slot['end'],
                    
                    // [CHANGED] Default to pending approval
                    'status' => 'pending', 
                    
                    'is_recurring' => $isRecurring,
                    'recurrence_type' => $recurrenceType,
                    'cost' => $cost,
                    
                    // [NOTE] Payment is collected/held immediately
                    'payment_status' => true, 
                ]);

                // Deduct from wallet (Funds are held)
                $user->deductCredits(
                    amount: $cost,
                    reservationId: $reservation->id,
                    reference: 'RES-' . $reservation->id,
                    description: "Payment for {$facility->name} reservation (Pending Approval)"
                );

                $created++;
            }

            DB::commit();

            $message = "Successfully requested {$created} reservation(s). Status is PENDING approval. Cost: ₱" . number_format($totalCost, 2);
            
            return redirect()->route('guest.reservations.index')->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Failed to create reservation: ' . $e->getMessage());
        }
    }
    private function generateReservationSlots($start, $end, $isRecurring, $recurrenceType)
    {
        $slots = [];
        $maxOccurrences = 5;

        // First slot
        $slots[] = [
            'start' => $start->copy(),
            'end' => $end->copy()
        ];

        if ($isRecurring && $recurrenceType) {
            for ($i = 1; $i < $maxOccurrences; $i++) {
                $newStart = $start->copy();
                $newEnd = $end->copy();

                switch ($recurrenceType) {
                    case 'daily':
                        $newStart->addDays($i);
                        $newEnd->addDays($i);
                        break;
                    case 'weekly':
                        $newStart->addWeeks($i);
                        $newEnd->addWeeks($i);
                        break;
                    case 'monthly':
                        $newStart->addMonths($i);
                        $newEnd->addMonths($i);
                        break;
                }

                $slots[] = [
                    'start' => $newStart,
                    'end' => $newEnd
                ];
            }
        }

        return $slots;
    }

    public function show(Reservation $reservation)
    {
        // Check if user owns this reservation
        if ($reservation->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
        
        $reservation->load(['facility', 'user']);
        
        // Load wallet transaction for this reservation
        $transaction = $reservation->user->walletTransactions()
            ->where('reservation_id', $reservation->id)
            ->first();
        
        return view('guest.reservations.show', compact('reservation', 'transaction'));
    }

    public function edit(Reservation $reservation)
    {
        // Check authorization
        if ($reservation->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
        
        // Only allow editing pending reservations
        if (!in_array($reservation->status, ['pending', 'approved'])) {
        return redirect()->route('guest.reservations.index')
            ->with('error', 'Only pending or approved reservations can be edited.');
    }
        
        // Get all facilities
        $facilities = Facility::orderBy('name')->get();
        
        // Get the specific facility for this reservation
        $facility = Facility::findOrFail($reservation->facility_id);
        
        // Get existing reservations for this facility (excluding current reservation)
        $existingReservations = Reservation::with('user')
            ->where('facility_id', $reservation->facility_id)
            ->where('id', '!=', $reservation->id)
            ->where('start_time', '>', now())
            ->whereNotIn('status', ['rejected', 'cancelled'])
            ->orderBy('start_time')
            ->limit(10)
            ->get();
        
        // Pass facility to the view
        return view('guest.reservations.edit', compact(
            'reservation',
            'facilities',
            'facility',
            'existingReservations',
        ));
    }

    public function update(Request $request, Reservation $reservation)
    {
        // Check authorization
        if ($reservation->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
        
        // Only allow updating pending reservations
        if (!in_array($reservation->status, ['pending', 'approved'])) {
        return redirect()->route('guest.reservations.index')
            ->with('error', 'Only pending or approved reservations can be updated.');
        }
        
        // Validate separate date and time fields
        $validatedData = $request->validate([
            'facility_id' => 'required|exists:facilities,id',
            'event_name' => 'required|string|max:255',
            'participants' => [
                'nullable',
                'integer',
                'min:1',
                function ($attribute, $value, $fail) use ($request) {
                    $facility = Facility::find($request->facility_id);
                    if ($facility && $value) {
                        $maxCapacity = $facility->max_capacity ?? $facility->capacity;
                        if ($value > $maxCapacity) {
                            $fail("The number of participants cannot exceed {$maxCapacity} for {$facility->name}.");
                        }
                    }
                },
            ],
            'notes' => 'nullable|string|max:1000',
            
            // Handle separate date and time fields
            'start_date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_date' => 'required|date|after_or_equal:start_date',
            'end_time' => 'required|date_format:H:i|after:start_time',
        ]);
        
      try {
    // 1. Parse Dates: Cleaner object-oriented approach
    $start = Carbon::parse($validatedData['start_date'])
        ->setTimeFromTimeString($validatedData['start_time']);
        
    $end = Carbon::parse($validatedData['end_date'])
        ->setTimeFromTimeString($validatedData['end_time']);
    
    // 2. Find Facility
    $facility = Facility::findOrFail($validatedData['facility_id']);

    // 3. [CRITICAL ADDITION] Calculate New Cost
    // This defines the $newCost variable needed for the update() call later
    $duration = $start->diffInHours($end, true);
    $hours = ceil($duration); // Round up partial hours (e.g., 1.5 hrs -> 2 hrs)
    $newCost = $hours * ($facility->hourly_rate ?? 0);

    // ... continue with operating hours check ...
            // Validate operating hours
            if ($facility->opening_time && $facility->closing_time) {
                $facilityOpen = $start->copy()->setTimeFromTimeString($facility->opening_time);
                $facilityClose = $start->copy()->setTimeFromTimeString($facility->closing_time);
                
                if ($start->lt($facilityOpen) || $end->gt($facilityClose)) {
                    return back()->withErrors(['time' => 'Reservation time must be within facility operating hours (' . $facility->opening_time . ' - ' . $facility->closing_time . ')'])->withInput();
                }
            }

            // Check for conflicts
            $conflict = Reservation::where('facility_id', $validatedData['facility_id'])
                ->where('id', '!=', $reservation->id)
                ->whereNotIn('status', ['rejected', 'cancelled'])
                ->where(function($q) use ($start, $end) {
                    $q->whereBetween('start_time', [$start, $end])
                      ->orWhereBetween('end_time', [$start, $end])
                      ->orWhere(function($q2) use ($start, $end) {
                          $q2->where('start_time', '<', $start)->where('end_time', '>', $end);
                      });
                })->exists();
            
            if ($conflict) {
                return back()->withErrors(['time' => 'This time slot is already booked for the selected facility.'])->withInput();
            }
            
            // Update the reservation
            $reservation->update([
                'facility_id' => $validatedData['facility_id'],
                'event_name' => $validatedData['event_name'],
                'participants' => $validatedData['participants'],
                'notes' => $validatedData['notes'],
                'start_time' => $start,
                'end_time' => $end,
                'status' => 'pending', // Reset to pending when modified
            ]);
            
            return redirect()->route('guest.reservations.index')
                ->with('success', 'Reservation updated successfully! Your changes are pending approval.');
                
        } catch (\Exception $e) {
            return back()->withErrors([
                'error' => 'An error occurred while updating the reservation. Please try again.'
            ])->withInput();
        }
    }

    public function destroy(Reservation $reservation)
    {
        // Check if user owns this reservation
        if ($reservation->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
        
        // Only allow deletion of cancelled reservations
        if ($reservation->status !== 'cancelled') {
            return redirect()->back()->with('error', 'Only cancelled reservations can be deleted.');
        }
        
        // Check if the 7-day grace period has passed
        if (!$reservation->canBeDeleted()) {
            return redirect()->back()->with('error', 'Reservations can only be permanently deleted 7 days after cancellation.');
        }
        
        // Permanently delete the reservation
        $reservation->delete();
        
        return redirect()->route('guest.reservations.index')
            ->with('success', 'Reservation deleted permanently.');
    }

    public function checkAvailability(Request $request)
    {
        $request->validate([
            'facility_id' => 'required|exists:facilities,id',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'reservation_id' => 'nullable|exists:reservations,id',
        ]);

        $facility = Facility::find($request->facility_id);
        $start = Carbon::parse($request->start_time);
        $end = Carbon::parse($request->end_time);

        // Validate operating hours
        if ($facility->opening_time && $facility->closing_time) {
            $facilityOpen = Carbon::parse($request->start_time)->setTimeFromTimeString($facility->opening_time);
            $facilityClose = Carbon::parse($request->start_time)->setTimeFromTimeString($facility->closing_time);
            
            if ($start->lt($facilityOpen) || $end->gt($facilityClose)) {
                return response()->json([
                    'available' => false,
                    'message' => 'Time slot is outside facility operating hours (' . $facility->opening_time . ' - ' . $facility->closing_time . ')',
                    'facility' => ['name' => $facility->name]
                ]);
            }
        } 

        $conflict = Reservation::where('facility_id', $request->facility_id)
            ->whereNotIn('status', ['rejected', 'cancelled'])
            ->when($request->reservation_id, fn($q) => $q->where('id', '!=', $request->reservation_id))
            ->where(function($q) use ($start, $end) {
                $q->whereBetween('start_time', [$start, $end])
                  ->orWhereBetween('end_time', [$start, $end])
                  ->orWhere(function($q2) use ($start, $end) {
                      $q2->where('start_time', '<', $start)->where('end_time', '>', $end);
                  });
            })->exists();

        return response()->json([
            'available' => !$conflict,
            'message' => $conflict ? 'This time slot is already booked' : 'Time slot is available!',
            'facility' => ['name' => $facility->name]
        ]);
    }
}
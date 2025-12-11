<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Facility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FacilityController extends Controller
{
    /**
     * Display a listing of facilities
     */
    public function index()
    {
        $facilities = Facility::latest()->paginate(10);
        return view('admin.facilities.index', compact('facilities'));
    }

    /**
     * Show the form for creating a new facility
     */
    public function create()
    {
        return view('admin.facilities.create');
    }

    /**
     * Store a newly created facility in storage
     */
    public function store(Request $request)
    {
        // Validate the request
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'type' => 'required|string',
            'location' => 'required|string|max:255',
            'capacity' => 'nullable|integer|min:1',
            'max_capacity' => 'nullable|integer|min:1',
            'opening_time' => 'required',
            'closing_time' => 'required',
            'is_public' => 'required|boolean',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);

        // Additional validation for capacity
        if ($request->capacity && $request->max_capacity) {
            if ($request->max_capacity < $request->capacity) {
                return back()->withErrors([
                    'max_capacity' => 'Maximum capacity must be greater than or equal to minimum capacity'
                ])->withInput();
            }
        }

        // Additional validation for operating hours
        if ($request->closing_time <= $request->opening_time) {
            return back()->withErrors([
                'closing_time' => 'Closing time must be after opening time'
            ])->withInput();
        }

        // Handle image upload
        $imagePath = null;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $imagePath = $image->storeAs('facilities', $imageName, 'public');
        }

        // Create the facility
        try {
            $facility = Facility::create([
                'name' => $validated['name'],
                'description' => $validated['description'],
                'type' => $validated['type'],
                'location' => $validated['location'],
                'capacity' => $validated['capacity'],
                'max_capacity' => $validated['max_capacity'],
                'opening_time' => $validated['opening_time'],
                'closing_time' => $validated['closing_time'],
                'is_public' => $validated['is_public'],
                'image' => $imagePath,
            ]);

            return redirect()
                ->route('admin.facilities.index')
                ->with('success', 'Facility created successfully!');
                
        } catch (\Exception $e) {
            // If there was an error, delete the uploaded image
            if ($imagePath && Storage::disk('public')->exists($imagePath)) {
                Storage::disk('public')->delete($imagePath);
            }

            return back()
                ->withErrors(['error' => 'Failed to create facility: ' . $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Display the specified facility
     */
    public function show(Facility $facility)
    {
        return view('admin.facilities.show', compact('facility'));
    }

    /**
     * Show the form for editing the specified facility
     */
    public function edit(Facility $facility)
    {
        return view('admin.facilities.edit', compact('facility'));
    }

    /**
     * Update the specified facility in storage
     */
    public function update(Request $request, Facility $facility)
    {
        // Validate the request
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'type' => 'required|string',
            'location' => 'required|string|max:255',
            'capacity' => 'nullable|integer|min:1',
            'max_capacity' => 'nullable|integer|min:1',
            'opening_time' => 'required',
            'closing_time' => 'required',
            'is_public' => 'required|boolean',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);

        // Additional validation for capacity
        if ($request->capacity && $request->max_capacity) {
            if ($request->max_capacity < $request->capacity) {
                return back()->withErrors([
                    'max_capacity' => 'Maximum capacity must be greater than or equal to minimum capacity'
                ])->withInput();
            }
        }

        // Additional validation for operating hours
        if ($request->closing_time <= $request->opening_time) {
            return back()->withErrors([
                'closing_time' => 'Closing time must be after opening time'
            ])->withInput();
        }

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($facility->image && Storage::disk('public')->exists($facility->image)) {
                Storage::disk('public')->delete($facility->image);
            }

            $image = $request->file('image');
            $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $validated['image'] = $image->storeAs('facilities', $imageName, 'public');
        }

        // Update the facility
        try {
            $facility->update($validated);

            return redirect()
                ->route('admin.facilities.index')
                ->with('success', 'Facility updated successfully!');
                
        } catch (\Exception $e) {
            return back()
                ->withErrors(['error' => 'Failed to update facility: ' . $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Remove the specified facility from storage
     */
    public function destroy(Facility $facility)
    {
        try {
            // Delete image if exists
            if ($facility->image && Storage::disk('public')->exists($facility->image)) {
                Storage::disk('public')->delete($facility->image);
            }

            $facility->delete();

            return redirect()
                ->route('admin.facilities.index')
                ->with('success', 'Facility deleted successfully!');
                
        } catch (\Exception $e) {
            return back()
                ->with('error', 'Failed to delete facility: ' . $e->getMessage());
        }
    }
}
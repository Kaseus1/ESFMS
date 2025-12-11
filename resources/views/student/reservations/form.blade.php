<form action="{{ isset($reservation) ? route('reservations.update', $reservation->id) : route('reservations.store') }}" method="POST">
    @csrf
    @if(isset($reservation))
        @method('PUT')
    @endif

    <!-- Facility selection -->
    <div class="mb-3">
        <label for="facility_id" class="form-label">Facility</label>
        <select name="facility_id" class="form-control" required>
            <option value="">-- Select Facility --</option>
            @foreach($facilities as $facility)
                <option value="{{ $facility->id }}" 
                    {{ (old('facility_id', $reservation->facility_id ?? '') == $facility->id) ? 'selected' : '' }}>
                    {{ $facility->name }}
                </option>
            @endforeach
        </select>
    </div>

    <!-- Event Name -->
    <div class="mb-3">
        <label for="event_name" class="form-label">Event Name</label>
        <input type="text" name="event_name" class="form-control" 
            value="{{ old('event_name', $reservation->event_name ?? '') }}" required>
    </div>

    <!-- Participants -->
    <div class="mb-3">
        <label for="participants" class="form-label">Number of Participants</label>
        <input type="number" name="participants" class="form-control"
            value="{{ old('participants', $reservation->participants ?? '') }}">
    </div>

    <!-- Notes -->
    <div class="mb-3">
        <label for="notes" class="form-label">Notes</label>
        <textarea name="notes" class="form-control">{{ old('notes', $reservation->notes ?? '') }}</textarea>
    </div>

    <!-- Start & End time -->
    <div class="mb-3">
        <label for="start_time" class="form-label">Start Time</label>
        <input type="datetime-local" name="start_time" class="form-control"
            value="{{ old('start_time', isset($reservation) ? $reservation->start_time->format('Y-m-d\TH:i') : ($start_time ?? '')) }}" required>
    </div>

    <div class="mb-3">
        <label for="end_time" class="form-label">End Time</label>
        <input type="datetime-local" name="end_time" class="form-control"
            value="{{ old('end_time', isset($reservation) ? $reservation->end_time->format('Y-m-d\TH:i') : ($end_time ?? '')) }}" required>
    </div>

    <!-- Recurring checkbox -->
    <div class="mb-3 form-check">
        <input type="checkbox" name="is_recurring" id="is_recurring" class="form-check-input" value="1"
            {{ old('is_recurring', $reservation->is_recurring ?? false) ? 'checked' : '' }}>
        <label for="is_recurring" class="form-check-label">Recurring Booking?</label>
    </div>

    <!-- Recurring type -->
    <div class="mb-3" id="recurring_type_container" 
         style="{{ old('is_recurring', $reservation->is_recurring ?? false) ? '' : 'display:none;' }}">
        <label for="recurring_type" class="form-label">Recurring Type</label>
        <select name="recurring_type" class="form-control">
            <option value="">--Select--</option>
            <option value="daily" {{ old('recurring_type', $reservation->recurring_type ?? '') == 'daily' ? 'selected' : '' }}>Daily</option>
            <option value="weekly" {{ old('recurring_type', $reservation->recurring_type ?? '') == 'weekly' ? 'selected' : '' }}>Weekly</option>
            <option value="monthly" {{ old('recurring_type', $reservation->recurring_type ?? '') == 'monthly' ? 'selected' : '' }}>Monthly</option>
        </select>
    </div>

    <button type="submit" class="btn btn-primary">
        {{ isset($reservation) ? 'Update Reservation' : 'Create Reservation' }}
    </button>
</form>

<script>
    document.getElementById('is_recurring').addEventListener('change', function() {
        document.getElementById('recurring_type_container').style.display = this.checked ? '' : 'none';
    });
</script>

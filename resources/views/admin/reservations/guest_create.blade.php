
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Request a Booking</title>
    @vite(['resources/css/app.css'])
</head>
<body class="p-6">
<div class="max-w-2xl mx-auto bg-white p-6 rounded shadow">
    <h1 class="text-2xl font-semibold mb-4">Request a Facility Booking</h1>

    @if(session('error'))
        <div class="mb-4 text-red-700">{{ session('error') }}</div>
    @endif

    @if(session('success'))
        <div class="mb-4 text-green-700">{{ session('success') }}</div>
    @endif

    <form method="POST" action="{{ route('guest.request.store') }}">
        @csrf

        <div class="mb-3">
            <label class="block text-sm">Full name</label>
            <input type="text" name="full_name" value="{{ old('full_name') }}" required class="w-full border p-2 rounded" />
            @error('full_name') <div class="text-red-600 text-sm">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label class="block text-sm">Email</label>
            <input type="email" name="email" value="{{ old('email') }}" required class="w-full border p-2 rounded" />
            @error('email') <div class="text-red-600 text-sm">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label class="block text-sm">Contact number</label>
            <input type="text" name="contact_number" value="{{ old('contact_number') }}" class="w-full border p-2 rounded" />
            @error('contact_number') <div class="text-red-600 text-sm">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label class="block text-sm">Facility</label>
            <select name="facility_id" required class="w-full border p-2 rounded">
                <option value="">Select a facility</option>
                @foreach($facilities as $f)
                    <option value="{{ $f->id }}" {{ old('facility_id') == $f->id ? 'selected' : '' }}>{{ $f->name }}</option>
                @endforeach
            </select>
            @error('facility_id') <div class="text-red-600 text-sm">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label class="block text-sm">Event date</label>
            <input type="date" name="event_date" value="{{ old('event_date') }}" required class="w-full border p-2 rounded" />
            @error('event_date') <div class="text-red-600 text-sm">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3 grid grid-cols-2 gap-3">
            <div>
                <label class="block text-sm">Start time</label>
                <input type="time" name="start_time" value="{{ old('start_time') }}" required class="w-full border p-2 rounded" />
                @error('start_time') <div class="text-red-600 text-sm">{{ $message }}</div> @enderror
            </div>
            <div>
                <label class="block text-sm">End time</label>
                <input type="time" name="end_time" value="{{ old('end_time') }}" required class="w-full border p-2 rounded" />
                @error('end_time') <div class="text-red-600 text-sm">{{ $message }}</div> @enderror
            </div>
        </div>

        <div class="mb-3">
            <label class="block text-sm">Purpose</label>
            <textarea name="purpose" class="w-full border p-2 rounded" required>{{ old('purpose') }}</textarea>
            @error('purpose') <div class="text-red-600 text-sm">{{ $message }}</div> @enderror
        </div>

        <div class="flex justify-end gap-3">
            <a href="{{ route('home') }}" class="px-4 py-2 border rounded">Cancel</a>
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Submit Request</button>
        </div>
    </form>
</div>
</body>
</html>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Pending</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8">
            <div class="text-center">
                <svg class="mx-auto h-24 w-24 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <h2 class="mt-6 text-3xl font-extrabold text-gray-900">
                    Registration Pending
                </h2>
                <p class="mt-2 text-sm text-gray-600">
                    Your account is awaiting approval
                </p>
            </div>

            <div class="mt-8 bg-white shadow-lg rounded-lg p-8">
                <div class="space-y-4">
                    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4">
                        <div class="flex">
                            <div class="ml-3">
                                <p class="text-sm text-yellow-700">
                                    <strong>Thank you, {{ session('registration_name') }}!</strong>
                                </p>
                                <p class="mt-2 text-sm text-yellow-700">
                                    We've sent a confirmation email to <strong>{{ session('registration_email') }}</strong>
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <h3 class="font-semibold text-gray-900">What happens next?</h3>
                        <ul class="list-disc list-inside text-sm text-gray-600 space-y-1">
                            <li>Our admin team will review your registration</li>
                            <li>You'll receive an email once approved (typically within 1-2 business days)</li>
                            <li>After approval, you can login and start using the system</li>
                        </ul>
                    </div>

                    <div class="pt-4 border-t border-gray-200">
                        <a href="{{ route('home') }}" 
                           class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Return to Home
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
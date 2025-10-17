@extends('admin.layouts.admin')

@section('title', 'Two-Factor Authentication Setup')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Two-Factor Authentication</h1>
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Secure your account with 2FA</p>
        </div>
        <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white text-sm font-medium rounded-lg transition-colors duration-200">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back to Dashboard
        </a>
    </div>

    <div class="flex justify-center">
        <div class="w-full max-w-2xl">
            <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h6 class="text-lg font-semibold text-gray-900 dark:text-white">Setup Two-Factor Authentication</h6>
                </div>
                <div class="p-6">
                    @if(session('success'))
                        <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg mb-4 flex items-center justify-between">
                            <span>{{ session('success') }}</span>
                            <button type="button" class="text-green-400 hover:text-green-600" onclick="this.parentElement.remove()">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg mb-4 flex items-center justify-between">
                            <span>{{ session('error') }}</span>
                            <button type="button" class="text-red-400 hover:text-red-600" onclick="this.parentElement.remove()">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                    @endif

                    <div class="text-center mb-6">
                        <div class="mb-4 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                            <div id="qrCodeContainer" class="hidden">
                                <div class="bg-white p-4 border-2 border-dashed border-gray-300 rounded-lg">
                                    <div class="text-sm text-gray-600 dark:text-gray-400 mb-2">QR Code not available</div>
                                    <div class="text-xs text-gray-500">Please use the secret key below instead</div>
                                </div>
                            </div>
                            <div id="qrCodeFallback">
                                <div class="bg-white p-4 border-2 border-dashed border-gray-300 rounded-lg">
                                    <div class="text-sm text-gray-600 dark:text-gray-400 mb-2">Setup Instructions</div>
                                    <div class="text-xs text-gray-500 mb-3">Choose one of these methods to setup 2FA:</div>
                                    <div class="space-y-2 text-left">
                                        <div class="text-xs text-gray-600">
                                            <strong>Method 1:</strong> Use the secret key below
                                        </div>
                                        <div class="text-xs text-gray-600">
                                            <strong>Method 2:</strong> Copy the URL below to your authenticator app
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Setup your authenticator app using one of the methods above</p>
                    </div>

                    <form id="enable2FAForm">
                        @csrf
                        <div class="mb-6">
                            <label for="secret" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Secret Key</label>
                            <div class="flex">
                                <input type="text" class="flex-1 px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-l-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white" id="secret" name="secret" value="{{ $secret }}" readonly>
                                <button type="button" class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white border border-gray-300 dark:border-gray-600 rounded-r-lg transition-colors duration-200" onclick="copySecret()">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                    </svg>
                                </button>
                            </div>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Save this secret key in a safe place. You can use this if QR code doesn't work.</p>
                        </div>

                        <div class="mb-6">
                            <label for="manualUrl" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Manual Entry URL</label>
                            <div class="flex">
                                <input type="text" class="flex-1 px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-l-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white" id="manualUrl" readonly>
                                <button type="button" class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white border border-gray-300 dark:border-gray-600 rounded-r-lg transition-colors duration-200" onclick="copyManualUrl()">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                    </svg>
                                </button>
                            </div>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Alternative method: Copy this URL to your authenticator app</p>
                        </div>

                        <div class="mb-6">
                            <label for="code" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Verification Code</label>
                            <input type="text" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white" id="code" name="code" placeholder="Enter 6-digit code" maxlength="6" required>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Enter the 6-digit code from your authenticator app</p>
                        </div>

                        <div class="flex justify-end">
                            <button type="submit" id="submitBtn" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                </svg>
                                <span id="submitText">Enable Two-Factor Authentication</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Generate manual URL immediately
    generateManualUrl();
});

function generateManualUrl() {
    const secret = document.getElementById('secret').value;
    const manualUrl = `otpauth://totp/Laravel:admin@example.com?secret=${secret}&issuer=Laravel`;
    document.getElementById('manualUrl').value = manualUrl;
}

function copyManualUrl() {
    const manualUrlInput = document.getElementById('manualUrl');
    manualUrlInput.select();
    manualUrlInput.setSelectionRange(0, 99999);
    document.execCommand('copy');
    
    // Show feedback
    const button = event.target.closest('button');
    const originalHTML = button.innerHTML;
    button.innerHTML = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>';
    button.classList.add('bg-green-600', 'hover:bg-green-700');
    button.classList.remove('bg-blue-500', 'hover:bg-blue-600');
    
    setTimeout(() => {
        button.innerHTML = originalHTML;
        button.classList.remove('bg-green-600', 'hover:bg-green-700');
        button.classList.add('bg-blue-500', 'hover:bg-blue-600');
    }, 2000);
}

// Removed unnecessary QR code functions

document.getElementById('enable2FAForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const submitBtn = document.getElementById('submitBtn');
    const submitText = document.getElementById('submitText');
    const originalText = submitText.textContent;
    
    // Show loading state
    submitBtn.disabled = true;
    submitBtn.classList.add('opacity-75', 'cursor-not-allowed');
    submitText.textContent = 'Enabling...';
    
    const formData = new FormData(this);
    
    fetch('{{ route("admin.security.two-factor.enable") }}', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Show success message
            submitText.textContent = 'Success! Redirecting...';
            setTimeout(() => {
                window.location.href = '{{ route("admin.dashboard") }}';
            }, 1000);
        } else {
            // Reset button state
            submitBtn.disabled = false;
            submitBtn.classList.remove('opacity-75', 'cursor-not-allowed');
            submitText.textContent = originalText;
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        // Reset button state
        submitBtn.disabled = false;
        submitBtn.classList.remove('opacity-75', 'cursor-not-allowed');
        submitText.textContent = originalText;
        alert('Error: ' + error.message);
    });
});

function copySecret() {
    const secretInput = document.getElementById('secret');
    secretInput.select();
    secretInput.setSelectionRange(0, 99999);
    document.execCommand('copy');
    
    // Show feedback
    const button = event.target.closest('button');
    const originalHTML = button.innerHTML;
    button.innerHTML = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>';
    button.classList.add('bg-green-600', 'hover:bg-green-700');
    button.classList.remove('bg-gray-500', 'hover:bg-gray-600');
    
    setTimeout(() => {
        button.innerHTML = originalHTML;
        button.classList.remove('bg-green-600', 'hover:bg-green-700');
        button.classList.add('bg-gray-500', 'hover:bg-gray-600');
    }, 2000);
}
</script>
@endsection

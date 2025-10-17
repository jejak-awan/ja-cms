@extends('admin.layouts.admin')

@section('title', 'Two-Factor Authentication Verification')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex justify-center">
        <div class="w-full max-w-md">
            <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg overflow-hidden">
                <div class="px-6 py-4 text-center border-b border-gray-200 dark:border-gray-700">
                    <h6 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center justify-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                        </svg>
                        Two-Factor Authentication Required
                    </h6>
                </div>
                <div class="p-6">
                    <div class="text-center mb-6">
                        <svg class="w-16 h-16 text-blue-600 dark:text-blue-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                        </svg>
                        <h5 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">Enter Verification Code</h5>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Please enter the 6-digit code from your authenticator app</p>
                    </div>

                    <form id="verify2FAForm">
                        @csrf
                        <div class="mb-6">
                            <label for="code" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Verification Code</label>
                            <input type="text" class="w-full px-4 py-3 text-center text-lg border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white" id="code" name="code" 
                                   placeholder="000000" maxlength="6" required autofocus>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Enter the 6-digit code from your authenticator app</p>
                        </div>

                        <div class="mb-6">
                            <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-3 bg-blue-600 hover:bg-blue-700 text-white text-lg font-medium rounded-lg transition-colors duration-200">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Verify Code
                            </button>
                        </div>

                        <div class="text-center">
                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                Don't have access to your authenticator app? 
                                <button type="button" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 font-medium" onclick="showRecoveryCodes()">Use recovery code</button>
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recovery Codes Modal -->
<div id="recoveryCodesModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 hidden">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-lg bg-white dark:bg-gray-800">
        <div class="flex justify-between items-center mb-4">
            <h5 class="text-lg font-semibold text-gray-900 dark:text-white">Use Recovery Code</h5>
            <button type="button" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300" onclick="hideRecoveryCodes()">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <div class="mb-4">
            <form id="recoveryCodeForm">
                @csrf
                <div class="mb-4">
                    <label for="recoveryCode" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Recovery Code</label>
                    <input type="text" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white" id="recoveryCode" name="code" 
                           placeholder="Enter recovery code" required>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Enter one of your recovery codes</p>
                </div>
            </form>
        </div>
        <div class="flex justify-end space-x-3">
            <button type="button" class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white text-sm font-medium rounded-lg transition-colors duration-200" onclick="hideRecoveryCodes()">Cancel</button>
            <button type="button" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors duration-200" onclick="verifyRecoveryCode()">Verify</button>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.getElementById('verify2FAForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    fetch('{{ route("admin.two-factor.verify") }}', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            window.location.href = '{{ route("admin.dashboard") }}';
        } else {
            alert('Invalid verification code. Please try again.');
            document.getElementById('code').value = '';
            document.getElementById('code').focus();
        }
    })
    .catch(error => {
        alert('Error: ' + error.message);
    });
});

function showRecoveryCodes() {
    document.getElementById('recoveryCodesModal').classList.remove('hidden');
}

function hideRecoveryCodes() {
    document.getElementById('recoveryCodesModal').classList.add('hidden');
}

function verifyRecoveryCode() {
    const formData = new FormData(document.getElementById('recoveryCodeForm'));
    
    fetch('{{ route("admin.two-factor.verify") }}', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            hideRecoveryCodes();
            window.location.href = '{{ route("admin.dashboard") }}';
        } else {
            alert('Invalid recovery code. Please try again.');
            document.getElementById('recoveryCode').value = '';
        }
    })
    .catch(error => {
        alert('Error: ' + error.message);
    });
}

// Auto-focus on code input
document.getElementById('code').addEventListener('input', function() {
    if (this.value.length === 6) {
        document.getElementById('verify2FAForm').dispatchEvent(new Event('submit'));
    }
});
</script>
@endsection

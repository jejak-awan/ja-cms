@extends('admin.layouts.admin')

@section('title', 'Recovery Codes')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Recovery Codes</h1>
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Save these recovery codes in a safe place</p>
        </div>
        <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white text-sm font-medium rounded-lg transition-colors duration-200">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back to Dashboard
        </a>
    </div>

    <div class="flex justify-center">
        <div class="w-full max-w-4xl">
            <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h6 class="text-lg font-semibold text-yellow-600 dark:text-yellow-400 flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                        Important: Save These Codes
                    </h6>
                </div>
                <div class="p-6">
                    <div class="bg-yellow-50 border border-yellow-200 text-yellow-800 px-4 py-3 rounded-lg mb-6">
                        <strong>Warning:</strong> These recovery codes can be used to access your account if you lose your authenticator device. 
                        Store them in a safe place and don't share them with anyone.
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                        @foreach($recoveryCodes as $index => $code)
                        <div class="bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg p-4">
                            <div class="flex justify-between items-center">
                                <div>
                                    <div class="text-xl font-bold text-blue-600 dark:text-blue-400">{{ $code }}</div>
                                    <div class="text-sm text-gray-600 dark:text-gray-400">Recovery Code {{ $index + 1 }}</div>
                                </div>
                                <button class="px-3 py-1 bg-blue-100 hover:bg-blue-200 text-blue-800 text-sm font-medium rounded-lg transition-colors duration-200" onclick="copyCode('{{ $code }}')">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <div class="flex flex-wrap gap-3">
                        <button class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition-colors duration-200" onclick="printCodes()">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                            </svg>
                            Print Codes
                        </button>
                        <button class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors duration-200" onclick="downloadCodes()">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Download as Text
                        </button>
                        <button class="inline-flex items-center px-4 py-2 bg-yellow-600 hover:bg-yellow-700 text-white text-sm font-medium rounded-lg transition-colors duration-200" onclick="regenerateCodes()">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
                            Regenerate Codes
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function copyCode(code) {
    navigator.clipboard.writeText(code).then(function() {
        // Show feedback
        const button = event.target.closest('button');
        const originalHTML = button.innerHTML;
        button.innerHTML = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>';
        button.classList.add('bg-green-100', 'hover:bg-green-200', 'text-green-800');
        button.classList.remove('bg-blue-100', 'hover:bg-blue-200', 'text-blue-800');
        
        setTimeout(() => {
            button.innerHTML = originalHTML;
            button.classList.remove('bg-green-100', 'hover:bg-green-200', 'text-green-800');
            button.classList.add('bg-blue-100', 'hover:bg-blue-200', 'text-blue-800');
        }, 2000);
    });
}

function printCodes() {
    const codes = @json($recoveryCodes);
    const printWindow = window.open('', '_blank');
    printWindow.document.write(`
        <html>
            <head>
                <title>Recovery Codes</title>
                <style>
                    body { font-family: Arial, sans-serif; margin: 20px; }
                    .header { text-align: center; margin-bottom: 30px; }
                    .codes { display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; }
                    .code { border: 1px solid #ccc; padding: 10px; text-align: center; }
                    .warning { background: #fff3cd; border: 1px solid #ffeaa7; padding: 10px; margin-bottom: 20px; }
                </style>
            </head>
            <body>
                <div class="header">
                    <h1>Two-Factor Authentication Recovery Codes</h1>
                    <p>Generated on: ${new Date().toLocaleString()}</p>
                </div>
                <div class="warning">
                    <strong>Important:</strong> Store these codes in a safe place. Each code can only be used once.
                </div>
                <div class="codes">
                    ${codes.map((code, index) => `
                        <div class="code">
                            <strong>Code ${index + 1}</strong><br>
                            ${code}
                        </div>
                    `).join('')}
                </div>
            </body>
        </html>
    `);
    printWindow.document.close();
    printWindow.print();
}

function downloadCodes() {
    const codes = @json($recoveryCodes);
    const content = `Two-Factor Authentication Recovery Codes
Generated on: ${new Date().toLocaleString()}

IMPORTANT: Store these codes in a safe place. Each code can only be used once.

${codes.map((code, index) => `Code ${index + 1}: ${code}`).join('\n')}

If you lose access to your authenticator device, you can use one of these codes to access your account.
Each code can only be used once, so make sure to generate new codes after using one.`;

    const blob = new Blob([content], { type: 'text/plain' });
    const url = window.URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = 'recovery-codes.txt';
    document.body.appendChild(a);
    a.click();
    document.body.removeChild(a);
    window.URL.revokeObjectURL(url);
}

function regenerateCodes() {
    if (confirm('Are you sure you want to regenerate recovery codes? This will invalidate all existing codes.')) {
        fetch('{{ route("admin.security.two-factor.regenerate-codes") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Recovery codes regenerated successfully!');
                location.reload();
            } else {
                alert('Error regenerating codes: ' + data.message);
            }
        })
        .catch(error => {
            alert('Error: ' + error.message);
        });
    }
}
</script>
@endsection

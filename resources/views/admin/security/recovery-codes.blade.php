@extends('admin.layouts.admin')

@section('title', 'Recovery Codes')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Recovery Codes</h1>
            <p class="text-muted">Save these recovery codes in a safe place</p>
        </div>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Dashboard
        </a>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-warning">
                        <i class="fas fa-exclamation-triangle"></i> Important: Save These Codes
                    </h6>
                </div>
                <div class="card-body">
                    <div class="alert alert-warning">
                        <strong>Warning:</strong> These recovery codes can be used to access your account if you lose your authenticator device. 
                        Store them in a safe place and don't share them with anyone.
                    </div>

                    <div class="row">
                        @foreach($recoveryCodes as $index => $code)
                        <div class="col-md-6 mb-3">
                            <div class="card border-left-primary">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <div class="h5 text-primary">{{ $code }}</div>
                                            <div class="text-muted">Recovery Code {{ $index + 1 }}</div>
                                        </div>
                                        <button class="btn btn-sm btn-outline-primary" onclick="copyCode('{{ $code }}')">
                                            <i class="fas fa-copy"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <div class="mt-4">
                        <button class="btn btn-success" onclick="printCodes()">
                            <i class="fas fa-print"></i> Print Codes
                        </button>
                        <button class="btn btn-info" onclick="downloadCodes()">
                            <i class="fas fa-download"></i> Download as Text
                        </button>
                        <button class="btn btn-warning" onclick="regenerateCodes()">
                            <i class="fas fa-sync-alt"></i> Regenerate Codes
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
        button.innerHTML = '<i class="fas fa-check"></i>';
        button.classList.add('btn-success');
        button.classList.remove('btn-outline-primary');
        
        setTimeout(() => {
            button.innerHTML = originalHTML;
            button.classList.remove('btn-success');
            button.classList.add('btn-outline-primary');
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

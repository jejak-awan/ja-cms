@extends('admin.layouts.admin')

@section('title', 'Two-Factor Authentication Setup')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Two-Factor Authentication</h1>
            <p class="text-muted">Secure your account with 2FA</p>
        </div>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Dashboard
        </a>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Setup Two-Factor Authentication</h6>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show">
                            {{ session('success') }}
                            <button type="button" class="close" data-dismiss="alert">
                                <span>&times;</span>
                            </button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show">
                            {{ session('error') }}
                            <button type="button" class="close" data-dismiss="alert">
                                <span>&times;</span>
                            </button>
                        </div>
                    @endif

                    <div class="text-center mb-4">
                        <div class="mb-3">
                            {!! $qrCode !!}
                        </div>
                        <p class="text-muted">Scan this QR code with your authenticator app</p>
                    </div>

                    <form id="enable2FAForm">
                        @csrf
                        <div class="form-group">
                            <label for="secret">Secret Key</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="secret" name="secret" value="{{ $secret }}" readonly>
                                <div class="input-group-append">
                                    <button type="button" class="btn btn-outline-secondary" onclick="copySecret()">
                                        <i class="fas fa-copy"></i>
                                    </button>
                                </div>
                            </div>
                            <small class="form-text text-muted">Save this secret key in a safe place</small>
                        </div>

                        <div class="form-group">
                            <label for="code">Verification Code</label>
                            <input type="text" class="form-control" id="code" name="code" placeholder="Enter 6-digit code" maxlength="6" required>
                            <small class="form-text text-muted">Enter the 6-digit code from your authenticator app</small>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-shield-alt"></i> Enable Two-Factor Authentication
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
document.getElementById('enable2FAForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
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
            alert('Two-factor authentication enabled successfully!');
            window.location.href = '{{ route("admin.dashboard") }}';
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
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
    button.innerHTML = '<i class="fas fa-check"></i>';
    button.classList.add('btn-success');
    button.classList.remove('btn-outline-secondary');
    
    setTimeout(() => {
        button.innerHTML = originalHTML;
        button.classList.remove('btn-success');
        button.classList.add('btn-outline-secondary');
    }, 2000);
}
</script>
@endsection

@extends('admin.layouts.admin')

@section('title', 'Two-Factor Authentication Verification')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header py-3 text-center">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-shield-alt"></i> Two-Factor Authentication Required
                    </h6>
                </div>
                <div class="card-body">
                    <div class="text-center mb-4">
                        <i class="fas fa-mobile-alt fa-3x text-primary mb-3"></i>
                        <h5>Enter Verification Code</h5>
                        <p class="text-muted">Please enter the 6-digit code from your authenticator app</p>
                    </div>

                    <form id="verify2FAForm">
                        @csrf
                        <div class="form-group">
                            <label for="code">Verification Code</label>
                            <input type="text" class="form-control form-control-lg text-center" id="code" name="code" 
                                   placeholder="000000" maxlength="6" required autofocus>
                            <small class="form-text text-muted">Enter the 6-digit code from your authenticator app</small>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary btn-block btn-lg">
                                <i class="fas fa-check"></i> Verify Code
                            </button>
                        </div>

                        <div class="text-center">
                            <small class="text-muted">
                                Don't have access to your authenticator app? 
                                <a href="#" onclick="showRecoveryCodes()">Use recovery code</a>
                            </small>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recovery Codes Modal -->
<div class="modal fade" id="recoveryCodesModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Use Recovery Code</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="recoveryCodeForm">
                    @csrf
                    <div class="form-group">
                        <label for="recoveryCode">Recovery Code</label>
                        <input type="text" class="form-control" id="recoveryCode" name="code" 
                               placeholder="Enter recovery code" required>
                        <small class="form-text text-muted">Enter one of your recovery codes</small>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="verifyRecoveryCode()">Verify</button>
            </div>
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
    $('#recoveryCodesModal').modal('show');
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
            $('#recoveryCodesModal').modal('hide');
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

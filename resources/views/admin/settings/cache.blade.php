@extends('admin.layouts.admin')

@section('title', 'Cache Management')

@section('content')
<div class="max-w-xl mx-auto mt-8 p-6 bg-white rounded shadow">
    <h2 class="text-2xl font-bold mb-4">Cache Management</h2>
    <div id="toast" class="hidden mb-4 px-4 py-2 rounded border text-sm"></div>
    <div id="status-panel" class="mb-4 text-sm text-gray-700 space-y-1 hidden">
        <div>Driver: <span id="status-driver" class="inline-block px-2 py-0.5 rounded bg-gray-100">-</span></div>
        <div>Original Store: <span id="status-original" class="inline-block px-2 py-0.5 rounded bg-gray-100">-</span></div>
        <div>Global TTL: <span id="status-ttl" class="inline-block px-2 py-0.5 rounded bg-gray-100">-</span> seconds</div>
    </div>
    <form id="cache-settings-form" class="space-y-4">
        <div>
            <label class="font-semibold">Enable Cache</label>
            <input type="checkbox" name="enabled" id="cache-enabled" class="ml-2">
        </div>
        <div>
            <label class="font-semibold">Cache TTL (seconds)</label>
            <input type="number" name="ttl" id="cache-ttl" class="ml-2 border rounded px-2 py-1 w-32">
        </div>
        <div>
            <label class="font-semibold">Debug Mode</label>
            <input type="checkbox" name="debug" id="cache-debug" class="ml-2">
        </div>
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Update Settings</button>
    </form>
    <hr class="my-6">
    <button id="flush-cache-btn" class="bg-red-600 text-white px-4 py-2 rounded">Flush Cache</button>
    <div id="cache-status" class="mt-6 text-xs text-gray-500"></div>
</div>

<script>
function fetchCacheStatus() {
    fetch('/admin/cache/status')
        .then(res => res.json())
        .then(data => {
            document.getElementById('cache-enabled').checked = !!data.enabled;
            document.getElementById('cache-ttl').value = data.ttl;
            document.getElementById('cache-debug').checked = !!data.debug;
            // Pretty status
            document.getElementById('status-driver').innerText = data.driver || '-';
            document.getElementById('status-original').innerText = data.original_store || '-';
            document.getElementById('status-ttl').innerText = data.ttl;
            document.getElementById('status-panel').classList.remove('hidden');
            document.getElementById('cache-status').innerText = 'Raw Status: ' + JSON.stringify(data);
        });
}

document.getElementById('cache-settings-form').addEventListener('submit', function(e) {
    e.preventDefault();
    fetch('/admin/cache/update', {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            enabled: document.getElementById('cache-enabled').checked,
            ttl: parseInt(document.getElementById('cache-ttl').value),
            debug: document.getElementById('cache-debug').checked
        })
    })
    .then(res => res.json())
    .then(data => {
        showToast(data.success ? 'Settings updated!' : (data.message || 'Failed to update'), data.success);
        fetchCacheStatus();
    }).catch(() => showToast('Failed to update', false));
});

document.getElementById('flush-cache-btn').addEventListener('click', function() {
    if (!confirm('Flush all cache? This cannot be undone.')) return;
    fetch('/admin/cache/flush', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        }
    })
        .then(res => res.json())
        .then(data => {
                showToast(data.message || 'Flushed', true);
                fetchCacheStatus();
            }).catch(() => showToast('Failed to flush', false));
});

fetchCacheStatus();

function showToast(message, success) {
    const el = document.getElementById('toast');
    el.classList.remove('hidden');
    el.classList.remove('border-red-400','text-red-700','bg-red-50');
    el.classList.remove('border-green-400','text-green-700','bg-green-50');
    if (success) {
        el.classList.add('border-green-400','text-green-700','bg-green-50');
    } else {
        el.classList.add('border-red-400','text-red-700','bg-red-50');
    }
    el.innerText = message;
    setTimeout(() => { el.classList.add('hidden'); }, 2500);
}
</script>
@endsection

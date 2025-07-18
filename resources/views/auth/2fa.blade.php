@extends('layouts.app')

@section('title', 'Two-Factor Authentication')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-md mx-auto bg-white rounded-xl shadow-xl p-8">
        <h2 class="text-2xl font-bold text-gray-800 mb-4">Two-Factor Authentication</h2>
        <p class="mb-6 text-gray-600">Enter the 6-digit code sent to your email. The code is valid for 10 minutes.</p>
        <div id="otp-timer" class="mb-4 text-center text-lg font-semibold text-blue-600"></div>
        @if ($errors->any())
            <div class="mb-4 text-red-700 bg-red-100 border border-red-400 rounded p-3">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form method="POST" action="{{ route('2fa.verify') }}" class="space-y-6">
            @csrf
            <div>
                <label for="code" class="block text-sm font-medium text-gray-700 mb-2">2FA Code</label>
                <input type="text" id="code" name="code" required autofocus maxlength="6" minlength="6" pattern="\d{6}" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Enter 6-digit code">
            </div>
            <button id="verify-btn" type="submit" class="w-full bg-gradient-to-r from-blue-500 to-blue-600 text-white py-3 px-4 rounded-lg font-semibold hover:from-blue-600 hover:to-blue-700 transition-all duration-200">Verify</button>
        </form>
        <div class="mt-6 text-center">
            <a href="/" class="text-gray-500 hover:text-gray-700 text-sm">← Back to login</a>
        </div>
    </div>
</div>
@endsection
<script>
<?php echo 'window.otpExpiresAt = ' . json_encode($expires ?? '') . ';'; ?>
<?php echo 'window.otpStatus = ' . json_encode(session('status')) . ';'; ?>
<?php echo 'window.otpError = ' . json_encode($errors->any() ? $errors->first() : null) . ';'; ?>
</script>
@section('scripts')
@include('components.notifications')
<script>
document.addEventListener('DOMContentLoaded', function() {
    if (window.otpStatus) {
        showSuccess(window.otpStatus);
    }
    if (window.otpError) {
        showError(window.otpError);
    }

    // OTP Countdown Timer
    if (window.otpExpiresAt) {
        let expiresAt = new Date(window.otpExpiresAt);
        if (isNaN(expiresAt.getTime())) return; // Invalid date, do not run timer
        let timerDiv = document.getElementById('otp-timer');
        let verifyBtn = document.getElementById('verify-btn');
        function updateTimer() {
            let now = new Date();
            let diff = Math.floor((expiresAt - now) / 1000);
            if (diff > 0) {
                let min = Math.floor(diff / 60);
                let sec = diff % 60;
                timerDiv.textContent = `Code expires in ${min}:${sec.toString().padStart(2, '0')}`;
                verifyBtn.disabled = false;
            } else {
                timerDiv.textContent = 'Code expired. Please login again.';
                verifyBtn.disabled = true;
            }
        }
        updateTimer();
        let interval = setInterval(() => {
            updateTimer();
            if (new Date() >= expiresAt) {
                clearInterval(interval);
            }
        }, 1000);
    }
});
</script>
@endsection 
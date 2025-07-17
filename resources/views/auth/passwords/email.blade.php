@extends('layouts.app')

@section('title', 'Reset Password')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-md mx-auto bg-white rounded-xl shadow-xl p-8">
        <h2 class="text-2xl font-bold text-gray-800 mb-4">Forgot your password?</h2>
        <p class="mb-6 text-gray-600">Enter your email address and we'll send you a link to reset your password.</p>
        @if (session('status'))
            <div class="mb-4 text-green-700 bg-green-100 border border-green-400 rounded p-3">
                {{ session('status') }}
            </div>
        @endif
        @if ($errors->any())
            <div class="mb-4 text-red-700 bg-red-100 border border-red-400 rounded p-3">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
            @csrf
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                <input type="email" id="email" name="email" required autofocus class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Enter your email address">
            </div>
            <button type="submit" class="w-full bg-gradient-to-r from-blue-500 to-blue-600 text-white py-3 px-4 rounded-lg font-semibold hover:from-blue-600 hover:to-blue-700 transition-all duration-200">Send Password Reset Link</button>
        </form>
        <div class="mt-6 text-center">
            <a href="/" class="text-gray-500 hover:text-gray-700 text-sm">← Back to login</a>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    @if (session('status'))
        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: 'success',
            title: @json(session('status')),
            showConfirmButton: false,
            timer: 2500,
            timerProgressBar: true
        });
    @endif
    @if ($errors->any())
        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: 'error',
            title: @json($errors->first()),
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true
        });
    @endif
});
</script>
@endsection 
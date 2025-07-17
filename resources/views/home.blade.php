@extends('layouts.app')

@section('title', 'POS System - User Selection')

@section('styles')
<!-- Fonts -->
<link rel="preconnect" href="https://fonts.bunny.net">
<link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
<style>
    .role-button {
        transition: all 0.3s ease;
    }
    .role-button:hover {
        transform: translateY(-2px);
    }
    .login-form {
        opacity: 0;
        transform: translateY(20px);
        transition: all 0.5s ease;
    }
    .login-form.show {
        opacity: 1;
        transform: translateY(0);
    }
</style>
@endsection

@section('content')
<!-- Header with Register Button -->
<div class="absolute top-4 right-4 z-10">
    <a
        href="/register"
        class="inline-block px-6 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg font-semibold transition-colors shadow-lg"
    >
        Register
    </a>
</div>

<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="text-center mb-12">
        <h1 class="text-4xl font-bold text-gray-800 mb-4">POS System</h1>
        <p class="text-lg text-gray-600">Select your role to continue</p>
    </div>

    <!-- Role Selection Buttons -->
    <div class="max-w-4xl mx-auto mb-12">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Administrator Button -->
            <button 
                onclick="selectRole('administrator')"
                class="role-button bg-gradient-to-r from-purple-500 to-purple-600 text-white p-8 rounded-xl shadow-lg hover:shadow-xl"
            >
                <div class="text-center">
                    <div class="text-4xl mb-4">👨‍💼</div>
                    <h3 class="text-xl font-semibold mb-2">Administrator</h3>
                    <p class="text-purple-100 text-sm">Full system access and management</p>
                </div>
            </button>

            <!-- Supervisor Button -->
            <button 
                onclick="selectRole('supervisor')"
                class="role-button bg-gradient-to-r from-blue-500 to-blue-600 text-white p-8 rounded-xl shadow-lg hover:shadow-xl"
            >
                <div class="text-center">
                    <div class="text-4xl mb-4">👨‍💻</div>
                    <h3 class="text-xl font-semibold mb-2">Supervisor</h3>
                    <p class="text-blue-100 text-sm">Team management and oversight</p>
                </div>
            </button>

            <!-- Cashier Button -->
            <button 
                onclick="selectRole('cashier')"
                class="role-button bg-gradient-to-r from-green-500 to-green-600 text-white p-8 rounded-xl shadow-lg hover:shadow-xl"
            >
                <div class="text-center">
                    <div class="text-4xl mb-4">💼</div>
                    <h3 class="text-xl font-semibold mb-2">Cashier</h3>
                    <p class="text-green-100 text-sm">Sales and transaction processing</p>
                </div>
            </button>
        </div>
    </div>

    <!-- Login Form -->
    <div id="loginForm" class="login-form max-w-md mx-auto">
        <div class="bg-white rounded-xl shadow-xl p-8">
            <div class="text-center mb-6">
                <h2 class="text-2xl font-bold text-gray-800 mb-2">Login</h2>
                <p class="text-gray-600">Welcome, <span id="selectedRole" class="font-semibold"></span></p>
            </div>

            <form id="loginFormElement" class="space-y-6">
                <input type="hidden" name="role" id="roleInput" required>
                <div>
                    <label for="username" class="block text-sm font-medium text-gray-700 mb-2">Username</label>
                    <input 
                        type="text" 
                        id="username" 
                        name="username" 
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="Enter your username"
                        required
                    >
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                    <div class="relative">
                        <input 
                            type="password" 
                            id="password" 
                            name="password" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent pr-12"
                            placeholder="Enter your password"
                            required
                        >
                        <button type="button" id="toggleLoginPassword" tabindex="-1" class="absolute inset-y-0 right-0 flex items-center px-3 text-gray-500 focus:outline-none" aria-label="Show password">
                            <svg id="loginEyeIcon" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                        </button>
                    </div>
                </div>

                <div class="flex items-center justify-between">
                    <label class="flex items-center">
                        <input type="checkbox" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        <span class="ml-2 text-sm text-gray-600">Remember me</span>
                    </label>
                    <a href="{{ route('password.request') }}" class="text-sm text-blue-600 hover:text-blue-800">Forgot password?</a>
                </div>

                <button 
                    type="submit" 
                    class="w-full bg-gradient-to-r from-blue-500 to-blue-600 text-white py-3 px-4 rounded-lg font-semibold hover:from-blue-600 hover:to-blue-700 transition-all duration-200"
                >
                    Sign In
                </button>
            </form>

            <div class="mt-6 text-center">
                <button 
                    onclick="goBack()" 
                    class="text-gray-500 hover:text-gray-700 text-sm"
                >
                    ← Back to role selection
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    function selectRole(role) {
        const roleNames = {
            'administrator': 'Administrator',
            'supervisor': 'Supervisor',
            'cashier': 'Cashier'
        };
        document.getElementById('selectedRole').textContent = roleNames[role];
        document.querySelector('.grid').style.display = 'none';
        document.getElementById('loginForm').classList.add('show');
        localStorage.setItem('selectedRole', role);
        document.getElementById('roleInput').value = role;
        const usernames = {
            'administrator': 'admin',
            'supervisor': 'supervisor',
            'cashier': 'cashier'
        };
        document.getElementById('username').value = usernames[role];
        document.getElementById('password').focus();
    }
    window.selectRole = selectRole;

    function goBack() {
        document.querySelector('.grid').style.display = 'grid';
        document.getElementById('loginForm').classList.remove('show');
        document.getElementById('loginFormElement').reset();
    }
    window.goBack = goBack;

    // SweetAlert2 Toast helpers
    function showSuccessAndRedirect(message, redirectUrl) {
        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: 'success',
            title: message,
            showConfirmButton: false,
            timer: 1800,
            timerProgressBar: true,
            didClose: () => {
                window.location.href = redirectUrl;
            }
        });
    }
    function showError(message) {
        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: 'error',
            title: message,
            showConfirmButton: false,
            timer: 2500,
            timerProgressBar: true
        });
    }

    var loginForm = document.getElementById('loginFormElement');
    if (loginForm) {
        loginForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const username = document.getElementById('username').value;
            const password = document.getElementById('password').value;
            const role = document.getElementById('roleInput').value;
            if (!role) {
                showError('Please select a role before logging in.');
                return;
            }
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.textContent;
            submitBtn.textContent = 'Signing in...';
            submitBtn.disabled = true;
            fetch('/login', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    username: username,
                    password: password,
                    role: role
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showSuccessAndRedirect(data.message, data.redirect);
                } else {
                    showError(data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showError('An error occurred. Please try again.');
            })
            .finally(() => {
                submitBtn.textContent = originalText;
                submitBtn.disabled = false;
            });
        });
    }

    // Show/hide password toggle for login
    const loginPasswordInput = document.getElementById('password');
    const toggleLoginPassword = document.getElementById('toggleLoginPassword');
    const loginEyeIcon = document.getElementById('loginEyeIcon');
    if (toggleLoginPassword && loginPasswordInput) {
        toggleLoginPassword.addEventListener('click', function() {
            const isPassword = loginPasswordInput.type === 'password';
            loginPasswordInput.type = isPassword ? 'text' : 'password';
            loginEyeIcon.innerHTML = isPassword
                ? '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.477 0-8.268-2.943-9.542-7a9.956 9.956 0 012.042-3.368m3.087-2.933A9.956 9.956 0 0112 5c4.477 0 8.268 2.943 9.542 7a9.973 9.973 0 01-4.043 5.306M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3l18 18" />'
                : '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />';
        });
    }

    // Add some interactive effects
    const buttons = document.querySelectorAll('.role-button');
    buttons.forEach(button => {
        button.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-4px) scale(1.02)';
        });
        button.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0) scale(1)';
        });
    });
});
</script>
@endsection 
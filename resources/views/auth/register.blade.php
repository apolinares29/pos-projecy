@extends('layouts.app')

@section('title', 'Register - POS System')

@section('styles')
<style>
    .role-option {
        transition: all 0.3s ease;
    }
    .role-option:hover {
        transform: translateY(-2px);
    }
    .role-option.selected {
        border-color: #3b82f6;
        background-color: #eff6ff;
    }
</style>
@endsection

@section('content')
<body class="bg-gradient-to-br from-blue-50 to-indigo-100 min-h-screen">
    <div class="container mx-auto px-4 py-8">
        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-800 mb-2">Create Account</h1>
            <p class="text-gray-600">Join our POS system with your preferred role</p>
        </div>

        <!-- Registration Form -->
        <div class="max-w-2xl mx-auto">
            <div class="bg-white rounded-xl shadow-xl p-8">
                <form id="registerForm" class="space-y-6">
                    <!-- Personal Information -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="first_name" class="block text-sm font-medium text-gray-700 mb-2">First Name</label>
                            <input 
                                type="text" 
                                id="first_name" 
                                name="first_name" 
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                placeholder="Enter your first name"
                                required
                            >
                        </div>
                        <div>
                            <label for="last_name" class="block text-sm font-medium text-gray-700 mb-2">Last Name</label>
                            <input 
                                type="text" 
                                id="last_name" 
                                name="last_name" 
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                placeholder="Enter your last name"
                                required
                            >
                        </div>
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                        <input 
                            type="email" 
                            id="email" 
                            name="email" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="Enter your email address"
                            required
                        >
                        <p class="text-sm text-gray-500 mt-1">We'll send a verification email to this address</p>
                    </div>

                    <div>
                        <label for="username" class="block text-sm font-medium text-gray-700 mb-2">Username</label>
                        <input 
                            type="text" 
                            id="username" 
                            name="username" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="Choose a unique username"
                            required
                        >
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                            <div class="relative">
                                <input 
                                    type="password" 
                                    id="password" 
                                    name="password" 
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent pr-12"
                                    placeholder="Create a strong password"
                                    required
                                >
                                <button type="button" id="toggleRegisterPassword" tabindex="-1" class="absolute inset-y-0 right-0 flex items-center px-3 text-gray-500 focus:outline-none" aria-label="Show password">
                                    <svg id="registerEyeIcon" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                </button>
                            </div>
                        </div>
                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Confirm Password</label>
                            <div class="relative">
                                <input 
                                    type="password" 
                                    id="password_confirmation" 
                                    name="password_confirmation" 
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent pr-12"
                                    placeholder="Confirm your password"
                                    required
                                >
                                <button type="button" id="toggleRegisterPasswordConfirm" tabindex="-1" class="absolute inset-y-0 right-0 flex items-center px-3 text-gray-500 focus:outline-none" aria-label="Show password confirmation">
                                    <svg id="registerEyeIconConfirm" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Role Selection -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-4">Select Your Role</label>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div 
                                class="role-option border-2 border-gray-200 rounded-lg p-4 cursor-pointer"
                                data-role="administrator"
                                onclick="selectRole('administrator')"
                            >
                                <div class="text-center">
                                    <div class="text-3xl mb-2">👨‍💼</div>
                                    <h3 class="font-semibold text-gray-900">Administrator</h3>
                                    <p class="text-sm text-gray-600 mt-1">Full system access</p>
                                </div>
                            </div>

                            <div 
                                class="role-option border-2 border-gray-200 rounded-lg p-4 cursor-pointer"
                                data-role="supervisor"
                                onclick="selectRole('supervisor')"
                            >
                                <div class="text-center">
                                    <div class="text-3xl mb-2">👨‍💻</div>
                                    <h3 class="font-semibold text-gray-900">Supervisor</h3>
                                    <p class="text-sm text-gray-600 mt-1">Team management</p>
                                </div>
                            </div>

                            <div 
                                class="role-option border-2 border-gray-200 rounded-lg p-4 cursor-pointer"
                                data-role="cashier"
                                onclick="selectRole('cashier')"
                            >
                                <div class="text-center">
                                    <div class="text-3xl mb-2">💼</div>
                                    <h3 class="font-semibold text-gray-900">Cashier</h3>
                                    <p class="text-sm text-gray-600 mt-1">Sales processing</p>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" id="selected_role" name="role" required>
                    </div>

                    <!-- Terms and Conditions -->
                    <div class="flex items-center">
                        <input 
                            type="checkbox" 
                            id="terms" 
                            name="terms" 
                            class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                            required
                        >
                        <label for="terms" class="ml-2 text-sm text-gray-600">
                            I agree to the <a href="#" class="text-blue-600 hover:text-blue-800">Terms and Conditions</a>
                        </label>
                    </div>

                    <!-- Submit Button -->
                    <button 
                        type="submit" 
                        class="w-full bg-gradient-to-r from-blue-500 to-blue-600 text-white py-3 px-4 rounded-lg font-semibold hover:from-blue-600 hover:to-blue-700 transition-all duration-200"
                    >
                        Create Account
                    </button>

                    <!-- Login Link -->
                    <div class="text-center">
                        <p class="text-gray-600">
                            Already have an account? 
                            <a href="/" class="text-blue-600 hover:text-blue-800 font-medium">Sign in here</a>
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
@include('components.notifications')
<script>
    // Enhanced notification helpers
    function showSuccessAndRedirect(message, redirectUrl) {
        showSuccess(message);
        setTimeout(() => {
            window.location.href = redirectUrl;
        }, 2000);
    }
</script>
<script>
    let selectedRole = '';

    function selectRole(role) {
        // Remove previous selection
        document.querySelectorAll('.role-option').forEach(option => {
            option.classList.remove('selected');
        });

        // Add selection to clicked option
        document.querySelector(`[data-role="${role}"]`).classList.add('selected');
        
        // Update hidden input
        document.getElementById('selected_role').value = role;
        selectedRole = role;
    }

    // Handle form submission
    document.getElementById('registerForm').addEventListener('submit', function(e) {
        e.preventDefault();

        // Validate password confirmation
        const password = document.getElementById('password').value;
        const passwordConfirmation = document.getElementById('password_confirmation').value;

        if (password !== passwordConfirmation) {
            Swal.fire({
                icon: 'error',
                title: 'Registration Failed',
                text: 'Passwords do not match!',
                confirmButtonText: 'OK',
            });
            return;
        }

        if (!selectedRole) {
            showError('Please select a role!');
            return;
        }

        // Show loading state
        const submitBtn = this.querySelector('button[type="submit"]');
        const originalText = submitBtn.textContent;
        submitBtn.textContent = 'Creating Account...';
        submitBtn.disabled = true;

        // Get form data
        const formData = new FormData(this);

        // Send registration request
        fetch('/register', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: formData
        })
        .then(async response => {
            let data;
            try {
                data = await response.json();
            } catch (e) {
                // Not JSON, show error
                Swal.fire({
                    icon: 'error',
                    title: 'Registration Failed',
                    text: 'Invalid server response.'
                });
                console.error('Non-JSON response:', response);
                return;
            }
            console.log('Registration response:', data);
            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Account successfully registered!',
                    text: data.message,
                    confirmButtonText: 'OK',
                }).then(() => {
                    window.location.href = '/';
                });
            } else {
                let errorMessage = data.message || 'Registration failed. Please try again.';
                // Show validation errors if available
                if (data.errors) {
                    errorMessage = 'Please fix the following errors:\n';
                    Object.keys(data.errors).forEach(field => {
                        errorMessage += `• ${data.errors[field][0]}\n`;
                    });
                }
                Swal.fire({
                    icon: 'error',
                    title: 'Registration Failed',
                    text: errorMessage,
                    confirmButtonText: 'OK',
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showError('An error occurred. Please try again.');
        })
        .finally(() => {
            // Reset button state
            submitBtn.textContent = originalText;
            submitBtn.disabled = false;
        });
    });

    // Add interactive effects
    document.addEventListener('DOMContentLoaded', function() {
        const roleOptions = document.querySelectorAll('.role-option');
        
        roleOptions.forEach(option => {
            option.addEventListener('mouseenter', function() {
                if (!this.classList.contains('selected')) {
                    this.style.transform = 'translateY(-2px)';
                }
            });
            
            option.addEventListener('mouseleave', function() {
                if (!this.classList.contains('selected')) {
                    this.style.transform = 'translateY(0)';
                }
            });
        });
    });

    // Show/hide password toggle for register
    const registerPasswordInput = document.getElementById('password');
    const toggleRegisterPassword = document.getElementById('toggleRegisterPassword');
    const registerEyeIcon = document.getElementById('registerEyeIcon');
    if (toggleRegisterPassword && registerPasswordInput) {
        toggleRegisterPassword.addEventListener('click', function() {
            const isPassword = registerPasswordInput.type === 'password';
            registerPasswordInput.type = isPassword ? 'text' : 'password';
            registerEyeIcon.innerHTML = isPassword
                ? '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.477 0-8.268-2.943-9.542-7a9.956 9.956 0 012.042-3.368m3.087-2.933A9.956 9.956 0 0112 5c4.477 0 8.268 2.943 9.542 7a9.973 9.973 0 01-4.043 5.306M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3l18 18" />'
                : '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />';
        });
    }
    const registerPasswordInputConfirm = document.getElementById('password_confirmation');
    const toggleRegisterPasswordConfirm = document.getElementById('toggleRegisterPasswordConfirm');
    const registerEyeIconConfirm = document.getElementById('registerEyeIconConfirm');
    if (toggleRegisterPasswordConfirm && registerPasswordInputConfirm) {
        toggleRegisterPasswordConfirm.addEventListener('click', function() {
            const isPassword = registerPasswordInputConfirm.type === 'password';
            registerPasswordInputConfirm.type = isPassword ? 'text' : 'password';
            registerEyeIconConfirm.innerHTML = isPassword
                ? '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.477 0-8.268-2.943-9.542-7a9.956 9.956 0 012.042-3.368m3.087-2.933A9.956 9.956 0 0112 5c4.477 0 8.268 2.943 9.542 7a9.973 9.973 0 01-4.043 5.306M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3l18 18" />'
                : '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />';
        });
    }
</script>
@endsection 
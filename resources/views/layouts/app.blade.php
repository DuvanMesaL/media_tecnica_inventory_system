<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'School Inventory System')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#eff6ff',
                            100: '#dbeafe',
                            200: '#bfdbfe',
                            300: '#93c5fd',
                            400: '#60a5fa',
                            500: '#3b82f6',
                            600: '#2563eb',
                            700: '#1d4ed8',
                            800: '#1e40af',
                            900: '#1e3a8a',
                        },
                        secondary: {
                            50: '#f8fafc',
                            100: '#f1f5f9',
                            200: '#e2e8f0',
                            300: '#cbd5e1',
                            400: '#94a3b8',
                            500: '#64748b',
                            600: '#475569',
                            700: '#334155',
                            800: '#1e293b',
                            900: '#0f172a',
                        }
                    },
                    animation: {
                        'fade-in': 'fadeIn 0.5s ease-in-out',
                        'slide-in': 'slideIn 0.3s ease-out',
                        'bounce-subtle': 'bounceSubtle 2s infinite',
                    },
                    keyframes: {
                        fadeIn: {
                            '0%': { opacity: '0' },
                            '100%': { opacity: '1' },
                        },
                        slideIn: {
                            '0%': { transform: 'translateX(-100%)' },
                            '100%': { transform: 'translateX(0)' },
                        },
                        bounceSubtle: {
                            '0%, 100%': { transform: 'translateY(0)' },
                            '50%': { transform: 'translateY(-5px)' },
                        }
                    }
                }
            }
        }
    </script>
    <style>
        .sidebar-transition {
            transition: transform 0.3s ease-in-out;
        }

        .notification-badge {
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }

        .hover-lift {
            transition: all 0.3s ease-in-out;
        }

        .hover-lift:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        .glass-effect {
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.9);
        }

        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .card-shadow {
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }
    </style>
</head>
<body class="bg-gradient-to-br from-gray-50 to-gray-100 font-sans">
    <div class="min-h-screen flex">
        <!-- Mobile menu overlay -->
        <div id="mobile-menu-overlay" class="fixed inset-0 z-40 bg-gray-900 bg-opacity-75 hidden lg:hidden transition-opacity"></div>

        <!-- Sidebar -->
        <div id="sidebar" class="gradient-bg text-white w-64 space-y-6 py-7 px-2 fixed inset-y-0 left-0 transform -translate-x-full lg:translate-x-0 sidebar-transition z-50 shadow-2xl">
            <!-- Logo -->
            <div class="text-white flex items-center space-x-3 px-4">
                <div class="bg-white bg-opacity-20 p-3 rounded-xl backdrop-blur-sm">
                    <i class="fas fa-tools text-2xl"></i>
                </div>
                <div>
                    <span class="text-xl font-bold">Inventory</span>
                    <div class="text-xs text-gray-200">Management System</div>
                </div>
            </div>

            <!-- User Info -->
            <div class="px-4 py-4 bg-white bg-opacity-10 rounded-xl mx-2 backdrop-blur-sm">
                <div class="flex items-center space-x-3">
                    <div class="bg-white bg-opacity-20 p-3 rounded-full">
                        <i class="fas fa-user text-sm"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-white truncate">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-gray-200">{{ ucfirst(auth()->user()->getRoleNames()->first() ?? 'User') }}</p>
                    </div>
                </div>
            </div>

            <!-- Navigation -->
            <nav class="space-y-2 px-2">
                <a href="{{ route('dashboard') }}"
                   class="text-white hover:bg-white hover:bg-opacity-20 group flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200 {{ request()->routeIs('dashboard') ? 'bg-white bg-opacity-20 shadow-lg' : '' }}">
                    <i class="fas fa-tachometer-alt mr-3 text-lg"></i>
                    Dashboard
                </a>

                <a href="{{ route('tools.index') }}"
                   class="text-white hover:bg-white hover:bg-opacity-20 group flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200 {{ request()->routeIs('tools.*') ? 'bg-white bg-opacity-20 shadow-lg' : '' }}">
                    <i class="fas fa-wrench mr-3 text-lg"></i>
                    Tools
                    @php
                        $lowStockCount = \App\Models\Tool::where('available_quantity', '<=', 5)->where('available_quantity', '>', 0)->count();
                        $outOfStockCount = \App\Models\Tool::where('available_quantity', 0)->count();
                    @endphp
                    @if($lowStockCount > 0 || $outOfStockCount > 0)
                        <span class="ml-auto bg-red-500 text-white text-xs rounded-full px-2 py-1 notification-badge animate-bounce-subtle">
                            {{ $lowStockCount + $outOfStockCount }}
                        </span>
                    @endif
                </a>

                <a href="{{ route('loans.index') }}"
                   class="text-white hover:bg-white hover:bg-opacity-20 group flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200 {{ request()->routeIs('loans.*') ? 'bg-white bg-opacity-20 shadow-lg' : '' }}">
                    <i class="fas fa-handshake mr-3 text-lg"></i>
                    Loans
                    @php
                        $pendingLoans = \App\Models\ToolLoan::where('status', 'pending')->count();
                        $overdueLoans = \App\Models\ToolLoan::where('status', 'delivered')->where('expected_return_date', '<', now())->count();
                    @endphp
                    @if($pendingLoans > 0 || $overdueLoans > 0)
                        <span class="ml-auto bg-yellow-500 text-white text-xs rounded-full px-2 py-1 notification-badge animate-bounce-subtle">
                            {{ $pendingLoans + $overdueLoans }}
                        </span>
                    @endif
                </a>

                @can('view reports')
                <a href="{{ route('reports.index') }}"
                   class="text-white hover:bg-white hover:bg-opacity-20 group flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200 {{ request()->routeIs('reports.*') ? 'bg-white bg-opacity-20 shadow-lg' : '' }}">
                    <i class="fas fa-chart-bar mr-3 text-lg"></i>
                    Reports
                </a>
                @endcan

                @can('manage system')
                <div class="pt-4">
                    <div class="text-gray-200 px-4 py-2 text-xs font-semibold uppercase tracking-wider">
                        Administration
                    </div>
                    <a href="{{ route('warehouses.index') }}"
                       class="text-white hover:bg-white hover:bg-opacity-20 group flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200 {{ request()->routeIs('warehouses.*') ? 'bg-white bg-opacity-20 shadow-lg' : '' }}">
                        <i class="fas fa-warehouse mr-3 text-lg"></i>
                        Warehouses
                    </a>
                    <a href="{{ route('programs.index') }}"
                       class="text-white hover:bg-white hover:bg-opacity-20 group flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200 {{ request()->routeIs('programs.*') ? 'bg-white bg-opacity-20 shadow-lg' : '' }}">
                        <i class="fas fa-graduation-cap mr-3 text-lg"></i>
                        Programs
                    </a>
                    <a href="{{ route('users.index') }}"
                       class="text-white hover:bg-white hover:bg-opacity-20 group flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200 {{ request()->routeIs('users.*') ? 'bg-white bg-opacity-20 shadow-lg' : '' }}">
                        <i class="fas fa-users mr-3 text-lg"></i>
                        Users
                    </a>
                </div>
                @endcan
            </nav>

            <!-- Logout -->
            <div class="absolute bottom-4 left-2 right-2">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full text-white hover:bg-red-600 group flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200 bg-red-500 bg-opacity-80">
                        <i class="fas fa-sign-out-alt mr-3 text-lg"></i>
                        Sign Out
                    </button>
                </form>
            </div>
        </div>

        <!-- Main content -->
        <div class="flex-1 flex flex-col overflow-hidden lg:ml-64">
            <!-- Top navigation -->
            <header class="glass-effect border-b border-gray-200 shadow-sm">
                <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between items-center">
                        <div class="flex items-center">
                            <!-- Mobile menu button -->
                            <button id="mobile-menu-button" class="lg:hidden text-gray-500 hover:text-gray-700 focus:outline-none focus:text-gray-700 mr-4 p-2 rounded-lg hover:bg-gray-100 transition-colors">
                                <i class="fas fa-bars text-xl"></i>
                            </button>
                            <div>
                                <h1 class="text-3xl font-bold bg-gradient-to-r from-primary-600 to-purple-600 bg-clip-text text-transparent">@yield('header', 'Dashboard')</h1>
                                <div class="text-sm text-gray-500 flex items-center mt-1">
                                    <i class="fas fa-calendar-alt mr-2"></i>
                                    {{ now()->format('l, F j, Y') }}
                                </div>
                            </div>
                        </div>

                        <!-- Quick Actions -->
                        <div class="flex items-center space-x-4">
                            <!-- Notifications -->
                            <div class="relative">
                                <button class="text-gray-500 hover:text-gray-700 focus:outline-none focus:text-gray-700 p-2 rounded-lg hover:bg-gray-100 transition-colors">
                                    <i class="fas fa-bell text-xl"></i>
                                    @if($pendingLoans > 0 || $overdueLoans > 0 || $lowStockCount > 0)
                                        <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-6 w-6 flex items-center justify-center notification-badge animate-bounce-subtle">
                                            {{ $pendingLoans + $overdueLoans + $lowStockCount }}
                                        </span>
                                    @endif
                                </button>
                            </div>

                            <!-- Quick Add -->
                            <div class="relative">
                                <button id="quick-add-button" class="bg-gradient-to-r from-primary-600 to-primary-700 hover:from-primary-700 hover:to-primary-800 text-white px-6 py-2 rounded-xl text-sm font-medium transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                                    <i class="fas fa-plus mr-2"></i>Quick Add
                                </button>
                                <div id="quick-add-menu" class="hidden absolute right-0 mt-2 w-56 bg-white rounded-xl shadow-2xl z-10 border border-gray-100 overflow-hidden">
                                    <div class="py-2">
                                        @can('manage tools')
                                        <a href="{{ route('tools.create') }}" class="block px-4 py-3 text-sm text-gray-700 hover:bg-primary-50 hover:text-primary-700 transition-colors">
                                            <i class="fas fa-wrench mr-3 text-primary-500"></i>Add Tool
                                        </a>
                                        @endcan
                                        <a href="{{ route('loans.create') }}" class="block px-4 py-3 text-sm text-gray-700 hover:bg-primary-50 hover:text-primary-700 transition-colors">
                                            <i class="fas fa-handshake mr-3 text-primary-500"></i>Request Loan
                                        </a>
                                        @can('manage users')
                                        <a href="{{ route('register') }}" class="block px-4 py-3 text-sm text-gray-700 hover:bg-primary-50 hover:text-primary-700 transition-colors">
                                            <i class="fas fa-user-plus mr-3 text-primary-500"></i>Add User
                                        </a>
                                        @endcan
                                    </div>
                                </div>
                            </div>

                            <!-- User Profile -->
                            <div class="flex items-center space-x-3 bg-white bg-opacity-50 rounded-xl px-4 py-2">
                                <div class="text-right">
                                    <div class="text-sm font-medium text-gray-900">{{ auth()->user()->name }}</div>
                                    <div class="text-xs text-gray-500">{{ ucfirst(auth()->user()->getRoleNames()->first() ?? 'User') }}</div>
                                </div>
                                <div class="bg-gradient-to-r from-primary-500 to-primary-600 p-2 rounded-full">
                                    <i class="fas fa-user text-white text-sm"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page content -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gradient-to-br from-gray-50 to-gray-100">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    <!-- Flash Messages -->
                    @if(session('success'))
                        <div class="mb-6 bg-gradient-to-r from-green-50 to-emerald-50 border border-green-200 text-green-800 px-6 py-4 rounded-xl flex items-center shadow-lg animate-fade-in">
                            <i class="fas fa-check-circle mr-3 text-green-600 text-lg"></i>
                            <div>
                                <strong>Success!</strong> {{ session('success') }}
                            </div>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="mb-6 bg-gradient-to-r from-red-50 to-pink-50 border border-red-200 text-red-800 px-6 py-4 rounded-xl flex items-center shadow-lg animate-fade-in">
                            <i class="fas fa-exclamation-circle mr-3 text-red-600 text-lg"></i>
                            <div>
                                <strong>Error!</strong> {{ session('error') }}
                            </div>
                        </div>
                    @endif

                    @if(session('warning'))
                        <div class="mb-6 bg-gradient-to-r from-yellow-50 to-amber-50 border border-yellow-200 text-yellow-800 px-6 py-4 rounded-xl flex items-center shadow-lg animate-fade-in">
                            <i class="fas fa-exclamation-triangle mr-3 text-yellow-600 text-lg"></i>
                            <div>
                                <strong>Warning!</strong> {{ session('warning') }}
                            </div>
                        </div>
                    @endif

                    @if(session('info'))
                        <div class="mb-6 bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 text-blue-800 px-6 py-4 rounded-xl flex items-center shadow-lg animate-fade-in">
                            <i class="fas fa-info-circle mr-3 text-blue-600 text-lg"></i>
                            <div>
                                <strong>Info:</strong> {{ session('info') }}
                            </div>
                        </div>
                    @endif

                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    <!-- JavaScript -->
    <script>
        // Mobile menu toggle
        const mobileMenuButton = document.getElementById('mobile-menu-button');
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('mobile-menu-overlay');

        function toggleMobileMenu() {
            sidebar.classList.toggle('-translate-x-full');
            overlay.classList.toggle('hidden');
        }

        mobileMenuButton?.addEventListener('click', toggleMobileMenu);
        overlay?.addEventListener('click', toggleMobileMenu);

        // Quick add menu toggle
        const quickAddButton = document.getElementById('quick-add-button');
        const quickAddMenu = document.getElementById('quick-add-menu');

        quickAddButton?.addEventListener('click', function(e) {
            e.stopPropagation();
            quickAddMenu.classList.toggle('hidden');
        });

        // Close quick add menu when clicking outside
        document.addEventListener('click', function() {
            quickAddMenu?.classList.add('hidden');
        });

        // Auto-hide flash messages
        setTimeout(function() {
            const flashMessages = document.querySelectorAll('[class*="bg-gradient-to-r"]');
            flashMessages.forEach(function(message) {
                if (message.textContent.includes('Success!') || message.textContent.includes('Info:')) {
                    message.style.transition = 'opacity 0.5s ease-out, transform 0.5s ease-out';
                    message.style.opacity = '0';
                    message.style.transform = 'translateY(-20px)';
                    setTimeout(function() {
                        message.remove();
                    }, 500);
                }
            });
        }, 5000);

        // Add loading states to forms
        document.querySelectorAll('form').forEach(function(form) {
            form.addEventListener('submit', function() {
                const submitButton = form.querySelector('button[type="submit"]');
                if (submitButton) {
                    submitButton.disabled = true;
                    submitButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Processing...';
                }
            });
        });

        // Enhanced table interactions
        document.querySelectorAll('table tbody tr').forEach(function(row) {
            row.addEventListener('mouseenter', function() {
                this.style.backgroundColor = '#f8fafc';
                this.style.transform = 'scale(1.01)';
                this.style.transition = 'all 0.2s ease-in-out';
            });
            row.addEventListener('mouseleave', function() {
                this.style.backgroundColor = '';
                this.style.transform = '';
            });
        });

        // Add smooth scrolling
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                document.querySelector(this.getAttribute('href')).scrollIntoView({
                    behavior: 'smooth'
                });
            });
        });
    </script>
</body>
</html>

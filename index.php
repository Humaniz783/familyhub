<?php
require_once __DIR__ . '/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_shopping'])) {
        $item = trim($_POST['item'] ?? '');
        if ($item !== '') {
            $stmt = $pdo->prepare('INSERT INTO shopping_list (item) VALUES (?)');
            $stmt->execute([$item]);
        }
    } elseif (isset($_POST['add_task'])) {
        $task = trim($_POST['task'] ?? '');
        if ($task !== '') {
            $stmt = $pdo->prepare('INSERT INTO tasks (task) VALUES (?)');
            $stmt->execute([$task]);
        }
    } elseif (isset($_POST['delete_shopping'])) {
        $id = (int)($_POST['id'] ?? 0);
        $pdo->prepare('DELETE FROM shopping_list WHERE id = ?')->execute([$id]);
    } elseif (isset($_POST['delete_task'])) {
        $id = (int)($_POST['id'] ?? 0);
        $pdo->prepare('DELETE FROM tasks WHERE id = ?')->execute([$id]);
    }
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}

$shoppingItems = $pdo->query('SELECT id, item, checked FROM shopping_list ORDER BY id DESC')->fetchAll();
$tasks = $pdo->query('SELECT id, task, completed FROM tasks ORDER BY id DESC')->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FamilyHub - Family Collaboration App</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .sidebar {
            transition: all 0.3s ease;
        }
        .active-tab {
            border-bottom: 3px solid #3b82f6;
            color: #3b82f6;
        }
        .notification-badge {
            position: absolute;
            top: -5px;
            right: -5px;
        }
        .chat-message:hover .message-actions {
            opacity: 1;
        }
        .message-actions {
            opacity: 0;
            transition: opacity 0.2s ease;
        }
        .progress-ring__circle {
            transition: stroke-dashoffset 0.35s;
            transform: rotate(-90deg);
            transform-origin: 50% 50%;
        }
    </style>
</head>
<body class="bg-gray-100 font-sans">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <div class="sidebar bg-white w-64 md:w-20 lg:w-64 flex-shrink-0 shadow-lg">
            <div class="p-4 flex items-center justify-between border-b">
                <div class="flex items-center">
                    <i class="fas fa-home text-blue-500 text-2xl mr-3"></i>
                    <span class="text-xl font-bold hidden md:block lg:block">FamilyHub</span>
                </div>
                <button id="sidebarToggle" class="text-gray-500 focus:outline-none md:hidden lg:hidden">
                    <i class="fas fa-bars"></i>
                </button>
            </div>
            <div class="p-4 border-b">
                <div class="flex items-center">
                    <div class="relative">
                        <img src="https://randomuser.me/api/portraits/women/44.jpg" alt="Profile" class="w-10 h-10 rounded-full">
                        <span class="absolute bottom-0 right-0 w-3 h-3 bg-green-500 rounded-full border-2 border-white"></span>
                    </div>
                    <div class="ml-3 hidden md:hidden lg:block">
                        <p class="font-medium">Sarah Johnson</p>
                        <p class="text-xs text-gray-500">Admin</p>
                    </div>
                </div>
            </div>
            <nav class="mt-4">
                <div class="px-2">
                    <button class="w-full flex items-center p-3 text-white bg-blue-500 rounded-lg">
                        <i class="fas fa-home mr-3"></i>
                        <span class="hidden md:hidden lg:inline">Dashboard</span>
                    </button>
                </div>
                <div class="px-2 mt-2">
                    <button class="w-full flex items-center p-3 text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">
                        <i class="fas fa-wallet mr-3"></i>
                        <span class="hidden md:hidden lg:inline">Finances</span>
                    </button>
                </div>
                <div class="px-2 mt-2">
                    <button class="w-full flex items-center p-3 text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">
                        <i class="fas fa-shopping-cart mr-3"></i>
                        <span class="hidden md:hidden lg:inline">Shopping</span>
                        <span class="ml-auto bg-red-500 text-white text-xs px-2 py-1 rounded-full hidden md:hidden lg:inline">3</span>
                    </button>
                </div>
                <div class="px-2 mt-2">
                    <button class="w-full flex items-center p-3 text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">
                        <i class="fas fa-tasks mr-3"></i>
                        <span class="hidden md:hidden lg:inline">Tasks</span>
                    </button>
                </div>
                <div class="px-2 mt-2">
                    <button class="w-full flex items-center p-3 text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">
                        <i class="fas fa-bell mr-3"></i>
                        <span class="hidden md:hidden lg:inline">Reminders</span>
                        <span class="ml-auto bg-yellow-500 text-white text-xs px-2 py-1 rounded-full hidden md:hidden lg:inline">2</span>
                    </button>
                </div>
                <div class="px-2 mt-2">
                    <button class="w-full flex items-center p-3 text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">
                        <i class="fas fa-comments mr-3"></i>
                        <span class="hidden md:hidden lg:inline">Chat</span>
                        <span class="ml-auto bg-blue-500 text-white text-xs px-2 py-1 rounded-full hidden md:hidden lg:inline">5</span>
                    </button>
                </div>
                <div class="px-2 mt-2">
                    <button class="w-full flex items-center p-3 text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">
                        <i class="fas fa-home mr-3"></i>
                        <span class="hidden md:hidden lg:inline">Assets</span>
                    </button>
                </div>
                <div class="px-2 mt-2">
                    <button class="w-full flex items-center p-3 text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">
                        <i class="fas fa-file-invoice-dollar mr-3"></i>
                        <span class="hidden md:hidden lg:inline">Shared Expenses</span>
                    </button>
                </div>
            </nav>
            <div class="absolute bottom-0 w-full p-4 border-t">
                <button class="w-full flex items-center p-3 text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">
                    <i class="fas fa-cog mr-3"></i>
                    <span class="hidden md:hidden lg:inline">Settings</span>
                </button>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Top Navigation -->
            <header class="bg-white shadow-sm">
                <div class="flex items-center justify-between px-6 py-4">
                    <h1 class="text-2xl font-semibold text-gray-800">Family Dashboard</h1>
                    <div class="flex items-center space-x-4">
                        <div class="relative">
                            <button class="text-gray-500 focus:outline-none">
                                <i class="fas fa-bell text-xl"></i>
                                <span class="notification-badge bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">3</span>
                            </button>
                        </div>
                        <div class="relative">
                            <button class="text-gray-500 focus:outline-none">
                                <i class="fas fa-envelope text-xl"></i>
                                <span class="notification-badge bg-blue-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">5</span>
                            </button>
                        </div>
                        <div class="relative">
                            <img src="https://randomuser.me/api/portraits/women/44.jpg" alt="Profile" class="w-10 h-10 rounded-full">
                        </div>
                    </div>
                </div>
                <div class="border-b border-gray-200">
                    <nav class="flex -mb-px">
                        <button class="mr-8 py-4 px-1 border-b-2 font-medium text-sm active-tab">
                            Overview
                        </button>
                        <button class="mr-8 py-4 px-1 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700 hover:border-gray-300">
                            Finances
                        </button>
                        <button class="mr-8 py-4 px-1 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700 hover:border-gray-300">
                            Shopping
                        </button>
                        <button class="mr-8 py-4 px-1 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700 hover:border-gray-300">
                            Tasks
                        </button>
                        <button class="mr-8 py-4 px-1 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700 hover:border-gray-300">
                            Chat
                        </button>
                    </nav>
                </div>
            </header>

            <!-- Main Content Area -->
            <main class="flex-1 overflow-y-auto p-6 bg-gray-100">
                <!-- Welcome Banner -->
                <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl p-6 text-white mb-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-2xl font-bold">Welcome back, Sarah!</h2>
                            <p class="mt-2">Here's what's happening with your family today.</p>
                        </div>
                        <button class="bg-white text-blue-600 px-4 py-2 rounded-lg font-medium hover:bg-blue-50 transition-colors">
                            Quick Add
                        </button>
                    </div>
                </div>

                <!-- Stats Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                    <div class="bg-white rounded-xl shadow p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500">Family Budget</p>
                                <h3 class="text-2xl font-bold mt-2">$4,250.00</h3>
                                <p class="text-green-500 text-sm mt-1">+12% from last month</p>
                            </div>
                            <div class="bg-blue-100 p-3 rounded-full">
                                <i class="fas fa-wallet text-blue-500 text-xl"></i>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white rounded-xl shadow p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500">Shopping List</p>
                                <h3 class="text-2xl font-bold mt-2">8 Items</h3>
                                <p class="text-red-500 text-sm mt-1">3 urgent</p>
                            </div>
                            <div class="bg-green-100 p-3 rounded-full">
                                <i class="fas fa-shopping-cart text-green-500 text-xl"></i>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white rounded-xl shadow p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500">Pending Tasks</p>
                                <h3 class="text-2xl font-bold mt-2">5 Tasks</h3>
                                <p class="text-yellow-500 text-sm mt-1">2 overdue</p>
                            </div>
                            <div class="bg-yellow-100 p-3 rounded-full">
                                <i class="fas fa-tasks text-yellow-500 text-xl"></i>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white rounded-xl shadow p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500">Family Assets</p>
                                <h3 class="text-2xl font-bold mt-2">12 Items</h3>
                                <p class="text-blue-500 text-sm mt-1">2 need maintenance</p>
                            </div>
                            <div class="bg-purple-100 p-3 rounded-full">
                                <i class="fas fa-home text-purple-500 text-xl"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Activity and Quick Actions -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Recent Transactions -->
                    <div class="lg:col-span-2 bg-white rounded-xl shadow p-6">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-lg font-semibold">Recent Transactions</h3>
                            <button class="text-blue-500 hover:text-blue-700 text-sm font-medium">View All</button>
                        </div>
                        <div class="space-y-4">
                            <div class="flex items-center justify-between p-3 hover:bg-gray-50 rounded-lg transition-colors">
                                <div class="flex items-center">
                                    <div class="bg-blue-100 p-2 rounded-lg">
                                        <i class="fas fa-shopping-bag text-blue-500"></i>
                                    </div>
                                    <div class="ml-4">
                                        <p class="font-medium">Grocery Shopping</p>
                                        <p class="text-sm text-gray-500">Supermarket</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="font-medium text-red-500">-$85.20</p>
                                    <p class="text-sm text-gray-500">Today, 10:30 AM</p>
                                </div>
                            </div>
                            <div class="flex items-center justify-between p-3 hover:bg-gray-50 rounded-lg transition-colors">
                                <div class="flex items-center">
                                    <div class="bg-green-100 p-2 rounded-lg">
                                        <i class="fas fa-dollar-sign text-green-500"></i>
                                    </div>
                                    <div class="ml-4">
                                        <p class="font-medium">Salary Deposit</p>
                                        <p class="text-sm text-gray-500">John's Salary</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="font-medium text-green-500">+$3,500.00</p>
                                    <p class="text-sm text-gray-500">Yesterday, 9:00 AM</p>
                                </div>
                            </div>
                            <div class="flex items-center justify-between p-3 hover:bg-gray-50 rounded-lg transition-colors">
                                <div class="flex items-center">
                                    <div class="bg-purple-100 p-2 rounded-lg">
                                        <i class="fas fa-lightbulb text-purple-500"></i>
                                    </div>
                                    <div class="ml-4">
                                        <p class="font-medium">Electricity Bill</p>
                                        <p class="text-sm text-gray-500">Utilities</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="font-medium text-red-500">-$120.75</p>
                                    <p class="text-sm text-gray-500">Mar 15, 2:15 PM</p>
                                </div>
                            </div>
                            <div class="flex items-center justify-between p-3 hover:bg-gray-50 rounded-lg transition-colors">
                                <div class="flex items-center">
                                    <div class="bg-yellow-100 p-2 rounded-lg">
                                        <i class="fas fa-utensils text-yellow-500"></i>
                                    </div>
                                    <div class="ml-4">
                                        <p class="font-medium">Dinner Out</p>
                                        <p class="text-sm text-gray-500">Restaurant</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="font-medium text-red-500">-$65.40</p>
                                    <p class="text-sm text-gray-500">Mar 14, 7:30 PM</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="bg-white rounded-xl shadow p-6">
                        <h3 class="text-lg font-semibold mb-6">Quick Actions</h3>
                        <div class="grid grid-cols-2 gap-4">
                            <button class="flex flex-col items-center justify-center p-4 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors">
                                <div class="bg-blue-100 p-3 rounded-full mb-2">
                                    <i class="fas fa-plus text-blue-500"></i>
                                </div>
                                <span class="text-sm font-medium">Add Expense</span>
                            </button>
                            <button class="flex flex-col items-center justify-center p-4 bg-green-50 rounded-lg hover:bg-green-100 transition-colors">
                                <div class="bg-green-100 p-3 rounded-full mb-2">
                                    <i class="fas fa-cart-plus text-green-500"></i>
                                </div>
                                <span class="text-sm font-medium">Add to Shopping</span>
                            </button>
                            <button class="flex flex-col items-center justify-center p-4 bg-yellow-50 rounded-lg hover:bg-yellow-100 transition-colors">
                                <div class="bg-yellow-100 p-3 rounded-full mb-2">
                                    <i class="fas fa-tasks text-yellow-500"></i>
                                </div>
                                <span class="text-sm font-medium">Create Task</span>
                            </button>
                            <button class="flex flex-col items-center justify-center p-4 bg-purple-50 rounded-lg hover:bg-purple-100 transition-colors">
                                <div class="bg-purple-100 p-3 rounded-full mb-2">
                                    <i class="fas fa-bell text-purple-500"></i>
                                </div>
                                <span class="text-sm font-medium">Set Reminder</span>
                            </button>
                            <button class="flex flex-col items-center justify-center p-4 bg-red-50 rounded-lg hover:bg-red-100 transition-colors">
                                <div class="bg-red-100 p-3 rounded-full mb-2">
                                    <i class="fas fa-comment text-red-500"></i>
                                </div>
                                <span class="text-sm font-medium">Family Chat</span>
                            </button>
                            <button class="flex flex-col items-center justify-center p-4 bg-indigo-50 rounded-lg hover:bg-indigo-100 transition-colors">
                                <div class="bg-indigo-100 p-3 rounded-full mb-2">
                                    <i class="fas fa-home text-indigo-500"></i>
                                </div>
                                <span class="text-sm font-medium">Add Asset</span>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Shopping List and Tasks -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-6">
                    <!-- Shopping List -->
                    <div class="bg-white rounded-xl shadow p-6">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-lg font-semibold">Shopping List</h3>
                            <button class="text-blue-500 hover:text-blue-700 text-sm font-medium">View All</button>
                        </div>
                        <div class="space-y-3">
                            <?php foreach ($shoppingItems as $row): ?>
                                <div class="flex items-center justify-between p-3 hover:bg-gray-50 rounded-lg transition-colors">
                                    <div class="flex items-center">
                                        <input type="checkbox" class="rounded text-blue-500" <?= $row['checked'] ? 'checked' : '' ?> disabled>
                                        <span class="ml-3"><?= htmlspecialchars($row['item']) ?></span>
                                    </div>
                                    <form method="post">
                                        <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                        <button name="delete_shopping" class="ml-3 text-gray-400 hover:text-red-500">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <div class="mt-4 flex">
                            <form method="post" class="flex w-full">
                                <input type="text" name="item" placeholder="Add new item..." class="flex-1 border rounded-l-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <button name="add_shopping" class="bg-blue-500 text-white px-4 py-2 rounded-r-lg hover:bg-blue-600 transition-colors">
                                    Add
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Family Tasks -->
                    <div class="bg-white rounded-xl shadow p-6">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-lg font-semibold">Family Tasks</h3>
                            <button class="text-blue-500 hover:text-blue-700 text-sm font-medium">View All</button>
                        </div>
                        <div class="space-y-3">
                            <?php foreach ($tasks as $task): ?>
                                <div class="flex items-center justify-between p-3 hover:bg-gray-50 rounded-lg transition-colors">
                                    <div class="flex items-center">
                                        <input type="checkbox" class="rounded text-blue-500" <?= $task['completed'] ? 'checked' : '' ?> disabled>
                                        <span class="ml-3 <?= $task['completed'] ? 'line-through text-gray-500' : '' ?>"><?= htmlspecialchars($task['task']) ?></span>
                                    </div>
                                    <form method="post">
                                        <input type="hidden" name="id" value="<?= $task['id'] ?>">
                                        <button name="delete_task" class="ml-3 text-gray-400 hover:text-red-500">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <div class="mt-4 flex">
                            <form method="post" class="flex w-full">
                                <input type="text" name="task" placeholder="Add new task..." class="flex-1 border rounded-l-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <button name="add_task" class="bg-blue-500 text-white px-4 py-2 rounded-r-lg hover:bg-blue-600 transition-colors">
                                    Add
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Family Chat Preview -->
                <div class="bg-white rounded-xl shadow p-6 mt-6">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-semibold">Family Chat</h3>
                        <button class="text-blue-500 hover:text-blue-700 text-sm font-medium">View All Messages</button>
                    </div>
                    <div class="space-y-4">
                        <div class="flex items-start">
                            <img src="https://randomuser.me/api/portraits/men/32.jpg" alt="John" class="w-10 h-10 rounded-full">
                            <div class="ml-3">
                                <div class="bg-gray-100 p-3 rounded-lg">
                                    <div class="flex items-center">
                                        <span class="font-medium">John</span>
                                        <span class="ml-2 text-xs text-gray-500">10:30 AM</span>
                                    </div>
                                    <p class="mt-1">Has anyone seen my blue jacket? I can't find it anywhere.</p>
                                </div>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <img src="https://randomuser.me/api/portraits/women/44.jpg" alt="Sarah" class="w-10 h-10 rounded-full">
                            <div class="ml-3">
                                <div class="bg-blue-100 p-3 rounded-lg">
                                    <div class="flex items-center">
                                        <span class="font-medium">Sarah</span>
                                        <span class="ml-2 text-xs text-gray-500">10:35 AM</span>
                                    </div>
                                    <p class="mt-1">I think it's in the hall closet. I'll check when I get home.</p>
                                </div>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <img src="https://randomuser.me/api/portraits/men/75.jpg" alt="Michael" class="w-10 h-10 rounded-full">
                            <div class="ml-3">
                                <div class="bg-gray-100 p-3 rounded-lg">
                                    <div class="flex items-center">
                                        <span class="font-medium">Michael</span>
                                        <span class="ml-2 text-xs text-gray-500">10:40 AM</span>
                                    </div>
                                    <p class="mt-1">Don't forget we have the family dinner at grandma's this Sunday at 2pm!</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-4 flex">
                        <input type="text" placeholder="Type a message..." class="flex-1 border rounded-l-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <button class="bg-blue-500 text-white px-4 py-2 rounded-r-lg hover:bg-blue-600 transition-colors">
                            Send
                        </button>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script>
        // Toggle sidebar on mobile
        document.getElementById('sidebarToggle').addEventListener('click', function() {
            const sidebar = document.querySelector('.sidebar');
            sidebar.classList.toggle('hidden');
        });

        // Tab switching functionality
        const tabs = document.querySelectorAll('nav button');
        tabs.forEach(tab => {
            tab.addEventListener('click', function() {
                tabs.forEach(t => t.classList.remove('active-tab'));
                this.classList.add('active-tab');
            });
        });

        // Simulate loading data
        setTimeout(() => {
            const loadingElements = document.querySelectorAll('.animate-pulse');
            loadingElements.forEach(el => el.classList.remove('animate-pulse'));
        }, 1000);
    </script>
</body>
</html>

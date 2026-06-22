 <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HR Admin Portal - Departments & Sidebar</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-[#f8fafc] text-slate-700 min-h-screen flex font-sans overflow-x-hidden">

    <aside class="w-64 bg-[#1e293b] text-slate-300 flex flex-col fixed h-full z-10">
        <div class="p-6 border-b border-slate-700">
            <h1 class="text-xl font-bold text-white tracking-wide">Admin Portal</h1>
            <p class="text-[10px] text-slate-400 mt-0.5">HR MANAGEMENT SYSTEM</p>
        </div>
        
        <nav class="flex-1 p-4 space-y-1">
            <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-slate-800 hover:text-white transition">
                <i class="fa-solid fa-chart-pie text-lg"></i>
                <span>Dashboard</span>
            </a>
            <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-slate-800 hover:text-white transition">
                <i class="fa-solid fa-users text-lg"></i>
                <span>Employees</span>
            </a>
            <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-lg bg-indigo-600 text-white font-medium transition">
                <i class="fa-solid fa-sitemap text-lg"></i>
                <span>Departments</span>
            </a>
            <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-slate-800 hover:text-white transition">
                <i class="fa-solid fa-calendar-check text-lg"></i>
                <span>Attendance</span>
            </a>
            <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-slate-800 hover:text-white transition">
                <i class="fa-solid fa-envelope-open-text text-lg"></i>
                <span>Leave Requests</span>
            </a>
            <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-slate-800 hover:text-white transition mt-auto">
                <i class="fa-solid fa-gear text-lg"></i>
                <span>Settings</span>
            </a>
        </nav>

        <div class="p-4 border-t border-slate-700 flex items-center gap-3">
            <div class="w-10 h-10 rounded-full bg-slate-500 flex items-center justify-center text-white font-bold">HR</div>
            <div>
                <p class="text-sm font-semibold text-white">HR Admin</p>
                <p class="text-xs text-slate-400">Admin Access</p>
            </div>
        </div>
    </aside>

    <main class="flex-1 pl-64 min-w-0 pr-0 transition-all duration-300" id="main-content">
        
        <header class="bg-white border-b border-slate-200 h-16 flex items-center justify-between px-8 sticky top-0 z-20">
            <div class="relative w-96">
                <i class="fa-solid fa-magnifying-glass absolute left-3 top-3.5 text-slate-400 text-sm"></i>
                <input type="text" placeholder="Search departments or managers..." 
                       class="w-full pl-10 pr-4 py-2 border border-slate-200 rounded-xl bg-slate-50 text-sm focus:outline-none">
            </div>

            <div class="flex items-center gap-4">
                <button class="text-slate-500 hover:bg-slate-100 p-2 rounded-xl transition"><i class="fa-solid fa-bell text-lg"></i></button>
                <button class="text-slate-500 hover:bg-slate-100 p-2 rounded-xl transition"><i class="fa-solid fa-gear text-lg"></i></button>
                <button class="bg-indigo-600 text-white px-4 py-2 rounded-xl text-sm font-medium hover:bg-indigo-700 transition flex items-center gap-2">
                    <i class="fa-solid fa-plus text-xs"></i>
                    <span>Add Employee</span>
                </button>
            </div>
        </header>
    <div class="p-8 space-y-6 max-w-[1400px]">
            
            <div class="flex justify-between items-center">
                <div>
                    <h2 class="text-2xl font-bold text-slate-900">Department Management</h2>
                    <p class="text-sm text-slate-500 mt-0.5">Monitor and manage institutional structures and leadership.</p>
                </div>
                <button class="bg-[#1e293b] hover:bg-slate-800 text-white text-xs font-bold px-4 py-2.5 rounded-xl transition flex items-center gap-2 shadow-sm">
                    <i class="fa-solid fa-building-user"></i>
                    <span>New Department</span>
                </button>
            </div>

            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="bg-white p-4 border border-slate-200 rounded-xl shadow-sm">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Total Departments</p>
                    <h3 class="text-xl font-extrabold text-slate-900 mt-1">12</h3>
                </div>
                <div class="bg-white p-4 border border-slate-200 rounded-xl shadow-sm">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Total Headcount</p>
                    <h3 class="text-xl font-extrabold text-slate-900 mt-1">452</h3>
                </div>
                <div class="bg-white p-4 border border-slate-200 rounded-xl shadow-sm">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Management Ratio</p>
                    <h3 class="text-xl font-extrabold text-slate-900 mt-1">1:37</h3>
                </div>
                <div class="bg-white p-4 border border-slate-200 rounded-xl shadow-sm">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Vacant Positions</p>
                    <h3 class="text-xl font-extrabold text-red-500 mt-1">08</h3>
                </div>
            </div>

            <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 text-[10px] font-bold text-slate-400 tracking-wider uppercase border-b border-slate-100">
                            <th class="py-3.5 px-6">Department Name</th>
                            <th class="py-3.5 px-6">Manager</th>
                            <th class="py-3.5 px-6">Employee Count</th>
                            <th class="py-3.5 px-6">Description</th>
                            <th class="py-3.5 px-6 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-xs font-medium text-slate-600">
                        
                        <tr class="hover:bg-slate-50/50 transition">
                            <td class="py-4 px-6 flex items-center gap-3">
                                <div class="w-8 h-8 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-sm"><i class="fa-solid fa-code"></i></div>
                                <span class="font-bold text-slate-900">Engineering</span>
                            </td>
                            <td class="py-4 px-6">Alex Sterling <span class="text-[10px] text-slate-400 block font-normal">CTO</span></td>
                            <td class="py-4 px-6 font-semibold">142</td>
                            <td class="py-4 px-6 text-slate-400">Product development, system architecture...</td>
                            <td class="py-4 px-6 text-center relative">
                                <button onclick="toggleSalesSidebar()" class="w-7 h-7 inline-flex items-center justify-center text-slate-400 hover:text-indigo-600 hover:bg-slate-100 rounded-lg transition relative group">
        <i class="fa-solid fa-pencil text-xs"></i>
                                    <span class="absolute bottom-8 left-1/2 -translate-x-1/2 bg-slate-800 text-white text-[10px] px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition pointer-events-none">Edit</span>
                                </button>
                                <button class="w-7 h-7 inline-flex items-center justify-center text-slate-400 hover:text-red-500 hover:bg-slate-100 rounded-lg transition ml-1">
                                    <i class="fa-solid fa-trash text-xs"></i>
                                </button>
                            </td>
                        </tr>

                        <tr class="hover:bg-slate-50/50 transition bg-slate-50/30">
                            <td class="py-4 px-6 flex items-center gap-3">
                                <div class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-sm"><i class="fa-solid fa-wallet"></i></div>
                                <span class="font-bold text-slate-900">Sales & Revenue</span>
                            </td>
                            <td class="py-4 px-6">Rachel Meyer <span class="text-[10px] text-slate-400 block font-normal">VP Sales</span></td>
                            <td class="py-4 px-6 font-semibold">86</td>
                            <td class="py-4 px-6 text-slate-400">Enterprise accounts, global strategies...</td>
                            <td class="py-4 px-6 text-center">
                                <button onclick="toggleSalesSidebar()" class="w-7 h-7 inline-flex items-center justify-center text-slate-400 hover:text-indigo-600 hover:bg-slate-100 rounded-lg transition">
                                    <i class="fa-solid fa-pencil text-xs"></i>
                                </button>
                                <button class="w-7 h-7 inline-flex items-center justify-center text-slate-400 hover:text-red-500 hover:bg-slate-100 rounded-lg transition ml-1">
                                    <i class="fa-solid fa-trash text-xs"></i>
                                </button>
                            </td>
                        </tr>

                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <div id="sales-sidebar" class="fixed top-0 right-0 h-full w-[400px] bg-white border-l border-slate-200 shadow-2xl z-30 translate-x-full transition-transform duration-300 ease-in-out flex flex-col">
        
        <div class="p-6 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
            <div>
                <h3 class="text-base font-bold text-slate-900">Sales Team</h3>
                <p class="text-[11px] text-slate-400 uppercase font-bold tracking-wider mt-0.5">Employees List</p>
            </div>
            <button onclick="toggleSalesSidebar()" class="w-8 h-8 text-slate-400 hover:text-slate-600 hover:bg-slate-100 rounded-full flex items-center justify-center transition">
                <i class="fa-solid fa-xmark text-sm"></i>
            </button>
        </div>

        <div class="flex-1 overflow-y-auto p-5 space-y-3">
            
            <div class="flex items-center justify-between p-4 border border-slate-100 rounded-xl hover:border-slate-200 hover:shadow-sm transition bg-white group">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-slate-100 text-slate-700 font-bold flex items-center justify-center text-xs border border-slate-200">
                        ED
                    </div>
                    <div>
                        <p class="text-xs font-bold text-slate-900">Ethan Davis</p>
                        <p class="text-[11px] text-slate-400 mt-0.5">Senior Lead Developer</p>
                    </div>
                </div>
                <i class="fa-solid fa-chevron-right text-slate-300 text-xs group-hover:text-slate-500 transition pr-1"></i>
            </div>
         <div class="flex items-center justify-between p-4 border border-slate-100 rounded-xl hover:border-slate-200 hover:shadow-sm transition bg-white group">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-700 font-bold flex items-center justify-center text-xs border border-indigo-100">
                        SC
                    </div>
                    <div>
                        <p class="text-xs font-bold text-slate-900">Sophia Chen</p>
                        <p class="text-[11px] text-slate-400 mt-0.5">Full Stack Engineer</p>
                    </div>
                </div>
                <i class="fa-solid fa-chevron-right text-slate-300 text-xs group-hover:text-slate-500 transition pr-1"></i>
            </div>

            <div class="flex items-center justify-between p-4 border border-slate-100 rounded-xl hover:border-slate-200 hover:shadow-sm transition bg-white group">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-700 font-bold flex items-center justify-center text-xs border border-emerald-100">
                        MK
                    </div>
                    <div>
                        <p class="text-xs font-bold text-slate-900">Marcus Knight</p>
                        <p class="text-[11px] text-slate-400 mt-0.5">Systems Architect</p>
                    </div>
                </div>
                <i class="fa-solid fa-chevron-right text-slate-300 text-xs group-hover:text-slate-500 transition pr-1"></i>
            </div>

        </div>

        <div class="p-4 border-t border-slate-100 bg-slate-50/50">
            <button class="w-full bg-[#20545c] hover:bg-[#173e44] text-white text-xs font-bold py-3 rounded-xl transition flex items-center justify-center gap-2 shadow-md">
                <i class="fa-solid fa-user-plus text-[10px]"></i>
                <span>Assign New Employee</span>
            </button>
        </div>
    </div>

    <script>
        function toggleSalesSidebar() {
            const sidebar = document.getElementById('sales-sidebar');
            const mainContent = document.getElementById('main-content');
            
            // Toggle sidebar slide animation
            if (sidebar.classList.contains('translate-x-full')) {
                sidebar.classList.remove('translate-x-full');
                sidebar.classList.add('translate-x-0');
            } else {
                sidebar.classList.remove('translate-x-0');
                sidebar.classList.add('translate-x-full');
            }
        }
    </script>

</body>
</html>
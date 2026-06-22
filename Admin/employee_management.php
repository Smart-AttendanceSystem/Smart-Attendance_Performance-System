 <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Department Management Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-[#fcfcfd] text-[#475569] min-h-screen flex overflow-x-hidden antialiased">

    <aside class="w-[240px] bg-[#0f172a] text-[#94a3b8] flex flex-col fixed h-full z-20 border-r border-slate-800">
        <div class="p-5 border-b border-slate-800">
            <h1 class="text-lg font-bold text-white tracking-tight">Admin Portal</h1>
            <p class="text-[9px] font-semibold text-slate-500 tracking-wider mt-0.5 uppercase">HR Management System</p>
        </div>
        
        <nav class="flex-1 p-3 space-y-0.5">
            <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-medium hover:bg-slate-800/60 hover:text-white transition">
                <i class="fa-solid fa-chart-pie text-sm opacity-70"></i>
                <span>Dashboard</span>
            </a>
            <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-medium hover:bg-slate-800/60 hover:text-white transition">
                <i class="fa-solid fa-users text-sm opacity-70"></i>
                <span>Employees</span>
            </a>
            <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-semibold bg-[#4f46e5] text-white transition shadow-sm">
                <i class="fa-solid fa-sitemap text-sm"></i>
                <span>Departments</span>
            </a>
            <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-medium hover:bg-slate-800/60 hover:text-white transition">
                <i class="fa-solid fa-calendar text-sm opacity-70"></i>
                <span>Attendance</span>
            </a>
            <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-medium hover:bg-slate-800/60 hover:text-white transition">
                <i class="fa-solid fa-envelope-open-text text-sm opacity-70"></i>
                <span>Leave Requests</span>
            </a>
        </nav>

        <div class="p-4 border-t border-slate-800 flex items-center gap-3 bg-slate-900/40">
            <div class="w-8 h-8 rounded-full bg-slate-700 text-white font-bold flex items-center justify-center text-xs">AD</div>
            <div class="min-w-0 flex-1">
                <p class="text-xs font-semibold text-white truncate">Admin User</p>
                <p class="text-[10px] text-slate-500 truncate">Super Administrator</p>
            </div>
        </div>
    </aside>

    <main class="flex-1 pl-[240px] flex flex-col min-w-0">
        
        <header class="bg-white border-b border-slate-100 h-14 flex items-center justify-between px-6 sticky top-0 z-10">
            <div class="relative w-72">
                <i class="fa-solid fa-magnifying-glass absolute left-3 top-2.5 text-slate-400 text-xs"></i>
                <input type="text" placeholder="Search departments or managers..." 
                       class="w-full pl-8 pr-3 py-1.5 border border-slate-200/80 rounded-xl bg-slate-50/50 text-xs text-slate-700 focus:outline-none">
            </div>

            <div class="flex items-center gap-3">
                <button class="text-slate-400 hover:text-slate-600 p-1.5 rounded-lg transition"><i class="fa-solid fa-bell text-sm"></i></button>
                
                <button onclick="openAddModal()" class="bg-[#0f172a] text-white px-3 py-1.5 rounded-xl text-xs font-semibold hover:bg-slate-800 transition flex items-center gap-1.5 shadow-sm">
 <i class="fa-solid fa-plus text-[10px]"></i>
                    <span>Add Employee</span>
                </button>
            </div>
        </header>

        <div class="p-6 space-y-6 max-w-[1400px] w-full mx-auto">
            
            <div class="flex justify-between items-start">
                <div>
                    <h2 class="text-xl font-bold text-[#0f172a] tracking-tight">Department Management</h2>
                    <p class="text-xs text-[#64748b] mt-0.5">Monitor and manage institutional structures and leadership.</p>
                </div>
                <button class="bg-white border border-slate-200 hover:bg-slate-50 text-[#0f172a] text-xs font-bold px-3 py-2 rounded-xl transition flex items-center gap-2 shadow-xs">
                    <i class="fa-solid fa-building text-slate-400 text-[11px]"></i>
                    <span>New Department</span>
                </button>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                <div class="bg-white p-4 border border-slate-200/70 rounded-2xl shadow-xs">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Total Departments</p>
                    <div class="flex items-baseline gap-2 mt-1">
                        <h3 class="text-xl font-extrabold text-slate-900">12</h3>
                        <span class="text-[9px] font-semibold text-emerald-600 bg-emerald-50 px-1.5 py-0.5 rounded">Active</span>
                    </div>
                </div>
                <div class="bg-white p-4 border border-slate-200/70 rounded-2xl shadow-xs">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Total Headcount</p>
                    <div class="flex items-baseline gap-1 mt-1">
                        <h3 class="text-xl font-extrabold text-slate-900">452</h3>
                        <span class="text-[9px] text-indigo-600 font-medium ml-1">+4% <i class="fa-solid fa-arrow-up text-[8px]"></i></span>
                    </div>
                </div>
                <div class="bg-white p-4 border border-slate-200/70 rounded-2xl shadow-xs">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Management Ratio</p>
                    <h3 class="text-xl font-extrabold text-slate-900 mt-1">1:37</h3>
                </div>
                <div class="bg-white p-4 border border-slate-200/70 rounded-2xl shadow-xs">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Vacant Positions</p>
                    <div class="flex items-baseline gap-2 mt-1">
                        <h3 class="text-xl font-extrabold text-red-500">08</h3>
                        <span class="text-[8px] font-bold text-red-600 bg-red-50 px-1.5 py-0.5 rounded uppercase tracking-wide">Critical</span>
                    </div>
                </div>
            </div>

            <div class="bg-white border border-slate-200/70 rounded-2xl shadow-xs overflow-hidden flex flex-col justify-between">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/70 text-[10px] font-bold text-slate-400 tracking-wider uppercase border-b border-slate-100">
                            <th class="py-3 px-5">Department Name</th>
                            <th class="py-3 px-5">Manager</th>
                            <th class="py-3 px-5">Employee Count</th>
                            <th class="py-3 px-5">Description</th>
                            <th class="py-3 px-5 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-xs text-slate-600">
 <tr class="hover:bg-slate-50/40 transition">
                            <td class="py-4 px-5 font-semibold text-slate-900">Engineering</td>
                            <td class="py-4 px-5">
                                <div class="flex items-center gap-2.5">
                                    <span class="w-5.5 h-5.5 rounded-full bg-cyan-100 text-cyan-700 font-bold text-[9px] flex items-center justify-center">AS</span>
                                    <div>
                                        <p class="font-bold text-slate-800 leading-none">Alex Sterling</p>
                                        <span class="text-[9px] text-slate-400">CTO</span>
                                    </div>
                                </div>
                            </td>
                            <td class="py-4 px-5 font-semibold text-slate-700">142</td>
                            <td class="py-4 px-5 text-slate-400 max-w-xs truncate">Product development, system architecture...</td>
                            <td class="py-4 px-5 text-center space-x-2">
                                <a href="javascript:void(0)" class="text-slate-400 p-1 cursor-default opacity-80"><i class="fa-solid fa-pencil text-[11px]"></i></a>
                                <button class="text-slate-400 hover:text-red-500 p-1"><i class="fa-solid fa-trash-can text-[11px]"></i></button>
                            </td>
                        </tr>
                        <tr class="hover:bg-slate-50/40 transition">
                            <td class="py-4 px-5 font-semibold text-slate-900">Sales & Revenue</td>
                            <td class="py-4 px-5">
                                <div class="flex items-center gap-2.5">
                                    <span class="w-5.5 h-5.5 rounded-full bg-emerald-100 text-emerald-700 font-bold text-[9px] flex items-center justify-center">RM</span>
                                    <div>
                                        <p class="font-bold text-slate-800 leading-none">Rachel Meyer</p>
                                        <span class="text-[9px] text-slate-400">VP Sales</span>
                                    </div>
                                </div>
                            </td>
                            <td class="py-4 px-5 font-semibold text-slate-700">86</td>
                            <td class="py-4 px-5 text-slate-400 max-w-xs truncate">Enterprise accounts, global revenue pipelines...</td>
                            <td class="py-4 px-5 text-center space-x-2">
                                <a href="javascript:void(0)" class="text-slate-400 p-1 cursor-default opacity-80"><i class="fa-solid fa-pencil text-[11px]"></i></a>
                                <button class="text-slate-400 hover:text-red-500 p-1"><i class="fa-solid fa-trash-can text-[11px]"></i></button>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <div class="px-5 py-3.5 bg-slate-50/50 border-t border-slate-100 flex items-center justify-between text-xs font-medium text-slate-500">
                    <div>Showing 1 to 2 of 12 entries</div>
                    <div class="flex items-center gap-1">
                        <button class="px-2.5 py-1.5 border border-slate-200 rounded-md bg-white hover:bg-slate-50 transition text-[11px]">Previous</button>
                        <button class="w-7 h-7 bg-indigo-600 text-white rounded-md font-bold text-[11px]">1</button>
                        <button class="w-7 h-7 border border-slate-200 rounded-md bg-white hover:bg-slate-50 transition text-[11px]">2</button>
                        <button class="w-7 h-7 border border-slate-200 rounded-md bg-white hover:bg-slate-50 transition text-[11px]">3</button>
                        <button class="px-2.5 py-1.5 border border-slate-200 rounded-md bg-white hover:bg-slate-50 transition text-[11px]">Next</button>
                    </div>
                </div>
            </div>
</div>
    </main>

    <div id="add-modal-backdrop" class="fixed inset-0 bg-slate-900/40 backdrop-blur-[1px] z-50 hidden opacity-0 transition-opacity duration-200 flex items-center justify-center p-4">
        <div id="add-modal-box" class="bg-white w-full max-w-[620px] rounded-xl shadow-2xl overflow-hidden border border-slate-100 transform scale-95 opacity-0 transition-all duration-200 ease-out">
            
            <div class="bg-[#334155] px-6 py-4 flex justify-between items-center text-white">
                <div>
                    <h3 class="text-sm font-bold tracking-wide">Add New Employee</h3>
                    <p class="text-[10px] text-slate-400 mt-0.5 font-normal">Create a new record in the HR database</p>
                </div>
                <button onclick="closeAddModal()" class="text-slate-400 hover:text-white transition p-1 text-sm"><i class="fa-solid fa-xmark"></i></button>
            </div>

            <form class="p-6 space-y-4 text-xs font-semibold text-slate-700" onsubmit="event.preventDefault();">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="space-y-1.5">
                        <label class="block text-slate-500 font-medium">Full Name</label>
                        <input type="text" placeholder="e.g. John Doe" class="w-full px-3 py-2 border border-slate-200 rounded-md font-medium text-slate-800 placeholder-slate-400 focus:outline-none focus:border-slate-400">
                    </div>
                    <div class="space-y-1.5">
                        <label class="block text-slate-500 font-medium">Email Address</label>
                        <input type="email" placeholder="john.doe@company.com" class="w-full px-3 py-2 border border-slate-200 rounded-md font-medium text-slate-800 placeholder-slate-400 focus:outline-none focus:border-slate-400">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="space-y-1.5 relative">
                        <label class="block text-slate-500 font-medium">Department</label>
                        <select class="w-full px-3 py-2 border border-slate-200 rounded-md bg-white font-medium text-slate-800 focus:outline-none focus:border-slate-400 appearance-none cursor-pointer">
                            <option>Engineering</option>
                            <option>Sales & Revenue</option>
                            <option>Human Resources</option>
                        </select>
                        <i class="fa-solid fa-chevron-down absolute right-3 bottom-3 text-slate-400 text-[10px]"></i>
                    </div>
                    <div class="space-y-1.5">
                        <label class="block text-slate-500 font-medium">Position</label>
                        <input type="text" placeholder="e.g. Lead Developer" class="w-full px-3 py-2 border border-slate-200 rounded-md font-medium text-slate-800 placeholder-slate-400 focus:outline-none focus:border-slate-400">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="space-y-2">
                        <label class="block text-slate-500 font-medium">Employment Type</label>
                        <div class="flex items-center gap-4 pt-1 text-slate-800">
                            <label class="flex items-center gap-2 cursor-pointer select-none">
                                <input type="radio" name="emp_type" checked class="w-3.5 h-3.5 text-slate-700 border-slate-300 focus:ring-0">
                                <span>Full-time</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer select-none">
     <input type="radio" name="emp_type" class="w-3.5 h-3.5 text-slate-700 border-slate-300 focus:ring-0">
                                <span>Contract</span>
                            </label>
                        </div>
                    </div>
                    <div class="space-y-1.5">
                        <label class="block text-slate-500 font-medium">Joining Date</label>
                        <input type="date" class="w-full px-3 py-2 border border-slate-200 rounded-md text-slate-500 focus:outline-none focus:border-slate-400">
                    </div>
                </div>

                <div class="flex justify-end items-center gap-2 pt-4 border-t border-slate-100">
                    <button type="button" onclick="closeAddModal()" class="text-slate-500 hover:text-slate-700 px-4 py-2 font-medium">Cancel</button>
                    <button type="submit" class="bg-[#2a4b4f] hover:bg-[#1f383b] text-white px-5 py-2 rounded-md shadow-xs transition">Save Employee</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        const modalBackdrop = document.getElementById('add-modal-backdrop');
        const modalBox = document.getElementById('add-modal-box');

        function openAddModal() {
            modalBackdrop.classList.remove('hidden');
            setTimeout(() => {
                modalBackdrop.classList.remove('opacity-0');
                modalBackdrop.classList.add('opacity-100');
                modalBox.classList.remove('scale-95', 'opacity-0');
                modalBox.classList.add('scale-100', 'opacity-100');
            }, 10);
        }

        function closeAddModal() {
            modalBackdrop.classList.remove('opacity-100');
            modalBackdrop.classList.add('opacity-0');
            modalBox.classList.remove('scale-100', 'opacity-100');
            modalBox.classList.add('scale-95', 'opacity-0');
            setTimeout(() => {
                modalBackdrop.classList.add('hidden');
            }, 200);
        }
    </script>

</body>
</html>
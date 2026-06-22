<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Portal - Attendance Management</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-[#f8fafc] text-slate-700 min-h-screen flex font-sans">

    <aside class="w-64 bg-[#1e293b] text-slate-300 flex flex-col fixed h-full z-10">
        <div class="p-6 border-b border-slate-700">
            <h1 class="text-xl font-bold text-white tracking-wide">Admin</h1>
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
            <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-slate-800 hover:text-white transition">
                <i class="fa-solid fa-sitemap text-lg"></i>
                <span>Departments</span>
            </a>
            <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-lg bg-indigo-600 text-white font-medium transition">
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
            <div class="w-10 h-10 rounded-full bg-slate-500 flex items-center justify-center text-white font-bold">
                AR
            </div>
            <div>
                <p class="text-sm font-semibold text-white">Alex Rivera</p>
                <p class="text-xs text-slate-400">Admin Access</p>
            </div>
        </div>
    </aside>

    <main class="flex-1 pl-64 min-w-0">
        
        <header class="bg-white border-b border-slate-200 h-16 flex items-center justify-between px-8 sticky top-0 z-20">
            <div class="relative w-96">
                <i class="fa-solid fa-magnifying-glass absolute left-3 top-3.5 text-slate-400 text-sm"></i>
                <input type="text" placeholder="Search employees, reports..." 
                       class="w-full pl-10 pr-4 py-2 border border-slate-200 rounded-xl bg-slate-50 text-sm focus:outline-none">
            </div>

            <div class="flex items-center gap-4">
                <button class="text-slate-500 hover:bg-slate-100 p-2 rounded-xl transition"><i class="fa-solid fa-bell text-lg"></i></button>
                <button class="text-slate-500 hover:bg-slate-100 p-2 rounded-xl transition"><i class="fa-solid fa-gear text-lg"></i></button>
                <button class="bg-[#1e293b] text-white px-4 py-2 rounded-xl text-sm font-medium hover:bg-slate-800 transition flex items-center gap-2">
                    <i class="fa-solid fa-plus text-xs"></i>
                    <span>Add Employee</span>
                </button>
            </div>
        </header>
         <div class="p-8 space-y-6 max-w-[1600px] mx-auto">
            
            <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4">
                <div>
                    <h2 class="text-2xl font-bold text-slate-900">Attendance Management</h2>
                    <p class="text-sm text-slate-500 mt-0.5">Real-time monitoring of daily employee presence and punctuality.</p>
                </div>
                
                <div class="flex items-center gap-3">
                    <div class="relative bg-white border border-slate-200 px-3 py-2 rounded-xl shadow-sm flex items-center gap-2 text-xs font-semibold">
                        <span class="text-slate-400 uppercase">Date Range</span>
                        <span class="text-slate-800">10/27/2023</span>
                        <i class="fa-regular fa-calendar text-slate-400 ml-2"></i>
                    </div>
                    <div class="relative bg-white border border-slate-200 px-3 py-2 rounded-xl shadow-sm flex items-center gap-2 text-xs font-semibold">
                        <span class="text-slate-400 uppercase">Department</span>
                        <span class="text-slate-800">All Departments</span>
                        <i class="fa-solid fa-chevron-down text-slate-400 text-[10px] ml-1"></i>
                    </div>
                    <button class="w-9 h-9 bg-slate-800 text-white rounded-xl flex items-center justify-center hover:bg-slate-700 transition">
                        <i class="fa-solid fa-sliders text-sm"></i>
                    </button>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5">
                
                <div class="bg-white p-5 border border-slate-200 rounded-2xl shadow-sm flex justify-between items-center">
                    <div>
                        <p class="text-xs font-bold text-slate-400 tracking-wider uppercase">Present Today</p>
                        <h3 class="text-2xl font-extrabold text-slate-900 mt-1">142</h3>
                        <p class="text-[11px] text-emerald-600 font-semibold mt-1">+3% vs prev. week</p>
                    </div>
                    <div class="w-8 h-8 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center text-sm">
                        <i class="fa-solid fa-circle-check"></i>
                    </div>
                </div>

                <div class="bg-white p-5 border border-slate-200 rounded-2xl shadow-sm flex justify-between items-center">
                    <div>
                        <p class="text-xs font-bold text-slate-400 tracking-wider uppercase">Absent</p>
                        <h3 class="text-2xl font-extrabold text-slate-900 mt-1">12</h3>
                        <p class="text-[11px] text-red-500 font-semibold mt-1">-2 from yesterday</p>
                    </div>
                    <div class="w-8 h-8 rounded-full bg-red-50 text-red-500 flex items-center justify-center text-sm">
                        <i class="fa-solid fa-circle-xmark"></i>
                    </div>
                </div>

                <div class="bg-white p-5 border border-slate-200 rounded-2xl shadow-sm flex justify-between items-center">
                    <div>
                        <p class="text-xs font-bold text-slate-400 tracking-wider uppercase">Late Arrivals</p>
                        <h3 class="text-2xl font-extrabold text-slate-900 mt-1">8</h3>
                        <p class="text-[11px] text-slate-500 mt-1">Average 14m delay</p>
                    </div>
                    <div class="w-8 h-8 rounded-full bg-purple-50 text-purple-600 flex items-center justify-center text-sm">
                        <i class="fa-solid fa-clock"></i>
                    </div>
                </div>
             <div class="bg-white p-5 border border-slate-200 rounded-2xl shadow-sm flex justify-between items-center">
                    <div>
                        <p class="text-xs font-bold text-slate-400 tracking-wider uppercase">Approved Leave</p>
                        <h3 class="text-2xl font-extrabold text-slate-900 mt-1">5</h3>
                        <p class="text-[11px] text-slate-400 mt-1">Scheduled for today</p>
                    </div>
                    <div class="w-8 h-8 rounded-full bg-slate-100 text-slate-500 flex items-center justify-center text-sm">
                        <i class="fa-solid fa-plane-departure text-xs"></i>
                    </div>
                </div>

            </div>

            <div class="bg-white border border-slate-200 rounded-2xl shadow-sm flex flex-col overflow-hidden">
                
                <div class="p-6 border-b border-slate-100 flex justify-between items-center">
                    <h4 class="text-base font-bold text-slate-900">Daily Attendance Log</h4>
                    <div class="flex items-center gap-3">
                        <button class="border border-slate-200 text-slate-700 text-xs font-bold px-3 py-2 rounded-xl hover:bg-slate-50 transition flex items-center gap-1.5 shadow-sm">
                            <i class="fa-solid fa-download"></i>
                            <span>Export CSV</span>
                        </button>
                        <button class="w-8 h-8 flex items-center justify-center text-slate-400 hover:text-slate-600"><i class="fa-solid fa-ellipsis-vertical"></i></button>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 text-[10px] font-bold text-slate-400 tracking-wider uppercase border-b border-slate-100">
                                <th class="py-3 px-6">Employee</th>
                                <th class="py-3 px-6">ID</th>
                                <th class="py-3 px-6">Date</th>
                                <th class="py-3 px-6">Check-In</th>
                                <th class="py-3 px-6">Check-Out</th>
                                <th class="py-3 px-6">Status</th>
                                <th class="py-3 px-6 text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-xs font-medium text-slate-600">
                            
                            <tr class="hover:bg-slate-50/50 transition">
                                <td class="py-3.5 px-6 flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-slate-200 font-bold flex items-center justify-center text-[10px] text-slate-700 overflow-hidden">
                                        <i class="fa-solid fa-user"></i>
                                    </div>
                                    <span class="font-bold text-slate-900">Jordan Smith</span>
                                </td>
                                <td class="py-3.5 px-6 font-mono text-slate-400 text-[11px]">EMP-0492</td>
                                <td class="py-3.5 px-6 text-slate-500">Oct 27, 2023</td>
                                <td class="py-3.5 px-6 font-mono text-slate-700">08:55 AM</td>
                                <td class="py-3.5 px-6 font-mono text-slate-500">05:30 PM</td>
                                <td class="py-3.5 px-6">
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700">
                    <span class="w-1 h-1 rounded-full bg-emerald-500"></span> Present
                                    </span>
                                </td>
                                <td class="py-3.5 px-6 text-center">
                                    <button class="text-slate-400 hover:text-indigo-600"><i class="fa-solid fa-bars-staggered"></i></button>
                                </td>
                            </tr>

                            <tr class="hover:bg-slate-50/50 transition">
                                <td class="py-3.5 px-6 flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-slate-200 font-bold flex items-center justify-center text-[10px] text-slate-700 overflow-hidden">
                                        <i class="fa-solid fa-user"></i>
                                    </div>
                                    <span class="font-bold text-slate-900">Sarah Jenkins</span>
                                </td>
                                <td class="py-3.5 px-6 font-mono text-slate-400 text-[11px]">EMP-0315</td>
                                <td class="py-3.5 px-6 text-slate-500">Oct 27, 2023</td>
                                <td class="py-3.5 px-6 font-mono text-red-500 font-bold">09:15 AM</td>
                                <td class="py-3.5 px-6 font-mono text-slate-500">06:05 PM</td>
                                <td class="py-3.5 px-6">
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold bg-amber-50 text-amber-700">
                                        <span class="w-1 h-1 rounded-full bg-amber-500"></span> Late
                                    </span>
                                </td>
                                <td class="py-3.5 px-6 text-center">
                                    <button class="text-slate-400 hover:text-indigo-600"><i class="fa-solid fa-bars-staggered"></i></button>
                                </td>
                            </tr>

                            <tr class="hover:bg-slate-50/50 transition">
                                <td class="py-3.5 px-6 flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-slate-200 font-bold flex items-center justify-center text-[10px] text-slate-700 overflow-hidden">
                                        <i class="fa-solid fa-user"></i>
                                    </div>
                                    <span class="font-bold text-slate-900">Michael Chen</span>
                                </td>
                                <td class="py-3.5 px-6 font-mono text-slate-400 text-[11px]">EMP-0882</td>
                                <td class="py-3.5 px-6 text-slate-500">Oct 27, 2023</td>
                                <td class="py-3.5 px-6 font-mono text-slate-300">--:--</td>
                                <td class="py-3.5 px-6 font-mono text-slate-300">--:--</td>
                                <td class="py-3.5 px-6">
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold bg-red-50 text-red-600">
                                        <span class="w-1 h-1 rounded-full bg-red-500"></span> Absent
                                    </span>
                                </td>
                                <td class="py-3.5 px-6 text-center">
                                    <button class="text-slate-400 hover:text-indigo-600"><i class="fa-solid fa-bars-staggered"></i></button>
                                </td>
                            </tr>

                            <tr class="hover:bg-slate-50/50 transition">
                     <td class="py-3.5 px-6 flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-slate-200 font-bold flex items-center justify-center text-[10px] text-slate-700 overflow-hidden">
                                        <i class="fa-solid fa-user"></i>
                                    </div>
                                    <span class="font-bold text-slate-900">Elena Rodriguez</span>
                                </td>
                                <td class="py-3.5 px-6 font-mono text-slate-400 text-[11px]">EMP-0551</td>
                                <td class="py-3.5 px-6 text-slate-500">Oct 27, 2023</td>
                                <td class="py-3.5 px-6 font-mono text-slate-700">08:42 AM</td>
                                <td class="py-3.5 px-6 font-mono text-slate-500">05:15 PM</td>
                                <td class="py-3.5 px-6">
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700">
                                        <span class="w-1 h-1 rounded-full bg-emerald-500"></span> Present
                                    </span>
                                </td>
                                <td class="py-3.5 px-6 text-center">
                                    <button class="text-slate-400 hover:text-indigo-600"><i class="fa-solid fa-bars-staggered"></i></button>
                                </td>
                            </tr>

                            <tr class="hover:bg-slate-50/50 transition">
                                <td class="py-3.5 px-6 flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-slate-200 font-bold flex items-center justify-center text-[10px] text-slate-700 overflow-hidden">
                                        <i class="fa-solid fa-user"></i>
                                    </div>
                                    <span class="font-bold text-slate-900">David Park</span>
                                </td>
                                <td class="py-3.5 px-6 font-mono text-slate-400 text-[11px]">EMP-0229</td>
                                <td class="py-3.5 px-6 text-slate-500">Oct 27, 2023</td>
                                <td class="py-3.5 px-6 font-mono text-slate-300">--:--</td>
                                <td class="py-3.5 px-6 font-mono text-slate-300">--:--</td>
                                <td class="py-3.5 px-6">
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold bg-blue-50 text-blue-600">
                                        <span class="w-1 h-1 rounded-full bg-blue-500"></span> On Leave
                                    </span>
                                </td>
                                <td class="py-3.5 px-6 text-center">
                                    <button class="text-slate-400 hover:text-indigo-600"><i class="fa-solid fa-bars-staggered"></i></button>
                                </td>
                            </tr>

                        </tbody>
                    </table>
                </div>

                <div class="p-4 bg-slate-50 border-t border-slate-100 flex justify-between items-center text-xs font-semibold text-slate-500">
                    <span>Showing 5 of 159 employees</span>
                    <div class="flex gap-2">
                        <button class="px-3 py-1.5 bg-white border border-slate-200 rounded-lg hover:bg-slate-50 transition">Previous</button>
                        <button class="px-4 py-1.5 bg-slate-800 text-white rounded-lg hover:bg-slate-700 transition">Next</button>
                    </div>
                </div>

            </div>

        </div>
    </main>

</body>
</html>
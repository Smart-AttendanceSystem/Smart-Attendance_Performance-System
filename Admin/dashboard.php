 <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-[#f8fafc] text-slate-700 min-h-screen flex font-sans">

    <aside class="w-64 bg-[#1e293b] text-slate-300 flex flex-col fixed h-full z-10">
        <div class="p-6 border-b border-slate-700">
            <h1 class="text-xl font-bold text-white tracking-wide">Admin</h1>
        </div>
        
        <nav class="flex-1 p-4 space-y-1">
            <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-lg bg-indigo-600 text-white font-medium transition">
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
            <div class="w-10 h-10 rounded-full bg-slate-500 overflow-hidden flex items-center justify-center text-white font-bold">
                A
            </div>
            <div>
                <p class="text-sm font-semibold text-white">Admin</p>
                <p class="text-xs text-slate-400">Administrator</p>
            </div>
            <button class="ml-auto text-slate-400 hover:text-red-400 transition">
                <i class="fa-solid fa-right-from-bracket"></i>
            </button>
        </div>
    </aside>

    <main class="flex-1 pl-64 min-w-0">
        
        <header class="bg-white border-b border-slate-200 h-16 flex items-center justify-between px-8 sticky top-0 z-20">
            <div class="relative w-96">
                <i class="fa-solid fa-magnifying-glass absolute left-3 top-3.5 text-slate-400 text-sm"></i>
                <input type="text" placeholder="Search employees, files, or reports..." 
                       class="w-full pl-10 pr-4 py-2 border border-slate-200 rounded-xl bg-slate-50 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
            </div>

            <div class="flex items-center gap-4">
                <button class="relative w-10 h-10 flex items-center justify-center text-slate-500 hover:bg-slate-100 rounded-xl transition">
                    <i class="fa-solid fa-bell text-lg"></i>
                    <span class="absolute top-2 right-2.5 w-2 h-2 bg-red-500 rounded-full"></span>
                </button>
             <button class="w-10 h-10 flex items-center justify-center text-slate-500 hover:bg-slate-100 rounded-xl transition">
                    <i class="fa-solid fa-sliders text-lg"></i>
                </button>
                <button class="bg-[#1e293b] text-white px-4 py-2 rounded-xl text-sm font-medium hover:bg-slate-800 transition flex items-center gap-2 shadow-sm">
                    <i class="fa-solid fa-user-plus text-xs"></i>
                    <span>Add Employee</span>
                </button>
            </div>
        </header>

        <div class="p-8 space-y-6 max-w-[1600px] mx-auto">
            
            <div class="flex justify-between items-start">
                <div>
                    <h2 class="text-2xl font-bold text-slate-900">Dashboard Overview</h2>
                    <p class="text-sm text-slate-500 mt-1">Summary of human resources performance and daily activity.</p>
                </div>
                <div class="bg-white border border-slate-200 p-1 rounded-xl flex gap-1 shadow-sm text-xs font-semibold">
                    <button class="px-4 py-1.5 rounded-lg bg-slate-100 text-slate-900">TODAY</button>
                    <button class="px-4 py-1.5 rounded-lg text-slate-500 hover:bg-slate-50">WEEK</button>
                    <button class="px-4 py-1.5 rounded-lg text-slate-500 hover:bg-slate-50">MONTH</button>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5">
                
                <div class="bg-white p-5 border border-slate-200 rounded-2xl shadow-sm flex flex-col justify-between">
                    <div class="flex justify-between items-start">
                        <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-lg">
                            <i class="fa-solid fa-users"></i>
                        </div>
                        <span class="text-xs font-bold text-emerald-600 bg-emerald-50 px-2 py-1 rounded-lg flex items-center gap-1">
                            +4% <i class="fa-solid fa-arrow-trend-up"></i>
                        </span>
                    </div>
                    <div class="mt-4">
                        <p class="text-xs font-bold text-slate-400 tracking-wider uppercase">Total Employees</p>
                        <h3 class="text-3xl font-extrabold text-slate-900 mt-1">1,248</h3>
                        <p class="text-xs text-slate-400 mt-1.5"><span class="font-medium text-slate-500">12 New hires</span> this month</p>
                    </div>
                </div>

                <div class="bg-white p-5 border border-slate-200 rounded-2xl shadow-sm flex flex-col justify-between">
                    <div class="flex justify-between items-start">
                        <div class="w-10 h-10 rounded-xl bg-teal-50 text-teal-600 flex items-center justify-center text-lg">
                            <i class="fa-solid fa-calendar-check"></i>
                        </div>
                        <span class="text-xs font-bold text-red-600 bg-red-50 px-2 py-1 rounded-lg flex items-center gap-1">
                            -1.2% <i class="fa-solid fa-arrow-trend-down"></i>
                        </span>
                    </div>
                    <div class="mt-4">
                        <p class="text-xs font-bold text-slate-400 tracking-wider uppercase">Attendance Rate</p>
                        <h3 class="text-3xl font-extrabold text-slate-900 mt-1">94.2%</h3>
                        <p class="text-xs text-slate-400 mt-1.5">System average: <span class="font-medium text-slate-500">95.0%</span></p>
                    </div>
                </div>

                <div class="bg-white p-5 border border-slate-200 rounded-2xl shadow-sm flex flex-col justify-between">
                 <div class="flex justify-between items-start">
                        <div class="w-10 h-10 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center text-lg">
                            <i class="fa-solid fa-clock"></i>
                        </div>
                        <span class="text-xs font-bold text-slate-600 bg-slate-100 px-2 py-1 rounded-lg flex items-center gap-1">
                            -8% <i class="fa-solid fa-arrow-trend-down"></i>
                        </span>
                    </div>
                    <div class="mt-4">
                        <p class="text-xs font-bold text-slate-400 tracking-wider uppercase">Late Arrivals Today</p>
                        <h3 class="text-3xl font-extrabold text-slate-900 mt-1">14</h3>
                        <p class="text-xs text-slate-400 mt-1.5"><span class="font-medium text-slate-500">3 more</span> than yesterday</p>
                    </div>
                </div>

                <div class="bg-white p-5 border border-slate-200 rounded-2xl shadow-sm flex flex-col justify-between">
                    <div class="flex justify-between items-start">
                        <div class="w-10 h-10 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center text-lg">
                            <i class="fa-solid fa-envelope-open-text"></i>
                        </div>
                        <span class="text-[10px] font-bold text-red-700 bg-red-100 px-2 py-0.5 rounded uppercase tracking-wider">
                            Urgent
                        </span>
                    </div>
                    <div class="mt-4">
                        <p class="text-xs font-bold text-slate-400 tracking-wider uppercase">Pending Leave Requests</p>
                        <h3 class="text-3xl font-extrabold text-slate-900 mt-1">28</h3>
                        <p class="text-xs text-slate-400 mt-1.5"><span class="font-medium text-purple-600">8 awaiting</span> your approval</p>
                    </div>
                </div>

            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                
                <div class="bg-white p-6 border border-slate-200 rounded-2xl shadow-sm lg:col-span-2 flex flex-col justify-between min-h-[300px]">
                    <div class="flex justify-between items-center">
                        <div>
                            <h4 class="text-base font-bold text-slate-900">Monthly Performance Report</h4>
                            <p class="text-xs text-slate-500 mt-0.5">Attendance vs. Late arrivals trends</p>
                        </div>
                        <button class="text-xs font-semibold text-slate-500 bg-slate-50 px-3 py-1.5 rounded-xl border border-slate-200 flex items-center gap-1.5 hover:bg-slate-100 transition">
                            <span>Last 30 Days</span>
                            <i class="fa-solid fa-chevron-down text-[10px]"></i>
                        </button>
                    </div>

                    <div class="flex-1 flex flex-col justify-between mt-6 border-b border-slate-100 pb-2 relative">
                        <div class="w-full border-t border-dashed border-slate-100 h-0"></div>
                        <div class="w-full border-t border-dashed border-slate-100 h-0"></div>
                        <div class="w-full border-t border-dashed border-slate-100 h-0"></div>
                        
                        <div class="flex justify-between text-[11px] text-slate-400 font-medium px-4 mt-auto pt-4">
                            <span class="flex items-center gap-1.5"><span class="w-2 h-2 rounded-full bg-slate-800"></span> WK 01</span>
                            <span class="flex items-center gap-1.5"><span class="w-2 h-2 rounded-full bg-slate-400"></span> WK 02</span>
                     <span class="flex items-center gap-1.5"><span class="w-2 h-2 rounded-full bg-slate-400"></span> WK 03</span>
                            <span class="flex items-center gap-1.5"><span class="w-2 h-2 rounded-full bg-slate-400"></span> WK 04</span>
                            <span class="text-indigo-600 font-bold flex items-center gap-1.5"><span class="w-2 h-2 rounded-full bg-indigo-600"></span> TODAY</span>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-6 border border-slate-200 rounded-2xl shadow-sm flex flex-col">
                    <div class="flex justify-between items-center mb-5">
                        <h4 class="text-base font-bold text-slate-900">Recent Notifications</h4>
                        <span class="text-[10px] font-bold text-red-600 bg-red-50 px-2 py-0.5 rounded-full uppercase">4 New</span>
                    </div>

                    <div class="space-y-4 flex-1">
                        <div class="flex gap-3 items-start p-2.5 rounded-xl hover:bg-slate-50 transition">
                            <div class="w-9 h-9 rounded-xl bg-teal-50 text-teal-600 flex items-center justify-center text-sm flex-shrink-0">
                                <i class="fa-solid fa-envelope-open-text"></i>
                            </div>
                            <div class="min-w-0">
                                <h5 class="text-xs font-bold text-slate-900 truncate">New Leave Request: Jane Doe</h5>
                                <p class="text-[11px] text-slate-500 mt-0.5">Annual Leave Request for Oct 12-15</p>
                                <span class="text-[10px] text-slate-400 uppercase font-semibold mt-1 block">2 minutes ago</span>
                            </div>
                        </div>

                        <div class="flex gap-3 items-start p-2.5 rounded-xl hover:bg-slate-50 transition">
                            <div class="w-9 h-9 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-sm flex-shrink-0">
                                <i class="fa-solid fa-cake-candles"></i>
                            </div>
                            <div class="min-w-0">
                                <h5 class="text-xs font-bold text-slate-900 truncate">Employee Anniversary</h5>
                                <p class="text-[11px] text-slate-500 mt-0.5">Mark Smith celebrates 5 years today!</p>
                                <span class="text-[10px] text-slate-400 uppercase font-semibold mt-1 block">1 hour ago</span>
                            </div>
                        </div>

                        <div class="flex gap-3 items-start p-2.5 rounded-xl hover:bg-slate-50 transition">
                            <div class="w-9 h-9 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-sm flex-shrink-0">
                                <i class="fa-solid fa-receipt"></i>
                            </div>
                            <div class="min-w-0">
                                <h5 class="text-xs font-bold text-slate-900 truncate">Payroll Processed</h5>
                                <p class="text-[11px] text-slate-500 mt-0.5">Monthly salary statements are ready.</p>
                                <span class="text-[10px] text-slate-400 uppercase font-semibold mt-1 block">3 hours ago</span>
                            </div>
                        </div>
                    </div>

                    <button class="w-full mt-4 text-center text-xs font-bold text-slate-500 hover:text-indigo-600 transition pt-3 border-t border-slate-100 tracking-wide uppercase">
                        View All Notifications
                    </button>
                </div>

            </div>
             <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                
                <div class="bg-white p-6 border border-slate-200 rounded-2xl shadow-sm flex flex-col">
                    <div class="mb-4">
                        <h4 class="text-base font-bold text-slate-900">Upcoming Holidays</h4>
                        <p class="text-xs text-slate-400 mt-0.5">Month: October 2023</p>
                    </div>

                    <div class="space-y-4 flex-1">
                        <div class="flex items-center justify-between p-3 rounded-xl bg-slate-50 border border-slate-100">
                            <div>
                                <h5 class="text-xs font-bold text-slate-900">National Unity Day</h5>
                                <p class="text-[11px] text-emerald-600 font-semibold mt-0.5">Public Holiday</p>
                            </div>
                            <div class="text-right">
                                <span class="text-[10px] font-bold text-slate-400 uppercase block">OCT</span>
                                <span class="text-lg font-extrabold text-slate-800 leading-none">14</span>
                            </div>
                        </div>

                        <div class="flex items-center justify-between p-3 rounded-xl bg-slate-50 border border-slate-100">
                            <div>
                                <h5 class="text-xs font-bold text-slate-900">Halloween Break</h5>
                                <p class="text-[11px] text-amber-600 font-semibold mt-0.5">Corporate Day Off</p>
                            </div>
                            <div class="text-right">
                                <span class="text-[10px] font-bold text-slate-400 uppercase block">OCT</span>
                                <span class="text-lg font-extrabold text-slate-800 leading-none">31</span>
                            </div>
                        </div>

                        <div class="flex items-center justify-between p-3 rounded-xl opacity-60 bg-slate-50 border border-slate-100">
                            <div>
                                <h5 class="text-xs font-bold text-slate-900">All Saints' Day</h5>
                                <p class="text-[11px] text-slate-500 font-semibold mt-0.5">Upcoming Next Month</p>
                            </div>
                            <div class="text-right">
                                <span class="text-[10px] font-bold text-slate-400 uppercase block">NOV</span>
                                <span class="text-lg font-extrabold text-slate-800 leading-none">01</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white border border-slate-200 rounded-2xl shadow-sm lg:col-span-2 flex flex-col overflow-hidden">
                    <div class="p-6 border-b border-slate-100 flex justify-between items-center">
                        <div>
                            <h4 class="text-base font-bold text-slate-900">Today's Attendance Detail</h4>
                            <p class="text-xs text-slate-500 mt-0.5">Live feed of employee clock-ins</p>
                        </div>
                        <button class="w-8 h-8 flex items-center justify-center text-slate-400 hover:text-slate-600 hover:bg-slate-50 rounded-lg transition">
                            <i class="fa-solid fa-ellipsis-vertical"></i>
                        </button>
                    </div>

                    <div class="overflow-x-auto flex-1">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-50 text-[10px] font-bold text-slate-400 tracking-wider uppercase border-b border-slate-100">
                     <th class="py-3 px-6">Employee</th>
                                    <th class="py-3 px-6">Department</th>
                                    <th class="py-3 px-6">Clock-In</th>
                                    <th class="py-3 px-6">Status</th>
                                    <th class="py-3 px-6 text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 text-xs font-medium text-slate-600">
                                <tr class="hover:bg-slate-50/80 transition">
                                    <td class="py-3.5 px-6 flex items-center gap-3">
                                        <div class="w-7 h-7 rounded-full bg-indigo-100 text-indigo-700 font-bold flex items-center justify-center text-[10px]">RW</div>
                                        <span class="font-bold text-slate-900">Robert J. Wilson</span>
                                    </td>
                                    <td class="py-3.5 px-6">Engineering</td>
                                    <td class="py-3.5 px-6 font-mono text-slate-500">08:45 AM</td>
                                    <td class="py-3.5 px-6">
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> PRESENT
                                        </span>
                                    </td>
                                    <td class="py-3.5 px-6 text-center">
                                        <button class="text-slate-400 hover:text-indigo-600 transition"><i class="fa-regular fa-eye text-sm"></i></button>
                                    </td>
                                </tr>
                                <tr class="hover:bg-slate-50/80 transition">
                                    <td class="py-3.5 px-6 flex items-center gap-3">
                                        <div class="w-7 h-7 rounded-full bg-pink-100 text-pink-700 font-bold flex items-center justify-center text-[10px]">SK</div>
                                        <span class="font-bold text-slate-900">Sarah Kensington</span>
                                    </td>
                                    <td class="py-3.5 px-6">Marketing</td>
                                    <td class="py-3.5 px-6 font-mono text-slate-500">09:15 AM</td>
                                    <td class="py-3.5 px-6">
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold bg-amber-50 text-amber-700">
                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span> LATE
                                        </span>
                                    </td>
                                    <td class="py-3.5 px-6 text-center">
                                        <button class="text-slate-400 hover:text-indigo-600 transition"><i class="fa-regular fa-eye text-sm"></i></button>
                                    </td>
                                </tr>
                                <tr class="hover:bg-slate-50/80 transition">
                                    <td class="py-3.5 px-6 flex items-center gap-3">
                                        <div class="w-7 h-7 rounded-full bg-teal-100 text-teal-700 font-bold flex items-center justify-center text-[10px]">MA</div>
                                        <span class="font-bold text-slate-900">Marcus Aurelius</span>
                                    </td>
                                    <td class="py-3.5 px-6">Design</td>
                             <td class="py-3.5 px-6 font-mono text-slate-500">08:58 AM</td>
                                    <td class="py-3.5 px-6">
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> PRESENT
                                        </span>
                                    </td>
                                    <td class="py-3.5 px-6 text-center">
                                        <button class="text-slate-400 hover:text-indigo-600 transition"><i class="fa-regular fa-eye text-sm"></i></button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>

        </div>
    </main>

</body>
</html>
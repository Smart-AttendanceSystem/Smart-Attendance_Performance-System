 <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Portal - Settings & Security</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-[#f8fafc] text-slate-700 min-h-screen flex font-sans">

    <aside class="w-64 bg-[#1e293b] text-slate-300 flex flex-col fixed h-full z-10">
        <div class="p-6 border-b border-slate-700">
            <h1 class="text-xl font-bold text-white tracking-wide">Admin Portal</h1>
            <p class="text-[10px] text-slate-400 mt-0.5">HR Management System</p>
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
            <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-slate-800 hover:text-white transition">
                <i class="fa-solid fa-calendar-check text-lg"></i>
                <span>Attendance</span>
            </a>
            <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-slate-800 hover:text-white transition">
                <i class="fa-solid fa-envelope-open-text text-lg"></i>
                <span>Leave Requests</span>
            </a>
            <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-lg bg-indigo-600 text-white font-medium transition mt-auto">
                <i class="fa-solid fa-gear text-lg"></i>
                <span>Settings</span>
            </a>
        </nav>

        <div class="p-4 border-t border-slate-700 flex items-center gap-3">
            <div class="w-10 h-10 rounded-full bg-slate-500 overflow-hidden flex items-center justify-center text-white font-bold">
                AH
            </div>
            <div>
                <p class="text-sm font-semibold text-white">Admin</p>
                <p class="text-xs text-slate-400">Super Administrator</p>
            </div>
        </div>
    </aside>

    <main class="flex-1 pl-64 min-w-0">
        
        <header class="bg-white border-b border-slate-200 h-16 flex items-center justify-between px-8 sticky top-0 z-20">
            <div class="flex items-center gap-4">
                <h2 class="text-md font-bold text-slate-800">HR Admin</h2>
                <div class="relative w-80">
                    <i class="fa-solid fa-magnifying-glass absolute left-3 top-3 text-slate-400 text-xs"></i>
                    <input type="text" placeholder="Search settings..." 
                           class="w-full pl-9 pr-4 py-1.5 border border-slate-200 rounded-xl bg-slate-50 text-xs focus:outline-none focus:ring-1 focus:ring-indigo-500">
                </div>
            </div>

            <div class="flex items-center gap-4">
                <button class="relative text-slate-500 hover:bg-slate-100 p-2 rounded-lg transition">
                    <i class="fa-solid fa-bell text-md"></i>
                    <span class="absolute top-1.5 right-2 w-1.5 h-1.5 bg-red-500 rounded-full"></span>
             </button>
                <button class="text-slate-500 hover:bg-slate-100 p-2 rounded-lg transition">
                    <i class="fa-solid fa-gear text-md"></i>
                </button>
                <button class="bg-slate-800 text-white px-4 py-2 rounded-xl text-xs font-medium hover:bg-slate-700 transition flex items-center gap-2">
                    <i class="fa-solid fa-user-plus text-[10px]"></i>
                    <span>Add Employee</span>
                </button>
            </div>
        </header>

        <div class="p-8 space-y-6 max-w-[1400px] mx-auto">
            
            <div>
                <h3 class="text-xl font-bold text-slate-900">Settings & Security</h3>
                <p class="text-xs text-slate-500 mt-0.5">Manage your account preferences, security credentials, and system notification rules.</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                
                <div class="bg-white p-6 border border-slate-200 rounded-2xl shadow-sm lg:col-span-2 space-y-4">
                    <div class="flex justify-between items-center pb-2 border-b border-slate-100">
                        <div>
                            <h4 class="text-sm font-bold text-slate-900">Admin Profile</h4>
                            <p class="text-[11px] text-slate-400 mt-0.5">Update your public information and avatar.</p>
                        </div>
                        <button class="text-xs font-semibold border border-slate-200 px-3 py-1.5 rounded-xl hover:bg-slate-50 transition">EDIT PHOTO</button>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="text-[10px] font-bold text-slate-400 block uppercase mb-1">Full Name</label>
                            <input type="text" value="Alexandra Hamilton" class="w-full px-3 py-2 border border-slate-200 rounded-xl text-xs bg-slate-50 focus:outline-none">
                        </div>
                        <div>
                            <label class="text-[10px] font-bold text-slate-400 block uppercase mb-1">Email Address</label>
                            <input type="email" value="alexandra.h@hr-portal.com" class="w-full px-3 py-2 border border-slate-200 rounded-xl text-xs bg-slate-50 focus:outline-none">
                        </div>
                        <div>
                            <label class="text-[10px] font-bold text-slate-400 block uppercase mb-1">Job Title</label>
                            <input type="text" value="Senior HR Administrator" class="w-full px-3 py-2 border border-slate-200 rounded-xl text-xs bg-slate-50 focus:outline-none">
                        </div>
                        <div>
                            <label class="text-[10px] font-bold text-slate-400 block uppercase mb-1">Department</label>
                            <div class="relative">
                                <select class="w-full px-3 py-2 border border-slate-200 rounded-xl text-xs bg-slate-50 appearance-none focus:outline-none">
                                    <option>Administration</option>
                                </select>
                                <i class="fa-solid fa-chevron-down absolute right-3 top-3 text-[10px] text-slate-400"></i>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="text-[10px] font-bold text-slate-400 block uppercase mb-1">Bio / Notes</label>
                        <textarea rows="3" class="w-full px-3 py-2 border border-slate-200 rounded-xl text-xs bg-slate-50 focus:outline-none resize-none">Responsible for system-wide employee oversight and payroll synchronization across all regional offices.</textarea>
                    </div>
             <div class="flex justify-end pt-2">
                        <button class="bg-slate-700 text-white text-xs font-semibold px-4 py-2 rounded-xl hover:bg-slate-600 transition">Save Changes</button>
                    </div>
                </div>

                <div class="space-y-4">
                    <div class="bg-white p-6 border border-slate-200 rounded-2xl shadow-sm space-y-3">
                        <h4 class="text-sm font-bold text-slate-900">Security</h4>
                        <p class="text-[11px] text-slate-400">Maintain a strong password to protect the HR environment.</p>

                        <div class="space-y-3 pt-2">
                            <div>
                                <label class="text-[10px] font-bold text-slate-400 block uppercase mb-1">Current Password</label>
                                <input type="password" value="••••••••" class="w-full px-3 py-2 border border-slate-200 rounded-xl text-xs bg-slate-50 focus:outline-none">
                            </div>
                            <div>
                                <label class="text-[10px] font-bold text-slate-400 block uppercase mb-1">New Password</label>
                                <input type="password" value="••••••••" class="w-full px-3 py-2 border border-slate-200 rounded-xl text-xs bg-slate-50 focus:outline-none">
                            </div>
                            <div>
                                <label class="text-[10px] font-bold text-slate-400 block uppercase mb-1">Confirm Password</label>
                                <input type="password" value="••••••••" class="w-full px-3 py-2 border border-slate-200 rounded-xl text-xs bg-slate-50 focus:outline-none">
                            </div>
                        </div>

                        <button class="w-full bg-slate-100 text-slate-700 text-xs font-semibold py-2 rounded-xl hover:bg-slate-200 transition mt-2">Update Password</button>
                    </div>

                    <div class="bg-red-50/50 border border-red-200 p-5 rounded-2xl shadow-sm flex flex-col gap-2">
                        <div class="flex gap-2.5 items-start text-red-800">
                            <i class="fa-solid fa-shield-halved text-sm mt-0.5"></i>
                            <div>
                                <h5 class="text-xs font-bold">Two-Factor Auth</h5>
                                <p class="text-[11px] text-red-600 mt-0.5">Highly recommended for administrative accounts.</p>
                            </div>
                        </div>
                        <button class="w-full bg-red-600 hover:bg-red-700 text-white text-xs font-semibold py-2 rounded-xl transition mt-1 uppercase tracking-wide">Enable Now</button>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                
                <div class="bg-white p-6 border border-slate-200 rounded-2xl shadow-sm flex flex-col justify-between">
                    <div>
                        <h4 class="text-sm font-bold text-slate-900">Notifications Settings</h4>
                        <p class="text-[11px] text-slate-400 mt-0.5">Define when and how you want to be alerted about staff activities.</p>
                        
                        <div class="mt-5 space-y-5">
                            <div class="flex justify-between items-start">
                                <div class="flex gap-3 items-start">
                                    <i class="fa-regular fa-envelope text-slate-400 text-sm mt-0.5"></i>
                                    <div>
                                        <h5 class="text-xs font-bold text-slate-800">Email Summary</h5>
                                        <p class="text-[11px] text-slate-400 mt-0.5">Receive a daily digest of all attendance logs.</p>
                         </div>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" checked class="sr-only peer">
                                    <div class="w-8 h-4 bg-slate-200 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-3 after:w-3 after:transition-all peer-checked:bg-slate-800"></div>
                                </label>
                            </div>

                            <div class="flex justify-between items-start">
                                <div class="flex gap-3 items-start">
                                    <i class="fa-regular fa-calendar text-slate-400 text-sm mt-0.5"></i>
                                    <div>
                                        <h5 class="text-xs font-bold text-slate-800">Leave Request Alerts</h5>
                                        <p class="text-[11px] text-slate-400 mt-0.5">Instant notification for urgent leave submissions.</p>
                                    </div>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" checked class="sr-only peer">
                                    <div class="w-8 h-4 bg-slate-200 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-3 after:w-3 after:transition-all peer-checked:bg-slate-800"></div>
                                </label>
                            </div>

                            <div class="flex justify-between items-start">
                                <div class="flex gap-3 items-start">
                                    <i class="fa-solid fa-triangle-exclamation text-slate-400 text-sm mt-0.5"></i>
                                    <div>
                                        <h5 class="text-xs font-bold text-slate-800">Security Breaches</h5>
                                        <p class="text-[11px] text-slate-400 mt-0.5">Alert me for failed login attempts from unknown IPs.</p>
                                    </div>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" checked class="sr-only peer">
                                    <div class="w-8 h-4 bg-slate-200 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-3 after:w-3 after:transition-all peer-checked:bg-slate-800"></div>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white border border-slate-200 rounded-2xl shadow-sm lg:col-span-2 flex flex-col overflow-hidden">
                    <div class="p-6 border-b border-slate-100 flex justify-between items-center">
                        <div>
                            <h4 class="text-sm font-bold text-slate-900">User Management</h4>
                            <p class="text-[11px] text-slate-400 mt-0.5">Active administrators on the system.</p>
                        </div>
                 <button class="bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold px-3 py-1.5 rounded-xl transition uppercase tracking-wide flex items-center gap-1.5">
                            <i class="fa-solid fa-user-gear text-[10px]"></i>
                            <span>Manage Roles</span>
                        </button>
                    </div>

                    <div class="overflow-x-auto flex-1">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-50 text-[10px] font-bold text-slate-400 tracking-wider uppercase border-b border-slate-100">
                                    <th class="py-3 px-6">User</th>
                                    <th class="py-3 px-6">Role</th>
                                    <th class="py-3 px-6">Status</th>
                                    <th class="py-3 px-6 text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 text-xs font-medium text-slate-600">
                                <tr class="hover:bg-slate-50/50 transition">
                                    <td class="py-3 px-6 flex items-center gap-3">
                                        <div class="w-7 h-7 rounded-full bg-indigo-100 text-indigo-700 font-bold flex items-center justify-center text-[10px]">JD</div>
                                        <div>
                                            <span class="font-bold text-slate-900 block leading-none">John Doe</span>
                                            <span class="text-[10px] text-slate-400 font-normal">j.doe@hr-portal.com</span>
                                        </div>
                                    </td>
                                    <td class="py-3 px-6 text-slate-500">Super Admin</td>
                                    <td class="py-3 px-6">
                                        <span class="inline-flex px-2 py-0.5 rounded text-[9px] font-bold bg-emerald-50 text-emerald-700 uppercase tracking-wide">Active</span>
                                    </td>
                                    <td class="py-3 px-6 text-center">
                                        <button class="text-slate-400 hover:text-slate-600"><i class="fa-solid fa-ellipsis-vertical"></i></button>
                                    </td>
                                </tr>
                                <tr class="hover:bg-slate-50/50 transition">
                                    <td class="py-3 px-6 flex items-center gap-3">
                                        <div class="w-7 h-7 rounded-full bg-slate-700 text-white font-bold flex items-center justify-center text-[10px]">SM</div>
                                        <div>
                                            <span class="font-bold text-slate-900 block leading-none">Sarah Miller</span>
                                            <span class="text-[10px] text-slate-400 font-normal">s.miller@hr-portal.com</span>
                                        </div>
                                    </td>
                                    <td class="py-3 px-6 text-slate-500">HR Manager</td>
                                    <td class="py-3 px-6">
                                        <span class="inline-flex px-2 py-0.5 rounded text-[9px] font-bold bg-emerald-50 text-emerald-700 uppercase tracking-wide">Active</span>
                                    </td>
                                    <td class="py-3 px-6 text-center">
                                        <button class="text-slate-400 hover:text-slate-600"><i class="fa-solid fa-ellipsis-vertical"></i></button>
                     </td>
                                </tr>
                                <tr class="hover:bg-slate-50/50 transition">
                                    <td class="py-3 px-6 flex items-center gap-3">
                                        <div class="w-7 h-7 rounded-full bg-amber-100 text-amber-700 font-bold flex items-center justify-center text-[10px]">RB</div>
                                        <div>
                                            <span class="font-bold text-slate-900 block leading-none">Robert Brown</span>
                                            <span class="text-[10px] text-slate-400 font-normal">r.brown@hr-portal.com</span>
                                        </div>
                                    </td>
                                    <td class="py-3 px-6 text-slate-500">Viewer</td>
                                    <td class="py-3 px-6">
                                        <span class="inline-flex px-2 py-0.5 rounded text-[9px] font-bold bg-slate-100 text-slate-400 uppercase tracking-wide">Inactive</span>
                                    </td>
                                    <td class="py-3 px-6 text-center">
                                        <button class="text-slate-400 hover:text-slate-600"><i class="fa-solid fa-ellipsis-vertical"></i></button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="p-3 bg-slate-50/50 border-t border-slate-100 text-right">
                        <button class="text-[11px] font-semibold text-slate-400 hover:text-indigo-600 transition">View access logs</button>
                    </div>
                </div>

            </div>

        </div>
    </main>

</body>
</html>
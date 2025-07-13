            <div id="sidebar"
                class="bg-base-100 text-white w-64 transition-all duration-300 shadow-lg h-screen overflow-hidden hidden md:flex flex-col">
                <!-- Top Section -->
                <div class="flex flex-col h-screen">
                    <!-- Logo -->
                    <div class="text-red-600 p-2 m-2 text-lg font-semibold flex justify-center items-center">

                        <span class="h-[calc(theme(spacing.5))]"> Matchaneru </span>
                    </div>
                    <!-- Menu Sidebar -->
                    <div class="flex flex-col gap-2 m-2 overflow-y-auto flex-1 pr-2 scrollbar-gutter-stable">
                        <ul
                            class="menu rounded-box w-full text-base-content [&::-webkit-scrollbar]:w-2 [&::-webkit-scrollbar-thumb]:bg-base-300 [&::-webkit-scrollbar-thumb]:rounded-full [&::-webkit-scrollbar-track]:bg-transparent hover:[&::-webkit-scrollbar-thumb]:bg-base-content/20 transition-colors duration-200">
                            <li class="menu-title">ERP Menu</li>
                            <!-- Purchase Module -->
                            <li>
                                <details open>
                                    <summary>
                                        <i data-lucide="shopping-cart" class="w-4 h-4 mr-2"></i>
                                        Purchase
                                    </summary>
                                    <ul>
                                        <li><a><i data-lucide="file-text" class="w-4 h-4 mr-2"></i>Purchase Order</a>
                                        </li>
                                        <li><a><i data-lucide="clipboard-check" class="w-4 h-4 mr-2"></i>Vendor Bill</a>
                                        </li>
                                        <li><a><i data-lucide="users" class="w-4 h-4 mr-2"></i>Suppliers</a></li>
                                    </ul>
                                </details>
                            </li>
                            <!-- Inventory Module -->
                            <li>
                                <details>
                                    <summary>
                                        <i data-lucide="box" class="w-4 h-4 mr-2"></i>
                                        Inventory
                                    </summary>
                                    <ul>
                                        <li><a><i data-lucide="package" class="w-4 h-4 mr-2"></i>Stock Items</a></li>
                                        <li><a><i data-lucide="truck" class="w-4 h-4 mr-2"></i>Incoming Shipments</a>
                                        </li>
                                        <li><a><i data-lucide="archive" class="w-4 h-4 mr-2"></i>Warehouses</a></li>
                                    </ul>
                                </details>
                            </li>
                            <!-- Sales Module -->
                            <li>
                                <details>
                                    <summary>
                                        <i data-lucide="dollar-sign" class="w-4 h-4 mr-2"></i>
                                        Sales
                                    </summary>
                                    <ul>
                                        <li><a><i data-lucide="file-text" class="w-4 h-4 mr-2"></i>Sales Order</a></li>
                                        <li><a><i data-lucide="credit-card" class="w-4 h-4 mr-2"></i>Invoice</a></li>
                                        <li><a><i data-lucide="user-check" class="w-4 h-4 mr-2"></i>Customers</a></li>
                                    </ul>
                                </details>
                            </li>
                            <!-- Accounting Module -->
                            <li>
                                <details>
                                    <summary>
                                        <i data-lucide="file-chart" class="w-4 h-4 mr-2"></i>
                                        Accounting
                                    </summary>
                                    <ul>
                                        <li><a><i data-lucide="dollar-sign" class="w-4 h-4 mr-2"></i>Journal Entries</a>
                                        </li>
                                        <li><a><i data-lucide="calendar" class="w-4 h-4 mr-2"></i>Fiscal Period</a></li>
                                        <li><a><i data-lucide="bar-chart" class="w-4 h-4 mr-2"></i>Reports</a></li>
                                    </ul>
                                </details>
                            </li>

                            <!-- HR Module -->
                            <li>
                                <details>
                                    <summary>
                                        <i data-lucide="briefcase" class="w-4 h-4 mr-2"></i>
                                        HR
                                    </summary>
                                    <ul>
                                        <li><a><i data-lucide="user" class="w-4 h-4 mr-2"></i>Employees</a></li>
                                        <li><a><i data-lucide="calendar-check" class="w-4 h-4 mr-2"></i>Attendance</a>
                                        </li>
                                        <li><a><i data-lucide="file" class="w-4 h-4 mr-2"></i>Payslips</a></li>
                                    </ul>
                                </details>
                            </li>
                            <!-- Additional HR Modules removed for brevity -->
                            <!-- HR Module -->
                            <li>
                                <details>
                                    <summary>
                                        <i data-lucide="briefcase" class="w-4 h-4 mr-2"></i>
                                        HR
                                    </summary>
                                    <ul>
                                        <li><a><i data-lucide="user" class="w-4 h-4 mr-2"></i>Employees</a></li>
                                        <li><a><i data-lucide="calendar-check" class="w-4 h-4 mr-2"></i>Attendance</a>
                                        </li>
                                        <li><a><i data-lucide="file" class="w-4 h-4 mr-2"></i>Payslips</a></li>
                                    </ul>
                                </details>
                            </li>
                            <!-- HR Module -->
                            <li>
                                <details>
                                    <summary>
                                        <i data-lucide="briefcase" class="w-4 h-4 mr-2"></i>
                                        HR
                                    </summary>
                                    <ul>
                                        <li><a><i data-lucide="user" class="w-4 h-4 mr-2"></i>Employees</a></li>
                                        <li><a><i data-lucide="calendar-check" class="w-4 h-4 mr-2"></i>Attendance</a>
                                        </li>
                                        <li><a><i data-lucide="file" class="w-4 h-4 mr-2"></i>Payslips</a></li>
                                    </ul>
                                </details>
                            </li>

                        </ul>
                    </div>

                    <hr class="order-t text-neutral-content" />
                    <div class="p-2 w-full">
                        <ul class="menu rounded-box w-full text-base-content text-sm">
                            <li>
                                <a>
                                    <i data-lucide="settings" class="w-5 h-5"></i>
                                    Settings
                                </a>
                            </li>
                            <li>
                                <a>
                                    <i data-lucide="help-circle" class="w-5 h-5"></i>
                                    Help
                                </a>
                            </li>
                        </ul>
                        <div class="dropdown dropdown-top dropdown-end w-full">
                            <div tabindex="0" role="button"
                                class="w-full bg-base-200 hover:bg-base-300 rounded-md p-1">
                                <div class="flex items-center gap-3 w-full">
                                    <div class="avatar">
                                        <div class="w-8 rounded-md">
                                            <img src="https://i.pravatar.cc/40" alt="User avatar" />
                                        </div>
                                    </div>
                                    <div class="flex-1 text-left text-sm">
                                        <p class="font-medium text-base-content">{{ Auth::user()->name }}</p>
                                        <p class="text-neutral">@withden</p>
                                    </div>
                                    <i data-lucide="chevron-down" class="w-4 h-4 text-base-content/70"></i>
                                </div>
                            </div>

                            <ul tabindex="0"
                                class="dropdown-content z-[1] menu p-2 shadow-lg bg-base-100 rounded-box w-52 text-base-content">
                                <li>
                                    <a href="/profile" class="flex items-center gap-2">
                                        <i data-lucide="user" class="w-4 h-4"></i>
                                        <span>My Profile</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="/settings" class="flex items-center gap-2">
                                        <i data-lucide="settings" class="w-4 h-4"></i>
                                        <span>Settings</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="/help" class="flex items-center gap-2">
                                        <i data-lucide="help-circle" class="w-4 h-4"></i>
                                        <span>Help Center</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="/notifications" class="flex items-center gap-2">
                                        <i data-lucide="bell" class="w-4 h-4"></i>
                                        <span>Notifications</span>
                                    </a>
                                </li>
                                <li class="border-t border-base-200 mt-2 pt-2">
                                    <a href="/switch-account" class="flex items-center gap-2">
                                        <i data-lucide="repeat" class="w-4 h-4"></i>
                                        <span>Switch Account</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
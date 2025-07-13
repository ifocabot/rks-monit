                <header class="flex justify-between items-center px-4 py-2 bg-base-100 shadow-lg">
                    <!-- Left Side - Menu & Search Only -->
                    <div class="flex items-center">
                        <!-- Desktop Hamburger -->
                        <button id="toggleSidebarBtn"
                            class="btn btn-square btn-ghost text-base-neutral hidden md:flex"
                            aria-label="Toggle Sidebar">
                            <i data-lucide="menu"></i>
                        </button>

                        <!-- Mobile Drawer -->
                        <div class="drawer md:hidden">
                            <input id="my-drawer-4" type="checkbox" class="drawer-toggle" />
                            <div class="drawer-content">
                                <label for="my-drawer-4" class="btn btn-square btn-ghost text-base-neutral">
                                    <i data-lucide="menu"></i>
                                </label>
                            </div>
                            <div class="drawer-side">
                                <label for="my-drawer-4" aria-label="close sidebar" class="drawer-overlay"></label>
                                <ul class="menu bg-base-200 text-base-content min-h-full w-80 p-4">
                                    <li><a>Sidebar Item 1</a></li>
                                    <li><a>Sidebar Item 2</a></li>
                                </ul>
                            </div>
                        </div>

                        <!-- Search Button -->
                        <div class="ml-2">
                            <!-- Desktop Search -->
                            <button
                                class="btn btn-outline border-[#e5e5e5] text-base-neutral btn-ghost hidden md:flex items-center gap-2 w-48 justify-start"
                                onclick="my_modal_2.showModal()">
                                <i data-lucide="search" class="w-5 h-5"></i>
                                <span>Search</span>
                            </button>
                            <!-- Mobile Search -->
                            <button
                                class="btn btn-outline border-[#e5e5e5] text-base-neutral btn-square btn-ghost md:hidden"
                                onclick="my_modal_2.showModal()">
                                <i data-lucide="search" class="w-5 h-5"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Right Side - Compact for Mobile -->
                    <div class="flex items-center gap-1 md:gap-2">
                        <!-- Theme Toggle -->
                        <div class="dropdown dropdown-end">
                            <button class="btn btn-sm md:btn-circle btn-ghost" aria-label="Theme">
                                <i data-lucide="palette" class="w-4 h-4 md:w-5 md:h-5"></i>
                            </button>
                            <ul class="dropdown-content menu p-2 bg-base-100 shadow rounded-box w-40">
                                <li><button onclick="switchTheme('light')">Light</button></li>
                                <li><button onclick="switchTheme('dark')">Dark</button></li>
                                <li><button onclick="switchTheme('corporate')">Corporate</button></li>
                            </ul>
                        </div>

                        <!-- Settings -->
                        <button class="btn btn-sm md:btn-circle btn-ghost" aria-label="Settings">
                            <i data-lucide="settings-2" class="w-4 h-4 md:w-5 md:h-5"></i>
                        </button>

                        <!-- Notifications -->
                        <div class="dropdown dropdown-end">
                            <button class="btn btn-sm md:btn-circle btn-ghost" aria-label="Notifications">
                                <i data-lucide="bell" class="w-4 h-4 md:w-5 md:h-5"></i>
                            </button>
                            <ul class="dropdown-content menu p-2 bg-base-100 shadow rounded-box w-64">
                                <li><span>Notifications</span></li>
                                <li><a>New Message Received</a></li>
                                <li><a>Server Maintenance</a></li>
                            </ul>
                        </div>

                        <!-- User Dropdown -->
                        <div class="dropdown dropdown-end">
                            <button
                                class="btn btn-ghost btn-sm md:btn-lg bg-base-100 flex items-center space-x-1 text-sm">
                                <div class="avatar">
                                    <div class="w-6 md:w-8 rounded-xl">
                                        <img src="https://i.pravatar.cc/40" alt="Avatar" />
                                    </div>
                                </div>
                                <div class="hidden md:block">
                                    <p>{{ Auth::user()->name }}</p>
                                    <small>Profile</small>
                                </div>
                            </button>
                            <ul class="dropdown-content menu p-2 bg-base-100 shadow rounded-box w-48">
                                <li><a>My Profile</a></li>
                                <li><a>Settings</a></li>
                                <li><a>Help</a></li>
                                <li><a>Logout</a></li>
                            </ul>
                        </div>
                    </div>
                </header>
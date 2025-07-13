    <!-- Modal -->
    <dialog id="my_modal_2" class="modal">
        <div class="modal-box">
            <!-- Search Input -->
            <div class="flex items-center border rounded px-2 py-1 gap-2">
                <i data-lucide="search" class="w-4 h-4"></i>
                <input type="search" placeholder="Search" aria-label="Search"
                    class="grow outline-none bg-transparent" />
                <form method="dialog">
                    <button class="btn btn-sm btn-circle btn-ghost" aria-label="Close">
                        <i data-lucide="x" class="w-4 h-4"></i>
                    </button>
                </form>
            </div>

            <!-- Actions Menu -->
            <ul class="menu w-full">
                <li class="menu-title">Actions</li>
                <li>
                    <a>
                        <i data-lucide="folder-plus" class="w-4 h-4"></i>
                        <span>Create a new folder</span>
                    </a>
                </li>
                <li>
                    <a>
                        <i data-lucide="file-plus" class="w-4 h-4"></i>
                        <span>Upload new document</span>
                    </a>
                </li>
                <li>
                    <a>
                        <i data-lucide="user-plus" class="w-4 h-4"></i>
                        <span>Invite to project</span>
                    </a>
                </li>
            </ul>

            <hr class />

            <!-- Quick Links Menu -->
            <ul class="menu w-full">
                <li class="menu-title">Quick Links</li>
                <li>
                    <a>
                        <i data-lucide="folders" class="w-4 h-4"></i>
                        <span>File Manager</span>
                    </a>
                </li>
                <li>
                    <a>
                        <i data-lucide="user" class="w-4 h-4"></i>
                        <span>Profile</span>
                    </a>
                </li>
                <li>
                    <a>
                        <i data-lucide="layout-dashboard" class="w-4 h-4"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li>
                    <a>
                        <i data-lucide="help-circle" class="w-4 h-4"></i>
                        <span>Support</span>
                    </a>
                </li>
                <li>
                    <a>
                        <i data-lucide="keyboard" class="w-4 h-4"></i>
                        <span>Keyboard Shortcuts</span>
                    </a>
                </li>
            </ul>
        </div>
        <form method="dialog" class="modal-backdrop">
            <button>close</button>
        </form>
    </dialog>

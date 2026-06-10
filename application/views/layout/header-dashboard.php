<?php
/**
 * Created by PhpStorm.
 * User: iy2
 * Date: 4/8/2016
 * Time: 2:58 PM
 */
?>
<?php require_once 'head-only.php'; ?>
<!-- TopNavBar -->
<nav class="bg-primary text-on-primary docked full-width top-0 z-50 border-b border-primary-container shadow-sm">
    <div class="flex justify-between items-center w-full px-margin-desktop py-md mx-auto max-w-max-width">
        <div class="flex flex-col">
            <span class="font-headline-sm text-headline-sm font-bold text-on-primary">Easton Park Residence
                Jatinangor</span>
            <span class="font-label-sm text-label-sm text-on-primary/80 uppercase tracking-wider">Building
                Management System</span>
        </div>
        <div class="hidden md:flex items-center gap-lg">
            <div class="flex gap-md">
                <a class="text-on-primary border-b-2 border-primary-fixed font-bold font-label-md text-label-md py-1"
                    href="#">Dashboard</a>
                <a class="text-on-primary/80 font-medium font-label-md text-label-md hover:bg-primary-container/20 transition-colors px-2 py-1 rounded"
                    href="#">Residents</a>
                <a class="text-on-primary/80 font-medium font-label-md text-label-md hover:bg-primary-container/20 transition-colors px-2 py-1 rounded"
                    href="#">Maintenance</a>
                <a class="text-on-primary/80 font-medium font-label-md text-label-md hover:bg-primary-container/20 transition-colors px-2 py-1 rounded"
                    href="#">Financials</a>
            </div>
            <div class="flex items-center gap-md border-l border-on-primary/20 pl-md">
                <button
                    class="hover:bg-primary-container/20 transition-colors p-base rounded-full flex items-center">
                    <span class="material-symbols-outlined">notifications</span>
                </button>
                <button
                    class="hover:bg-primary-container/20 transition-colors p-base rounded-full flex items-center">
                    <span class="material-symbols-outlined">settings</span>
                </button>
                <div
                    class="w-10 h-10 rounded-full border-2 border-primary-fixed bg-surface-container overflow-hidden">
                    <img alt="Property Manager Profile" class="w-full h-full object-cover"
                        src="https://lh3.googleusercontent.com/aida-public/AB6AXuCqigRCrTQ9eYTASYUI8_cClqRkE1O7DrQ1ZO-UCz67cx19y1G_076Qm6T93SKDOe5qzDE4E_eKaCXQ8RvWJmiW70_mtkUqOPPeDJsZKTxYQ-9zKFI7HLUh01wdjafLjT7ijoT0gep2SKImt4_5gYuH0Wh9Edxy824YNyQ4KWaS4FhHB5ERQswhESEgNm6QcFtXlYTJEOHAxGjojIvb2C2_f7hrl84wzXqMlrt28JEXcdkJm5OH-L981lJZdBkpwD3HHVmUttb1urk" />
                </div>
            </div>
        </div>
        <!-- Mobile Toggle -->
        <button class="md:hidden text-on-primary">
            <span class="material-symbols-outlined">menu</span>
        </button>
    </div>
</nav>
<!-- Main Content Shell -->
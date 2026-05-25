<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>

<div class="sidebar">

    <!-- TOP -->
    <div class="sidebar-top">

        <!-- BRAND -->
        <div class="brand">
            <span class="brand-white">Intern</span><span class="brand-blue">Track Pro</span>
        </div>

        <!-- MENU -->
        <nav class="sidebar-menu">

            <a href="dashboard.php"
               class="nav-item <?php if($current_page == 'dashboard.php') echo 'active'; ?>">
                <span>🏠</span> Dashboard
            </a>

            <a href="students.php"
               class="nav-item <?php if(in_array($current_page, ['students.php','add_student.php','edit_student.php'])) echo 'active'; ?>">
                <span>🎓</span> Students
            </a>

            <a href="companies.php"
               class="nav-item <?php if(in_array($current_page, ['companies.php','add_company.php','edit_company.php'])) echo 'active'; ?>">
                <span>🏢</span> Companies
            </a>

            <a href="applications.php"
               class="nav-item <?php if(in_array($current_page, ['applications.php','add_application.php','edit_application.php'])) echo 'active'; ?>">
                <span>📄</span> Applications
            </a>

            <a href="interviews.php"
               class="nav-item <?php if(in_array($current_page, ['interviews.php','add_interview.php','edit_interview.php'])) echo 'active'; ?>">
                <span>🎤</span> Interviews
            </a>

            <a href="reports.php"
               class="nav-item <?php if($current_page == 'reports.php') echo 'active'; ?>">
                <span>📊</span> Reports
            </a>

            <!-- LOGOUT -->
            <a href="logout.php"
               class="nav-item logout-btn <?php if($current_page == 'logout.php') echo 'active'; ?>">
                <span>🚪</span> Logout
            </a>

        </nav>
    </div>

    <!-- THEME TOGGLE (STAY WHERE IT IS) -->
    <div class="sidebar-bottom">

        <button id="themeToggle" class="theme-btn">
            <span id="themeIcon">🌙</span>
            <span id="themeText">Dark Mode</span>
        </button>

    </div>

</div>
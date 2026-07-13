<?php
/**
 * Shared sidebar toggle + state script.
 * Include at the bottom of every dashboard page, right before </body>.
 * Expects #sidebar, #sidebarOverlay, #mainContent, #mainHeader to exist.
 * Uses the html.sidebar-open / html.sidebar-closed classes defined in dashboard.css.
 */
?>
<script>
(function () {
    const sidebar   = document.getElementById('sidebar');
    const overlay   = document.getElementById('sidebarOverlay');
    const main      = document.getElementById('mainContent');
    const header    = document.getElementById('mainHeader');
    const html      = document.documentElement;

    function isOpen() {
        return html.classList.contains('sidebar-open');
    }

    function applyState(open) {
        if (open) {
            html.classList.remove('sidebar-closed');
            html.classList.add('sidebar-open');
            localStorage.setItem('sidebarClosed', '0');
        } else {
            html.classList.remove('sidebar-open');
            html.classList.add('sidebar-closed');
            localStorage.setItem('sidebarClosed', '1');
        }
    }

    window.toggleSidebar = function () {
        applyState(!isOpen());
    };

    // Ensure correct state after DOM is ready (handles edge cases)
    document.addEventListener('DOMContentLoaded', function () {
        var stored = localStorage.getItem('sidebarClosed');
        var shouldClose = stored === '1' || (stored === null && window.innerWidth < 768);
        applyState(!shouldClose);
    });

    window.addEventListener('resize', function () {
        if (window.innerWidth >= 768) {
            // On desktop, respect localStorage
            var stored = localStorage.getItem('sidebarClosed');
            applyState(stored !== '1');
        } else {
            // On mobile, close sidebar
            applyState(false);
        }
    });
})();
</script>

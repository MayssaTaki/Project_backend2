document.addEventListener('DOMContentLoaded', function () {
    const notificationsMenu = document.getElementById('notificationsMenu');
    const notificationsDropdown = document.getElementById('notificationsDropdown');

    async function fetchNotifications() {
        try {
            // استدعاء Route لإحضار الإشعارات
            const response = await fetch('/test-notifications');
            const notifications = await response.json();

            // تحديث محتوى القائمة المنسدلة
            notificationsMenu.innerHTML = ''; // مسح المحتوى القديم
            notificationsMenu.innerHTML += `<span class="dropdown-header">${notifications.length} إشعارات جديدة</span>`;

            notifications.forEach(notification => {
                notificationsMenu.innerHTML += `
                    <div class="dropdown-divider"></div>
                    <a href="#" class="dropdown-item">
                        <i class="fas fa-info-circle mr-2"></i> ${notification.text}
                        <span class="float-right text-muted text-sm">${notification.time}</span>
                    </a>
                `;
            });

            notificationsMenu.innerHTML += `
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item dropdown-footer">عرض كل الإشعارات</a>
            `;
        } catch (error) {
            console.error('خطأ أثناء جلب الإشعارات:', error);
        }
    }

    // عند النقر على أيقونة الإشعارات
    if (notificationsDropdown) {
        notificationsDropdown.addEventListener('click', function (event) {
            event.preventDefault();
            // إظهار/إخفاء القائمة
            notificationsMenu.classList.toggle('d-none');
            fetchNotifications(); // جلب الإشعارات
        });
    }
});

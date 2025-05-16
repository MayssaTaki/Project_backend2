document.addEventListener('DOMContentLoaded', function () {
    // تعريف العناصر
    const notificationsMenu = document.getElementById('notificationsMenu');
    const notificationsDropdown = document.getElementById('notificationsDropdown');

    // تحقق من وجود العناصر
    if (notificationsDropdown && notificationsMenu) {
        // إضافة حدث عند النقر على أيقونة الإشعارات
        notificationsDropdown.addEventListener('click', function (event) {
            event.preventDefault(); // منع السلوك الافتراضي
            notificationsMenu.classList.toggle('d-none'); // عرض/إخفاء القائمة المنسدلة
            fetchNotifications(); // استدعاء دالة جلب الإشعارات
        });
    } else {
        // تسجيل الأخطاء إذا لم يتم العثور على العناصر
        if (!notificationsMenu) {
            console.error('Element with id "notificationsMenu" is not found in the DOM.');
        }
        if (!notificationsDropdown) {
            console.error('Element with id "notificationsDropdown" is not found in the DOM.');
        }
    }

    // دالة لجلب الإشعارات
    async function fetchNotifications() {
        try {
            const response = await fetch('/test-notifications');
            const notifications = await response.json();

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
});

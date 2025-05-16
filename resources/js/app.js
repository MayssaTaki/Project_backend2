import './bootstrap';
import toastr from 'toastr';
import 'toastr/build/toastr.min.css';
// الحصول على معرف المستخدم الحالي
const userId = document.querySelector('meta[name="user-id"]').content;

window.Echo.private(`App.Models.User.${userId}`)
    .notification((notification) => {
        // تحديث عداد الإشعارات
        const notificationCountElement = document.getElementById('notification-count');
        const notificationsContent = document.getElementById('notifications-content');

        let count = parseInt(notificationCountElement.textContent || 0);
        count += 1;
        notificationCountElement.textContent = count;

        // إضافة الإشعار الجديد إلى القائمة
        const notificationItem = `
            <span class="dropdown-item">
                <i class="fas fa-file mr-2"></i> ${notification.message}
                <span class="float-right text-muted text-sm">Just now</span>
            </span>
        `;
        notificationsContent.innerHTML = notificationItem + notificationsContent.innerHTML;
    });
    document.addEventListener('DOMContentLoaded', function () {
        const notificationsDropdown = document.getElementById('notificationsDropdown');
        const notificationsList = document.createElement('ul');
        notificationsList.classList.add('dropdown-menu', 'dropdown-menu-lg', 'dropdown-menu-right');
    
        // جلب الإشعارات عند فتح القائمة
        notificationsDropdown.addEventListener('click', function () {
            fetch('/notifications')
                .then(response => response.json())
                .then(data => {
                    notificationsList.innerHTML = ''; // تنظيف القائمة
    
                    if (data.length === 0) {
                        const emptyMessage = document.createElement('li');
                        emptyMessage.textContent = 'لا توجد إشعارات جديدة';
                        notificationsList.appendChild(emptyMessage);
                    } else {
                        data.forEach(notification => {
                            const listItem = document.createElement('li');
                            listItem.classList.add('notification-item');
                            listItem.textContent = notification.data.message;
    
                            listItem.addEventListener('click', function () {
                                markAsRead(notification.id);
                            });
    
                            notificationsList.appendChild(listItem);
                        });
                    }
                });
        });
    
        // وظيفة لتمييز الإشعار كمقروء
        function markAsRead(notificationId) {
            fetch('/notifications/mark-as-read', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ id: notificationId })
            }).then(response => {
                console.log('Notification marked as read');
            });
        }
    
        notificationsDropdown.appendChild(notificationsList);
    });
    
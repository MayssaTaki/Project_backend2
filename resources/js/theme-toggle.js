document.addEventListener('DOMContentLoaded', () => {
    // تحقق من الحالة المخزنة في LocalStorage
    const savedTheme = localStorage.getItem('theme') || 'light-mode';
    document.body.classList.add(savedTheme);

    // تعريف الزر
    const toggleButton = document.getElementById('toggle-dark-mode');
    if (toggleButton) {
        toggleButton.addEventListener('click', () => {
            // تبديل بين الوضعين
            const isDark = document.body.classList.contains('dark-mode');
            const newTheme = isDark ? 'light-mode' : 'dark-mode';

            // تعديل الكلاس
            document.body.classList.remove(isDark ? 'dark-mode' : 'light-mode');
            document.body.classList.add(newTheme);

            // حفظ الحالة في LocalStorage
            localStorage.setItem('theme', newTheme);
        });
    }
});

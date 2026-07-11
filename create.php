<div class="max-w-2xl mx-auto bg-white rounded-xl shadow-sm p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800"><i class="fas fa-user-plus text-blue-500"></i> إضافة مستخدم جديد</h1>
        <a href="/users" class="text-blue-600 hover:text-blue-800 text-sm"><i class="fas fa-arrow-left"></i> العودة</a>
    </div>

    <form method="POST" action="/users/store">
        <div class="grid grid-cols-2 gap-4">
            <div class="mb-3">
                <label class="block text-sm font-medium text-gray-700">اسم المستخدم <span class="text-red-500">*</span></label>
                <input type="text" name="username" placeholder="username" class="w-full border rounded-lg px-3 py-2" required>
            </div>
            <div class="mb-3">
                <label class="block text-sm font-medium text-gray-700">الاسم الكامل <span class="text-red-500">*</span></label>
                <input type="text" name="full_name" placeholder="أحمد محمد" class="w-full border rounded-lg px-3 py-2" required>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div class="mb-3">
                <label class="block text-sm font-medium text-gray-700">البريد الإلكتروني <span class="text-red-500">*</span></label>
                <input type="email" name="email" placeholder="user@example.com" class="w-full border rounded-lg px-3 py-2" required>
            </div>
            <div class="mb-3">
                <label class="block text-sm font-medium text-gray-700">رقم الهاتف <span class="text-red-500">*</span></label>
                <input type="text" name="phone" placeholder="01012345678" class="w-full border rounded-lg px-3 py-2" required>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div class="mb-3">
                <label class="block text-sm font-medium text-gray-700">الدور <span class="text-red-500">*</span></label>
                <select name="role" class="w-full border rounded-lg px-3 py-2" required>
                    <option value="admin">مدير</option>
                    <option value="technician">فني</option>
                    <option value="accountant">محاسب</option>
                    <option value="reception">استقبال</option>
                    <option value="manager">مشرف</option>
                    <option value="sales">مبيعات</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="block text-sm font-medium text-gray-700">كلمة المرور <span class="text-red-500">*</span></label>
                <input type="password" name="password" placeholder="كلمة المرور" class="w-full border rounded-lg px-3 py-2" required>
            </div>
        </div>

        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 rounded-lg transition">
            <i class="fas fa-save"></i> إضافة المستخدم
        </button>
    </form>
</div>
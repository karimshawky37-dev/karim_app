<div class="max-w-2xl mx-auto bg-white rounded-xl shadow-sm p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800"><i class="fas fa-edit text-amber-500"></i> تعديل مستخدم: <?php echo $user['full_name']; ?></h1>
        <a href="/users" class="text-blue-600 hover:text-blue-800 text-sm"><i class="fas fa-arrow-left"></i> العودة</a>
    </div>

    <form method="POST" action="/users/update">
        <input type="hidden" name="id" value="<?php echo $user['id']; ?>">

        <div class="grid grid-cols-2 gap-4">
            <div class="mb-3">
                <label class="block text-sm font-medium text-gray-700">الاسم الكامل <span class="text-red-500">*</span></label>
                <input type="text" name="full_name" value="<?php echo $user['full_name']; ?>" class="w-full border rounded-lg px-3 py-2" required>
            </div>
            <div class="mb-3">
                <label class="block text-sm font-medium text-gray-700">البريد الإلكتروني <span class="text-red-500">*</span></label>
                <input type="email" name="email" value="<?php echo $user['email']; ?>" class="w-full border rounded-lg px-3 py-2" required>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div class="mb-3">
                <label class="block text-sm font-medium text-gray-700">رقم الهاتف <span class="text-red-500">*</span></label>
                <input type="text" name="phone" value="<?php echo $user['phone']; ?>" class="w-full border rounded-lg px-3 py-2" required>
            </div>
            <div class="mb-3">
                <label class="block text-sm font-medium text-gray-700">الدور <span class="text-red-500">*</span></label>
                <select name="role" class="w-full border rounded-lg px-3 py-2" required>
                    <option value="admin" <?php echo $user['role'] == 'admin' ? 'selected' : ''; ?>>مدير</option>
                    <option value="technician" <?php echo $user['role'] == 'technician' ? 'selected' : ''; ?>>فني</option>
                    <option value="accountant" <?php echo $user['role'] == 'accountant' ? 'selected' : ''; ?>>محاسب</option>
                    <option value="reception" <?php echo $user['role'] == 'reception' ? 'selected' : ''; ?>>استقبال</option>
                    <option value="manager" <?php echo $user['role'] == 'manager' ? 'selected' : ''; ?>>مشرف</option>
                    <option value="sales" <?php echo $user['role'] == 'sales' ? 'selected' : ''; ?>>مبيعات</option>
                </select>
            </div>
        </div>

        <div class="mb-3">
            <label class="block text-sm font-medium text-gray-700">كلمة المرور (اتركها فارغة إذا لا تريد تغييرها)</label>
            <input type="password" name="password" placeholder="كلمة المرور الجديدة" class="w-full border rounded-lg px-3 py-2">
        </div>

        <div class="mb-3">
            <label class="block text-sm font-medium text-gray-700">الحالة</label>
            <div class="flex items-center gap-2">
                <input type="checkbox" name="is_active" value="1" <?php echo $user['is_active'] ? 'checked' : ''; ?> class="w-4 h-4">
                <span>نشط</span>
            </div>
        </div>

        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 rounded-lg transition">
            <i class="fas fa-save"></i> تحديث المستخدم
        </button>
    </form>
</div>
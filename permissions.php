<div class="max-w-2xl mx-auto bg-white rounded-xl shadow-sm p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">
            <i class="fas fa-lock text-purple-500"></i> 
            صلاحيات المستخدم: <?php echo $user['full_name']; ?>
        </h1>
        <a href="/users" class="text-blue-600 hover:text-blue-800 text-sm"><i class="fas fa-arrow-left"></i> العودة</a>
    </div>
    
    <form method="POST" action="/users/update-permissions">
        <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
        
        <div class="bg-yellow-50 p-3 rounded-lg border-r-4 border-yellow-500 mb-4">
            <p class="text-sm text-yellow-700">
                <i class="fas fa-info-circle"></i>
                حدد الصلاحيات التي يمتلكها هذا المستخدم. المدير لديه جميع الصلاحيات تلقائياً.
            </p>
        </div>
        
        <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
            <?php foreach ($allPermissions as $p): ?>
                <?php 
                $perm = $p['permission'];
                $checked = in_array($perm, $userPerms) || $user['role'] == 'admin';
                ?>
                <label class="flex items-center gap-2 p-2 border rounded-lg hover:bg-gray-50 transition <?php echo $checked ? 'border-blue-500 bg-blue-50' : ''; ?>">
                    <input type="checkbox" name="permissions[]" value="<?php echo $perm; ?>" 
                           <?php echo $checked ? 'checked' : ''; ?>
                           <?php echo $user['role'] == 'admin' ? 'disabled' : ''; ?>>
                    <span class="text-sm"><?php echo $perm; ?></span>
                </label>
            <?php endforeach; ?>
        </div>
        
        <div class="mt-4">
            <button type="submit" class="w-full bg-purple-600 hover:bg-purple-700 text-white py-2 rounded-lg transition">
                <i class="fas fa-save"></i> حفظ الصلاحيات
            </button>
        </div>
    </form>
</div>
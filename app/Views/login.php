<?php ob_start(); ?>

<section class="min-h-[80vh] flex items-center justify-center p-6 bg-gray-50">
    <div class="bg-white rounded-[3rem] shadow-2xl p-12 max-w-md w-full border border-gray-100 relative overflow-hidden">
        <div class="absolute top-0 left-0 w-full h-2 bg-[#1a4731]"></div>
        
        <div class="text-center mb-10">
            <h2 class="text-3xl font-bold text-gray-800 uppercase tracking-tighter">Bienvenido</h2>
            <p class="text-gray-400 text-sm mt-2">Accede a tu cuenta gourmet.</p>
        </div>
        
        <?php if (isset($error)): ?>
            <div class="bg-red-50 text-red-500 p-4 rounded-2xl mb-6 text-sm font-bold border border-red-100">
                ⚠️ <?php echo $error; ?>
            </div>
        <?php endif; ?>
        
        <form action="index.php?action=login" method="POST" class="space-y-6">
            <div>
                <label class="form-label">Correo Electrónico</label>
                <input type="email" name="email" required placeholder="email@ejemplo.com" class="input-biconoir">
            </div>
            
            <div>
                <label class="form-label">Contraseña</label>
                <input type="password" name="password" required placeholder="••••••••" class="input-biconoir">
            </div>
            
            <button type="submit" class="w-full btn-primary !py-4">Entrar al sistema</button>
            
            <div class="text-center pt-4">
                <p class="text-sm text-gray-500">¿Eres nuevo? <a href="index.php?action=register" class="text-[#1a4731] font-bold hover:underline">Regístrate aquí</a></p>
            </div>
        </form>
    </div>
</section>

<?php 
$content = ob_get_clean(); 
include __DIR__ . '/layout.php'; 
?>

<?php ob_start(); ?>

<section class="min-h-[80vh] flex items-center justify-center p-6 bg-gray-50">
    <div class="bg-white rounded-[3rem] shadow-2xl p-12 max-w-md w-full border border-gray-100">
        <div class="text-center mb-10">
            <h2 class="text-3xl font-bold text-gray-800 uppercase tracking-tighter">Únete a Biconoir</h2>
            <p class="text-gray-400 text-sm mt-2 italic">Crea tu cuenta y empieza a disfrutar.</p>
        </div>
        
        <form action="index.php?action=register" method="POST" class="space-y-6">
            <div>
                <label class="form-label">Nombre Completo</label>
                <input type="text" name="name" required placeholder="Kevin David Cevallos Vega" class="input-biconoir">
            </div>
            
            <div>
                <label class="form-label">Teléfono</label>
                <input type="tel" name="phone" required placeholder="0998877665" class="input-biconoir">
            </div>
            
            <div>
                <label class="form-label">Correo Electrónico</label>
                <input type="email" name="email" required placeholder="email@ejemplo.com" class="input-biconoir">
            </div>
            
            <div>
                <label class="form-label">Contraseña</label>
                <input type="password" name="password" required placeholder="••••••••" class="input-biconoir">
            </div>
            
            <button type="submit" class="w-full btn-primary !py-4">Registrarse ahora</button>
            
            <div class="text-center pt-4">
                <p class="text-sm text-gray-500">¿Ya tienes cuenta? <a href="index.php?action=login" class="text-[#1a4731] font-bold hover:underline">Inicia sesión</a></p>
            </div>
        </form>
    </div>
</section>

<?php 
$content = ob_get_clean(); 
include __DIR__ . '/layout.php'; 
?>

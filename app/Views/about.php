<?php ob_start(); ?>

<section class="py-24 bg-white">
    <div class="container mx-auto px-6">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-20 items-center">
            <div class="relative">
                <img src="https://images.unsplash.com/photo-1552566626-52f8b828add9?q=80&w=800" class="rounded-[3rem] shadow-2xl z-10 relative">
                <div class="absolute -bottom-10 -right-10 bg-biconoir-green w-64 h-64 rounded-full -z-0 opacity-10"></div>
            </div>
            <div class="space-y-8">
                <h2 class="text-5xl font-bold text-gray-800 leading-tight">La esencia detrás de <span class="text-biconoir-green">Biconoir's</span></h2>
                <p class="text-gray-500 text-xl leading-relaxed">
                    Desde nuestra fundación, hemos buscado redefinir lo que significa comer fuera. No se trata solo de la comida, se trata de la atmósfera, el servicio y los recuerdos que creas en nuestra mesa.
                </p>
                <p class="text-gray-500 text-lg leading-relaxed">
                    Cada ingrediente es seleccionado cuidadosamente de productores locales, garantizando frescura y apoyando a nuestra comunidad. Nuestros chefs combinan técnicas tradicionales con presentaciones vanguardistas.
                </p>
                <div class="flex gap-12 pt-4">
                    <div>
                        <span class="block text-4xl font-bold text-biconoir-green italic">Est. 2014</span>
                        <span class="text-sm text-gray-400 uppercase tracking-widest font-bold">Trayectoria</span>
                    </div>
                    <div>
                        <span class="block text-4xl font-bold text-biconoir-green italic">100%</span>
                        <span class="text-sm text-gray-400 uppercase tracking-widest font-bold">Compromiso</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php 
$content = ob_get_clean(); 
include __DIR__ . '/layout.php'; 
?>

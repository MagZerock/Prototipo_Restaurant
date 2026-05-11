<?php ob_start(); ?>

<link rel="stylesheet" href="css/home.css">

<section class="hero-section">
    <div class="max-w-4xl animate-fade-in">
        <h1 class="text-5xl md:text-8xl font-bold mb-6 tracking-tighter uppercase leading-none">Sabor & <br> Elegancia</h1>
        <p class="text-xl md:text-2xl mb-12 font-light text-gray-200 tracking-wide">Descubre la verdadera cocina gourmet en el corazón de la ciudad.</p>
        <div class="flex flex-wrap justify-center gap-6">
            <a href="index.php?action=reservations" class="btn-primary !px-12 !py-5 text-xl">RESERVAR AHORA</a>
            <a href="index.php?action=menu" class="bg-white/10 backdrop-blur-md border-2 border-white/30 text-white px-12 py-5 rounded-2xl font-bold hover:bg-white hover:text-[#1a4731] transition-all">VER LA CARTA</a>
        </div>
    </div>
</section>

<section class="py-24 bg-white">
    <div class="container mx-auto px-6 grid grid-cols-1 md:grid-cols-3 gap-16">
        <div class="text-center group">
            <div class="w-20 h-20 bg-green-50 rounded-3xl flex items-center justify-center mx-auto mb-8 group-hover:bg-[#1a4731] transition-colors duration-500">
                <span class="text-4xl group-hover:scale-110 transition-transform">✨</span>
            </div>
            <h3 class="text-2xl font-bold text-gray-800 mb-4">Excelencia</h3>
            <p class="text-gray-500 leading-relaxed italic">Cada plato es una obra de arte creada por nuestros maestros culinarios.</p>
        </div>
        <div class="text-center group">
            <div class="w-20 h-20 bg-green-50 rounded-3xl flex items-center justify-center mx-auto mb-8 group-hover:bg-[#1a4731] transition-colors duration-500">
                <span class="text-4xl group-hover:scale-110 transition-transform">🍷</span>
            </div>
            <h3 class="text-2xl font-bold text-gray-800 mb-4">Exclusividad</h3>
            <p class="text-gray-500 leading-relaxed italic">Un ambiente íntimo y sofisticado diseñado para los paladares más exigentes.</p>
        </div>
        <div class="text-center group">
            <div class="w-20 h-20 bg-green-50 rounded-3xl flex items-center justify-center mx-auto mb-8 group-hover:bg-[#1a4731] transition-colors duration-500">
                <span class="text-4xl group-hover:scale-110 transition-transform">📍</span>
            </div>
            <h3 class="text-2xl font-bold text-gray-800 mb-4">Ubicación</h3>
            <p class="text-gray-500 leading-relaxed italic">Encuéntranos en el sector más exclusivo de la ciudad.</p>
        </div>
    </div>
</section>

<?php include __DIR__ . '/locations_section.php'; ?>

<?php 
$content = ob_get_clean(); 
include __DIR__ . '/layout.php'; 
?>

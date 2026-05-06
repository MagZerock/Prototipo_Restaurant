<?php ob_start(); ?>

<section class="py-24 bg-[#1a4731] min-h-[90vh] flex items-center">
    <div class="container mx-auto px-6 max-w-2xl">
        <div class="bg-white rounded-[3rem] shadow-2xl p-12 relative overflow-hidden">
            <div class="absolute top-0 right-0 p-8 text-6xl opacity-10 font-serif">"</div>
            
            <h2 class="text-4xl font-bold text-gray-800 mb-2">Tu opinión nos hace crecer</h2>
            <p class="text-gray-400 mb-10">Cuéntanos cómo fue tu experiencia en Biconoir's.</p>
            
            <form action="index.php?action=survey" method="POST" class="space-y-8">
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-4">Calificación</label>
                    <div class="flex gap-4">
                        <?php for($i=1; $i<=5; $i++): ?>
                        <label class="flex-grow cursor-pointer group text-center">
                            <input type="radio" name="rating" value="<?php echo $i; ?>" class="hidden peer" required>
                            <div class="py-3 rounded-2xl border-2 border-gray-100 peer-checked:border-[#1a4731] peer-checked:bg-green-50 peer-checked:text-[#1a4731] group-hover:bg-gray-50 transition font-bold text-gray-400">
                                <?php echo $i; ?> ⭐
                            </div>
                        </label>
                        <?php endfor; ?>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Nombre</label>
                    <input type="text" name="customer" required placeholder="Tu nombre" class="w-full px-5 py-4 border rounded-2xl focus:ring-2 focus:ring-[#1a4731] outline-none bg-gray-50">
                </div>
                
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Comentarios</label>
                    <textarea name="comment" required placeholder="¿Qué fue lo que más te gustó?" class="w-full px-5 py-4 border rounded-2xl focus:ring-2 focus:ring-[#1a4731] outline-none bg-gray-50 h-32"></textarea>
                </div>
                
                <button type="submit" class="w-full bg-[#1a4731] text-white py-5 rounded-2xl font-bold text-xl shadow-xl hover:bg-[#112d1f] transition">Enviar Encuesta</button>
            </form>
        </div>
    </div>
</section>

<?php 
$content = ob_get_clean(); 
include __DIR__ . '/layout.php'; 
?>

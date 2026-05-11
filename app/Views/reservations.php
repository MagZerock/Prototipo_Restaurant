<?php ob_start(); ?>

<section class="py-24 min-h-[80vh] flex items-center justify-center bg-gray-50">
    <div class="container mx-auto px-6 max-w-4xl">
        <!-- Main Card -->
        <div class="bg-white rounded-[4rem] shadow-[0_50px_100px_-20px_rgba(26,71,49,0.15)] p-12 md:p-20 relative overflow-hidden border border-gray-100">
            <!-- Decorative element -->
            <div class="absolute top-0 left-0 w-full h-3 bg-gradient-to-r from-[#1a4731] via-green-600 to-[#1a4731]"></div>
            
            <div class="text-center mb-16">
                <h2 class="text-6xl font-bold text-gray-800 mb-4 tracking-tighter uppercase">Reserva tu Mesa</h2>
                <p class="text-gray-400 italic text-lg">Cenas íntimas, eventos o reuniones corporativas.</p>
                <div class="h-1.5 w-24 bg-[#1a4731] mx-auto mt-8 rounded-full"></div>
            </div>
            
            <form action="index.php?action=reservations" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Name -->
                <div class="md:col-span-2">
                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-3 ml-2">Nombre Completo</label>
                    <input type="text" name="customer" required placeholder="Ej. Juan Pérez" 
                        class="w-full px-8 py-5 bg-gray-50 border-0 rounded-[2rem] focus:ring-2 focus:ring-[#1a4731] outline-none text-gray-700 transition-all shadow-inner text-lg">
                </div>
                
                <!-- Date -->
                <div>
                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-3 ml-2">Fecha de Visita</label>
                    <input type="date" name="date" required 
                        class="w-full px-8 py-5 bg-gray-50 border-0 rounded-[2rem] focus:ring-2 focus:ring-[#1a4731] outline-none text-gray-700 transition-all shadow-inner">
                </div>
                
                <!-- Time -->
                <div>
                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-3 ml-2">Hora de Reserva</label>
                    <input type="time" name="time" required 
                        class="w-full px-8 py-5 bg-gray-50 border-0 rounded-[2rem] focus:ring-2 focus:ring-[#1a4731] outline-none text-gray-700 transition-all shadow-inner">
                </div>
                
                <!-- Pax -->
                <div>
                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-3 ml-2">Comensales</label>
                    <div class="relative">
                        <input type="number" name="pax" required min="1" max="20" placeholder="Número de personas" 
                            class="w-full px-8 py-5 bg-gray-50 border-0 rounded-[2rem] focus:ring-2 focus:ring-[#1a4731] outline-none text-gray-700 transition-all shadow-inner">
                        <span class="absolute right-8 top-1/2 -translate-y-1/2 text-gray-300 font-bold">Pax</span>
                    </div>
                </div>
                
                <!-- Type -->
                <div>
                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-3 ml-2">Tipo de Experiencia</label>
                    <select name="type" required 
                        class="w-full px-8 py-5 bg-gray-50 border-0 rounded-[2rem] focus:ring-2 focus:ring-[#1a4731] outline-none text-gray-700 transition-all shadow-inner appearance-none cursor-pointer">
                        <option value="Normal">Cena Gourmet Estándar</option>
                        <option value="Evento">Celebración Especial</option>
                        <option value="Corporativo">Almuerzo de Negocios</option>
                        <option value="Cata">Degustación de Vinos</option>
                    </select>
                </div>
                
                <!-- Notes -->
                <div class="md:col-span-2">
                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-3 ml-2">Notas Adicionales (Alergias o Preferencias)</label>
                    <textarea name="notes" placeholder="Cuéntanos algún detalle para hacer tu visita perfecta..." 
                        class="w-full px-8 py-6 bg-gray-50 border-0 rounded-[2.5rem] focus:ring-2 focus:ring-[#1a4731] outline-none text-gray-700 transition-all shadow-inner h-40 resize-none"></textarea>
                </div>
                
                <!-- Submit -->
                <div class="md:col-span-2 pt-10">
                    <button type="submit" 
                        class="w-full bg-[#1a4731] text-white py-7 rounded-[2.5rem] font-bold text-2xl shadow-[0_20px_50px_rgba(26,71,49,0.3)] hover:bg-black hover:scale-[1.02] transition-all transform active:scale-95 flex items-center justify-center space-x-4">
                        <span>Confirmar Reserva</span>
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </button>
                    <p class="text-center text-[10px] text-gray-400 uppercase tracking-[0.2em] font-bold mt-8">Te enviaremos una confirmación al completar los datos.</p>
                </div>
            </form>
        </div>
    </div>
</section>

<?php 
$content = ob_get_clean(); 
include __DIR__ . '/layout.php'; 
?>

<?php ob_start(); ?>

<section class="py-24 min-h-[80vh] flex items-center justify-center bg-gray-50">
    <div class="container mx-auto px-6 max-w-4xl">
        
        <div class="flex justify-center mb-8 space-x-4">
            <button onclick="switchTab('nueva')" id="tab_nueva" class="px-8 py-4 rounded-full font-bold text-sm tracking-widest uppercase transition-all bg-[#1a4731] text-white shadow-lg">Hacer una Reserva</button>
            <button onclick="switchTab('mis')" id="tab_mis" class="px-8 py-4 rounded-full font-bold text-sm tracking-widest uppercase transition-all bg-white text-gray-500 hover:bg-gray-100 border border-gray-200">Mis Reservas</button>
        </div>

        <div id="panel_nueva_reserva" class="bg-white rounded-[4rem] shadow-[0_50px_100px_-20px_rgba(26,71,49,0.15)] p-12 md:p-20 relative overflow-hidden border border-gray-100 block">
            <div class="absolute top-0 left-0 w-full h-3 bg-gradient-to-r from-[#1a4731] via-green-600 to-[#1a4731]"></div>
            
            <div class="text-center mb-16">
                <h2 class="text-6xl font-bold text-gray-800 mb-4 tracking-tighter uppercase">Reserva tu Mesa</h2>
                <p class="text-gray-400 italic text-lg">Cenas íntimas, eventos o reuniones corporativas.</p>
                <div class="h-1.5 w-24 bg-[#1a4731] mx-auto mt-8 rounded-full"></div>
            </div>
            
            <form action="index.php?action=reservations" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="md:col-span-2">
                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-3 ml-2">Nombre Completo</label>
                    <input type="text" name="customer" required placeholder="Ej. Juan Pérez" 
                        class="w-full px-8 py-5 bg-gray-50 border-0 rounded-[2rem] focus:ring-2 focus:ring-[#1a4731] outline-none text-gray-700 transition-all shadow-inner text-lg">
                </div>
                
                <input type="hidden" name="date" id="selected_date" required>
                <input type="hidden" name="time" id="selected_time" required>

                <div>
                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-3 ml-2">Duración de la Reserva</label>
                    <select name="duration" id="duration_select" required class="w-full px-8 py-5 bg-gray-50 border-0 rounded-[2rem] focus:ring-2 focus:ring-[#1a4731] outline-none text-gray-700 transition-all shadow-inner appearance-none cursor-pointer">
                        <option value="1">1 Hora</option>
                        <option value="2">2 Horas</option>
                        <option value="3">3 Horas</option>
                        <option value="4">4 Horas</option>
                    </select>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-3 ml-2">Selecciona Fecha y Hora</label>
                    <div class="bg-gray-50 rounded-[2rem] p-6 border border-gray-100 shadow-inner">
                        <div class="flex space-x-3 overflow-x-auto pb-4 mb-4 hide-scrollbar" id="calendar_days">
                            <!-- Days will be injected here -->
                        </div>
                        <div id="time_slots" class="grid grid-cols-4 sm:grid-cols-5 md:grid-cols-6 gap-3">
                            <div class="col-span-full text-center text-gray-400 text-sm italic py-4">Selecciona un día para ver los horarios.</div>
                        </div>
                        
                        <div class="mt-6 pt-4 border-t border-gray-200 flex flex-wrap justify-center items-center gap-6 text-[10px] text-gray-500 font-bold uppercase tracking-widest">
                            <span class="flex items-center"><span class="w-4 h-4 bg-white border-2 border-gray-200 rounded-md mr-2"></span> Disponible</span>
                            <span class="flex items-center"><span class="w-4 h-4 bg-[#1a4731] border-2 border-[#1a4731] rounded-md mr-2 shadow-md"></span> Seleccionado</span>
                            <span class="flex items-center"><span class="w-4 h-4 bg-green-100 border-2 border-green-300 rounded-md mr-2"></span> Ocupado</span>
                        </div>
                    </div>
                </div>
                
                <div>
                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-3 ml-2">Comensales</label>
                    <div class="relative">
                        <input type="number" name="pax" required min="1" max="20" placeholder="Número de personas" 
                            class="w-full px-8 py-5 bg-gray-50 border-0 rounded-[2rem] focus:ring-2 focus:ring-[#1a4731] outline-none text-gray-700 transition-all shadow-inner">
                        <span class="absolute right-8 top-1/2 -translate-y-1/2 text-gray-300 font-bold">Pax</span>
                    </div>
                </div>
                
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
                
                <div class="md:col-span-2">
                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-3 ml-2">Notas Adicionales (Alergias o Preferencias)</label>
                    <textarea name="notes" placeholder="Cuéntanos algún detalle para hacer tu visita perfecta..." 
                        class="w-full px-8 py-6 bg-gray-50 border-0 rounded-[2.5rem] focus:ring-2 focus:ring-[#1a4731] outline-none text-gray-700 transition-all shadow-inner h-40 resize-none"></textarea>
                </div>
                
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
        
        <!-- Panel Mis Reservas -->
        <div id="panel_mis_reservas" class="bg-white rounded-[4rem] shadow-[0_50px_100px_-20px_rgba(26,71,49,0.15)] p-12 relative overflow-hidden border border-gray-100 hidden">
            <div class="absolute top-0 left-0 w-full h-3 bg-gradient-to-r from-gray-200 via-gray-300 to-gray-200"></div>
            
            <div class="flex justify-between items-center mb-8">
                <div>
                    <h3 class="text-3xl font-bold text-gray-800 tracking-tighter uppercase">Mis Reservas</h3>
                    <p class="text-gray-400 italic mt-1">Gestiona tus reservaciones actuales.</p>
                </div>
                <div class="bg-gray-50 px-6 py-3 rounded-2xl border border-gray-100">
                    <p class="text-[10px] text-gray-500 font-bold uppercase tracking-widest">Capacidad Usada</p>
                    <p class="text-2xl font-bold text-[#1a4731]"><span class="text-4xl"><?php echo count($userReservations); ?></span> / 2</p>
                </div>
            </div>

            <?php if (empty($userReservations)): ?>
                <div class="text-center py-12 bg-gray-50 rounded-[2rem] border border-gray-100">
                    <p class="text-gray-400 italic">No tienes reservas activas en este momento.</p>
                </div>
            <?php else: ?>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <?php foreach ($userReservations as $ur): ?>
                        <div class="bg-gray-50 rounded-[2rem] p-6 border border-gray-100 flex flex-col relative group hover:border-red-200 transition-colors">
                            <div class="flex justify-between items-start mb-4">
                                <div>
                                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Fecha</p>
                                    <p class="text-xl font-bold text-gray-800"><?php echo $ur['date']; ?></p>
                                </div>
                                <div class="text-right">
                                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Hora</p>
                                    <p class="text-xl font-bold text-[#1a4731]"><?php echo substr($ur['time'], 0, 5); ?></p>
                                </div>
                            </div>
                            
                            <?php
                                $dur = '1h';
                                if (preg_match('/Duración:\s*([0-9\.]+)h\.?/i', $ur['notes'], $m)) {
                                    $dur = $m[1] . 'h';
                                }
                            ?>
                            
                            <div class="flex items-center space-x-4 mb-6">
                                <div class="flex items-center space-x-2 bg-white px-3 py-1.5 rounded-xl border border-gray-100">
                                    <span class="text-sm">👥</span>
                                    <span class="text-xs font-bold text-gray-600"><?php echo $ur['number_of_people']; ?> Pax</span>
                                </div>
                                <div class="flex items-center space-x-2 bg-white px-3 py-1.5 rounded-xl border border-gray-100">
                                    <span class="text-sm">⏱️</span>
                                    <span class="text-xs font-bold text-gray-600"><?php echo $dur; ?></span>
                                </div>
                            </div>
                            
                            <div class="mt-auto pt-4 border-t border-gray-200">
                                <button type="button" onclick="if(confirm('¿Estás seguro de que deseas cancelar esta reserva?')) window.location.href='index.php?action=cancel_reservation&id=<?php echo $ur['reservation_id']; ?>';" 
                                        class="w-full bg-white text-red-500 py-3 rounded-xl font-bold text-sm border-2 border-red-100 hover:bg-red-50 hover:border-red-200 transition-colors flex items-center justify-center space-x-2">
                                    <span>❌ Cancelar Reserva</span>
                                </button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<script>
    window.serverReservations = <?php echo isset($existingReservations) ? json_encode($existingReservations) : '[]'; ?>;
</script>

<?php 
$content = ob_get_clean(); 
include __DIR__ . '/layout.php'; 
?>

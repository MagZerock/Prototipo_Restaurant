document.addEventListener('DOMContentLoaded', function () {
    const reservationForm = document.querySelector('form[action="index.php?action=reservations"]');

    if (reservationForm) {
        const calendarDaysContainer = document.getElementById('calendar_days');
        const timeSlotsContainer = document.getElementById('time_slots');
        const hiddenDate = document.getElementById('selected_date');
        const hiddenTime = document.getElementById('selected_time');
        const durationSelect = document.getElementById('duration_select');

        let selectedDateStr = null;
        let selectedTimeStr = null;

        const days = [];
        let today = new Date();
        today.setHours(0, 0, 0, 0);

        for (let i = 1; i <= 14; i++) {
            let d = new Date(today);
            d.setDate(d.getDate() + i);
            days.push(d);
        }

        const slotCapacity = {};
        const reservations = window.serverReservations || [];

        reservations.forEach(res => {
            if (res.status === 'Rechazada' || res.status === 'Cancelled') return;

            const dateOnly = res.date.split(' ')[0];
            const timeStr = res.time.substring(0, 5);

            let duration = 1;
            if (res.duration !== undefined && res.duration !== null) {
                duration = parseFloat(res.duration);
            } else if (res.notes) {
                const match = res.notes.match(/Duraci[oó]n:\s*([0-9\.]+)h/i);
                if (match) duration = parseFloat(match[1]);
            }

            let [h, m] = timeStr.split(':').map(Number);
            let slotsToBlock = Math.max(1, Math.ceil(duration * 2));

            for (let j = 0; j < slotsToBlock; j++) {
                let slotH = h + Math.floor((m + j * 30) / 60);
                let slotM = (m + j * 30) % 60;
                let sTime = `${slotH.toString().padStart(2, '0')}:${slotM.toString().padStart(2, '0')}`;
                let key = `${dateOnly}_${sTime}`;
                slotCapacity[key] = (slotCapacity[key] || 0) + 1;
            }
        });

        function renderDays() {
            calendarDaysContainer.innerHTML = '';
            const dayNames = ['Dom', 'Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb'];
            const monthNames = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];

            days.forEach(d => {
                const dateStr = `${d.getFullYear()}-${(d.getMonth() + 1).toString().padStart(2, '0')}-${d.getDate().toString().padStart(2, '0')}`;
                const btn = document.createElement('button');
                btn.type = 'button';
                btn.className = `flex flex-col items-center min-w-[70px] py-3 px-4 rounded-2xl border-2 transition-all ${selectedDateStr === dateStr ? 'border-[#1a4731] bg-green-50 text-[#1a4731]' : 'border-gray-100 bg-white text-gray-500 hover:border-green-200'}`;

                btn.innerHTML = `
                    <span class="text-[10px] font-bold uppercase tracking-widest mb-1">${dayNames[d.getDay()]}</span>
                    <span class="text-2xl font-bold text-gray-800">${d.getDate()}</span>
                    <span class="text-[10px] font-bold">${monthNames[d.getMonth()]}</span>
                `;

                btn.onclick = () => {
                    selectedDateStr = dateStr;
                    selectedTimeStr = null;
                    hiddenDate.value = selectedDateStr;
                    hiddenTime.value = '';
                    renderDays();
                    renderTimes();
                };

                calendarDaysContainer.appendChild(btn);
            });
        }

        function renderTimes() {
            timeSlotsContainer.innerHTML = '';
            if (!selectedDateStr) {
                timeSlotsContainer.innerHTML = '<div class="col-span-full text-center text-gray-400 text-sm italic py-4">Selecciona un día para ver los horarios.</div>';
                return;
            }

            const reqDuration = parseInt(durationSelect.value) || 1;
            const reqSlots = reqDuration * 2;

            const times = [];
            for (let h = 12; h <= 22; h++) {
                times.push(`${h}:00`);
                times.push(`${h}:30`);
            }

            times.forEach(t => {
                let [baseH, baseM] = t.split(':').map(Number);
                let isAvailable = true;

                for (let j = 0; j < reqSlots; j++) {
                    let slotH = baseH + Math.floor((baseM + j * 30) / 60);
                    let slotM = (baseM + j * 30) % 60;
                    if (slotH >= 24) { isAvailable = false; break; }

                    let slotTimeStr = `${slotH.toString().padStart(2, '0')}:${slotM.toString().padStart(2, '0')}`;
                    let key = `${selectedDateStr}_${slotTimeStr}`;
                    let cap = slotCapacity[key] || 0;

                    if (cap >= 3) {
                        isAvailable = false;
                        break;
                    }
                }

                let currentCap = slotCapacity[`${selectedDateStr}_${t}`] || 0;

                const btn = document.createElement('button');
                btn.type = 'button';
                btn.disabled = !isAvailable;

                if (!isAvailable) {
                    btn.className = `py-3 rounded-xl border-2 border-green-300 bg-green-100 text-green-800 opacity-60 cursor-not-allowed font-bold text-sm relative flex flex-col items-center justify-center`;
                    if (currentCap >= 3) {
                        btn.innerHTML = `${t} <span class="text-[8px] text-green-900 mt-0.5 uppercase tracking-wider">Lleno</span>`;
                    } else if (currentCap > 0) {
                        btn.innerHTML = `${t} <span class="text-[8px] text-green-900 mt-0.5 uppercase tracking-wider">${3 - currentCap} disp. (Bloq.)</span>`;
                    } else {
                        btn.innerHTML = `${t} <span class="text-[8px] text-green-900 mt-0.5 uppercase tracking-wider">Choque</span>`;
                    }
                } else if (selectedTimeStr === t) {
                    btn.className = `py-3 rounded-xl border-2 border-[#1a4731] bg-[#1a4731] text-white font-bold text-sm shadow-md transition-transform transform scale-105 flex flex-col items-center justify-center`;
                    btn.innerHTML = `${t} <span class="text-[8px] text-green-200 mt-0.5 uppercase tracking-wider">Elegido</span>`;
                } else {
                    btn.className = `py-3 rounded-xl border-2 border-gray-100 bg-white text-gray-700 hover:border-green-300 hover:bg-green-50 font-bold text-sm transition-all flex flex-col items-center justify-center`;
                    if (currentCap > 0) {
                        btn.innerHTML = `${t} <span class="text-[8px] text-[#1a4731] mt-0.5 font-bold uppercase tracking-wider">${3 - currentCap} disp.</span>`;
                    } else {
                        btn.innerHTML = `${t} <span class="text-[8px] text-gray-400 mt-0.5 uppercase tracking-wider">Libre</span>`;
                    }
                }

                btn.onclick = () => {
                    selectedTimeStr = t;
                    hiddenTime.value = selectedTimeStr;
                    renderTimes();
                };

                timeSlotsContainer.appendChild(btn);
            });
        }

        durationSelect.addEventListener('change', renderTimes);

        renderDays();

        reservationForm.addEventListener('submit', function (e) {
            const customer = reservationForm.querySelector('input[name="customer"]').value.trim();
            const pax = reservationForm.querySelector('input[name="pax"]').value;

            if (!customer || !selectedDateStr || !selectedTimeStr || !pax) {
                e.preventDefault();
                alert('Por favor, completa todos los campos obligatorios y selecciona una fecha y hora en el calendario.');
                return;
            }

            if (pax < 1 || pax > 20) {
                e.preventDefault();
                alert('El número de comensales debe ser entre 1 y 20.');
                return;
            }

            const notesField = reservationForm.querySelector('textarea[name="notes"]');
            if (notesField && !notesField.value.includes('Duración:')) {
                notesField.value = `Duración: ${durationSelect.value}h. ` + notesField.value;
            }
        });
    }
});

window.switchTab = function (tab) {
    const pNueva = document.getElementById('panel_nueva_reserva');
    const pMis = document.getElementById('panel_mis_reservas');
    const tNueva = document.getElementById('tab_nueva');
    const tMis = document.getElementById('tab_mis');

    if (!pNueva || !pMis) return;

    if (tab === 'nueva') {
        pNueva.classList.remove('hidden');
        pNueva.classList.add('block');
        pMis.classList.remove('block');
        pMis.classList.add('hidden');

        tNueva.className = "px-8 py-4 rounded-full font-bold text-sm tracking-widest uppercase transition-all bg-[#1a4731] text-white shadow-lg";
        tMis.className = "px-8 py-4 rounded-full font-bold text-sm tracking-widest uppercase transition-all bg-white text-gray-500 hover:bg-gray-100 border border-gray-200";
    } else {
        pMis.classList.remove('hidden');
        pMis.classList.add('block');
        pNueva.classList.remove('block');
        pNueva.classList.add('hidden');

        tMis.className = "px-8 py-4 rounded-full font-bold text-sm tracking-widest uppercase transition-all bg-[#1a4731] text-white shadow-lg";
        tNueva.className = "px-8 py-4 rounded-full font-bold text-sm tracking-widest uppercase transition-all bg-white text-gray-500 hover:bg-gray-100 border border-gray-200";
    }
};

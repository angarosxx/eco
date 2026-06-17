<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crea tu cuenta en eco.cl</title>
    <link rel="stylesheet" href="https://rsms.me/inter/inter.css">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-gray-50 text-gray-900 antialiased min-h-screen flex flex-col justify-between">

    <div class="max-w-2xl mx-auto w-full px-4 py-12">
        <div class="bg-white rounded-2xl border border-gray-200 p-8 shadow-sm">
            <h2 class="text-2xl font-bold tracking-tight text-gray-950 mb-1">Crea tu cuenta en eco.cl</h2>
            <p class="text-sm text-gray-500 mb-6">Únete al marketplace B2C2B más rápido de Chile.</p>

            <form id="registration-form" action="/api/auth/process_register.php" method="POST" class="space-y-6">
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tipo de Perfil</label>
                    <div class="grid grid-cols-2 gap-4">
                        <label class="flex items-center justify-center p-3 border border-emerald-500 bg-emerald-50/50 rounded-xl cursor-pointer hover:bg-emerald-50 transition group">
                            <input type="radio" name="account_type" value="private" checked class="sr-only" onchange="toggleProfileFields('private')">
                            <span class="text-sm font-medium text-emerald-700">👤 Persona Particular</span>
                        </label>
                        <label class="flex items-center justify-center p-3 border border-gray-200 rounded-xl cursor-pointer hover:bg-gray-50 transition group">
                            <input type="radio" name="account_type" value="company" class="sr-only" onchange="toggleProfileFields('company')">
                            <span class="text-sm font-medium text-gray-600 group-hover:text-gray-900">🏢 Empresa / Contratista</span>
                        </label>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-gray-500 mb-1">Email</label>
                        <input type="email" name="email" required class="w-full h-11 px-3 border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-emerald-500">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-gray-500 mb-1">Contraseña</label>
                        <input type="password" name="password" required minlength="8" class="w-full h-11 px-3 border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-emerald-500">
                    </div>
                </div>

                <hr class="border-gray-100">

                <div id="fields-private" class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-gray-500 mb-1">Nombre</label>
                        <input type="text" name="first_name" class="w-full h-11 px-3 border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-emerald-500">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-gray-500 mb-1">Apellido</label>
                        <input type="text" name="last_name" class="w-full h-11 px-3 border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-emerald-500">
                    </div>
                </div>

                <div id="fields-company" class="grid grid-cols-1 sm:grid-cols-2 gap-4 hidden">
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-gray-500 mb-1">Razón Social</label>
                        <input type="text" name="company_name" class="w-full h-11 px-3 border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-emerald-500">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-gray-500 mb-1">RUT Empresa</label>
                        <input type="text" name="tax_id_vat" placeholder="12.345.678-9" class="w-full h-11 px-3 border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-emerald-500">
                    </div>
                    <div class="sm:col-span-2">
                        <label class="block text-xs font-semibold uppercase tracking-wider text-gray-500 mb-1">Dirección Comercial</label>
                        <input type="text" name="address" class="w-full h-11 px-3 border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-emerald-500">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-gray-500 mb-1">Región</label>
                        <select id="region-selector" class="w-full h-11 px-3 border border-gray-200 rounded-lg text-sm bg-white focus:outline-none focus:border-emerald-500">
                            <option value="">Selecciona Región</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-gray-500 mb-1">Comuna</label>
                        <select id="comuna-selector" name="comuna_id" required class="w-full h-11 px-3 border border-gray-200 rounded-lg text-sm bg-white focus:outline-none focus:border-emerald-500">
                            <option value="">Selecciona Comuna</option>
                        </select>
                    </div>
                </div>

                <button type="submit" class="w-full h-11 bg-emerald-600 hover:bg-emerald-700 text-white font-medium rounded-xl shadow-sm transition">
                    Registrar Cuenta
                </button>
            </form>
        </div>
    </div>

    <div id="alert-banner" class="hidden mb-6 p-4 rounded-lg bg-red-50 border border-red-200 text-sm text-red-600 flex items-center gap-2 animate-fade-in">
    <svg class="w-5 h-5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
    <span id="alert-message"></span>
</div>

<script>
    // 1. Toggle Account Profiles View Layout
    function toggleProfileFields(type) {
        const privateFields = document.getElementById('fields-private');
        const companyFields = document.getElementById('fields-company');
        
        if (type === 'private') {
            privateFields?.classList.remove('hidden');
            companyFields?.classList.add('hidden');
        } else {
            privateFields?.classList.add('hidden');
            companyFields?.classList.remove('hidden');
        }
    }

    // 2. Encapsulated Location API Layer
    const LocationAPI = {
        async fetchRegions() {
            const res = await fetch('/api/locations.php?action=regions');
            if (!res.ok) throw new Error('Failed to load regions');
            return res.json();
        },
        async fetchComunas(regionId) {
            const res = await fetch(`/api/locations.php?action=comunas&region_id=${regionId}`);
            if (!res.ok) throw new Error('Failed to load comunas');
            return res.json();
        }
    };

    // 3. Orchestration Engine
    document.addEventListener('DOMContentLoaded', () => {
        const regSel = document.getElementById('region-selector');
        const comSel = document.getElementById('comuna-selector');
        const regForm = document.getElementById('registration-form');
        const alertBanner = document.getElementById('alert-banner');
        const alertMessage = document.getElementById('alert-message');

        const showAlert = (msg) => {
            alertMessage.textContent = msg;
            alertBanner.classList.remove('hidden');
            alertBanner.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        };

        const hideAlert = () => alertBanner.classList.add('hidden');

        // Populate Regions Dropdown
        LocationAPI.fetchRegions()
            .then(data => {
                const regions = Array.isArray(data) ? data : (data.data || []);
                regSel.innerHTML = '<option value="">Selecciona Región</option>';
                regions.forEach(r => {
                    const label = r.roman_numeral ? `${r.roman_numeral} - ${r.name}` : r.name;
                    regSel.innerHTML += `<option value="${r.id}">${label}</option>`;
                });
            })
            .catch(err => console.error("Location init failed:", err));

        // Cascade Dynamic Comunas Dropdown
        regSel?.addEventListener('change', async () => {
            if (!regSel.value) {
                comSel.innerHTML = '<option value="">Selecciona Comuna</option>';
                return;
            }
            comSel.innerHTML = '<option value="">Cargando comunas...</option>';
            
            try {
                const data = await LocationAPI.fetchComunas(regSel.value);
                const comunas = Array.isArray(data) ? data : (data.data || []);
                comSel.innerHTML = '<option value="">Selecciona Comuna</option>';
                comunas.forEach(c => {
                    comSel.innerHTML += `<option value="${c.id}">${c.name}</option>`;
                });
            } catch (err) {
                comSel.innerHTML = '<option value="">Error cargando comunas</option>';
            }
        });

        // Professional AJAX Form Interception 
        regForm?.addEventListener('submit', async (e) => {
            e.preventDefault();
            hideAlert();

            const submitBtn = regForm.querySelector('button[type="submit"]');
            const originalBtnText = submitBtn ? submitBtn.innerHTML : '';
            
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<svg class="animate-spin h-5 w-5 mx-auto text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>';
            }

            try {
                const response = await fetch('/api/auth/process_register.php', {
                    method: 'POST',
                    body: new FormData(regForm)
                });

                const result = await response.json();

                if (result.success) {
                    window.location.href = result.redirect;
                } else {
                    showAlert(result.message || 'Error de registro.');
                }
            } catch (err) {
                showAlert('Error crítico al conectar con el servidor de autenticación.');
            } finally {
                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalBtnText;
                }
            }
        });
    });
</script>
    
</body>
</html>
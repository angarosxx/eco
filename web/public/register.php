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

            <form action="/api/auth/process_register.php" method="POST" class="space-y-6">
                
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

<script>
    function toggleProfileFields(type) {
        const privateFields = document.getElementById('fields-private');
        const companyFields = document.getElementById('fields-company');
        
        if (type === 'private') {
            privateFields.classList.remove('hidden');
            companyFields.classList.add('hidden');
        } else {
            privateFields.classList.add('hidden');
            companyFields.classList.remove('hidden');
        }
    }

    document.addEventListener('DOMContentLoaded', async () => {
        const regSel = document.getElementById('region-selector');
        const comSel = document.getElementById('comuna-selector');

        try {
            // 1. Fetch and load regions
            const resReg = await fetch('/api/locations.php?action=regions');
            const jsonReg = await resReg.json();
            
            // 🎯 FIXED: Support both flat array payloads and envelope configurations safely
            const regions = Array.isArray(jsonReg) ? jsonReg : (jsonReg.data || []);
            
            if (regions.length > 0) {
                // Keep default placeholder
                regSel.innerHTML = '<option value="">Selecciona Región</option>';
                regions.forEach(r => {
                    // Safe label fallback if database column 'roman_numeral' is omitted
                    const label = r.roman_numeral ? `${r.roman_numeral} - ${r.name}` : r.name;
                    regSel.innerHTML += `<option value="${r.id}">${label}</option>`;
                });
            }
        } catch (err) {
            console.error("Error loading regions:", err);
        }

        // 2. Listen for region shifts to reload Comunas
        regSel.addEventListener('change', async () => {
            if (!regSel.value) {
                comSel.innerHTML = '<option value="">Selecciona Comuna</option>';
                return;
            }

            comSel.innerHTML = '<option value="">Cargando comunas...</option>';

            try {
                const resCom = await fetch(`/api/locations.php?action=comunas&region_id=${regSel.value}`);
                const jsonCom = await resCom.json();
                
                // 🎯 FIXED: Map payload structure dynamically to match flat backend lists
                const comunas = Array.isArray(jsonCom) ? jsonCom : (jsonCom.data || []);
                
                comSel.innerHTML = '<option value="">Selecciona Comuna</option>';
                if (comunas.length > 0) {
                    comunas.forEach(c => {
                        comSel.innerHTML += `<option value="${c.id}">${c.name}</option>`;
                    });
                } else {
                    comSel.innerHTML = '<option value="">No se encontraron comunas</option>';
                }
            } catch (err) {
                console.error("Error loading comunas:", err);
                comSel.innerHTML = '<option value="">Error loading data</option>';
            }
        });
    });
</script>    
</body>
</html>
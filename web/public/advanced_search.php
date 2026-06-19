<?php
// ── 1. CONFIGURACIÓN DE LA BASE DE DATOS (BACKEND) ──
$db_host = 'localhost';
$db_name = 'tu_base_datos';
$db_user = 'tu_usuario';
$db_pass = 'tu_password';

try {
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8", $db_user, $db_pass, [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO:: some_core_setting_if_needed => true // Opcional
    ]);
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}

// ── 2. PROCESAR PETICIÓN DE BÚSQUEDA (AJAX / FETCH) ──
if (isset($_GET['action']) && $_GET['action'] === 'buscar') {
    header('Content-Type: application/json');

    // Capturar variables del frontend y normalizarlas (si están vacías, pasan como NULL a la DB)
    $marca       = !empty($_GET['marca'])       ? $_GET['marca']       : null;
    $modelo      = !empty($_GET['modelo'])      ? $_GET['modelo']      : null;
    $pais        = !empty($_GET['pais'])        ? $_GET['pais']        : null;
    $carroceria  = !empty($_GET['carroceria'])  ? $_GET['carroceria']  : null;
    $segmento    = null; // Reservado por estructura
    $combustible = !empty($_GET['combustible']) ? $_GET['combustible'] : null;
    $anioDesde   = !empty($_GET['anioDesde'])   ? (int)$_GET['anioDesde']   : null;
    $anioHasta   = !empty($_GET['anioHasta'])   ? (int)$_GET['anioHasta']   : null;
    $hpMin       = !empty($_GET['hpMin'])       ? (int)$_GET['hpMin']       : null;
    $hpMax       = !empty($_GET['hpMax'])       ? (int)$_GET['hpMax']       : null;
    $precioMin   = !empty($_GET['precioMin'])   ? (float)$_GET['precioMin'] : null;
    $precioMax   = !empty($_GET['precioMax'])   ? (float)$_GET['precioMax'] : null;
    $traccion    = !empty($_GET['traccion'])    ? $_GET['traccion']    : null;
    $transmision = !empty($_GET['transmision']) ? $_GET['transmision'] : null;
    $asientos    = null; // Reservado por estructura
    $autonomia   = !empty($_GET['autonomia'])   ? (int)$_GET['autonomia']   : null;
    
    // Parámetros de ordenamiento que vienen del frontend
    $sortCampo   = !empty($_GET['sortCampo'])   ? $_GET['sortCampo']   : 'marca';
    $sortDir     = !empty($_GET['sortDir'])     ? $_GET['sortDir']     : 'ASC';

    try {
        // Preparar la llamada al procedimiento almacenado nativo
        $stmt = $pdo->prepare("
            CALL busqueda_avanzada(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        
        $stmt->execute([
            $marca, $modelo, $pais, $carroceria, $segmento, $combustible,
            $anioDesde, $anioHasta, $hpMin, $hpMax, $precioMin, $precioMax,
            $traccion, $transmision, $asientos, $autonomia, $sortCampo, $sortDir
        ]);

        $resultados = $stmt->fetchAll();
        
        // Devolver los datos limpios al frontend inmediatamente
        echo json_encode($resultados);
    } catch (PDOException $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
    exit;
}

// ── 3. CARGA INICIAL DE DATOS (Para los Selects del formulario) ──
try {
    // Poblamos dinámicamente el select de marcas desde la DB real
    $stmtMarcas = $pdo->query("SELECT DISTINCT marca FROM vista_versiones_detalle ORDER BY marca ASC");
    $listaMarcas = $stmtMarcas->fetchAll(PDO::FETCH_COLUMN);
    
    // Obtenemos el total de registros global
    $stmtTotal = $pdo->query("SELECT COUNT(*) FROM vista_versiones_detalle");
    $totalVehiculos = $stmtTotal->fetchColumn();
} catch (PDOException $e) {
    $listaMarcas = [];
    $totalVehiculos = 0;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Buscador Avanzado de Vehículos</title>
    </head>
<body>

    <header>
        Muestrario de Vehículos (<span id="hdr-total"><?php echo $totalVehiculos; ?></span>)
    </header>

    <form id="filter-form" onsubmit="event.preventDefault(); buscar();">
        <select id="f-marca" onchange="buscar()">
            <option value="">Todas las Marcas</option>
            <?php foreach ($listaMarcas as $m): ?>
                <option value="<?php echo htmlspecialchars($m); ?>"><?php echo htmlspecialchars($m); ?></option>
            <?php endphp; ?>
        </select>
        
        <input type="text" id="f-modelo" placeholder="Modelo..." oninput="buscar()">
        <input type="hidden" id="f-pais" value="">
        <input type="hidden" id="f-carroceria" value="">
        <input type="hidden" id="f-combustible" value="">
        <input type="hidden" id="f-traccion" value="">
        <input type="hidden" id="f-transmision" value="">
        <input type="number" id="f-anio-desde" placeholder="Año desde" oninput="buscar()">
        <input type="number" id="f-anio-hasta" placeholder="Año hasta" oninput="buscar()">
        <input type="number" id="f-hp-min" placeholder="HP min" oninput="buscar()">
        <input type="number" id="f-hp-max" placeholder="HP max" oninput="buscar()">
        <input type="number" id="f-precio-min" placeholder="Precio min" oninput="buscar()">
        <input type="number" id="f-precio-max" placeholder="Precio max" oninput="buscar()">
        <input type="number" id="f-autonomia" placeholder="Autonomía min" oninput="buscar()">
        
        <select id="sort-campo" onchange="buscar()">
            <option value="marca">Marca</option>
            <option value="precio">Precio</option>
            <option value="potencia">Potencia</option>
            <option value="anio">Año</option>
            <option value="autonomia">Autonomía</option>
        </select>
        <select id="sort-dir" onchange="buscar()">
            <option value="ASC">Ascendente</option>
            <option value="DESC">Descendente</option>
        </select>
    </form>

    <div id="active-filters"></div>
    <div>Resultados encontrados: <span id="count-display">0</span></div>
    
    <div id="st-resultados">0</div>
    <div id="st-hp">—</div>
    <div id="st-precio">—</div>
    <div id="st-paises">—</div>

    <div id="sql-preview" class="sql-code"></div>

    <div id="cards-grid"></div>

    <div id="modal" onclick="closeModal(e)">
        <div class="modal-content">
            <h2 id="m-title"></h2>
            <p id="m-sub"></p>
            <div id="m-body"></div>
        </div>
    </div>

<script>
// El array dinámico global que usará la app en el cliente
let resultados = [];

// ── BUSCAR (Llamada asíncrona AJAX al backend PHP) ──
function buscar() {
  const marca       = document.getElementById('f-marca').value;
  const modelo      = document.getElementById('f-modelo').value.trim();
  const pais        = document.getElementById('f-pais').value;
  const carroceria  = document.getElementById('f-carroceria').value;
  const combustible = document.getElementById('f-combustible').value;
  const traccion    = document.getElementById('f-traccion').value;
  const transmision = document.getElementById('f-transmision').value;
  const anioDesde   = document.getElementById('f-anio-desde').value;
  const anioHasta   = document.getElementById('f-anio-hasta').value;
  const hpMin       = document.getElementById('f-hp-min').value;
  const hpMax       = document.getElementById('f-hp-max').value;
  const precioMin   = document.getElementById('f-precio-min').value;
  const precioMax   = document.getElementById('f-precio-max').value;
  const autonomia   = document.getElementById('f-autonomia').value;
  
  const sortCampo   = document.getElementById('sort-campo').value;
  const sortDir     = document.getElementById('sort-dir').value;

  // Construir los parámetros URL query string dinámicamente
  const params = new URLSearchParams({
    action: 'buscar',
    marca, modelo, pais, carroceria, combustible, traccion, transmision,
    anioDesde, anioHasta, hpMin, hpMax, precioMin, precioMax, autonomia,
    sortCampo, sortDir
  });

  // Pintar de manera visual la consulta SQL equivalente en el cliente
  renderSQL(marca, modelo, pais, carroceria, combustible, traccion, transmision, anioDesde, anioHasta, hpMin, hpMax, precioMin, precioMax, autonomia);
  renderTags(marca, modelo, pais, carroceria, combustible, traccion, transmision, anioDesde, anioHasta, hpMin, hpMax, precioMin, precioMax, autonomia);

  // Petición Fetch al propio archivo PHP
  fetch(`?${params.toString()}`)
    .then(res => res.json())
    .then(data => {
      if(data.error) {
         console.error("Error en DB:", data.error);
         return;
      }
      resultados = data;
      renderCards(); // El renderizado de tarjetas se mantiene intacto y rápido
    })
    .catch(err => console.error("Error al buscar:", err));
}

// ── RENDER CARDS ──
function renderCards() {
  const grid = document.getElementById('cards-grid');
  document.getElementById('count-display').textContent = resultados.length;

  // Actualización de Estadísticas en caliente
  const hpVals = resultados.filter(v => v.hp).map(v => parseInt(v.hp));
  const prVals = resultados.filter(v => v.precio).map(v => parseFloat(v.precio));
  const paisSet = new Set(resultados.map(v => v.pais));
  
  document.getElementById('st-resultados').textContent = resultados.length;
  document.getElementById('st-hp').textContent      = hpVals.length ? Math.round(hpVals.reduce((a,b)=>a+b,0)/hpVals.length) + ' hp' : '—';
  document.getElementById('st-precio').textContent  = prVals.length ? '$' + Math.round(prVals.reduce((a,b)=>a+b,0)/prVals.length/1000) + 'k' : '—';
  document.getElementById('st-paises').textContent  = paisSet.size || '—';

  if (!resultados.length) {
    grid.innerHTML = `<div class="empty" style="grid-column:1/-1"><h3>Sin resultados</h3></div>`;
    return;
  }

  grid.innerHTML = resultados.map((v, i) => {
    const specAuto = v.autonomia ? `<div class="spec"><div class="spec-val accent">${v.autonomia}</div><div class="spec-lbl">km auto.</div></div>` : `<div class="spec"><div class="spec-val">${v.transmision}</div><div class="spec-lbl">Transmis.</div></div>`;
    return `
    <div class="card" onclick="openModal(${i})">
      <div class="card-header">
        <div>
          <div class="card-brand">${v.marca}</div>
          <div class="card-name">${v.modelo}</div>
          <div class="card-version">${v.version}</div>
        </div>
        <div class="card-year">${v.anio}</div>
      </div>
      <div class="card-specs">
        <div class="spec"><div class="spec-val">${v.hp}</div><div class="spec-lbl">HP</div></div>
        ${specAuto}
      </div>
      <div class="card-footer">
        <div class="card-price">$${parseFloat(v.precio).toLocaleString()}</div>
        <div class="card-origin">📍 ${v.pais}</div>
      </div>
    </div>`;
  }).join('');
}

// ── MODAL DE DETALLES ──
function openModal(idx) {
  const v = resultados[idx];
  document.getElementById('m-title').textContent = `${v.marca} ${v.modelo}`;
  document.getElementById('m-sub').textContent   = `${v.version} · ${v.anio}`;
  document.getElementById('m-body').innerHTML = `
    <div class="modal-grid">
        <div><label>Motor:</label> <span>${v.motor}</span></div>
        <div><label>Potencia:</label> <span>${v.hp} HP</span></div>
        <div><label>Tracción:</label> <span>${v.traccion}</span></div>
        <div><label>Precio Base:</label> <span>$${parseFloat(v.precio).toLocaleString()} USD</span></div>
    </div>
    <div class="sql-panel">
      <div class="sql-panel-title">Consulta SQL equivalente</div>
      <div class="sql-code">SELECT * FROM vista_versiones_detalle WHERE marca = '${v.marca}' AND modelo = '${v.modelo}' AND version = '${v.version}';</div>
    </div>
  `;
  document.getElementById('modal').classList.add('open');
}

function closeModal(e) {
  if (e.target.id === 'modal') document.getElementById('modal').classList.remove('open');
}

// ── RENDER TAGS ACTIVOS ──
function renderTags(marca, modelo, pais, carroceria, combustible, traccion, transmision, ad, ah, hmin, hmax, pmin, pmax, auto) {
  const container = document.getElementById('active-filters');
  const tags = [];
  if (marca)      tags.push(`Marca: ${marca}`);
  if (modelo)     tags.push(`Modelo: ${modelo}`);
  if (ad || ah)   tags.push(`Año: ${ad||'?'} – ${ah||'?'}`);
  if (pmin||pmax) tags.push(`Precio: $${pmin||0} – $${pmax||'∞'}`);
  if (auto)       tags.push(`Autonomía ≥ ${auto} km`);
  container.innerHTML = tags.map(t => `<div class="filter-tag" onclick="resetFiltros()">✕ ${t}</div>`).join('');
}

// ── VISUALIZACIÓN PREVIA DE LA CONSULTA SQL EN VIVO ──
function renderSQL(marca, modelo, pais, carroceria, combustible, traccion, transmision, ad, ah, hmin, hmax, pmin, pmax, auto) {
  const params = [
    marca ? `'${marca}'` : 'NULL', modelo ? `'${modelo}'` : 'NULL', pais ? `'${pais}'` : 'NULL',
    carroceria ? `'${carroceria}'` : 'NULL', 'NULL', combustible ? `'${combustible}'` : 'NULL',
    ad || 'NULL', ah || 'NULL', hmin || 'NULL', hmax || 'NULL', pmin || 'NULL', pmax || 'NULL',
    traccion ? `'${traccion}'` : 'NULL', transmision ? `'${transmision}'` : 'NULL', 'NULL', auto || 'NULL',
    `'${document.getElementById('sort-campo').value}'`, `'${document.getElementById('sort-dir').value}'`
  ];
  document.getElementById('sql-preview').innerHTML = `CALL busqueda_avanzada(<br>&nbsp;&nbsp;${params.join(',<br>&nbsp;&nbsp;')}<br>);`;
}

// ── REINICIAR FILTROS ──
function resetFiltros() {
  ['f-marca'].forEach(id => { if(document.getElementById(id)) document.getElementById(id).value = ''; });
  ['f-modelo','f-anio-desde','f-anio-hasta','f-hp-min','f-hp-max','f-precio-min','f-precio-max','f-autonomia'].forEach(id => { if(document.getElementById(id)) document.getElementById(id).value = ''; });
  buscar();
}

// Ejecución inicial automática
buscar();
</script>
</body>
</html>
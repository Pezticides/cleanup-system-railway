<!DOCTYPE html>
<html>
<head>
    <title>Submit Clean-Up Report</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
    <style>
        body{font-family:Arial,sans-serif;background:#f4f7f4;margin:0}.navbar{background:#1b5e20;color:white;padding:18px 40px;display:flex;justify-content:space-between;align-items:center}.navbar a{color:white;text-decoration:none;margin-left:15px;font-weight:bold}.container{max-width:760px;background:white;margin:40px auto;padding:25px;border-radius:10px;box-shadow:0 3px 10px rgba(0,0,0,.1)}label{font-weight:bold}input,select,textarea{width:100%;padding:10px;margin-top:6px;margin-bottom:15px;border:1px solid #ccc;border-radius:6px;box-sizing:border-box}textarea{height:100px}button,.btn{background:#2e7d32;color:white;padding:12px 16px;border:0;border-radius:6px;cursor:pointer;font-weight:bold;text-decoration:none;display:inline-block}.back{display:inline-block;margin-top:15px;color:#1b5e20;text-decoration:none;font-weight:bold}.hint{color:#666;font-size:13px}#map{width:100%;height:360px;border-radius:10px;margin:10px 0 15px;border:1px solid #ccc}.preview-img{display:none;max-width:220px;border-radius:8px;margin-bottom:15px}.row{display:flex;gap:10px;flex-wrap:wrap}.row button{margin-bottom:10px}
    </style>
</head>
<body>
<div class="navbar"><h2>Clean-Up Reporting System</h2><div><a href="/">Home</a><a href="/report">Submit Report</a>@auth<a href="{{ route('messages.index') }}">Messages</a>@endauth<a href="/login">Admin</a></div></div>
<div class="container">
    <h1>Submit Clean-Up Report</h1>
    @if($errors->any())<div style="color:red"><ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
    <form action="/report" method="POST" enctype="multipart/form-data">
        @csrf
        <label>Your Name:</label>
        <input type="text" name="reporter_name" required value="{{ old('reporter_name', auth()->user()->name ?? '') }}">

        <label>Location Name / Landmark:</label>
        <input type="text" name="location" id="locationInput" required value="{{ old('location') }}" placeholder="Example: Zone 1, Barangay Carmen, CDO">

        <input type="hidden" name="latitude" id="latitude" value="{{ old('latitude') }}">
        <input type="hidden" name="longitude" id="longitude" value="{{ old('longitude') }}">

        <label>Pin Exact Location:</label>
        <div class="row">
            <button type="button" onclick="useMyLocation()">Use My Current Location</button>
            <button type="button" onclick="clearPin()" style="background:#777">Clear Pin</button>
        </div>
        <p class="hint" id="locationStatus">Click the map to place the exact pin. Desktop GPS can be wrong, so manual pin is recommended.</p>
        <div id="map"></div>

        <label>Concern Type:</label>
        <select name="concern_type" required>
            @foreach(['Garbage Dumping','Clogged Canal','Dirty Street','Unmanaged Waste','Flooding','Other'] as $type)
                <option value="{{ $type }}" {{ old('concern_type') == $type ? 'selected' : '' }}>{{ $type }}</option>
            @endforeach
        </select>

        <label>Description:</label>
        <textarea name="description" required>{{ old('description') }}</textarea>

        <label>Upload Picture:</label>
        <input type="file" name="photo" id="photoInput" accept="image/*">
        <img id="preview" class="preview-img">

        <button type="submit">Submit Report</button>
    </form>
    <a href="/" class="back">← Back to Reports</a>
</div>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
const oldLat = document.getElementById('latitude').value;
const oldLng = document.getElementById('longitude').value;
const defaultLat = oldLat || 8.4542;
const defaultLng = oldLng || 124.6319;
let map = L.map('map').setView([defaultLat, defaultLng], oldLat && oldLng ? 17 : 13);
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {maxZoom: 19, attribution: 'OpenStreetMap'}).addTo(map);
let marker = null;
function setPin(lat, lng, message='Pinned location'){
    document.getElementById('latitude').value = Number(lat).toFixed(7);
    document.getElementById('longitude').value = Number(lng).toFixed(7);
    if(marker){ marker.setLatLng([lat,lng]); } else { marker = L.marker([lat,lng], {draggable:true}).addTo(map); marker.on('dragend', e => { const p=e.target.getLatLng(); setPin(p.lat,p.lng,'Pin moved'); }); }
    map.setView([lat,lng], 17);
    document.getElementById('locationStatus').innerText = message + ': ' + Number(lat).toFixed(7) + ', ' + Number(lng).toFixed(7);
}
if(oldLat && oldLng){ setPin(oldLat, oldLng, 'Saved pin'); }
map.on('click', e => setPin(e.latlng.lat, e.latlng.lng, 'Manual pin set'));
function useMyLocation(){
    const status=document.getElementById('locationStatus');
    if(!navigator.geolocation){ status.innerText='Geolocation is not supported. Click the map instead.'; return; }
    status.innerText='Getting GPS location... allow browser permission.';
    navigator.geolocation.getCurrentPosition(pos => setPin(pos.coords.latitude, pos.coords.longitude, 'GPS pin set. Drag/click map if it is wrong'), () => status.innerText='GPS failed. Click the map to pin manually.', {enableHighAccuracy:true, timeout:10000});
}
function clearPin(){ if(marker){ map.removeLayer(marker); marker=null; } document.getElementById('latitude').value=''; document.getElementById('longitude').value=''; document.getElementById('locationStatus').innerText='Pin cleared. Click the map to set exact location.'; }
document.getElementById('photoInput').addEventListener('change', function(event){const file=event.target.files[0];const preview=document.getElementById('preview');if(file){preview.src=URL.createObjectURL(file);preview.style.display='block';}});
setTimeout(() => map.invalidateSize(), 300);
</script>
</body>
</html>

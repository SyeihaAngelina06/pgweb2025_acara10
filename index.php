<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <!-- leaflet css link  -->
    <link rel="stylesheet"
      href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css"
    />

    <title>Web-GIS with Geoserver and Leaflet</title>

    <style>
      body {
        margin: 0;
        padding: 0;
      }
      #map {
        width: 100%;
        height: 100vh;
      }
      .legend {
        padding: 6px 8px;
        font: 14px/16px Arial, Helvetica, sans-serif;
        background: white;
        background: rgba(255,255,255,0.8);
        box-shadow: 0 0 15px rgba(0,0,0,0.2);
        border-radius: 5px;
        max-width: 500px; /* Adjust as needed */
      }
      .legend h4 {
        margin: 0 0 5px;
        text-align: center;
      }
      .legend ul {
        list-style-type: none;
        padding: 0;
        margin: 0;
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
      }
      .legend li {
        display: flex;
        align-items: center;
        margin-right: 10px;
        margin-bottom: 5px;
      }
      .legend i {
        width: 18px;
        height: 18px;
        margin-right: 5px;
        border: 1px solid #555;
      }
    </style>
  </head>

  <body>
    <div id="map"></div>

    <!-- leaflet js link  -->
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>

    <script>
      // ===============================
      // MAP DASAR
      // ===============================
      var map = L.map("map").setView([-7.732521, 110.402376], 11);

      var osm = L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
        maxZoom: 19,
        attribution: "© OpenStreetMap contributors",
      }).addTo(map);

      // ===============================
      // LAYER WMS DARI GEOSERVER
      // ===============================

      // 1. ADMINISTRASI KECAMATAN
      var kecamatan = L.tileLayer.wms(
        "http://localhost:8080/geoserver/pgweb/wms",
        {
          layers: "pgweb:DIY_KECAMATAN3",
          format: "image/png",
          transparent: true
        }
      ).addTo(map);

      // 2. JALAN_LN_25K
      var jalan = L.tileLayer.wms(
        "http://localhost:8080/geoserver/pgweb/wms",
        {
          layers: "pgweb:JALAN_LN_25K",
          format: "image/png",
          transparent: true
        }
      ).addTo(map);

      // 3. data_kecamatan
      var toponimi = L.tileLayer.wms(
        "http://localhost:8080/geoserver/pgweb/wms",
        {
          layers: "pgweb:penduduk_sleman_view",
          format: "image/png",
          transparent: true
        }
      ).addTo(map);

      // Geoportal Sleman 
      var wmsLayer2 = L.tileLayer.wms("https://geoportal.slemankab.go.id/geoserver/wms",{
        layers:"geonode:jalan_kabupaten_sleman_2023",
        format:"image/png8",
        transparent:true,
        tiled:true,
        styles:""
      }).addTo(map);

      // ===============================
      // LAYER CONTROL
      // ===============================
      var overlayLayers = {
        "DIY_KECAMATAN3": kecamatan,
        "JALAN_LN_25K": jalan,
        "Jalan": wmsLayer2,
        "penduduk_sleman_view": toponimi
      };

      L.control.layers(null, overlayLayers).addTo(map);

      // ===============================
      // LEGEND
      // ===============================
      var legend = L.control({position: 'bottomleft'});

      legend.onAdd = function (map) {

          var div = L.DomUtil.create('div', 'info legend');
          div.innerHTML = '<h4>Legenda</h4><img src="http://localhost:8080/geoserver/pgweb/wms?REQUEST=GetLegendGraphic&VERSION=1.1.0&FORMAT=image/png&WIDTH=20&HEIGHT=20&LAYER=pgweb:DIY_KECAMATAN3&legend_options=forceLabels:on&LAYOUT=horizontal">';
          
          return div;
      };

      legend.addTo(map);
    </script>
  </body>
</html>
/**
 * Created by d12hanse on 03.11.2017.
 */
function onEachFeature(feature, layer) {
    if (feature.properties && feature.properties.fid) {
        if(feature.properties.popup)
        {
            layer.bindPopup(feature.properties.popup);
        }
        else {
            var popuptext = "";
            $.each(feature.properties, function (i, bla) {
                popuptext = popuptext + "<b>" + i + "</b>: " + bla + "<br>";
            });
            layer.bindPopup(popuptext);
        }
    }
}

function styleGeoJson(feature) {
    if (feature.properties.fill) {
        return {
            weight: 0,
            opacity: 1,
            color: 'white',
            dashArray: '3',
            fillOpacity: 0.7,
            fillColor: feature.properties.fill
        };
    } else {
        return {
            weight: 2,
            opacity: 1,
            color: 'white',
            dashArray: '3',
            fillOpacity: 0.3,
            fillColor: '#666666'
        };
    }
}
function styleKreis(feature) {
    return {
        fillColor: '#ff0000',
        weight: 2.5,
        opacity: 1,
        color: 'grey',
        dashArray: '3',
        fillOpacity: 0.1
    };
}
function styleshp(feature) {
    return {
        fillColor: '#ff0000',
        weight: 2.5,
        opacity: 1,
        color: 'blue',
        dashArray: '3',
        fillOpacity: 0
    };
}